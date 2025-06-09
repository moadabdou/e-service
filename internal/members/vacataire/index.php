<?php
require_once $_SERVER['DOCUMENT_ROOT'] . "/e-service/views/pages/dashboard/dashboard.php";
require_once $_SERVER['DOCUMENT_ROOT'] . "/e-service/models/entity/user.php";
require_once $_SERVER['DOCUMENT_ROOT'] . "/e-service/models/univeristy/vacataire_affectation.php";

session_start();
$id_user = $_SESSION['id_user'] ?? null;

if (!$id_user) {
    die("Utilisateur non connecté.");
}

$userModel = new UserModel();
$user = $userModel->getUser($id_user); // contient nom, prénom, rôle

$model = new VacataireAffectationModel();
$totalModules = $model->countModulesByVacataire($id_user);
$assignedModules = $model->getModulesByVacataire($id_user);

ob_start();
require $_SERVER['DOCUMENT_ROOT'] . "/e-service/views/pages/vacataire/dashboard_vacataire.php";
$content = ob_get_clean();

$dashboard = new DashBoard();
$dashboard->view("main", $content);
