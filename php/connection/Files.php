<?php
    // storage files in server
    class Files {
        private $path = '../storage/';

        public function __construct() {
            if (!file_exists($this->path)) {
                mkdir($this->path, 0777, true);
            }
        }

        public function saveFile($file) {
            $name = $file['name'];
            $tmp = $file['tmp_name'];
            $ext = pathinfo($name, PATHINFO_EXTENSION);
            $newName = uniqid() . '.' . $ext;
            $dest = $this->path . $newName;
            move_uploaded_file($tmp, $dest);
            return $newName;
        }
    }
?>