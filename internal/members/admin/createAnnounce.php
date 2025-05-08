<?php 
require_once $_SERVER['DOCUMENT_ROOT']."/e-service/views/pages/admin/createAnnounce.php";
require_once $_SERVER['DOCUMENT_ROOT']."/e-service/views/pages/dashboard/dashboard.php";
require_once $_SERVER['DOCUMENT_ROOT']."/e-service/controllers/entity/user.php";
require_once $_SERVER['DOCUMENT_ROOT']."/e-service/models/content/announce.php";
require_once $_SERVER['DOCUMENT_ROOT'] . "/e-service/models/content/activity.php";
$activityModel = new ActivityModel();
session_start();

$userController =  new UserController();

$userController->checkCurrentUserAuthority(["admin"]);


$dashboard = new DashBoard();
$announceModel = new AnnounceModel();

$info = [];

if ( $_SERVER["REQUEST_METHOD"] === 'POST'){

    $title = $_POST["title"]??null;
    $content = $_POST["content"] ?? null;

    if ($title && $content && preg_match("/^[a-zA-Z0-9\s.,!?()-]{200,400}$/", $content) && preg_match("/^[a-zA-Z ]{8,50}$/", $title)){
        if ($announceModel->createAnnounce($_SESSION["id_user"] ,$title, $content) === false) {
            $info = [
                "msg" => "La création de l'annonce a échoué. Veuillez réessayer.",
                "type" => "danger"
            ];



        } else {
            $info = [
                "msg" => "L'annonce a été créée avec succès !",
                "type" => "success"
            ];

            $activityModel->createActivity(
                "L'annonce ".$title." a été créée par l'administrateur ".$_SESSION["full_name"].".", 
                $_SESSION["id_user"]
            );
        }
    } else {
        $info = [
            "msg" => "Les données saisies sont invalides. Veuillez vérifier et réessayer.",
            "type" => "danger"
        ];
    }

}

$content = (new CreateAnnounceView())->view($info);

$dashboard->view("admin", "createAnnounce", $content);

?>