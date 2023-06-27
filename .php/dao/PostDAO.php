<?php
    class PostDAO {
        private $conn;

        public function __construct($conn) {
            $this->conn = $conn;
        }

        public function createPost($post) {
            $user = $post->getUser();
            $text = $post->getText();
            $image = $post->getImage();
            $likes = $post->getLikes();
            $dt = $post->getDt();

            $sql = "INSERT INTO posts (user, text, image, likes, dt) VALUES (?, ?, ?, ?, ?)";
            $stmt = $this->conn->prepare($sql);
            $stmt->bind_param("sssss", $user, $text, $image, $likes, $dt);
            $stmt->execute();

            $id = $stmt->insert_id;
            $post->setId($id);

            $stmt->close();
        }
        
    }
?>