<?php
session_start();

require_once $_SERVER['DOCUMENT_ROOT'] . "/e-service/views/pages/dashboard/dashboard.php";
require_once $_SERVER['DOCUMENT_ROOT'] . "/e-service/views/pages/professor/notes_history_view.php";
require_once $_SERVER['DOCUMENT_ROOT'] . "/e-service/controllers/entity/user.php";
require_once $_SERVER['DOCUMENT_ROOT'] . "/e-service/models/univeristy/notes.php";
require_once $_SERVER['DOCUMENT_ROOT'] . "/e-service/models/univeristy/filiere.php";


$userController = new UserController();
$FiliereModel = new FiliereModel();
$noteModel = new NoteModel();

$userController->checkCurrentUserAuthority(["professor", "professor/chef_deparetement", "professor/coordonnateur"]);

$professorId = $_SESSION['id_user'] ?? null;
$departmentId = $_SESSION['id_deparetement'] ?? null;

$successMessage = $_SESSION['success_message'] ?? null;
$errorMessage = $_SESSION['error_message'] ?? null;
unset($_SESSION['success_message'], $_SESSION['error_message']);


$notes = $noteModel->getNotesByProfessor($professorId);

$filliere = $FiliereModel->getFilieresByDepartment($departmentId);

$content = notesHistoryView($filliere, $notes, $successMessage, $errorMessage);

$dashboard = new DashBoard();
$dashboard->view($_SESSION["role"], "NotesHistory", $content);
