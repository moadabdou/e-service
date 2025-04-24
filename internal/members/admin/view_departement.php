<?php 
require_once $_SERVER['DOCUMENT_ROOT']."/e-service/views/pages/dashboard/dashboard.php";
require_once $_SERVER['DOCUMENT_ROOT']."/e-service/views/pages/admin/view_departement.php";
require_once $_SERVER['DOCUMENT_ROOT']."/e-service/controllers/entity/user.php";
require_once $_SERVER['DOCUMENT_ROOT']."/e-service/models/univeristy/departement.php";
require_once $_SERVER['DOCUMENT_ROOT']."/e-service/models/entity/professor.php";

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

    $head_condidates = $departementModel->getHeadCondidates($id_dep);

    if(is_array($head_condidates)){
        echo json_encode($head_condidates);
        header('Content-Type: application/json');
        http_response_code(200);
    }else {
        http_response_code(500);
    }

    die();

}else if($_SERVER["REQUEST_METHOD"] === "UPDATE"){

    $raw_data = file_get_contents("php://input");
    $data =  json_decode($raw_data, associative:true);    

    if($data !== null && isset($data["id_prof"]) && $departementModel->isQualifiedForHead($data["id_prof"])){

        $professorModel = new ProfessorModel();
        $id_prof = $data["id_prof"];

        if($professorModel->setAsDepartementHead($id_prof)){

            http_response_code(200);
        }else {
            var_dump($professorModel->getError());
            http_response_code(400);

        }
    
    }else {

        http_response_code(500);

    }
    
    die();
    
}else if($_SERVER["REQUEST_METHOD"] === "DELETE"){  

    if($departementModel->deleteHead($id_dep)){

        http_response_code(200);

    }else {
        var_dump($departementModel->getError());
        http_response_code(400);

    }
    
    die();
    
}


$dashboard = new DashBoard();

$content = (new DepartementView())->view($data_dep,$data_head);

$dashboard->view("admin", "", $content);

?>