<?php
    class FollowDAO {
        private $conn;

        public function __construct($conn) {
            $this->conn = $conn;
        }

        public function create(Follow $follow) {
            $followerId = $follow->getFollowerId();
            $followedId = $follow->getFollowedId();

            $sql = "INSERT INTO followers (follower_id, followed_id) VALUES (?, ?)";
            $stmt = $this->conn->prepare($sql);
            $stmt->bind_param("ss", $followerId, $followedId);
            $stmt->execute();
        }

        public function delete(Follow $follow) {
            $followerId = $follow->getFollowerId();
            $followedId = $follow->getFollowedId();

            $sql = "DELETE FROM followers WHERE follower_id = ? AND followed_id = ?";
            $stmt = $this->conn->prepare($sql);
            $stmt->bind_param("ss", $followerId, $followedId);
            $stmt->execute();
        }

        public function getFollowByUserAndFollowed($followerId, $followedId) {
            $sql = "SELECT * FROM followers WHERE follower_id = ? AND followed_id = ?";
            $stmt = $this->conn->prepare($sql);
            $stmt->bind_param("ss", $followerId, $followedId);
            $stmt->execute();
            $result = $stmt->get_result();
            if ($result->num_rows > 0) {
                $row = $result->fetch_assoc();
                $follow = new Follow(
                    $row['follower_id'],
                    $row['followed_id']
                );
                return $follow;
            } else {
                return null;
            }
        }

    }
?>