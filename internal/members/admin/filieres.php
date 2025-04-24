<?php 
require_once $_SERVER['DOCUMENT_ROOT']."/e-service/views/pages/dashboard/dashboard.php";
require_once $_SERVER['DOCUMENT_ROOT']."/e-service/views/pages/admin/filieres.php";
require_once $_SERVER['DOCUMENT_ROOT']."/e-service/views/layouts/filiere_form/filiere_form.php";
require_once $_SERVER['DOCUMENT_ROOT']."/e-service/views/layouts/filiere_list/filiere_list.php";
require_once $_SERVER['DOCUMENT_ROOT']."/e-service/controllers/entity/user.php";
require_once $_SERVER['DOCUMENT_ROOT']."/e-service/controllers/university/filiere.php";
require_once $_SERVER['DOCUMENT_ROOT']."/e-service/models/univeristy/filiere.php";
require_once $_SERVER['DOCUMENT_ROOT']."/e-service/models/univeristy/departement.php";

session_start();

$userController =  new UserController();

$userController->checkCurrentUserAuthority(["admin"]);

$dashboard = new DashBoard();
$filiereModel = new FiliereModel();
$filiereController =  new FiliereController();

$type  = (int)($_GET["filter"]?? 0) ;
$dep_id  = (int)($_GET["id_dep"]?? -1) ;
$info = null;


if($dep_id  < 1 ){
    $userController->redirectCurrentUserBasedOnRole();
}

$dep_data = (new DepartementModel())->getByID($dep_id);

if(!is_array($dep_data) || count($dep_data) === 0){
    $userController->redirectCurrentUserBasedOnRole();
}


$inner_content = "vide";

if ($type ===  1){

    if ( $_SERVER["REQUEST_METHOD"] === 'POST'){

        $title = $_POST["title"]??null;
        $desc = $_POST["description"] ?? null;


        if ( $filiereController->isValidData("title", $title)  &&  $filiereController->isValidData("description", $desc)){

            if ($filiereModel->createFiliere($title, $desc, $dep_id) === false){

                $info = [
                    "msg" => "La création de la filière a échoué. Veuillez réessayer.",
                    "type" => "danger"
                ];

            }else {

                $info = [
                    "msg" => "La filière a été créée avec succès !",
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

    $inner_content = (new FiliereForm())->view($info, $dep_id);

}else if($type ===  0){
    
    $data = $filiereModel->getFilieresByDepartment($dep_id);

    if (is_array($data)){
        $inner_content = (new FiliereList())->view($data, $dep_id);
    }else {
        $inner_content = "<h3>x_x server error occured</h3>";
    }

}

$content = (new FilieresView())->view($inner_content, $type, $dep_id, $dep_data);

$dashboard->view("admin", "", $content);

?>