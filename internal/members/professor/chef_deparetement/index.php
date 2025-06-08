<?php
session_start();

require_once $_SERVER['DOCUMENT_ROOT'] . "/e-service/controllers/entity/user.php";
require_once $_SERVER['DOCUMENT_ROOT'] . "/e-service/models/univeristy/statistics.php";
require_once $_SERVER['DOCUMENT_ROOT'] . "/e-service/views/pages/chef_deparetement/dashboard_chef_view.php";
require_once $_SERVER['DOCUMENT_ROOT'] . "/e-service/views/pages/dashboard/dashboard.php";
require_once $_SERVER['DOCUMENT_ROOT'] . "/e-service/models/entity/professor.php";
require_once $_SERVER['DOCUMENT_ROOT'] . "/e-service/models/univeristy/module.php";
require_once $_SERVER['DOCUMENT_ROOT'] . "/e-service/models/univeristy/deadline.php";
$deadlineModel = new DeadlineModel();

$userController = new UserController();
$userController->checkCurrentUserAuthority(["professor/chef_deparetement"]);

$departmentId = $_SESSION['id_deparetement'] ?? null;
if (!$departmentId) die("Département manquant.");

$StatisticsModel = new StatisticsModel();
$ProfessorModel = new ProfessorModel();
$moduleModel = new ModuleModel();

$totalHoursAssigned = $moduleModel->getTotalAssignedHoursByDepartment($departmentId);
$totalProfsCount = $ProfessorModel->getProfessorCountByDepartment($departmentId);
$workloadDistribution = $StatisticsModel->getWorkloadDistribution($departmentId);
$moduleChoicesStats = $StatisticsModel->getModuleChoicesStats($departmentId);
$validationStats = $StatisticsModel->getModuleValidationStats($departmentId);
$professorStats = $ProfessorModel->getProfessorsWithWorkload($departmentId); 
$modulesData = $StatisticsModel->getModuleChoicesStats($departmentId);      
$recentActivities = $StatisticsModel->getRecentModuleActivities($departmentId);
$pendingValidations = $StatisticsModel->getPendingValidationsCount($departmentId);
$modules = $moduleModel->getAllModulesByDepartment($departmentId);
$SousModuleCount = $moduleModel->getSousModulesCountByDepartment($departmentId);
$ModuleCount = $moduleModel->getModulesCountByDepartment($departmentId);
$vacantModulesCount = $StatisticsModel->getVacantModulesCount($departmentId);


$content = departmentHeadDashboard(
    $workloadDistribution,    
    $moduleChoicesStats,      
    $validationStats,         
    $professorStats,
    $modulesData,
    $recentActivities,
    $totalProfsCount,
    $totalHoursAssigned,
    $pendingValidations,
    $modules,
    $vacantModulesCount,
    $SousModuleCount,
    $ModuleCount,
    $deadlineModel
);

$dashboard = new DashBoard();
$dashboard->view("main_chef", $content);
