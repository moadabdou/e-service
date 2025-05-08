<?php 
require_once $_SERVER['DOCUMENT_ROOT']."/e-service/views/pages/admin/main.php";
require_once $_SERVER['DOCUMENT_ROOT']."/e-service/views/pages/dashboard/dashboard.php";
require_once $_SERVER['DOCUMENT_ROOT']."/e-service/controllers/entity/user.php";
require_once $_SERVER['DOCUMENT_ROOT'] . "/e-service/models/entity/professor.php";
require_once $_SERVER['DOCUMENT_ROOT'] . "/e-service/models/univeristy/module.php";
require_once $_SERVER['DOCUMENT_ROOT'] . "/e-service/models/univeristy/filiere.php";
require_once $_SERVER['DOCUMENT_ROOT']."/e-service/models/univeristy/departement.php";
require_once $_SERVER['DOCUMENT_ROOT']."/e-service/models/univeristy/filiere.php";
require_once $_SERVER['DOCUMENT_ROOT']."/e-service/models/content/activity.php";

session_start();

$userController =  new UserController();

$userController->checkCurrentUserAuthority(["admin"]);


$dashboard = new DashBoard();
$userModel =  new UserModel();
$professorModel = new ProfessorModel();
$departementModel =  new DepartementModel();
$filiereModel =  new FiliereModel();
$activityModel = new ActivityModel();
$data  = [];

$data['allUsers_Active_count'] = $userModel->countAllActive();
$data['allUsers_Disabled_count'] = $userModel->countAllDisabled();
$data['prof_count'] = $professorModel->getNormalProfessorsCount();
$data['vacataire_count'] = $professorModel->getVacatairesCount();
$data['departement_count'] = $departementModel->countDepartements();
$data['filiere_count'] = $filiereModel->countFilieres();
$data["recent_activity"] = $activityModel->getRecentActivities(10);

$content = (new MainView())->view($data);

$dashboard->view("admin", "main", $content);

?>