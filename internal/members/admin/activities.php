<?php
require_once $_SERVER['DOCUMENT_ROOT'] . "/e-service/views/pages/dashboard/dashboard.php";
require_once $_SERVER['DOCUMENT_ROOT'] . "/e-service/views/pages/admin/activities.php";
require_once $_SERVER['DOCUMENT_ROOT'] . "/e-service/models/content/activity.php";
require_once $_SERVER['DOCUMENT_ROOT'] . "/e-service/controllers/entity/user.php";

session_start();

$userController =  new UserController();
$userController->checkCurrentUserAuthority(["admin"]);


$activityModel = new ActivityModel();


$dashboard = new DashBoard();

$activities = $activityModel->getAllactivities();


$activitiesPage = new activitiesPage();

$content =  $activitiesPage->view($activities);

$dashboard->view("admin" , "", $content);

?>