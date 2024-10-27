<?php

    $response = array();
    if($_SERVER['REQUEST_METHOD']!='POST') {
        $response['error'] = true;
        $response['message'] = "Invalid Request method";
        echo json_encode($response);
		exit();
	}

    $input = file_get_contents("php://input");

    // Decode the JSON data into a PHP array
    $data = json_decode($input, true); // true makes it an associative array

    // Check if the decoding was successful and if the array is present
    if (json_last_error() != JSON_ERROR_NONE || !isset($data['excluded']) || !is_array($data['excluded'])) {
        $response['error'] = true;
        $response['message'] = "Error parsing input or invalid input";
    }


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

        $insertResult = $tour->insertTourExcluded($data['excluded']);

        if ($insertResult <= 0) {
            throw new \Exception("48: Tour excluded insertion failed!", 1);
        }

        if ($dbcon->commit()) {
            $response['error'] = false;
            $response['message'] = "Tour excluded added successfully!";
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
