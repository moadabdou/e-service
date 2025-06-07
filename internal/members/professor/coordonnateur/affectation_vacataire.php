<?php
require_once $_SERVER['DOCUMENT_ROOT'] . "/e-service/views/pages/dashboard/dashboard.php";
require_once $_SERVER['DOCUMENT_ROOT'] . "/e-service/controllers/entity/user.php";
require_once $_SERVER['DOCUMENT_ROOT'] . "/e-service/models/univeristy/module.php";
require_once $_SERVER['DOCUMENT_ROOT'] . "/e-service/models/univeristy/filiere.php";
require_once $_SERVER['DOCUMENT_ROOT'] . "/e-service/models/univeristy/vacataire_affectation.php";

session_start();

$userController = new UserController();
$userController->checkCurrentUserAuthority(["professor/coordonnateur"]);

$coordId = $_SESSION['id_user'] ?? null;
if (!$coordId) {
    die("Identifiant du coordonnateur introuvable.");
}

$moduleModel = new ModuleModel();
$filiereModel = new FiliereModel();
$affectationModel = new VacataireAffectationModel();

$message = null;

$filiereId = $filiereModel->getFiliereIdByCoordinator($coordId);
$modules = $moduleModel->getModulesByFiliereId($filiereId);
$vacataires = $affectationModel->getAvailableVacataires();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $vacataireId = $_POST['vacataire_id'] ?? null;
    $moduleId = $_POST['module_id'] ?? null;
    $annee = $_POST['annee'] ?? date('Y');

    if ($vacataireId && $moduleId && $annee) {
        $success = $affectationModel->assignModuleToVacataire($vacataireId, $coordId, $moduleId, $annee);
        if ($success) {
            $message = ['type' => 'success', 'text' => "Affectation réalisée avec succès."];
        } else {
            $message = ['type' => 'danger', 'text' => "Erreur lors de l'affectation."];
        }
    } else {
        $message = ['type' => 'warning', 'text' => "Veuillez remplir tous les champs."];
    }
}

$affectations = $affectationModel->getAssignedModulesByCoord($coordId);

ob_start();
require_once $_SERVER['DOCUMENT_ROOT'] . "/e-service/views/pages/coordonnateur/affectation_vacataire.php";
$content = ob_get_clean();

$dashboard = new DashBoard();
$dashboard->view("professor/coordonnateur", "affectationVacataire", $content);
