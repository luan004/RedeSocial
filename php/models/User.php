<?php
    class User {
        /* ATRIBUTOS */
        private $id;
        private $name;
        private $user;
        private $pass;
        private $aboutme;
        private $color;
        private $avatar;
        private $banner;
        private $dt;

        /* CONSTRUTOR */
        public function __construct($id, $name, $user, $pass, $aboutme, $color, $avatar, $banner, $dt) {
            $this->id = $id;
            $this->name = $name;
            $this->user = $user;
            $this->pass = $pass;
            $this->aboutme = $aboutme;
            $this->color = $color;
            $this->avatar = $avatar;
            $this->banner = $banner;
            $this->dt = $dt;
        }

        /* GETTERS */
        public function getId() {
            return $this->id;
        }
        public function getName() {
            return $this->name;
        }
        public function getUser() {
            return $this->user;
        }
        public function getPass() {
            return $this->pass;
        }
        public function getAboutMe() {
            return $this->aboutme;
        }
        public function getColor() {
            return $this->color;
        }
        public function getAvatar() {
            return $this->avatar;
        }
        public function getBanner() {
            return $this->banner;
        }
        public function getDt() {
            return $this->dt;
        }

        /* SETTERS */
        public function setId($id) {
            $this->id = $id;
        }
        public function setName($name) {
            $this->name = $name;
        }
        public function setUser($user) {
            $this->user = $user;
        }
        public function setPass($pass) {
            $this->pass = $pass;
        }
        public function setAboutMe($aboutme) {
            $this->aboutme = $aboutme;
        }
        public function setColor($color) {
            $this->color = $color;
        }
        public function setAvatar($avatar) {
            $this->avatar = $avatar;
        }
        public function setBanner($banner) {
            $this->banner = $banner;
        }
        public function setDt($dt) {
            $this->dt = $dt;
        }
    }
?>