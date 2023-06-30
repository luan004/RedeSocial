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

                    $post = array(
                        'postId' => $row['id'],
                        'user' =>  array(
                            'userId' => $row['user_id'],
                            'name' => $row2['name'],
                            'user' => $row2['user'],
                            'avatar' => $row2['avatar']
                        ),
                        'text' => $row['text'],
                        'image' => $row['image'],
                        'likes' => $row['likes'],
                        'ismy' => $reqUserId == $row['user_id'],
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