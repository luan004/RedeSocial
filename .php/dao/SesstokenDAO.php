<?php
    class SesstokenDAO {
        private $conn;

        public function __construct($conn) {
            $this->conn = $conn;
        }

        public function createSesstoken($sesstoken) {
            $userId = $sesstoken->getUserId();
            $token = $sesstoken->getToken();

            $sql = "INSERT INTO sesstokens (user_id, token) VALUES (?, ?)";
            $stmt = $this->conn->prepare($sql);
            $stmt->bind_param("is", $userId, $token);
            $stmt->execute();

            $id = $stmt->insert_id;
            $sesstoken->setId($id);

            $stmt->close();
        }
    }
?>