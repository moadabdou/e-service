<?php 
require_once $_SERVER['DOCUMENT_ROOT']."/e-service/views/pages/dashboard/dashboard.php";
require_once $_SERVER['DOCUMENT_ROOT']."/e-service/views/pages/admin/allUsers.php";
require_once $_SERVER['DOCUMENT_ROOT']."/e-service/controllers/entity/user.php";

session_start();

$userController =  new UserController();

$userController->checkCurrentUserAuthority(["admin"]);



$dashboard = new DashBoard();

$type  = (int)($_GET["filter"]?? 0) ;

$content = (new AllUsersView())->view($type, [["/e-service/internal/members/common/getResource.php?type=image&path=users_pp/default.webp", "moad abou",  "24/12/30"]]);

$dashboard->view("admin", "allUsers", $content);

?>