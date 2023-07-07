<?php
    class CommentDAO {
        private $conn;

        public function __construct($conn) {
            $this->conn = $conn;
        }

        public function create(Comment $comment) {
            $postId = $comment->getPostId();
            $userId = $comment->getUserId();
            $text = $comment->getText();

            $stmt = $this->conn->prepare("INSERT INTO comments (post_id, user_id, text, dt) VALUES (?, ?, ?, NOW())");
            $stmt->bind_param("iis", $postId, $userId, $text);
            $stmt->execute();

            $id = $stmt->insert_id;
            $comment->setId($id);

            $stmt->close();
        }

        public function getCommentsByPostId($id) {
            $stmt = $this->conn->prepare("SELECT * FROM comments WHERE post_id = ? ORDER BY dt DESC");
            $stmt->bind_param("i", $id);
            $stmt->execute();
            $result = $stmt->get_result();

            $comments = array();

            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    $comment = new Comment(
                        $row['id'],
                        $row['post_id'],
                        $row['user_id'],
                        $row['text'],
                        $row['dt']
                    );
                    array_push($comments, $comment);
                }
            }

            $stmt->close();

            return $comments;
        }

        public function getCommentsNumByPostId($id) {
            $stmt = $this->conn->prepare("SELECT * FROM comments WHERE post_id = ?");
            $stmt->bind_param("i", $id);
            $stmt->execute();
            $result = $stmt->get_result();
            $stmt->close();

            return $result->num_rows;
        }
    }
?>