<?php 

require_once __DIR__."/../model.php"; 

class NotificationModel extends Model{
    
    public function __construct()
    {
        parent::__construct();
    }

    public function getAllNotificationByUserId(int $userId): array{

        if ($this->db->query("SELECT * FROM notifications WHERE id_user=? ORDER BY date_time DESC", [$userId])){
            return $this->resolveNotificationData( $this->db->fetchAll(PDO::FETCH_ASSOC));
        }else {
            throw $this->db->getError(); //for now we gonna throw all  select queries
        }

    }

    public function getUnreadNotificationByUserId(int $userId): array{

        if ($this->db->query("SELECT * FROM notifications WHERE id_user=? AND status='unread' ORDER BY date_time DESC", [$userId])){
            return $this->resolveNotificationData( $this->db->fetchAll(PDO::FETCH_ASSOC));
        }else {
            throw $this->db->getError(); //for now we gonna throw all  select queries
        }

    }

    public function getUnreadNotificationCountByUserId(int $userId): int{
        
        if ($this->db->query("SELECT * FROM notifications WHERE id_user=? AND status='unread' ORDER BY date_time DESC", [$userId])){
            return $this->db->rowCount();
        }else {
            throw $this->db->getError(); //for now we gonna throw all  select queries
        }

    }

    public function resolveNotificationData(array $notifications): array{

        foreach($notifications as &$notification){
            foreach($notification as $key => $val){
                $notification[$key] = htmlspecialchars($val);
                if ($key === "image_url"){
                    $notification[$key] =  $val ?? "/e-service/storage/image/notification/default.webp";
                }else if($key === "date_time"){
                    $datetime = new DateTime($val);
                    $now = new DateTime();
                    $interval = $now->diff($datetime);
                    
                    if ($interval->d > 0){
                        $notification[$key] = $val;
                    } elseif ($interval->h > 0) {
                        $notification[$key] = $interval->h . ' hours ago';
                    } elseif ($interval->i > 0) {
                        $notification[$key] = $interval->i . ' minutes ago';
                    } else {
                        $notification[$key] = 'just now';
                    }
                }
            }
        }
        return $notifications;

    }

    public function deleteNotificationById(int $id, int $id_user) : bool {
        
        if ($this->db->query("DELETE FROM notifications WHERE id_notification = ? AND id_user=?", [$id, $id_user])) {
            return $this->db->rowCount() > 0;
        } else {
            $this->error = $this->db->getError();
            return false;
        }

    }

    public function markAllAsRead(int $id_user) : bool {
        
        if ($this->db->query("UPDATE notifications  SET status='read'  WHERE id_user=? AND status='unread'", [$id_user])) {
            return true;
        } else {
            $this->error = $this->db->getError();
            return false;
        }

    }

    public function markAllAsReadByID(int $id, int $id_user) : bool {
        
        if ($this->db->query("UPDATE notifications  SET status='read'  WHERE id_user=? AND id_notification=? ", [$id_user, $id])) {
            return true;
        } else {
            $this->error = $this->db->getError();
            return false;
        }

    }

    public function isNotificationOwnedBy(int $id, int $id_user) : array | false{
        if ($this->db->query("SELECT * FROM notifications WHERE id_user=? AND id_notification=?", [$id_user, $id])){
            return $this->db->rowCount() > 0 ? $this->db->fetch() : false;
        }else {
            throw $this->db->getError(); //for now we gonna throw all  select queries
        }
    }


}

?>