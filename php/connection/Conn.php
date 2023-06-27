<?php
    class Conn {
        private $host;
        private $user;
        private $pass;
        private $db;
        private $conn;

        public function __construct() {
            $this->host = 'localhost';
            $this->user = 'root';
            $this->pass = '';
            $this->db = 'tpwdb';
            $this->connect();
        }

        private function connect() {
            $this->conn = new mysqli($this->host, $this->user, $this->pass, $this->db);

            if ($this->conn->connect_errno) {
                die("Connection failed: " . $this->conn->connect_error);
            }
        }

        public function getConn() {
            return $this->conn;
        }
        
        public function close() {
            $this->conn->close();
        }

        public function prepare($sql) {
            return $this->conn->prepare($sql);
        }
    }
?>