<?php
session_start();

require_once $_SERVER['DOCUMENT_ROOT'] . "/e-service/views/pages/dashboard/dashboard.php";
require_once $_SERVER['DOCUMENT_ROOT'] . "/e-service/views/pages/professor/choose_units_form.php";
require_once $_SERVER['DOCUMENT_ROOT'] . "/e-service/models/univeristy/module.php";
require_once $_SERVER['DOCUMENT_ROOT'] . "/e-service/controllers/entity/user.php";
require_once $_SERVER['DOCUMENT_ROOT'] . "/e-service/models/content/notification.php";

$userController = new UserController();
$userController->checkCurrentUserAuthority(["professor"]);

$moduleModel = new ModuleModel();
$notificationModel = new NotificationModel();

$errors = [];
$info = null;

$departmentId = $_SESSION['id_deparetement'] ?? null;

$availableModules = $moduleModel->getAvailableModulesByDepartment($departmentId, $_SESSION['id_user']);
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
                "msg" => "Une erreur est survenue lors de l'enregistrement de vos choix.",
                "type" => "danger"
            ];
        } else {
            $moduleNames = [];
            foreach ($selectedModuleIds as $moduleId) {
                $module = $moduleModel->getModuleById($moduleId);
                if ($module) {
                    $moduleNames[] = $module['title']; 
                }
            }

            if (count($moduleNames) === 1) {
                $notificationMessage = "Votre choix de module a bien été enregistré : " . $moduleNames[0];
            } else {
                $moduleList = implode(", ", $moduleNames);
                $notificationMessage = "Vos choix de modules ont bien été enregistrés : " . $moduleList;
            }

            $notificationId = $notificationModel->createNotification(
                $_SESSION['id_user'],
                "Affectation enregistrée",
                $notificationMessage,
                null
            );

            if ($notificationId === false) {
                $info = [
                    "msg" => "Vos choix de modules ont bien été enregistrés, mais une erreur est survenue lors de la création de la notification.",
                    "type" => "warning" 
                ];
            } else {
                $info = [
                    "msg" => "Vos choix ont été enregistrés avec succès.",
                    "type" => "success"
                ];
            }

            $_SESSION['info'] = $info;

            header("Location: " . $_SERVER['REQUEST_URI']);
            exit();
        }
    }
}

if (isset($_SESSION['info'])) {
    $info = $_SESSION['info'];
    unset($_SESSION['info']);
}

$content = chooseUnitsFormView($availableModules, $selectedModules, $errors, $info);
$dashboard = new DashBoard();
$dashboard->view("professor", "chooseUnits", $content);

?>
