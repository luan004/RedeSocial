<?php
    class NotificationDAO {
        private $conn;

        public function __construct($conn) {
            $this->conn = $conn;
        }
    
        public function create(Notification $notification) {
            $type = $notification->getType();
            $userId = $notification->getUserId();
            $authorId = $notification->getAuthorId();
            $postId = $notification->getPostId();
        
            $stmt = $this->conn->prepare("INSERT INTO notifications (type, user_id, author_id, post_id, dt) VALUES (?, ?, ?, ?, NOW())");
            $stmt->bind_param("iiii", $type, $userId, $authorId, $postId);
            $stmt->execute();
    
            $id = $stmt->insert_id;
            $notification->setId($id);
    
            $stmt->close();
        }
    
        public function getNotificationById() {
            $stmt = $this->conn->prepare("SELECT * FROM notifications WHERE id = ?");
            $stmt->bind_param("i", $id);
            $stmt->execute();
    
            if ($stmt->num_rows > 0) {
                $row = $stmt->fetch_assoc();
                $notification = new Notification(
                    $row['id'],
                    $row['type'],
                    $row['user_id'],
                    $row['author_id'],
                    $row['post_id'],
                    $row['dt']
                );
            } else {
                $notification = null;
            }
    
            $stmt->close();
            return $notification;
        }
    
        public function getNotificationsByUserId($userId, $limit) {
            $stmt = $this->conn->prepare("SELECT * FROM notifications WHERE user_id = ? ORDER BY dt DESC LIMIT ?");
            $stmt->bind_param("ii", $userId, $limit);
            $stmt->execute();
            $result = $stmt->get_result();
        
            $notifications = array();
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    $notification = new Notification(
                        $row['id'],
                        $row['type'],
                        $row['user_id'],
                        $row['author_id'],
                        $row['post_id'],
                        $row['dt']
                    );
                    array_push($notifications, $notification);
                }
            }
        
            $stmt->close();
            return $notifications;
        }
    }
?>