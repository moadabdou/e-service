<?php
session_start();

require_once $_SERVER['DOCUMENT_ROOT'] . "/e-service/views/pages/dashboard/dashboard.php";
require_once $_SERVER['DOCUMENT_ROOT'] . "/e-service/models/entity/professor.php";
require_once $_SERVER['DOCUMENT_ROOT'] . "/e-service/views/pages/chef_deparetement/professor_module_choices_view.php";
require_once $_SERVER['DOCUMENT_ROOT'] . "/e-service/controllers/entity/user.php";

$userController = new UserController();
$userController->checkCurrentUserAuthority(["professor/chef_deparetement"]);

$ProfessorModel = new ProfessorModel();

$departmentId = $_SESSION['id_deparetement'] ?? null;

$professors = $ProfessorModel->getProfessorChoicesWithWorkload($departmentId);

$content = professorModuleChoicesView($professors);
$dashboard = new DashBoard();
$dashboard->view("professorChoices", $content);
