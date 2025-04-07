<?php
require_once $_SERVER['DOCUMENT_ROOT'] . "/e-service/views/pages/dashboard/dashboard.php";
require_once $_SERVER['DOCUMENT_ROOT'] . "/e-service/views/pages/notifications/notifications.php";
require_once $_SERVER['DOCUMENT_ROOT'] . "/e-service/controllers/entity/user.php";
require_once $_SERVER['DOCUMENT_ROOT'] . "/e-service/models/content/notification.php";

session_start();

$userController =  new UserController();
$userController->checkCurrentUserAuthority([]);


$notificationModel = new NotificationModel();


if ( $_SERVER["REQUEST_METHOD"] == "DELETE"){
    $rawInput = file_get_contents("php://input");
    $data = json_decode($rawInput, true);

    $noti_id = $data['notification_id'] ?? null;

    if ( !$notificationModel->deleteNotificationById($noti_id, $_SESSION["id_user"]) ){
        http_response_code(204);
    }

    die();
} 

enum NOTIFICATIONS: int {
    const MARKALLREAD = 0;
}

if ( $_SERVER["REQUEST_METHOD"] == "UPDATE"){
    $rawInput = file_get_contents("php://input");
    $data = json_decode($rawInput, true);

    $action = $data['action'] ?? null;

    if ($action === null || !is_numeric($action)){
        http_response_code(400);
        die();
    }
    
    $action = (int)$action;

    if( $action === NOTIFICATIONS::MARKALLREAD ){

        if( $notificationModel->markAllAsRead($_SESSION["id_user"]) ){
            http_response_code(200);
        }else{
            http_response_code(204);
        }

    }else {
        http_response_code(400);
    }

    die();
} 





$dashboard = new DashBoard();

$notification = [];
$maxPages = 0;
$type = "all";
$page  = (int)($_GET["page"] ?? 1);

if(isset($_GET["type"]) && $_GET["type"] === "unread" ){
    $notifications =  $notificationModel->getUnreadNotificationByUserId($_SESSION["id_user"], $page);
    $maxPages =  $notificationModel->getUnreadMaxPages($_SESSION["id_user"]);
    $type = "unread";
}else {
    $maxPages = $notificationModel->getMaxPages($_SESSION["id_user"]);
    $notifications =  $notificationModel->getAllNotificationByUserId($_SESSION["id_user"], $page);
}

$notificationPage = new NotificationPage();

$content =  $notificationPage->view($notifications, $type, $page, $maxPages);

$dashboard->view($_SESSION["role"], "notifications", $content);

?>