<?php

    $response = array();
    if ($_SERVER['REQUEST_METHOD'] != 'POST') {
        $response['error'] = true;
        $response['message'] = "Invalid Request method";
        echo json_encode($response);
		exit();
	}

    if (!isset($_POST['id'])) {
        $response['error'] = true;
        $response['message'] = "Required field missing!";
        echo json_encode($response);
        exit();
    }

    $id = trim(strip_tags($_POST['id']));

    $base_path = dirname(dirname(dirname(__FILE__)));

    require_once($base_path."/db/Database.php");
    require_once($base_path."/model/TourDetails.php");

    $db = new Database();
    $dbcon = $db->db_connect();

    if (!$db->is_connected()) {
        $response['error'] = true;
        $response['message'] = "Database is not connected!";
        echo json_encode($response);
        exit();
    }

    $tour = new TourDetails($dbcon);

    $tourResult = $tour->getATourDetails($id);

    $tourArray = array();

    if ($tourResult->num_rows > 0) {
          $response['error'] = false;
          while ($row = $tourResult->fetch_array(MYSQLI_ASSOC)) {
            $images = $tour->getATourImages($id);

            $imageArray = array();

            if ($images->num_rows > 0) {
                while ($imageRow = $images->fetch_array(MYSQLI_ASSOC)) {
                    $imageArray[] = $imageRow['image_url'];
                }
            }

            $row['images'] = $imageArray;
            $tourArray[] = $row;
          }

          $response['data'] = $toursArray;
    } else {
          $response['error'] = true;
          $response['message'] = "No tours found!";
    }

	echo json_encode($response);

    $dbcon->close();
?>
