<?php
    class UserDAO {
        private $conn;
    
        public function __construct($conn) {
            $this->conn = $conn;
        }
    
        public function create(User $userObj) {
            $name = $userObj->getName();
            $user = $userObj->getUser();
            $pass = $userObj->getPass();
            $aboutme = $userObj->getAboutMe();
            $color = $userObj->getColor();
            $avatar = $userObj->getAvatar();
            $banner = $userObj->getBanner();
        
            $stmt = $this->conn->prepare("INSERT INTO users (name, user, pass, aboutme, color, avatar, banner, dt) VALUES (?, ?, ?, ?, ?, ?, ?, CONVERT_TZ(NOW(), '+00:00', '-03:00'))");
            $stmt->bind_param("sssssss", $name, $user, $pass, $aboutme, $color, $avatar, $banner);
            $stmt->execute();

            $id = $stmt->insert_id;
            $userObj->setId($id);

            $stmt->close();
        }
        
        public function update(User $user) {
            $id = $user->getId();
            $name = $user->getName();
            $username = $user->getUser();
            $password = $user->getPass();
            $aboutme = $user->getAboutMe();
            $color = $user->getColor();
            $avatar = $user->getAvatar();
            $banner = $user->getBanner();

            $stmt = $this->conn->prepare("UPDATE users SET name=?, user=?, pass=?, aboutme=?, color=?, avatar=?, banner=? WHERE id=?");
            $stmt->bind_param("sssssssi", $name, $username, $password, $aboutme, $color, $avatar, $banner, $id);
            $stmt->execute();
            $stmt->close();
        }
    
        public function delete(User $user) {
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
                    $row['aboutme'],
                    $row['color'],
                    $row['avatar'],
                    $row['banner'],
                    $row['dt']
                );
                return $user;
            } else {
                return null;
            }
        }

        public function getUserById($id) {
            $stmt = $this->conn->prepare("SELECT * FROM users WHERE id = ?");
            $stmt->bind_param("i", $id);
            $stmt->execute();
            $result = $stmt->get_result();
        
            if ($result->num_rows > 0) {
                $row = $result->fetch_assoc();
                $user = new User(
                    $row['id'],
                    $row['name'],
                    $row['user'],
                    $row['pass'],
                    $row['aboutme'],
                    $row['color'],
                    $row['avatar'],
                    $row['banner'],
                    $row['dt']
                );
                return $user;
            } else {
                return null;
            }
        }

        function getUsersBySearch($search, $limit) {
            $stmt = $this->conn->prepare("SELECT * FROM users WHERE name LIKE ? OR user LIKE ? LIMIT ?");
            $stmt->bind_param("ssi", $search, $search, $limit);
            $stmt->execute();
            $result = $stmt->get_result();

            $users = array();

            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    $user = new User(
                        $row['id'],
                        $row['name'],
                        $row['user'],
                        $row['pass'],
                        $row['aboutme'],
                        $row['color'],
                        $row['avatar'],
                        $row['banner'],
                        $row['dt']
                    );

                    array_push($users, $user);
                }
            }
            return $users;
        }

        function getRandomUsers($limit) {
            $stmt = $this->conn->prepare("SELECT user, name, avatar FROM users ORDER BY RAND() LIMIT ".$limit.";");
            $stmt->execute();
            $result = $stmt->get_result();

            $users = array();

            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    $user = new User(
                        null,
                        $row['name'],
                        $row['user'],
                        null,
                        null,
                        null,
                        $row['avatar'],
                        null,
                        null
                    );

                    array_push($users, $user);
                }
            }
            return $users;
        }
    }
?>