<?php
    class Post {
        /* ATRIBUTOS */
        private $id;
        private $userId;
        private $text;
        private $image;
        private $dt;

        /* CONSTRUTOR */
        public function __construct($id, $userId, $text, $image, $dt) {
            $this->id = $id;
            $this->userId = $userId;
            $this->text = $text;
            $this->image = $image;
            $this->dt = $dt;
        }

        /* GETTERS */
        public function getId() {
            return $this->id;
        }
        public function getUserId() {
            return $this->userId;
        }
        public function getText() {
            return $this->text;
        }
        public function getImage() {
            return $this->image;
        }
        public function getLikes() {
            return $this->likes;
        }
        public function getDt() {
            return $this->dt;
        }

        /* SETTERS */
        public function setId($id) {
            $this->id = $id;
        }
        public function setUserId($userId) {
            $this->userId = $userId;
        }
        public function setText($text) {
            $this->text = $text;
        }
        public function setImage($image) {
            $this->image = $image;
        }
        public function setLikes($likes) {
            $this->likes = $likes;
        }
        public function setDt($dt) {
            $this->dt = $dt;
        }
    }
?>