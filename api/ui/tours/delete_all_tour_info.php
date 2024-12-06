<?php

    $response = array();
    if ($_SERVER['REQUEST_METHOD'] != 'POST') {
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

    if (!$db->is_connected()) {
        $response['error'] = true;
        $response['message'] = "Database is not connected!";
        echo json_encode($response);
        exit();
    }

    $tour = new TourDetails($dbcon);

    try {
        $dbcon->begin_transaction();

        $result = $tour->clearDatabase();

        if ($result <= 0) {
            throw new \Exception("48: Tour data deletion failed!", 1);
        }

        if ($dbcon->commit()) {
            $response['error'] = false;
            $response['message'] = "Tour data cleared successfully!";
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
