<?php

use function PHPSTORM_META\type;

require_once $_SERVER['DOCUMENT_ROOT']."/e-service/views/pages/dashboard/dashboard.php";
require_once $_SERVER['DOCUMENT_ROOT']."/e-service/views/pages/profile/profile.php";
require_once $_SERVER['DOCUMENT_ROOT']."/e-service/models/entity/user.php";
require_once $_SERVER['DOCUMENT_ROOT']."/e-service/controllers/entity/user.php";

session_start();

$userController =  new UserController();
$userController->checkCurrentUserAuthority([]);

$dashboard = new DashBoard();
$userModel = new UserModel();

$user_info = $userController->classifyDataBySelfEditability($userModel->getNonCriticalDataByEmail($_SESSION["email"]));
$user_pp = $user_info["img"]["value"];
unset($user_info["img"]);

$info = null;

if ($_SERVER["REQUEST_METHOD"] === "POST"){

    $value = isset($_POST["value"]) ? $_POST["value"] : (isset($_FILES["value"]) ? $_FILES["value"] : null);

    if ( !isset($_POST["key"]) || !$userController->isSelfEditable($_POST["key"]) ){
        $info = [
            "msg" => "Seems like  you dont have the right to edit this Data",
            "type" => "danger"
        ];
    }else if(!$value || !$userController->isValidUserData($_POST["key"], $value)){

        $info = [
            "msg" => "we were not able to  update your data because it was invalid",
            "type" => "danger"
        ];

    }else if ($_POST["key"] === "img"){

        $random16 = str_pad(mt_rand(0, 9999999999999999), 16, '0', STR_PAD_LEFT);


        $newFileName = "{$_SESSION["id_user"]}_{$random16}.png";

  
        $uploadDir = $_SERVER['DOCUMENT_ROOT']."/e-service/storage/image/users_pp/"; // Absolute path is safer
        $targetPath = $uploadDir . $newFileName;

        if (!file_exists($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }

        if (move_uploaded_file($value['tmp_name'], $targetPath)) {
            //remove the  old pecture  
            $old_pp_path = $uploadDir.$user_pp; 
            if (file_exists($old_pp_path)){
                unlink($old_pp_path);
            }

            $value = $newFileName;
            $user_pp = $newFileName;
        } else {
            $info = [
                "msg" => "a problem in  the server prevented us from updating your profile photo",
                "type" => "danger"
            ];
        }

    }

    if (!$info){
        $res =  $userModel->updateUserColumn($_POST["key"], $value , $_SESSION["id_user"]);
        if($res){
            $info = [
                "msg" => "your date is  updated seccessfuly",
                "type" => "success"
            ];
        }else {
            $info = [
                "msg" => "we were not able to  update your data because of an occured error ".$userModel->getError(),
                "type" => "danger"
            ];
        }
    }

}



$content = Profile::view($user_info, $user_pp, $info);
$dashboard->view($_SESSION["role"], "profile", $content);

?>