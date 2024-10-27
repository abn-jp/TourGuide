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
              c.name AS category,
              a.name AS area
            FROM tour_details AS t
            INNER JOIN category AS c ON c.id = t.id
            INNER JOIN area AS a ON a.id = t.id
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
        public function insertATourInfo($id, $title, $price, $categoryId, $description) {
            $sql = "INSERT INTO tour_details(id, title, price, category_id, description)
                VALUES(?, ?, ?, ?, ?)";
            $stmt = $this->dbcon->prepare($sql);
            $stmt->bind_param("issss", $id, $title, $price, $categoryId, $description);
            $stmt->execute();

            $affectedRows = $stmt->affected_rows;

            $stmt->close();

            if ($affectedRows > 0) {
              $lastInsertId = $this->dbcon->insert_id;
              return $lastInsertId;
            } else {
              return -1;
            }
        }

        // insert a tour itinerary info
        public function insertTourItinerary($list) {
          $sql = "INSERT INTO tour_itinerary(tour_id, itinerary) VALUES(?, ?)";
          $stmt = $this->dbcon->prepare($sql);
          $stmt->bind_param("is", $id, $itinerary);
          foreach ($list as $item) {
            $id = $item['id'];
            $itinerary = $item['itinerary'];

            $stmt->execute();
          }

          $affectedRows = $stmt->affected_rows;

          $stmt->close();

          if ($affectedRows > 0) {
            return 1;
          } else {
            return -1;
          }
        }

        // insert a tour included info
        public function insertTourIncluded($list) {
          $sql = "INSERT INTO tour_included(tour_id, included) VALUES(?, ?)";
          $stmt = $this->dbcon->prepare($sql);
          $stmt->bind_param("is", $id, $included);
          foreach ($list as $item) {
            $id = $item['id'];
            $included = $item['included'];

            $stmt->execute();
          }

          $affectedRows = $stmt->affected_rows;

          $stmt->close();

          if ($affectedRows > 0) {
            return 1;
          } else {
            return -1;
          }
        }

        // insert a tour excluded list info
        public function insertTourExcluded($list) {
          $sql = "INSERT INTO tour_excluded(tour_id, excluded) VALUES(?, ?)";
          $stmt = $this->dbcon->prepare($sql);
          $stmt->bind_param("is", $id, $excluded);
          foreach ($list as $item) {
            $id = $item['id'];
            $excluded = $item['excluded'];

            $stmt->execute();
          }

          $affectedRows = $stmt->affected_rows;

          $stmt->close();

          if ($affectedRows > 0) {
            return 1;
          } else {
            return -1;
          }
        }

        // insert a tour highlights list info
        public function insertTourHighlights($list) {
          $sql = "INSERT INTO tour_highlights(tour_id, highlights) VALUES(?, ?)";
          $stmt = $this->dbcon->prepare($sql);
          $stmt->bind_param("is", $id, $highlights);
          foreach ($list as $item) {
            $id = $item['id'];
            $highlights = $item['highlights'];

            $stmt->execute();
          }

          $affectedRows = $stmt->affected_rows;

          $stmt->close();

          if ($affectedRows > 0) {
            return 1;
          } else {
            return -1;
          }
        }
    }
 ?>
