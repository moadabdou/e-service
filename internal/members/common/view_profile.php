<?php 
require_once $_SERVER['DOCUMENT_ROOT']."/e-service/views/pages/dashboard/dashboard.php";
require_once $_SERVER['DOCUMENT_ROOT']."/e-service/controllers/entity/user.php";
require_once $_SERVER['DOCUMENT_ROOT']."/e-service/views/pages/profile/profile.php";
require_once $_SERVER['DOCUMENT_ROOT']."/e-service/models/entity/user.php";
require_once $_SERVER['DOCUMENT_ROOT']."/e-service/utils/passwordGenerator/passwordGenerator.php"; 
require_once $_SERVER['DOCUMENT_ROOT']."/e-service/utils/email/prepared_emails.php";
require_once $_SERVER['DOCUMENT_ROOT'] . "/e-service/models/content/notification.php";
require_once $_SERVER['DOCUMENT_ROOT']."/e-service/utils/navigation/navigation.php";

session_start();

$userController =  new UserController();

$userController->checkCurrentUserAuthority([]);


$userModel = new UserModel();


$info = null;
$id = (int)$_GET["id"]??-1;

if ($id === $_SESSION["id_user"]){
   Navigation::redirect("/e-service/internal/members/common/profile.php"); 
}

$user_data = $userModel->getNonCriticalDataById($id);

if (empty($user_data)){
    $userController->redirectCurrentUserBasedOnRole();
}

if ($_SERVER["REQUEST_METHOD"] === "POST" && $_SESSION["role"] === "admin" && !empty($user_data)){
    $type_request =$_POST["type_request"]??null;

    if ($type_request === 'info_update'){

            
        $value = isset($_POST["value"]) ? $_POST["value"] : null;
        if(!$value || $id===-1 || !$userController->isValidUserData($_POST["key"], $value) ){

            $info = [
                "msg" => "nous n'avons pas pu mettre à jour les données car elles sont invalides",
                "type" => "danger"
            ];
        }

        if (!$info){
            $res =  $userModel->updateUserColumn($_POST["key"], $value , $id);
            if($res){
                $user_data[$_POST["key"]] =  $value;
                $info = [
                "msg" => "les données ont été mises à jour avec succès",
                "type" => "success"
                ];
            }else {
                $info = [
                    "msg" => "nous n'avons pas pu mettre à jour les données en raison d'une erreur serveur",
                    "type" => "danger"
                ];
            }
        }

    }else if($type_request === 'account_operation'){

        $operation = $_POST["type_operation"]??null;
        
        if ($operation === "reset_password"){

            $email = new PreparedEmails();
            $generated_password = (new PasswordGenerator())->generate();

            $res = $email->passwordResetEmail($user_data["email"], $generated_password, $user_data["firstName"]);

            if ($res === true){

                $new_password = $userModel->updateUserColumn("password", password_hash($generated_password, PASSWORD_DEFAULT), $id);

                if($new_password === false){
    
                    $info = [
                        "msg" => "nous n'avons pas pu réinitialiser le mot de passe",
                        "type" => "danger"
                    ];
    
                }else {
                    $notificationModel = new NotificationModel();

                    $notificationModel->createNotification(
                        $id, 
                        "Réinitialisation du mot de passe", 
                        "Votre mot de passe a été réinitialisé par un administrateur. Veuillez vérifier votre email pour obtenir votre nouveau mot de passe temporaire.",
                        null
                    );

                    $info = [
                        "msg" => "le mot de passe a été réinitialisé avec succès",
                        "type" => "success"
                    ];

                }

            }else {

                $info = [
                    "msg" => "nous n'avons pas pu réinitialiser le mot de passe car l'email n'a pas pu être envoyé",
                    "type" => "danger"
                ];

            }

        }else if ($operation === "desactivate_account"){
            $res =  $userModel->updateUserColumn("status", "disabled" , $id);
            if($res === true){

                $user_data["status"] =  "disabled";
                $info = [
                    "msg" => "le compte a été désactivé avec succès",
                    "type" => "success"
                ];
                
            }else {

                $info = [
                    "msg" => "nous n'avons pas pu désactiver le compte",
                    "type" => "danger"
                ];

            }
        }else if($operation === "activate_account"){
            $res =  $userModel->updateUserColumn("status", "active" , $id);
            if($res === true){
                $user_data["status"] =  "active";
                $info = [
                    "msg" => "le compte a été activé avec succès",
                    "type" => "success"
                ];
                
            }else {

                $info = [
                    "msg" => "nous n'avons pas pu activer le compte",
                    "type" => "danger"
                ];

            }
        }


    }
    
}


if ($user_data === false){
    echo $userModel->getError();
}

$dashboard = new DashBoard();

$pp = $userController->absoluteProfilePictureUrl($user_data["img"]);
unset($user_data["img"]);

$content = Profile::viewAsOther($user_data,$id, $pp , $info);

$dashboard->view($_SESSION["role"], "", $content);
?>