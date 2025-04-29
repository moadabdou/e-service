<?php
require_once $_SERVER['DOCUMENT_ROOT'] . "/e-service/views/pages/dashboard/dashboard.php";
require_once $_SERVER['DOCUMENT_ROOT'] . "/e-service/controllers/entity/user.php";
require_once $_SERVER['DOCUMENT_ROOT'] . "/e-service/models/univeristy/module.php";
require_once $_SERVER['DOCUMENT_ROOT'] . "/e-service/models/univeristy/filiere.php";
require_once $_SERVER['DOCUMENT_ROOT'] . "/e-service/models/entity/user.php";
require_once $_SERVER['DOCUMENT_ROOT'] . "/e-service/models/univeristy/speciality.php";

session_start();

$userController = new UserController();
$userController->checkCurrentUserAuthority(["professor/coordonnateur"]);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $moduleModel = new ModuleModel();


    $semester = isset($_POST['semestre']) ? $_POST['semestre'] : null;

    $data = [
        'title' => $_POST['title'],
        'description' => $_POST['description'],
        'volume_horaire' => $_POST['volume_horaire'],
        'semester' => $semester,
        'credits' => $_POST['credits'],
        'id_filiere' => $_POST['id_filiere'],
        'responsable' => $_POST['responsable'],
        'speciality' => $_POST['speciality']
    ];

    $moduleModel->createModule($data);
}

$filiereModel = new FiliereModel();
$coordinatorId = $_SESSION['id_user'] ?? null;
$filiereId = $filiereModel->getFiliereIdByCoordinator($coordinatorId);

$userModel = new UserModel();
$professors = $userModel->getAllProfessors();

$specialityModel = new SpecialityModel();
$specialities = $specialityModel->getAllSpecialities();

ob_start();
include $_SERVER['DOCUMENT_ROOT'] . "/e-service/views/pages/coordonnateur/module_form.php";
$content = ob_get_clean();

$dashboard = new DashBoard();
$dashboard->view("professor/coordonnateur", "ModuleDescriptif", $content);
