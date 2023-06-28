<?php
    class PostDAO {
        private $conn;

        public function __construct($conn) {
            $this->conn = $conn;
        }

        public function create(Post $post) {
            $user = $post->getUserId();
            $text = $post->getText();
            $image = $post->getImage();
            $likes = $post->getLikes();

            $sql = "INSERT INTO posts (user_id, text, image, likes, dt) VALUES (?, ?, ?, ?, NOW())";
            $stmt = $this->conn->prepare($sql);
            $stmt->bind_param("ssss", $user, $text, $image, $likes);
            $stmt->execute();

            $id = $stmt->insert_id;
            $post->setId($id);

            $stmt->close();
        }

        public function delete(Post $post) {
            $id = $post->getId();

            /* DELETE ALL LIKES */
            $sql = "DELETE FROM likes WHERE post_id = ?";
            $stmt = $this->conn->prepare($sql);
            $stmt->bind_param("i", $id);
            $stmt->execute();

            /* DELETE ALL COMMENTS */
            $sql = "DELETE FROM comments WHERE post_id = ?";
            $stmt = $this->conn->prepare($sql);
            $stmt->bind_param("i", $id);
            $stmt->execute();

            $sql = "DELETE FROM posts WHERE id = ?";
            $stmt = $this->conn->prepare($sql);
            $stmt->bind_param("i", $id);
            $stmt->execute();

            $stmt->close();
        }
        
        public function getPostById($id) {
            $stmt = $this->conn->prepare("SELECT * FROM posts WHERE id = ?");
            $stmt->bind_param("i", $id);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows > 0) {
                $row = $result->fetch_assoc();
                $post = new Post(
                    $row['id'],
                    $row['user_id'],
                    $row['text'],
                    $row['image'],
                    $row['likes'],
                    $row['dt']
                );
                $stmt->close();
                return $post;
            } else {
                $stmt->close();
                return false;
            }
        }
    }
?>