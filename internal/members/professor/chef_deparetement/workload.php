<?php
session_start();

require_once $_SERVER['DOCUMENT_ROOT'] . "/e-service/views/pages/chef_deparetement/workload_view.php";
require_once $_SERVER['DOCUMENT_ROOT'] . "/e-service/views/pages/dashboard/dashboard.php";
require_once $_SERVER['DOCUMENT_ROOT'] . "/e-service/models/entity/professor.php";
require_once $_SERVER['DOCUMENT_ROOT'] . "/e-service/controllers/entity/user.php";

$userController = new UserController();
$userController->checkCurrentUserAuthority(["professor/chef_deparetement"]);

$ProfessorModel = new ProfessorModel();
$departmentId = $_SESSION['id_deparetement'] ?? null;

$workloads = $ProfessorModel->getProfessorsWithWorkload($departmentId);

$content = professorWorkloadView($workloads);
$dashboard = new DashBoard();
$dashboard->view($_SESSION['role'], "workload", $content);

?>