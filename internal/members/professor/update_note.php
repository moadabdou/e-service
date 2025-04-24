<?php
session_start();
require_once $_SERVER['DOCUMENT_ROOT'] . "/e-service/models/univeristy/notes.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['new_file'], $_POST['file_id'])) {
    $fileId = $_POST['file_id'];
    $newFile = $_FILES['new_file'];

    $noteModel = new NoteModel();
    $NewId = $noteModel->generateFileId(); 

    $result = $noteModel->updateNoteFile($fileId,$NewId, $newFile);

    if ($result) {
        $_SESSION['success_message'] = "Note modifiée avec succès.";
    } else {
        $_SESSION['error_message'] = "Erreur lors de la modification de la note.";
    }

    header("Location: /e-service/internal/members/professor/notes_history.php");
    exit;
}
?>
