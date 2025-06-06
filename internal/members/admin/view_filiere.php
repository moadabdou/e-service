<?php
require_once $_SERVER['DOCUMENT_ROOT']."/e-service/views/pages/dashboard/dashboard.php"; 
require_once $_SERVER['DOCUMENT_ROOT']."/e-service/views/pages/admin/view_filiere.php";
require_once $_SERVER['DOCUMENT_ROOT']."/e-service/controllers/entity/user.php";
require_once $_SERVER['DOCUMENT_ROOT']."/e-service/models/univeristy/filiere.php";
require_once $_SERVER['DOCUMENT_ROOT']."/e-service/models/entity/professor.php";
require_once $_SERVER['DOCUMENT_ROOT'] . "/e-service/models/content/activity.php";
$activityModel = new ActivityModel();

session_start();

$userController =  new UserController();

$userController->checkCurrentUserAuthority(["admin"]);

$id_filiere = $_GET["view"]??-1;
$filiereModel = new FiliereModel(); 

$filiere_data = [];
$data_coor = null;

$filiere_data = $filiereModel->getByID($id_filiere);
$data_coor = $filiereModel->getCoordinator($id_filiere);

if ( !is_array($filiere_data) || count($filiere_data) === 0){
    $userController->redirectCurrentUserBasedOnRole();
}

if ($_SERVER["REQUEST_METHOD"] === "POST"){

    $coordinator_condidates = $filiereModel->getCoordinatorCondidates($id_filiere);

    if(is_array($coordinator_condidates)){
        echo json_encode($coordinator_condidates);
        header('Content-Type: application/json');
        http_response_code(200);
    }else {
        var_dump( $filiereModel->getError() );
        http_response_code(500);
    }

    die();

}else if($_SERVER["REQUEST_METHOD"] === "UPDATE"){

    $raw_data = file_get_contents("php://input");
    $data =  json_decode($raw_data, associative:true);    

    if($data !== null && isset($data["id_prof"]) && $filiereModel->isQualifiedForCoordinator($data["id_prof"])){

        $professorModel = new ProfessorModel();
        $id_prof = $data["id_prof"];

        if($professorModel->setAsFiliereCoordinator($id_prof, $id_filiere)){
            $activityModel->createActivity(
                "Le professeur ".$professorModel->getFullName($id_prof)." a été nommé coordinateur de la filière ".$filiere_data["title"]." par l'administrateur ".$_SESSION["full_name"].".", 
                "fa-user-check"
            );
            http_response_code(200);
        }else {
            var_dump($professorModel->getError());
            http_response_code(400);

        }
    
    }else if(isset($data["title"]) && isset($data["description"])){

        $title = $data['title'] ?? null;
        $desc = $data['description'] ?? null;

        if ($filiereModel->updateFiliere($id_filiere, $title, $desc) === false){
            http_response_code(400);
            var_dump($filiereModel->getError());    
        }else {
            $activityModel->createActivity(
                "La filière ".$filiere_data["title"]." a été modifiée par l'administrateur ".$_SESSION["full_name"].".", 
                "fa-user-pen"
            );
            http_response_code(200);
        }

    }else {

        http_response_code(500);

    }
    
    die();
    
}else if($_SERVER["REQUEST_METHOD"] === "DELETE"){  

    if($filiereModel->deleteCoordinator($id_filiere)){

        $activityModel->createActivity(
            "Le coordinateur de la filière ".$filiere_data["title"]." a été supprimé par l'administrateur ".$_SESSION["full_name"].".", 
            "fa-user-xmark"
        );
        
        http_response_code(200);

    }else {
        var_dump($filiereModel->getError());
        http_response_code(400);

    }
    
    die();
    
}


$dashboard = new DashBoard();

$content = (new FiliereView())->view($filiere_data,$data_coor);

$dashboard->view("", $content);

?>