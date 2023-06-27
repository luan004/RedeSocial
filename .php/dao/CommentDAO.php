<?php
    class CommentDAO {
        private $conn;

        public function __construct($conn) {
            $this->conn = $conn;
        }

        public function createComment(Comment $comment) {
            $postId = $comment->getPostId();
            $userId = $comment->getUserId();
            $text = $comment->getText();
            $dt = $comment->getDt();

            $stmt = $this->conn->prepare("INSERT INTO comments (post_id, user_id, text, dt) VALUES (?, ?, ?, ?)");
            $stmt->bind_param("iiss", $postId, $userId, $text, $dt);
            $stmt->execute();

            $id = $stmt->insert_id;
            $comment->setId($id);

            $stmt->close();
        }
    }
?>