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
            $likes = $post->getLikes();

            $sql = "INSERT INTO posts (user_id, text, image, likes, dt) VALUES (?, ?, ?, ?, NOW())";
            $stmt = $this->conn->prepare($sql);
            $stmt->bind_param("ssss", $user, $text, $image, $likes);
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

            $sql = "DELETE FROM posts WHERE id = ?";
            $stmt = $this->conn->prepare($sql);
            $stmt->bind_param("i", $id);
            $stmt->execute();

            $stmt->close();
        }
        
        public function getAllPosts($reqUserId) {
            $stmt = $this->conn->prepare("SELECT * FROM posts ORDER BY dt DESC");
            $stmt->execute();
            $result = $stmt->get_result();
            $posts = array();

            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    // get user info
                    $stmt = $this->conn->prepare("SELECT name, user, avatar FROM users WHERE id = ?");
                    $stmt->bind_param("i", $row['user_id']);
                    $stmt->execute();
                    $result2 = $stmt->get_result();
                    $row2 = $result2->fetch_assoc();

                    if ($row2['avatar'] == null) $row2['avatar'] = 'https://ui-avatars.com/api/background=0D8ABC&color=fff?name='.$row2['user'];

                    // get likes count
                    $stmt = $this->conn->prepare("SELECT * FROM likes WHERE post_id = ?");
                    $stmt->bind_param("i", $row['id']);
                    $stmt->execute();
                    $result3 = $stmt->get_result();
                    $likes = $result3->num_rows;
                    
                    // check if a liked
                    $stmt = $this->conn->prepare("SELECT * FROM likes WHERE post_id = ? AND user_id = ?");
                    $stmt->bind_param("ii", $row['id'], $reqUserId);
                    $stmt->execute();
                    $result4 = $stmt->get_result();

                    $post = array(
                        'id' => $row['id'],
                        'user' =>  array(
                            'id' => $row['user_id'],
                            'name' => $row2['name'],
                            'user' => $row2['user'],
                            'avatar' => $row2['avatar']
                        ),
                        'text' => $row['text'],
                        'image' => $row['image'],
                        'likes' => $likes,
                        'ismy' => $reqUserId == $row['user_id'],
                        'iliked' => $result4->num_rows > 0,
                        'dt' => $row['dt'],
                    );
                    array_push($posts, $post);
                }
                $stmt->close();
                return array(
                    'type' => 'allPosts',
                    'count' => $result->num_rows,
                    'posts' => $posts
                );
            } else {
                $stmt->close();
                return false;
            }
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
                    $row['likes'],
                    $row['dt']
                );
                $stmt->close();
                return $post;
            } else {
                $stmt->close();
                return false;
            }
        }
    }
?>