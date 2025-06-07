<?php
require_once $_SERVER['DOCUMENT_ROOT'] . "/e-service/views/pages/dashboard/dashboard.php";
require_once $_SERVER['DOCUMENT_ROOT'] . "/e-service/controllers/entity/user.php";
require_once $_SERVER['DOCUMENT_ROOT'] . "/e-service/models/univeristy/filiere.php";
require_once $_SERVER['DOCUMENT_ROOT'] . "/e-service/models/univeristy/EmploiTempsUploadModel.php";

session_start();

$userController = new UserController();
$userController->checkCurrentUserAuthority(["professor", "professor/chef_deparetement", "professor/coordonnateur", "vacataire"]);

$filiereModel = new FiliereModel();
$emploiModel = new EmploiTempsUploadModel();

$filieres = $filiereModel->getAllFilieres();
$id_filiere = $_GET['id_filiere'] ?? null;
$semestre = $_GET['semestre'] ?? null;
$annee = $_GET['annee'] ?? date('Y');

$uploads = [];
if ($id_filiere && $semestre && $annee) {
    $uploads = $emploiModel->getUploads((int)$id_filiere, $semestre, (int)$annee);
}

ob_start();
include $_SERVER['DOCUMENT_ROOT'] . "/e-service/views/pages/common/emploi_temps_list.php";
$content = ob_get_clean();

$dashboard = new DashBoard();
$dashboard->view("general", "emploisList", $content);