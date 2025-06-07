<?php
require_once $_SERVER['DOCUMENT_ROOT'] . "/e-service/views/pages/dashboard/dashboard.php";
require_once $_SERVER['DOCUMENT_ROOT'] . "/e-service/views/pages/announces/announces.php";
require_once $_SERVER['DOCUMENT_ROOT'] . "/e-service/models/content/announce.php";
require_once $_SERVER['DOCUMENT_ROOT'] . "/e-service/controllers/entity/user.php";

session_start();

$userController =  new UserController();
$userController->checkCurrentUserAuthority([]);


$announceModel = new AnnounceModel();


if ( $_SERVER["REQUEST_METHOD"] === "DELETE") {
    $rawInput = file_get_contents("php://input");
    $data = json_decode($rawInput, true);

    $announce_id = $data['announce_id'] ?? -1;
    if ($announce_id) {
        $result = $announceModel->deleteAnnounce($announce_id, $_SESSION["id_user"]);
        if ($result) {
            echo json_encode(["success" => true]);
        } else {
            echo json_encode(["success" => false]);
        }
    } else {
        echo json_encode(["success" => false, "error" => "Invalid ID"]);
    }

    die();
} 



$dashboard = new DashBoard();

$announces = $announceModel->getAllAnnounces();


$announcesPage = new AnnouncesPage();

$content =  $announcesPage->view($announces);

$dashboard->view("announces", $content);

?>