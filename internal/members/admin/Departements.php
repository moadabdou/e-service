<?php 
require_once $_SERVER['DOCUMENT_ROOT']."/e-service/views/pages/dashboard/dashboard.php";
require_once $_SERVER['DOCUMENT_ROOT']."/e-service/views/pages/admin/departements.php";
require_once $_SERVER['DOCUMENT_ROOT']."/e-service/views/layouts/departement_form/departement_form.php";
require_once $_SERVER['DOCUMENT_ROOT']."/e-service/controllers/entity/user.php";

session_start();

$userController =  new UserController();

$userController->checkCurrentUserAuthority(["admin"]);


$dashboard = new DashBoard();
$type  = (int)($_GET["filter"]?? 0) ;

$inner_content = "vide";

if ($type ===  1){

    $inner_content = (new DepartementForm())->view();

}else if($type ===  0){
    $inner_content = "not ready yet";
}

$content = (new DepartementsView())->view($inner_content, $type);

$dashboard->view("admin", "deperatements", $content);

?>