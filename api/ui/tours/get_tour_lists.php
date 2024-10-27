<?php

    $response = array();
    if($_SERVER['REQUEST_METHOD'] != 'POST') {
        $response['error'] = true;
        $response['message'] = "Invalid Request method";
        echo json_encode($response);
		exit();
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

    $tours = new TourDetails($dbcon);

    $toursResult = $tours->getTourList();

    $toursArray = array();

    if ($toursResult->num_rows>0) {
        $response['error'] = false;
        while ($row = $toursResult->fetch_array(MYSQLI_ASSOC)) {
            $toursArray[] = $row;
        }

        $response['data'] = $toursArray;
    } else {
        $response['error'] = true;
        $response['message'] = "No tours found!";
    }

	echo json_encode($response);

    $dbcon->close();
?>
