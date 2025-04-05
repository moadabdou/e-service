<?php 

class Profile{

    public static function view(array $user_info, string $user_pp, ?array $info){
        $profile_picture_url = "/e-service/internal/members/common/getResource.php?type=image&path=users_pp/";
        ob_start();
        require_once __DIR__."/profile.view.php";
        return ob_get_clean();
    }

}

?>