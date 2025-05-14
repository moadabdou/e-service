<?php 
require_once $_SERVER['DOCUMENT_ROOT']."/e-service/views/pages/auth/auth.php";
require_once $_SERVER['DOCUMENT_ROOT']."/e-service/models/entity/professor.php";
require_once $_SERVER['DOCUMENT_ROOT']."/e-service/controllers/entity/user.php";

session_start();

$userController =  new UserController();

if (isset($_SESSION["role"])){
    $userController->redirectCurrentUserBasedOnRole();
}


$ProfessorModel = new ProfessorModel();
$auth = new Auth();




$info = null;
$errors = [];

if ($_SERVER["REQUEST_METHOD"] === "POST"){


    if (empty($_POST['email']) || !filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)){
        $errors["email"] = "Veuillez saisir une adresse e-mail valide !";
    }else {
        $user_info = $ProfessorModel->getUserByEmail($_POST['email']);
        if ($user_info === false){ 
            $errors["email"] = "Il semble que personne ne possède l'adresse e-mail que vous avez envoyée."; 
        }else if((!isset($_POST["password"]) || password_verify($_POST["password"] , $user_info["password"]) === false)){
            $errors["invalid"] = true;
        }else if($user_info["status"] === "disabled"){
            $errors["invalid"] = true;
            $errors["email"] = "Le compte associé à cet e-mail est désactivé, veuillez contacter l'administration.";
        }
    }

    if (count($errors)){

        $info = [
            "msg" => "Nous avons rencontré une erreur en essayant de vous connecter... Veuillez vérifier vos identifiants.",
            "type" => "danger"
        ];

        
    }else {
        session_start();

        $_SESSION["first_name"] = $user_info["firstName"];
        $_SESSION["last_name"] = $user_info["lastName"];
        $_SESSION["full_name"] = $user_info["firstName"]." ".$user_info["lastName"];
        $_SESSION["email"] = $user_info["email"];
        $_SESSION["role"] = $user_info["role"];
        $_SESSION["id_user"] = $user_info["id_user"];
        
        if ($_SESSION["role"]  === "professor"){
            $profModel = new ProfessorModel();
            $profRole = $profModel->getProfessorRole($_SESSION["id_user"]);
            if ($profRole !== "normal"){
                $_SESSION["role"] .= "/".$profRole;
            }
            
            //get departement id , i need it to show units, and i also do getProfessorByUserId method in professor.php 
            $professorData = $profModel->getProfessorByUserId($_SESSION["id_user"]);
            if ($professorData && isset($professorData["id_deparetement"])) {
                $_SESSION["id_deparetement"] = $professorData["id_deparetement"];
            }
        }

        $userController->redirectCurrentUserBasedOnRole($_SESSION["role"]);
        
    }
}

$auth->loginView($errors, $info);

?>