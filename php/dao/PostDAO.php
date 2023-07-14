<?php
    class PostDAO {
        private $conn;

        public function __construct($conn) {
            $this->conn = $conn;
        }

        public function create(Post $post) {
            $user = $post->getUserId();
            $text = $post->getText();
            $image = $post->getImage();

            $sql = "INSERT INTO posts (user_id, text, image, dt) VALUES (?, ?, ?, NOW())";
            $stmt = $this->conn->prepare($sql);
            $stmt->bind_param("sss", $user, $text, $image);
            $stmt->execute();

            $id = $stmt->insert_id;
            $post->setId($id);

            $stmt->close();
        }

        public function delete(Post $post) {
            $id = $post->getId();

            /* DELETE ALL LIKES */
            $sql = "DELETE FROM likes WHERE post_id = ?";
            $stmt = $this->conn->prepare($sql);
            $stmt->bind_param("i", $id);
            $stmt->execute();

            /* DELETE ALL COMMENTS */
            $sql = "DELETE FROM comments WHERE post_id = ?";
            $stmt = $this->conn->prepare($sql);
            $stmt->bind_param("i", $id);
            $stmt->execute();

            /* CHECK IF POST HAS IMAGE, IF TRUE, DELETE */
            $image = $post->getImage();
            if ($image) {
                $files = new Files();
                $files->deleteImage($image);
            }

            $sql = "DELETE FROM posts WHERE id = ?";
            $stmt = $this->conn->prepare($sql);
            $stmt->bind_param("i", $id);
            $stmt->execute();

            $stmt->close();
        }

        public function getFeed($reqUserId) {
            $stmt = $this->conn->prepare("SELECT * FROM posts WHERE user_id IN (SELECT followed_id FROM followers WHERE follower_id = ?) OR user_id = ? ORDER BY dt DESC");
            $stmt->bind_param("ii", $reqUserId, $reqUserId);
            $stmt->execute();
            $result = $stmt->get_result();
            $posts = array();

            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    $post = new Post(
                        $row['id'],
                        $row['user_id'],
                        $row['text'],
                        $row['image'],
                        $row['dt']
                    );
                    array_push($posts, $post);
                }
            }

            $stmt->close();
            return $posts;
        }
        
        public function getAllPosts($reqUserId) {
            $stmt = $this->conn->prepare("SELECT * FROM posts ORDER BY dt DESC");
            $stmt->execute();
            $result = $stmt->get_result();
            $posts = array();

            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    $post = new Post(
                        $row['id'],
                        $row['user_id'],
                        $row['text'],
                        $row['image'],
                        $row['dt']
                    );
                    array_push($posts, $post);
                }
            }

            $stmt->close();
            return $posts;
        }
        
        public function getPostById($id) {
            $stmt = $this->conn->prepare("SELECT * FROM posts WHERE id = ?");
            $stmt->bind_param("i", $id);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows > 0) {
                $row = $result->fetch_assoc();
                $post = new Post(
                    $row['id'],
                    $row['user_id'],
                    $row['text'],
                    $row['image'],
                    $row['dt']
                );
                $stmt->close();
                return $post;
            } else {
                $stmt->close();
                return false;
            }
        }

        public function getPostsByUserId($id, $reqUserId) {
            $stmt = $this->conn->prepare("SELECT * FROM posts WHERE user_id = ? ORDER BY dt DESC");
            $stmt->bind_param("i", $id);
            $stmt->execute();
            $result = $stmt->get_result();
            $posts = array();

            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    $post = new Post(
                        $row['id'],
                        $row['user_id'],
                        $row['text'],
                        $row['image'],
                        $row['dt']
                    );
                    array_push($posts, $post);
                }
            }

            $stmt->close();
            return $posts;
        }

        public function getHashtags($opt) {
            if ($opt == 'today') {
                $stmt = $this->conn->prepare("SELECT * FROM posts WHERE text LIKE '%#%' AND DATE(dt) = CURDATE()");
            } else {
                $stmt = $this->conn->prepare("SELECT * FROM posts WHERE text LIKE '%#%'");
            }
                
            $stmt->execute();
            $result = $stmt->get_result();
            $posts = array();

            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    $post = new Post(
                        null,
                        null,
                        $row['text'],
                        null,
                        null
                    );
                    array_push($posts, $post);
                }
            }

            $stmt->close();
            return $posts;
        }
    }
?>