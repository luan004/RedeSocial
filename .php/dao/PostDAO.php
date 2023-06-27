<?php
    class PostDAO {
        private $conn;

        public function __construct($conn) {
            $this->conn = $conn;
        }

        public function insert($post) {
            $user = $post->getUser();
            $text = $post->getText();
            $image = $post->getImage();
            $likes = $post->getLikes();
            $dt = $post->getDt();

            $sql = "INSERT INTO posts (user_id, text, image, likes, dt) VALUES (?, ?, ?, ?, NOW())";
            $stmt = $this->conn->prepare($sql);
            $stmt->bind_param("ssss", $user, $text, $image, $likes);
            $stmt->execute();

            $id = $stmt->insert_id;
            $post->setId($id);

            $stmt->close();
        }
        
    }
?>