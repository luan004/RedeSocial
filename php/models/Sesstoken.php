<?php
    class Sesstoken {
        /* ATRIBUTOS */
        private $id;
        private $userId;
        private $token;

        /* CONSTRUTOR */
        public function __construct($id, $userId, $token) {
            $this->id = $id;
            $this->userId = $userId;
            $this->token = $token;
        }

        /* GETTERS */
        public function getId() {
            return $this->id;
        }
        public function getUserId() {
            return $this->userId;
        }
        public function getToken() {
            return $this->token;
        }

        /* SETTERS */
        public function setId($id) {
            $this->id = $id;
        }
        public function setUserId($userId) {
            $this->userId = $userId;
        }
        public function setToken($token) {
            $this->token = $token;
        }
    }
?>