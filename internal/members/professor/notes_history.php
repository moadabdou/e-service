<?php
session_start();

require_once $_SERVER['DOCUMENT_ROOT'] . "/e-service/views/pages/dashboard/dashboard.php";
require_once $_SERVER['DOCUMENT_ROOT'] . "/e-service/views/pages/professor/notes_history_view.php";
require_once $_SERVER['DOCUMENT_ROOT'] . "/e-service/controllers/entity/user.php";
require_once $_SERVER['DOCUMENT_ROOT'] . "/e-service/models/univeristy/notes.php";
require_once $_SERVER['DOCUMENT_ROOT'] . "/e-service/models/univeristy/deadline.php";
require_once $_SERVER['DOCUMENT_ROOT'] . "/e-service/models/univeristy/filiere.php";


$userController = new UserController();
$FiliereModel = new FiliereModel();
$noteModel = new NoteModel();
$daedlineModel = new DeadlineModel();

$userController->checkCurrentUserAuthority(["professor","professor/chef_deparetement", "professor/coordonnateur"]);

$professorId = $_SESSION['id_user'] ?? null;
$departmentId = $_SESSION['id_deparetement'] ?? null;
$successMessage = $_SESSION['success_message'] ?? null;
$errorMessage = $_SESSION['error_message'] ?? null;
unset($_SESSION['success_message'], $_SESSION['error_message']);


$notes = $noteModel->getNotesByProfessor($professorId);
$IsDeadlineOpen=$daedlineModel->isFeatureOpen("upload_notes");
$filliere = $FiliereModel->getFilieresByDepartment($departmentId);
if (!$IsDeadlineOpen && !$errorMessage) {
    $errorMessage = "Vous ne pouvez pas modifier ou supprimer les notes car la date limite est dépassée.";
}
$content = notesHistoryView($filliere, $notes, $successMessage, $errorMessage, $IsDeadlineOpen);

$dashboard = new DashBoard();
$dashboard->view($_SESSION["role"], "NotesHistory", $content);
