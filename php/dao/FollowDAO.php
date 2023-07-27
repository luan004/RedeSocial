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

        public function getFollowersCount($id) {
            $sql = "SELECT COUNT(*) AS followersCount FROM followers WHERE followed_id = ?";
            $stmt = $this->conn->prepare($sql);
            $stmt->bind_param("s", $id);
            $stmt->execute();
            $result = $stmt->get_result();
            if ($result->num_rows > 0) {
                $row = $result->fetch_assoc();
                return $row['followersCount'];
            } else {
                return 0;
            }
        }

        public function getFollowingCount($id) {
            $sql = "SELECT COUNT(*) AS followingCount FROM followers WHERE follower_id = ?";
            $stmt = $this->conn->prepare($sql);
            $stmt->bind_param("s", $id);
            $stmt->execute();
            $result = $stmt->get_result();
            if ($result->num_rows > 0) {
                $row = $result->fetch_assoc();
                return $row['followingCount'];
            } else {
                return 0;
            }
        }

        public function getFollowers($id) {
            $stmt = $this->conn->prepare("SELECT * FROM followers WHERE followed_id = ?");
            $stmt->bind_param("s", $id);
            $stmt->execute();
            $result = $stmt->get_result();

            $followers = array();

            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    $follower = new Follow(
                        $row['follower_id'],
                        $row['followed_id']
                    );
                    $followers[] = $follower;
                }
            }

            return $followers;
        }
    }
?>