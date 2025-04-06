<?php 

class NotificationPage{

    public function view(array $notifications, string $type){
        ob_start();
        require_once __DIR__."/notifications.view.php";
        return ob_get_clean();
    }

    public function readView(array $notification){
        ob_start();
        require_once __DIR__."/read_notifications.view.php";
        return ob_get_clean();
    }

}

?>