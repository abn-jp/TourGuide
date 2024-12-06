<?php

    $response = array();
    if($_SERVER['REQUEST_METHOD'] != 'POST') {
        $response['error'] = true;
        $response['message'] = "Invalid Request method";
        echo json_encode($response);
		exit();
	}

    if(!isset($_POST['id']) || !isset($_POST['title']) || !isset($_POST['price']) || !isset($_POST['price_type']) || !isset($_POST['category_id']) || !isset($_POST['description']) || !isset($_POST['area_id']) || !isset($_POST['overview']) || !isset($_POST['remarks']) || !isset($_POST['cancel_policy']) || !isset($_POST['images'])) {
        $response['error'] = true;
        $response['message'] = "Required field missing!";
        echo json_encode($response);
        exit();
    }

    $id = $_POST['id'];
    $title = $_POST['title'];
    $price = $_POST['price'];
    $price_type = $_POST['price_type'];
    $category_id = $_POST['category_id'];
    $description = $_POST['description'];
    $area_id = $_POST['area_id'];
    $overview = base64_encode($_POST['overview']);
    $remarks = base64_encode($_POST['remarks']);
    $cancel_policy = base64_encode($_POST['cancel_policy']);


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

        $tourUpdateResult = $tour->updateATourInfo($id, $title, $price, $price_type, $category_id, $description, $area_id, $overview, $remarks, $cancel_policy);

        if ($tourUpdateResult <= 0) {
            throw new \Exception("48: Tour update failed!", 1);
        }

        $imageArray = json_decode($_POST['images'], true)['image_urls'];
        $insertTourImages = $tour->updateTourImages($id, $imageArray);
        if ($insertTourImages <= 0) {
            throw new \Exception("59: Tour image update failed!", 1);
        }

        if (isset($_POST['itinerary'])) {
            $itineraryArray = json_decode($_POST['itinerary'], true)['itinerary_list'];
            $tour ->insertTourItinerary($id, $itineraryArray);
        }

        if (isset($_POST['excluded'])) {
            $list = json_decode($_POST['excluded'], true)['excluded_list'];
            $tour ->updateTourExcluded($id, $list);
        }

        if (isset($_POST['included'])) {
            $list = json_decode($_POST['included'], true)['included_list'];
            $tour ->updateTourIncluded($id, $list);
        }

        if (isset($_POST['highlights'])) {
            $list = json_decode($_POST['highlights'], true)['highlights_list'];
            $tour ->updateTourHighlights($id, $list);
        }

        if ($dbcon->commit()) {
            $response['error'] = false;
            $response['message'] = "Tour updated successfully!";
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
