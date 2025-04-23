<?php 
require_once $_SERVER['DOCUMENT_ROOT']."/e-service/views/pages/dashboard/dashboard.php";
require_once $_SERVER['DOCUMENT_ROOT']."/e-service/views/pages/admin/view_dapartement.php";
require_once $_SERVER['DOCUMENT_ROOT']."/e-service/controllers/entity/user.php";
require_once $_SERVER['DOCUMENT_ROOT']."/e-service/models/univeristy/departement.php";

session_start();

$userController =  new UserController();

$userController->checkCurrentUserAuthority(["admin"]);

$id_dep = $_GET["view"]??-1;
$departementModel = new DepartementModel(); 

$data_dep = [];
$data_head = null;

$data_dep = $departementModel->getByID($id_dep);
$data_head = $departementModel->getHead($id_dep);


if ( !is_array($data_dep) || count($data_dep) === 0){
    $userController->redirectCurrentUserBasedOnRole();
}

if ($_SERVER["REQUEST_METHOD"] === "POST"){

    $head_condidates = $departementModel->getHeadCondidates();

    if(is_array($head_condidates)){
        echo json_encode($head_condidates);
        header('Content-Type: application/json');
        http_response_code(200);
    }else {
        http_response_code(500);
    }

    die();

}else if($_SERVER["REQUEST_METHOD"] === "UPDATE"){

    

}


$dashboard = new DashBoard();

$content = (new DepartementView())->view($data_dep,$data_head);

$dashboard->view("admin", "", $content);

?>