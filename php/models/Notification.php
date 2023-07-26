<?php
    class Notification {
        private $id;
        private $type;
        private $useId;
        private $authorId;
        private $postId;
        private $dt;

        // TYPES
        // 1 - follow
        // 2 - like
        // 3 - comment

        public function __construct($id, $type, $userId, $authorId, $postId, $dt) {
            $this->id = $id;
            $this->type = $type;
            $this->userId = $userId;
            $this->authorId = $authorId;
            $this->postId = $postId;
            $this->dt = $dt;
        }

        /* GETTERS */
        public function getId() {
            return $this->id;
        }
        public function getType() {
            return $this->type;
        }
        public function getUserId() {
            return $this->userId;
        }
        public function getAuthorId() {
            return $this->authorId;
        }
        public function getPostId() {
            return $this->postId;
        }
        public function getDt() {
            return $this->dt;
        }

        /* SETTERS */
        public function setId($id) {
            $this->id = $id;
        }
        public function setType($type) {
            $this->type = $type;
        }
        public function setUserId($userId) {
            $this->userId = $userId;
        }
        public function setAuthorId($authorId) {
            $this->authorId = $authorId;
        }
        public function setPostId($postId) {
            $this->postId = $postId;
        }
        public function setDt($dt) {
            $this->dt = $dt;
        }
    }
?>