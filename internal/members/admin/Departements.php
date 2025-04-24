<?php 
require_once $_SERVER['DOCUMENT_ROOT']."/e-service/views/pages/dashboard/dashboard.php";
require_once $_SERVER['DOCUMENT_ROOT']."/e-service/views/pages/admin/departements.php";
require_once $_SERVER['DOCUMENT_ROOT']."/e-service/views/layouts/departement_form/departement_form.php";
require_once $_SERVER['DOCUMENT_ROOT']."/e-service/views/layouts/departement_list/departement_list.php";
require_once $_SERVER['DOCUMENT_ROOT']."/e-service/controllers/entity/user.php";
require_once $_SERVER['DOCUMENT_ROOT']."/e-service/controllers/university/departement.php";
require_once $_SERVER['DOCUMENT_ROOT']."/e-service/models/univeristy/departement.php";
session_start();

$userController =  new UserController();

$userController->checkCurrentUserAuthority(["admin"]);


$dashboard = new DashBoard();
$departementModel = new DepartementModel();
$departementController =  new DepartementController();

$type  = (int)($_GET["filter"]?? 0) ;
$info = null;

$inner_content = "vide";

if ($type ===  1){

    if ( $_SERVER["REQUEST_METHOD"] === 'POST'){

        $title = $_POST["title"]??null;
        $desc = $_POST["description"] ?? null;


        if ( $departementController->isValidData("title", $title)  &&  $departementController->isValidData("description", $desc)){

            if ($departementModel->creatDepartement($title, $desc) === false){

                $info = [
                    "msg" => "Échec de la création du département. Veuillez réessayer.",
                    "type" => "danger"
                ];

            }else {

                $info = [
                    "msg" => "Département créé avec succès !",
                    "type" => "success"
                ];

            }

        }else {

            $info = [
                "msg" => "Les données saisies sont invalides. Veuillez vérifier et réessayer.",
                "type" => "danger"
            ];

        }

    }

    $inner_content = (new DepartementForm())->view($info);

}else if($type ===  0){
    
    $data = $departementModel->getAll();

    if (is_array($data)){
        $inner_content = (new DepartementList())->view($data);
    }else {
        $inner_content = "<h3>x_x server error occured</h3>";
    }
    

}

$content = (new DepartementsView())->view($inner_content, $type);

$dashboard->view("admin", "deperatements", $content);

?>