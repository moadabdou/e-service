<?php 

class Profile{

    // settings :  title => edit form

    public static function view(array $user_info, string $profile_picture_url, ?array $info){

        $general_settings = [ "change-password" => "Change your password"];

        ob_start();
        require_once __DIR__."/profile.view.php";
        return ob_get_clean();
    }

}

?>