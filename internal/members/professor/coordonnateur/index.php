<?php
require_once $_SERVER['DOCUMENT_ROOT'] . "/e-service/views/pages/dashboard/dashboard.php";
require_once $_SERVER['DOCUMENT_ROOT'] . "/e-service/models/entity/user.php";
require_once $_SERVER['DOCUMENT_ROOT'] . "/e-service/models/univeristy/filiere.php";
require_once $_SERVER['DOCUMENT_ROOT'] . "/e-service/models/univeristy/module.php";
require_once $_SERVER['DOCUMENT_ROOT'] . "/e-service/models/univeristy/EmploiTempsUploadModel.php";
require_once $_SERVER['DOCUMENT_ROOT'] . "/e-service/models/univeristy/vacataire_affectation.php";

session_start();

$userController = new UserController();
$userController->checkCurrentUserAuthority(["professor/coordonnateur"]);

$coordId = $_SESSION['id_user'] ?? null;

$userModel = new UserModel();
$filiereModel = new FiliereModel();
$moduleModel = new ModuleModel();
$emploiModel = new EmploiTempsUploadModel();
$vacataireModel = new VacataireAffectationModel();

$user = $userModel->getUser($coordId);
$filiereId = $filiereModel->getFiliereIdByCoordinator($coordId);
$filiereInfo = $filiereModel->getFiliereById($filiereId);

// Données pour le dashboard
$nb_profs = $userModel->countProfessorsByFiliere($filiereId);
$nb_modules = $moduleModel->countModulesByFiliere($filiereId);
$nb_emplois = $emploiModel->countUploadsByFiliere($filiereId);
$nb_vacataires = $vacataireModel->countDistinctVacatairesByCoord($coordId);
$nb_affectations = $vacataireModel->countAffectationsByCoord($coordId);

ob_start();
require_once $_SERVER['DOCUMENT_ROOT'] . "/e-service/views/pages/coordonnateur/dashboard_coordonnateur.php";
$content = ob_get_clean();

$dashboard = new DashBoard();
$dashboard->view("main_coor", $content);
