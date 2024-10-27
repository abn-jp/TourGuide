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

    $data = null;

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

            // Tour included items
            $included = $tour->getATourIncludedItems($id);
            $includedArray = array();

            if ($included->num_rows > 0) {
                while ($includedRow = $included->fetch_array(MYSQLI_ASSOC)) {
                    $includedArray[] = $includedRow['included'];
                }
            }
            $row['included'] = $includedArray;

            // Tour excluded items
            $excluded = $tour->getATourExcludedItems($id);
            $excludedArray = array();

            if ($excluded->num_rows > 0) {
                while ($excludedRow = $excluded->fetch_array(MYSQLI_ASSOC)) {
                    $excludedArray[] = $excludedRow['excluded'];
                }
            }
            $row['excluded'] = $excludedArray;

            // Tour itinerary
            $itinerary = $tour->getATourItinerary($id);
            $itineraryArray = array();

            if ($itinerary->num_rows > 0) {
                while ($itineraryRow = $itinerary->fetch_array(MYSQLI_ASSOC)) {
                    $itineraryArray[] = $itineraryRow['itinerary'];
                }
            }
            $row['itinerary'] = $itineraryArray;

            // Tour highlights
            $highlights = $tour->getATourHighlights($id);
            $highlightsArray = array();

            if ($highlights->num_rows > 0) {
                while ($highlightsRow = $highlights->fetch_array(MYSQLI_ASSOC)) {
                    $highlightsArray[] = $highlightsRow['highlights'];
                }
            }
            $row['highlights'] = $highlightsArray;


            // if (preg_match('/^[a-zA-Z0-9\/\r\n+]*={0,2}$/', $row['overview'])) {
            //     $row['overview'] = base64_decode($row['overview']);
            // } else {
            //     $row['overview'] = $row['overview'];
            // }
            // if (preg_match('/^[a-zA-Z0-9\/\r\n+]*={0,2}$/', $row['cancel_policy'])) {
            //     $row['cancel_policy'] = base64_decode($row['cancel_policy']);
            // } else {
            //     $row['cancel_policy'] = $row['cancel_policy'];
            // }
            // if (preg_match('/^[a-zA-Z0-9\/\r\n+]*={0,2}$/', $row['remarks'])) {
            //     $row['remarks'] = base64_decode($row['remarks']);
            // } else {
            //     $row['remarks'] = $row['remarks'];
            // }
            $row['overview'] = $row['overview'];
            $row['remarks'] = $row['remarks'];
            $row['cancel_policy'] = $row['cancel_policy'];

            $data = $row;
        }

        $response['data'] = $data;
    } else {
        $response['error'] = true;
        $response['message'] = "No tours found!";
    }

	echo json_encode($response);

    $dbcon->close();
?>
