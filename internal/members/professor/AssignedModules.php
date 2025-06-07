<?php
session_start();

require_once $_SERVER['DOCUMENT_ROOT'] . "/e-service/views/pages/dashboard/dashboard.php";
require_once $_SERVER['DOCUMENT_ROOT'] . "/e-service/views/pages/professor/AssignedModules_view.php"; // this contains assignedModulesView()
require_once $_SERVER['DOCUMENT_ROOT'] . "/e-service/models/univeristy/module.php";
require_once $_SERVER['DOCUMENT_ROOT'] . "/e-service/controllers/entity/user.php";

$userController = new UserController();
$userController->checkCurrentUserAuthority(["professor","professor/chef_deparetement", "professor/coordonnateur"]);

$professorId = $_SESSION['id_user'];

$moduleModel = new ModuleModel();

// Get only validated modules
$assignedModules = $moduleModel->getApprovedModulesByProfessor($professorId);

// Calculate total volume_horaire
$totalHours = 0;
foreach ($assignedModules as $module) {
    $totalHours += $module['volume_horaire'] ?? 0;
}

$content = assignedModulesView($assignedModules, $totalHours);

$dashboard = new DashBoard();
$dashboard->view("assignedModules", $content);
