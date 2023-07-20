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

        public function getLikeByUserAndPost($userId, $postId) {
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

        public function getLikeNumByPostId($id) {
            $stmt = $this->conn->prepare("SELECT * FROM likes WHERE post_id = ?");
            $stmt->bind_param("s", $id);
            $stmt->execute();
            $result = $stmt->get_result();
            $stmt->close();

            return $result->num_rows;
        }

        public function getLikeByPostIdUserId($postId, $userId) {
            $stmt = $this->conn->prepare("SELECT * FROM likes WHERE post_id = ? AND user_id = ?");
            $stmt->bind_param("ss", $postId, $userId);
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

    /* CLASS DIAGRAM */
    /* 
    - userId: int
    - postId: int
    ---
    + create(Like)
    + delete(Like)
    + getLikeByUserAndPost(int, int) : Like
    + getLikeNumByPostId(int) : int
    + getLikeByPostIdUserId(int, int) : Like


    */
?>