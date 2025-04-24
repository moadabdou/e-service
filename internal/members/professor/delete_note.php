<?php
session_start();
require_once $_SERVER['DOCUMENT_ROOT'] . "/e-service/models/univeristy/notes.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['file_id'])) {
    $fileId = $_POST['file_id'];


    $noteModel = new NoteModel();
    $result = $noteModel->deleteNoteByFileId($fileId);

    if ($result) {
        $_SESSION['success_message'] = "Note supprimée avec succès.";
    } else {
        $_SESSION['error_message'] = "Erreur lors de la suppression de la note.";
    }

    header("Location: /e-service/internal/members/professor/notes_history.php");
    exit;
}
?>
