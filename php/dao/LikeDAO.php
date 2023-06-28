<?php
    class LikeDAO {
        private $conn;

        public function __construct($conn) {
            $this->conn = $conn;
        }

        public function create(Like $like) {
            $userId = $like->getUserId();
            $postId = $like->getPostId();

            $sql = "INSERT INTO likes (user_id, post_id) VALUES (?, ?)";
            $stmt = $this->conn->prepare($sql);
            $stmt->bind_param("ss", $userId, $postId);
            $stmt->execute();
        }

        public function delete(Like $like) {
            $userId = $like->getUserId();
            $postId = $like->getPostId();

            $sql = "DELETE FROM likes WHERE user_id = ? AND post_id = ?";
            $stmt = $this->conn->prepare($sql);
            $stmt->bind_param("ss", $userId, $postId);
            $stmt->execute();
        }

        public function getLikeByUserIdAndPostId($userId, $postId) {
            $sql = "SELECT * FROM likes WHERE user_id = ? AND post_id = ?";
            $stmt = $this->conn->prepare($sql);
            $stmt->bind_param("ss", $userId, $postId);
            $stmt->execute();
            $result = $stmt->get_result();
            $stmt->close();

            if ($result->num_rows > 0) {
                $row = $result->fetch_assoc();
                $like = new Like(
                    $row['user_id'],
                    $row['post_id']
                );
                return $like;
            } else {
                return null;
            }
        }
    }
?>