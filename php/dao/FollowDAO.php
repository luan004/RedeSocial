<?php
    class FollowDAO {
        private $conn;

        public function __construct($conn) {
            $this->conn = $conn;
        }

        public function create(Follow $follow) {
            $followerId = $follow->getFollowerId();
            $followedId = $follow->getFollowedId();

            $sql = "INSERT INTO follows (follower_id, followed_id) VALUES (?, ?)";
            $stmt = $this->conn->prepare($sql);
            $stmt->bind_param("ss", $followerId, $followedId);
            $stmt->execute();
        }

        public function delete(Follow $follow) {
            $followerId = $follow->getFollowerId();
            $followedId = $follow->getFollowedId();

            $sql = "DELETE FROM follows WHERE follower_id = ? AND followed_id = ?";
            $stmt = $this->conn->prepare($sql);
            $stmt->bind_param("ss", $followerId, $followedId);
            $stmt->execute();
        }
    }
?>