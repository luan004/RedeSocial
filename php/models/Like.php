<?php
    class Like {
        /* ATRIBUTOS */
        private $userId;
        private $postId;
        
        /* CONSTRUTOR */
        public function __construct($userId, $postId) {
            $this->userId = $userId;
            $this->postId = $postId;
        }

        /* GETTERS */
        public function getUserId() {
            return $this->userId;
        }
        public function getPostId() {
            return $this->postId;
        }

        /* SETTERS */
        public function setUserId($userId) {
            $this->userId = $userId;
        }
        public function setPostId($postId) {
            $this->postId = $postId;
        }
    }
?>