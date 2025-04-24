<?php 
require_once $_SERVER['DOCUMENT_ROOT']."/e-service/views/pages/dashboard/dashboard.php";
require_once $_SERVER['DOCUMENT_ROOT'] . "/e-service/views/pages/notifications/notifications.php";
require_once $_SERVER['DOCUMENT_ROOT']."/e-service/controllers/entity/user.php";
require_once $_SERVER['DOCUMENT_ROOT'] . "/e-service/models/content/notification.php";
require_once $_SERVER['DOCUMENT_ROOT']."/e-service/utils/navigation/navigation.php";

session_start();

$userController =  new UserController();

$userController->checkCurrentUserAuthority([]);

$notificationModel =  new NotificationModel();
$notification = null;

if ( !isset($_GET["id"]) || ($notification = $notificationModel->isNotificationOwnedBy($_GET["id"], $_SESSION["id_user"])) ===  false){
    Navigation::redirect("/e-service/internal/members/common/notifications.php");
}

$notification = $notificationModel->resolveNotificationData([$notification])[0];

//mark  the notification as read
$notificationModel->markAsReadByID($_GET["id"], $_SESSION["id_user"]);

$dashboard = new DashBoard();
$notificationPage = new NotificationPage();

$content =  $notificationPage->readView($notification);

$dashboard->view($_SESSION["role"], "notifications", $content);

?>