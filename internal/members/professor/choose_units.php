<?php
session_start();


require_once $_SERVER['DOCUMENT_ROOT']."/e-service/views/pages/dashboard/dashboard.php";
require_once $_SERVER['DOCUMENT_ROOT'] . "/e-service/views/layouts/professor_views/choose_units_form.php";
require_once $_SERVER['DOCUMENT_ROOT'] . "/e-service/models/entity/module.php";
require_once $_SERVER['DOCUMENT_ROOT'] . "/e-service/controllers/entity/user.php";
require_once $_SERVER['DOCUMENT_ROOT'] . "/e-service/models/content/notification.php";

$userController = new UserController();
$userController->checkCurrentUserAuthority(["professor"]);


$moduleModel = new ModuleModel();
$notificationModel = new NotificationModel();

$errors = [];
$info = null;

$departmentId = $_SESSION['id_deparetement'] ?? null;

$availableModules = $moduleModel->getAvailableModulesByDepartment($departmentId,$_SESSION['id_user']);
$selectedModules = $moduleModel->getSelectedModulesByProfessor($_SESSION['id_user']);

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    if (empty($_POST['modules'])) {
        $errors["modules"] = "Vous devez sélectionner au moins un module.";
    }

    if (count($errors)) {
        $info = [
            "msg" => "Veuillez corriger les erreurs avant de valider vos choix.",
            "type" => "danger"
        ];
    
    } else {
        $selectedModuleIds = $_POST['modules'];
        $result = $moduleModel->assignModulesToProfessor($_SESSION['id_user'], $selectedModuleIds);

        if ($result === false) {
            $info = [
                "msg" => "Une erreur est survenue lors de l'enregistrement de vos choix.". print_r($selectedModuleIds, true),
                "type" => "danger"
            ];
        } else {
            $notificationModel->createNotification(
                $_SESSION['id_user'],                
                "Affectation enregistrée",
                "Vos choix de modules ont bien été enregistrés.", 
                null,
            );

            $info = [
                "msg" => "Vos choix ont été enregistrés avec succès.",
                "type" => "success"
            ];

            $selectedModules = $moduleModel->getSelectedModulesByProfessor($_SESSION['id_user']);
        }
    }
}

$content = chooseUnitsFormView($availableModules, $selectedModules, $errors, $info);

$dashboard = new DashBoard();
$dashboard->view("professor", "chooseUnits", $content);
?>
