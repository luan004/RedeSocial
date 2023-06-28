<!-- 
CREATE TABLE followers (
    follower_id INT,
    followed_id INT,
    FOREIGN KEY (follower_id) REFERENCES users(id),
    FOREIGN KEY (followed_id) REFERENCES users(id),
    PRIMARY KEY (follower_id, user_id)
);
 -->

<?php
    class Follow {
        /* ATRIBUTOS */
        private $followerId;
        private $followedId;

        /* CONSTRUTOR */
        public function __construct($followerId, $followedId) {
            $this->followerId = $followerId;
            $this->followedId = $followedId;
        }

        /* GETTERS */
        public function getFollowerId() {
            return $this->followerId;
        }
        public function getFollowedId() {
            return $this->followedId;
        }

        /* SETTERS */
        public function setFollowerId($followerId) {
            $this->followerId = $followerId;
        }
        public function setFollowedId($followedId) {
            $this->followedId = $followedId;
        }
    
    }
?>