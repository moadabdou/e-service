<?php
session_start();

require_once $_SERVER['DOCUMENT_ROOT'] . "/e-service/views/pages/dashboard/dashboard.php";
require_once $_SERVER['DOCUMENT_ROOT'] . "/e-service/views/pages/chef_deparetement/professors_list_view.php";
require_once $_SERVER['DOCUMENT_ROOT'] . "/e-service/controllers/entity/user.php";
require_once $_SERVER['DOCUMENT_ROOT'] . "/e-service/models/univeristy/professor.php";

$userController = new UserController();
$userController->checkCurrentUserAuthority(["professor/chef_deparetement"]);

$professor = $_SESSION;
$departmentId = $professor['id_deparetement'] ?? null;
$professorId = $professor['id_user'];
$userModel = new UserModel();
$professors = $userModel->getProfessorsByDepartment($departmentId,$professorId);

$content = chefDepProfessorsListView($professors);
$dashboard = new DashBoard();
$dashboard->view("professor/chef_deparetement", "professors", $content);
