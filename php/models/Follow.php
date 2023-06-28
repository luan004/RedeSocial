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