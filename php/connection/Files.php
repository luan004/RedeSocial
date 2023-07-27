<?php
    // storage files in server
    class Files {
        private $path = '../storage/';

        public function __construct() {
            if (!file_exists($this->path)) {
                mkdir($this->path, 0777, true);
            }
        }

        public function saveB64Image($fileString, $type, $id) {
            // Decodifique a string da imagem, remova o cabeçalho de codificação e salve-a em um arquivo
            $imageData = base64_decode(preg_replace('#^data:image/\w+;base64,#i', '', $fileString));
    
            $newName = $id . '-' . $type . '-' . uniqid() . '.png'; // Use a extensão adequada aqui
            $dest = $this->path . $newName;
            file_put_contents($dest, $imageData);
    
            return $newName;
        }

        public function getB64Image($imageName) {
            $dest = $this->path . $imageName;
            if (file_exists($dest)) {
                $image = file_get_contents($dest);
                $imageData = base64_encode($image);
                return $imageData;
            } else {
                return null;
            }
        }

        public function deleteImage($imageName) {
            $dest = $this->path . $imageName;
            unlink($dest);
        }

        public function deleteAllUserFiles($userId) {
            $files = glob($this->path . $userId . '-*');
            foreach ($files as $file) {
                unlink($file);
            }
        }
    }
?>