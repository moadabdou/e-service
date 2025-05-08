<?php 
require_once $_SERVER['DOCUMENT_ROOT']."/e-service/views/pages/dashboard/dashboard.php";
require_once $_SERVER['DOCUMENT_ROOT']."/e-service/views/pages/admin/allUsers.php";
require_once $_SERVER['DOCUMENT_ROOT']."/e-service/controllers/entity/user.php";
require_once $_SERVER['DOCUMENT_ROOT']."/e-service/models/entity/user.php";


session_start();

$userController =  new UserController();

$userController->checkCurrentUserAuthority(["admin"]);


/*
     * type is the  type of users you  wanna  show  
     *  0 => professors
     *  1 => departement head  
     *  2 => coordinator 
     *  3 => vacataires 
     *  4 => admins 
*/

$dashboard = new DashBoard();
$userModel = new UserModel();

$role  = (int)($_GET["role"]?? 0) ;
$status = $_GET["status"]?? 0 ;
$users_data  =  $userModel->getUsersByRole($role, $status);

if ($users_data === false){
    echo  $userModel->getError();
}

$content = (new AllUsersView())->view($role, $status, $users_data === false ?  [] : $users_data);

$dashboard->view("admin", "allUsers", $content);

?>