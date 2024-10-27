<?php

    $response = array();
    if($_SERVER['REQUEST_METHOD']!='POST') {
        $response['error'] = true;
        $response['message'] = "Invalid Request method";
        echo json_encode($response);
		exit();
	}

    if(!isset($_POST['id']) || !isset($_POST['title']) || !isset($_POST['price']) || !isset($_POST['category_id']) || !isset($_POST['description'])) {
        $response['error'] = true;
        $response['message'] = "Required field missing!";
        echo json_encode($response);
        exit();
    }

    $id = trim(strip_tags($_POST['id']));
    $title = trim(strip_tags($_POST['title']));
    $price = trim(strip_tags($_POST['price']));
    $category_id = trim(strip_tags($_POST['category_id']));
    $description = trim(strip_tags($_POST['description']));


    $base_path = dirname(dirname(dirname(__FILE__)));

    require_once($base_path."/db/Database.php");
    require_once($base_path."/model/TourDetails.php");

    $db = new Database();
    $dbcon = $db->db_connect();

    if(!$db->is_connected()) {
          $response['error'] = true;
          $response['message'] = "Database is not connected!";
          echo json_encode($response);
          exit();
    }

    $tour = new TourDetails($dbcon);

    try {
        $dbcon->begin_transaction();

        $tourInsertResult = $tour->insertATourInfo($id, $title, $price, $category_id, $description);

        if ($tourInsertResult <= 0) {
            throw new \Exception("48: Tour insertion failed!", 1);
        }

        if ($dbcon->commit()) {
            $response['error'] = false;
            $response['message'] = "Tour added successfully!";
        } else {
            throw new \Exception("55: Something went wrong! Please try again.", 1);
        }

    } catch (\Exception $e) {
        $dbcon->rollback();
        $response['error'] = true;
        $response['message'] = $e->getMessage();
    }

	echo json_encode($response);

    $dbcon->close();
?>
