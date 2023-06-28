<?php
    class Comment {
        /* ATRIBUTOS */
        private $id;
        private $postId;
        private $userId;
        private $text;
        private $dt;

        /* CONSTRUTOR */
        public function __construct($id, $postId, $userId, $text, $dt) {
            $this->id = $id;
            $this->postId = $postId;
            $this->userId = $userId;
            $this->text = $text;
            $this->dt = $dt;
        }

        /* GETTERS */
        public function getId() {
            return $this->id;
        }
        public function getPostId() {
            return $this->postId;
        }
        public function getUserId() {
            return $this->userId;
        }
        public function getText() {
            return $this->text;
        }
        public function getDt() {
            return $this->dt;
        }

        /* SETTERS */
        public function setId($id) {
            $this->id = $id;
        }
        public function setPostId($postId) {
            $this->postId = $postId;
        }
        public function setUserId($userId) {
            $this->userId = $userId;
        }
        public function setText($text) {
            $this->text = $text;
        }
        public function setDt($dt) {
            $this->dt = $dt;
        }
    }
?>