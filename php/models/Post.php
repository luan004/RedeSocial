<?php
    class Post {
        /* ATRIBUTOS */
        private $id;
        private $user;
        private $text;
        private $image;
        private $likes;
        private $dt;

        /* CONSTRUTOR */
        public function __construct($id, $user, $text, $image, $likes, $dt) {
            $this->id = $id;
            $this->user = $user;
            $this->text = $text;
            $this->image = $image;
            $this->likes = $likes;
            $this->dt = $dt;
        }

        /* GETTERS */
        public function getId() {
            return $this->id;
        }
        public function getUser() {
            return $this->user;
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
        public function setUser($user) {
            $this->user = $user;
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