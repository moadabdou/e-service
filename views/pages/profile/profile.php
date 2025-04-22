<?php 

class Profile{

    // settings :  setting => label

    public static function view(array $user_info, string $profile_picture_url, ?array $info){

        $general_settings = [ "change-password" => "Change your password"];

        ob_start();
        require_once __DIR__."/profile.view.php";
        return ob_get_clean();
    }

    public static function viewAsOther(array $user_info, int $id, string $profile_picture_url, ?array $info){
        ob_start();
        require_once __DIR__."/profile_other.view.php";
        return ob_get_clean();
    }

}

?>