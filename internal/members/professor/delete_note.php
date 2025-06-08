<?php
session_start();
require_once $_SERVER['DOCUMENT_ROOT'] . "/e-service/models/univeristy/notes.php";
require_once $_SERVER['DOCUMENT_ROOT'] . "/e-service/controllers/entity/user.php";

$userController = new UserController();
$userController->checkCurrentUserAuthority([
    "professor",
    "professor/chef_deparetement", 
    "professor/coordonnateur",
    "vacataire"
]);

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['file_id'])) {
    $fileId = $_POST['file_id'];

    $noteModel = new NoteModel();
    $result = $noteModel->deleteNoteByFileId($fileId);

    if ($result) {
        $_SESSION['success_message'] = "Note supprimée avec succès.";
    } else {
        $_SESSION['error_message'] = "Erreur lors de la suppression de la note.";
    }

    if (isset($_SESSION["role"])) {
        $baseRole = explode('/', $_SESSION["role"])[0]; // ex: "professor" or "vacataire"

        if ($baseRole === "vacataire") {
            header("Location: /e-service/internal/members/vacataire/notes_history.php");
        } else {
            header("Location: /e-service/internal/members/professor/notes_history.php");
        }
        exit;
    }

    // Redirection par défaut si le rôle n'est pas détecté
    header("Location: /e-service/internal/members/professor/notes_history.php");
    exit;
}
?>
