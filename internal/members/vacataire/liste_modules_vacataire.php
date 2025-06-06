<?php
require_once $_SERVER['DOCUMENT_ROOT'] . "/e-service/views/pages/dashboard/dashboard.php";
require_once $_SERVER['DOCUMENT_ROOT'] . "/e-service/controllers/entity/user.php";
require_once $_SERVER['DOCUMENT_ROOT'] . "/e-service/models/univeristy/vacataire_affectation.php";

session_start();

$userController = new UserController();
$userController->checkCurrentUserAuthority(["vacataire"]);

$vacataireId = $_SESSION['id_user'] ?? null;
$affectationModel = new VacataireAffectationModel();
$modules = [];

if ($vacataireId) {
    $modules = $affectationModel->getModulesByVacataireId($vacataireId);
}

ob_start();
include $_SERVER['DOCUMENT_ROOT'] . "/e-service/views/pages/vacataire/liste_modules_vacataire.php";
$content = ob_get_clean();

$dashboard = new DashBoard();
$dashboard->view("vacataire", "assignedModules", $content);
