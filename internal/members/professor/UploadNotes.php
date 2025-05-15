<?php
session_start();

require_once $_SERVER['DOCUMENT_ROOT'] . "/e-service/views/pages/dashboard/dashboard.php";
require_once $_SERVER['DOCUMENT_ROOT'] . "/e-service/views/pages/professor/upload_notes_view.php";
require_once $_SERVER['DOCUMENT_ROOT'] . "/e-service/views/pages/professor/upload_notes_single_view.php";
require_once $_SERVER['DOCUMENT_ROOT'] . "/e-service/controllers/entity/user.php";
require_once $_SERVER['DOCUMENT_ROOT'] . "/e-service/models/univeristy/module.php";
require_once $_SERVER['DOCUMENT_ROOT'] . "/e-service/models/univeristy/notes.php";
require_once $_SERVER['DOCUMENT_ROOT'] . "/e-service/models/univeristy/deadline.php";


$userController = new UserController();
$deadlineModel = new DeadlineModel();

$userController->checkCurrentUserAuthority(["professor", "professor/chef_deparetement", "professor/coordonnateur"]);



$professorId = $_SESSION['id_user'];
$moduleModel = new ModuleModel();
$noteModel = new NoteModel();

$assignedModules = $moduleModel->getApprovedModulesByProfessor($professorId);
$info = null;
$deadline = null;


if (!$deadlineModel->isFeatureOpen('upload_notes')) {
    $deadline = [
        "type" => "danger",
        "msg" => "La période d'envoi des notes est fermée. Vous ne pouvez pas envoyer de notes pour le moment.",
        "desc" => "Pour toute demande urgente, veuillez contacter l'administration."
    ];
}


// Check if uploading for a specific module
$singleModuleId = isset($_GET['module']) ? intval($_GET['module']) : null;

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['session_type'])) {
    $selectedModuleId = $singleModuleId ?? (isset($_POST['selected_module']) ? intval($_POST['selected_module']) : null);

    if (!$selectedModuleId) {
        $info = ["type" => "danger", "msg" => "Aucun module sélectionné."];
    } elseif (!empty($_FILES['notes_file']['tmp_name'])) {
        $fileTmp = $_FILES['notes_file']['tmp_name'];
        $fileType = mime_content_type($fileTmp);
        $Id = $noteModel->generateFileId();
        $fileId = getFileExtensionByType($fileType, $Id);

        $allowedTypes = ['application/pdf', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet', 'application/vnd.ms-excel'];

        if (in_array($fileType, $allowedTypes)) {
            $sessionType = $_POST['session_type'];

            $saveResult = $noteModel->saveUploadedNote($selectedModuleId, $professorId, $sessionType, $fileId);

            if ($saveResult && isset($saveResult['file_id'])) {
                $uploadDir = $_SERVER['DOCUMENT_ROOT'] . "/e-service/storage/pdfs/notes/";
                if (!is_dir($uploadDir)) {
                    mkdir($uploadDir, 0755, true);
                }

                $targetFile = $uploadDir . $fileId;

                if (move_uploaded_file($fileTmp, $targetFile)) {
                    $info = ["type" => "success", "msg" => "Fichier de notes envoyé et enregistré avec succès."];
                } else {
                    $info = ["type" => "warning", "msg" => "Erreur lors de l'enregistrement du fichier sur le serveur."];
                }
            } else {
                $info = ["type" => "warning", "msg" => "Erreur lors de l'enregistrement dans la base de données."];
            }
        } else {
            $info = ["type" => "danger", "msg" => "Format de fichier non autorisé."];
        }
    } else {
        $info = ["type" => "danger", "msg" => "Veuillez sélectionner un fichier."];
    }
}


// If single module => pass only that module
if ($singleModuleId) {
    $moduleInfo = $moduleModel->getModuleById($singleModuleId);

    $content = uploadSingleNoteView([$moduleInfo], $info);

} else {
    $content = uploadNotesView($assignedModules, $info,$deadline);
}



$dashboard = new DashBoard();
$dashboard->view($_SESSION["role"], "UploadNotes", $content);


?>
