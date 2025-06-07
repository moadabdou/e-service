<?php
session_start();

require_once $_SERVER['DOCUMENT_ROOT'] . "/e-service/controllers/entity/user.php";
require_once $_SERVER['DOCUMENT_ROOT'] . "/e-service/models/univeristy/notes.php";
require_once $_SERVER['DOCUMENT_ROOT'] . "/e-service/models/univeristy/module.php";
require_once $_SERVER['DOCUMENT_ROOT'] . "/e-service/views/pages/dashboard/dashboard.php";
require_once $_SERVER['DOCUMENT_ROOT'] . "/e-service/views/pages/professor/history_view.php";

$userController = new UserController();
$userController->checkCurrentUserAuthority(["professor", "professor/chef_deparetement", "professor/coordonnateur"]);

$professorId = $_SESSION['id_user'];
$professorName = $_SESSION['first_name'] . ' ' . $_SESSION['last_name'];

$noteModel = new NoteModel();
$moduleModel = new ModuleModel();

$notesHistory = $noteModel->getNotesGroupedByYear($professorId);
$moduleChoicesHistory = $moduleModel->getModuleChoicesGroupedByYear($professorId);

$content = professorHistoryView($notesHistory, $moduleChoicesHistory, $professorName);

$dashboard = new DashBoard();
$dashboard->view("ProfHistory", $content);
?>
