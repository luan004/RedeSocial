<?php
    class SesstokenDAO {
        private $conn;

        public function __construct($conn) {
            $this->conn = $conn;
        }

        public function createSesstoken(Sesstoken $sesstoken) {
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

        public function deleteSesstoken(Sesstoken $sesstoken) {
            $id = $sesstoken->getId();

            $stmt = $this->conn->prepare("DELETE FROM sesstokens WHERE id = ?");
            $stmt->bind_param("i", $id);
            $stmt->execute();
            $stmt->close();
        }

        public function getSesstokenByToken($token) {
            $stmt = $this->conn->prepare("SELECT * FROM sesstokens WHERE token = ?");
            $stmt->bind_param("s", $token);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows > 0) {
                $row = $result->fetch_assoc();
                $sesstoken = new Sesstoken(
                    $row['id'],
                    $row['user_id'],
                    $row['token']
                );
                $stmt->close();
                return $sesstoken;
            } else {
                $stmt->close();
                return false;
            }
        }
    }

    /* CLASS DIAGRAM */
    /* 
    + create(Sesstoken)
    + delete(Sesstoken)
    + getSesstokenByToken(String): Sesstoken
    
    */
?>