<?php
    class User {
        /* ATRIBUTOS */
        private $id;
        private $name;
        private $user;
        private $pass;
        private $avatar;
        private $banner;

        /* CONSTRUTOR */
        public function __construct($id, $name, $user, $pass, $avatar, $banner) {
            $this->id = $id;
            $this->name = $name;
            $this->user = $user;
            $this->pass = $pass;
            $this->avatar = $avatar;
            $this->banner = $banner;
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
        public function getAvatar() {
            return $this->avatar;
        }
        public function getBanner() {
            return $this->banner;
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
        public function setAvatar($avatar) {
            $this->avatar = $avatar;
        }
        public function setBanner($banner) {
            $this->banner = $banner;
        }
    }
?>