<?php
session_start();

require_once $_SERVER['DOCUMENT_ROOT'] . "/e-service/controllers/entity/user.php";
require_once $_SERVER['DOCUMENT_ROOT'] . "/e-service/models/univeristy/module.php";
require_once $_SERVER['DOCUMENT_ROOT'] . "/e-service/models/univeristy/notes.php";
require_once $_SERVER['DOCUMENT_ROOT'] . "/e-service/models/entity/professor.php";
require_once $_SERVER['DOCUMENT_ROOT'] . "/e-service/views/pages/dashboard/dashboard.php";
require_once $_SERVER['DOCUMENT_ROOT'] . "/e-service/views/pages/professor/dashboard_professor_view.php";

$userController = new UserController();
$userController->checkCurrentUserAuthority(["professor", "professor/chef_deparetement", "professor/coordonnateur"]);

$professorId = $_SESSION['id_user'] ?? null;

$moduleModel = new ModuleModel();
$noteModel = new NoteModel();
$professorModel = new ProfessorModel();

$chosenModules = $moduleModel->getSelectedModulesByProfessor($professorId);
$assignedModules = $moduleModel->getApprovedModulesByProfessor($professorId);
$uploadedNotes = $noteModel->getNotesByProfessor($professorId);
$activityHistory = $noteModel->getRecentProfessorActivities($professorId);
$pendingNotes = $moduleModel->getModulesWithoutNotes($professorId);
$upcomingDeadlines = [
    0=>"12"
]; 

$professorInfo = $professorModel->getProfessorInfo($professorId);
$professorName = $professorInfo['firstName'] . " " . $professorInfo['lastName'];
$department = $professorInfo['department_name'] ?? '';
$academicYear = date('Y');

$content = professorDashboard(
    $chosenModules,
    $assignedModules,
    $uploadedNotes,
    $activityHistory,
    $pendingNotes,
    $upcomingDeadlines,
    $professorName,
    $department,
    $academicYear
);

$dashboard = new DashBoard();
$dashboard->view($_SESSION['role'], "main", $content);
