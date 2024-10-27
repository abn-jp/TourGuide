<?php

    class TourDetails
    {
        private $dbcon = null;

        function __construct($dbcon)
        {
            $this->dbcon = $dbcon;
        }

        // get a tour details info
        public function getATourDetails($tourid) {
          $sql = "
            SELECT
                t.title,
                t.description,
                t.price,
                t.price_type,
                t.overview,
                t.remarks,
                t.cancel_policy,
                c.name AS category,
                a.name AS area
            FROM tour_details t
            INNER JOIN category c ON t.category_id = c.id
            INNER JOIN area a ON t.area_id = a.id
            WHERE t.id = ?
          ";
          $stmt = $this->dbcon->prepare($sql);
          $stmt->bind_param("i", $tourid);
          $stmt->execute();

          $affectedRows = $stmt->get_result();

          $stmt->close();

          return $affectedRows;
        }

        public function getTourList() {
          $sql = "SELECT id, title, price FROM `tour_details`";
          $stmt = $this->dbcon->prepare($sql);
          $stmt->execute();

          $affectedRows = $stmt->get_result();

          $stmt->close();

          return $affectedRows;
        }

        // get images of a tour
        public function getATourImages($tourid) {
          $sql = "SELECT * FROM tour_images WHERE tour_id = ?";

          $stmt = $this->dbcon->prepare($sql);
          $stmt->bind_param("i", $tourid);
          $stmt->execute();
          $result = $stmt->get_result();
          $stmt->close();

          return $result;
        }

        // get itinerary of a tour
        public function getATourItinerary($tourid) {
          $sql = "SELECT * FROM tour_itinerary WHERE tour_id = ?";

          $stmt = $this->dbcon->prepare($sql);
          $stmt->bind_param("i", $tourid);
          $stmt->execute();
          $result = $stmt->get_result();
          $stmt->close();

          return $result;
        }

        // get included items of a tour
        public function getATourIncludedItems($tourid) {
          $sql = "SELECT * FROM tour_included WHERE tour_id = ?";

          $stmt = $this->dbcon->prepare($sql);
          $stmt->bind_param("i", $tourid);
          $stmt->execute();
          $result = $stmt->get_result();
          $stmt->close();

          return $result;
        }

        // get excluded items of a tour
        public function getATourExcludedItems($tourid) {
          $sql = "SELECT * FROM tour_excluded WHERE tour_id = ?";

          $stmt = $this->dbcon->prepare($sql);
          $stmt->bind_param("i", $tourid);
          $stmt->execute();
          $result = $stmt->get_result();
          $stmt->close();

          return $result;
        }

        // get highlights of a tour
        public function getATourHighlights($tourid) {
          $sql = "SELECT * FROM tour_highlights WHERE tour_id = ?";

          $stmt = $this->dbcon->prepare($sql);
          $stmt->bind_param("i", $tourid);
          $stmt->execute();
          $result = $stmt->get_result();
          $stmt->close();

          return $result;
        }

        // insert a tour details info
        public function insertATourInfo($id, $title, $price, $price_type, $categoryId, $description, $area_id, $overview, $remarks, $cancel_policy) {
            $sql = "INSERT INTO tour_details(id, title, price, price_type, category_id, description, area_id, overview, remarks, cancel_policy)
                VALUES(?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
            $stmt = $this->dbcon->prepare($sql);
            $stmt->bind_param("isssisisss", $id, $title, $price, $price_type, $categoryId, $description, $area_id, $overview, $remarks, $cancel_policy);
            $stmt->execute();

            $affectedRows = $stmt->affected_rows;

            $stmt->close();

            if ($affectedRows > 0) {
              return 1;
            } else {
              return -1;
            }
        }

        // insert tour images
        public function insertTourImages($tour_id, $images) {
          $sql = "INSERT INTO tour_images(tour_id, image_url)
              VALUES(?, ?)";
          $stmt = $this->dbcon->prepare($sql);
          $stmt->bind_param("is", $tour_id, $image_url);

          // Execute the prepared statement for each image URL
          foreach ($images as $image_url) {
            if (!$stmt->execute()) {
                $stmt->close();
                return -1;
            }
          }

        $stmt->close();
        return 1;
      }

        // insert a tour itinerary info
        public function insertTourItinerary($tour_id, $list) {
          $sql = "INSERT INTO tour_itinerary(tour_id, itinerary) VALUES(?, ?)";
          $stmt = $this->dbcon->prepare($sql);
          $stmt->bind_param("is", $tour_id, $itinerary);
          foreach ($list as $itinerary) {
            if (!$stmt->execute()) {
                $stmt->close();
                return -1;
            }
          }

          $stmt->close();
          return 1;
        }

        // insert a tour included info
        public function insertTourIncluded($tour_id, $list) {
          $sql = "INSERT INTO tour_included(tour_id, included) VALUES(?, ?)";
          $stmt = $this->dbcon->prepare($sql);
          $stmt->bind_param("is", $tour_id, $included);
          foreach ($list as $included) {
            if (!$stmt->execute()) {
                $stmt->close();
                return -1;
            }
          }

          $stmt->close();
          return 1;
        }

        // insert a tour excluded list info
        public function insertTourExcluded($tour_id, $list) {
          $sql = "INSERT INTO tour_excluded(tour_id, excluded) VALUES(?, ?)";
          $stmt = $this->dbcon->prepare($sql);
          $stmt->bind_param("is", $tour_id, $excluded);
          foreach ($list as $excluded) {
            if (!$stmt->execute()) {
              $stmt->close();
              return -1;
            }
          }

          $stmt->close();
          return 1;
        }

        // insert a tour highlights list info
        public function insertTourHighlights($tour_id, $list) {
          $sql = "INSERT INTO tour_highlights(tour_id, highlights) VALUES(?, ?)";
          $stmt = $this->dbcon->prepare($sql);
          $stmt->bind_param("is", $tour_id, $highlights);
          foreach ($list as $highlights) {
            if (!$stmt->execute()) {
              $stmt->close();
              return -1;
            }
          }

          $stmt->close();
          return 1;
        }
    }
 ?>
