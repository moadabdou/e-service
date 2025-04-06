<?php 

class Profile{

    public static function view(array $user_info, string $profile_picture_url, ?array $info){
        ob_start();
        require_once __DIR__."/profile.view.php";
        return ob_get_clean();
    }

}

?>