<?php
    class UserDAO {
        private $conn;
    
        public function __construct($conn) {
            $this->conn = $conn;
        }
    
        public function createUser(User $userObj) {
            $name = $userObj->getName();
            $user = $userObj->getUser();
            $pass = $userObj->getPass();
            $avatar = $userObj->getAvatar();
            $banner = $userObj->getBanner();
        
            $stmt = $this->conn->prepare("INSERT INTO users (name, user, pass, avatar, banner) VALUES (?, ?, ?, ?, ?)");
            $stmt->bind_param("sssss", $name, $user, $pass, $avatar, $banner);
            $stmt->execute();

            $id = $stmt->insert_id;
            $userObj->setId($id);

            $stmt->close();
        }
        
        public function updateUser(User $user) {
            $id = $user->getId();
            $name = $user->getName();
            $username = $user->getUser();
            $password = $user->getPass();
            $avatar = $user->getAvatar();
            $banner = $user->getBanner();

            $stmt = $this->conn->prepare("UPDATE users SET name=?, user=?, pass=?, avatar=?, banner=? WHERE id=?");
            $stmt->bind_param("sssssi", $name, $username, $password, $avatar, $banner, $id);
            $stmt->execute();
            $stmt->close();
        }
    
        public function deleteUser(User $user) {
            $id = $user->getId();
    
            $stmt = $this->conn->prepare("DELETE FROM users WHERE id=?");
            $stmt->bind_param("i", $id);
            $stmt->execute();
            $stmt->close();
        }

        public function getUserByUsername($username) {
            $stmt = $this->conn->prepare("SELECT * FROM users WHERE user = ?");
            $stmt->bind_param("s", $username);
            $stmt->execute();
            $result = $stmt->get_result();
        
            if ($result->num_rows > 0) {
                $row = $result->fetch_assoc();
                $user = new User(
                    $row['id'],
                    $row['name'],
                    $row['user'],
                    $row['pass'],
                    $row['avatar'],
                    $row['banner']
                );
                return $user;
            } else {
                return null;
            }
        }
        
    }
    
?>