<?php

require_once $_SERVER['DOCUMENT_ROOT']."/e-service/views/pages/dashboard/dashboard.php";
require_once $_SERVER['DOCUMENT_ROOT']."/e-service/views/pages/profile/profile.php";
require_once $_SERVER['DOCUMENT_ROOT']."/e-service/models/entity/user.php";
require_once $_SERVER['DOCUMENT_ROOT']."/e-service/controllers/entity/user.php";

session_start();

$userController =  new UserController();
$userController->checkCurrentUserAuthority([]);

$dashboard = new DashBoard();
$userModel = new UserModel();
$user_info = $userController->classifyDataBySelfEditability($userModel->getNonCriticalDataById($_SESSION["id_user"]));
$user_pp = $user_info["img"]["value"];

$info = null;

if ($_SERVER["REQUEST_METHOD"] === "POST"){

    $type = $_POST["edit_type_request"]??null;

    if ($type == "info"){
        $value = isset($_POST["value"]) ? $_POST["value"] : (isset($_FILES["value"]) ? $_FILES["value"] : null);

        if ( !isset($_POST["key"]) || !$userController->isSelfEditable($_POST["key"]) ){
            $info = [
                "msg" => "Il semble que vous n'ayez pas le droit de modifier ces données",
                "type" => "danger"
            ];
        }else if(!$value || !$userController->isValidUserData($_POST["key"], $value)){

            $info = [
                "msg" => "Nous n'avons pas pu mettre à jour vos données car elles étaient invalides",
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
                //remove the  old Picture  
                $old_pp_path = $uploadDir.$user_pp; 
                if (file_exists($old_pp_path) && $user_pp != $userController->getDefaultPicture()){
                    unlink($old_pp_path);
                }

                $value = $newFileName;
                $user_pp = $newFileName;
            } else {
                $info = [
                    "msg" => "Un problème sur le serveur nous a empêché de mettre à jour votre photo de profil",
                    "type" => "danger"
                ];
            }

        }

        if (!$info){
            $res =  $userModel->updateUserColumn($_POST["key"], $value , $_SESSION["id_user"]);
            if($res){
                $user_info[$_POST["key"]]["value"] =  $value;
                $info = [
                    "msg" => "Vos données ont été mises à jour avec succès",
                    "type" => "success"
                ];
            }else {
                $info = [
                    "msg" => "Nous n'avons pas pu mettre à jour vos données en raison d'une erreur survenue",
                    "type" => "danger"
                ];
            }
        }
    
    }else if($type === "settings"){

        $settingName = $_POST["setting_name"]??null;

        if($settingName === "change-password"){
            
            $user_full_info = $userModel->getUserByID($_SESSION["id_user"]);

            if (!isset($_POST["current_password"]) || password_verify($_POST["current_password"] , $user_full_info["password"]) === false){

                $info = [
                    "msg" => "Nous n'avons pas pu mettre à jour votre mot de passe : mot de passe actuel incorrect !",
                    "type" => "danger"
                ];

            }else if( !isset($_POST["confirm_password"]) || !isset($_POST["new_password"]) || $_POST["new_password"] !== $_POST["confirm_password"]){

                $info = [
                    "msg" => "Nous n'avons pas pu mettre à jour votre mot de passe : le nouveau mot de passe et sa confirmation ne correspondent pas !",
                    "type" => "danger"
                ];

            }else {

                $hashed_password =  password_hash($_POST["new_password"], PASSWORD_DEFAULT);

                $res =  $userModel->updateUserColumn("password", $hashed_password , $_SESSION["id_user"]);
                if($res){
                    $info = [
                        "msg" => "Votre mot de passe a été mis à jour avec succès",
                        "type" => "success"
                    ];
                }else {
                    $info = [
                        "msg" => "Nous n'avons pas pu mettre à jour votre mot de passe en raison d'une erreur survenue",
                        "type" => "danger"
                    ];
                }

            }


        }

    }


}

unset($user_info["img"]); //we dont want this 
$content = Profile::view($user_info,$userController->absoluteProfilePictureUrl($user_pp), $info);
$dashboard->view("profile", $content);

?>