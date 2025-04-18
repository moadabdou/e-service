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

$professorId = $_SESSION['id_user'];
$departmentId = $_SESSION['id_deparetement'] ?? null;

$availableModules = $moduleModel->getAvailableModulesByDepartment($departmentId, $professorId);
$selectedModules = $moduleModel->getSelectedModulesByProfessor($professorId);

// Get professor limits
$professorData = $moduleModel->getProfessorHours($professorId); 

$totalHours = $moduleModel->getTotalHoursFromChoix($professorId);

$maxHours = $professorData['max_hours'] ?? PHP_INT_MAX;
$minHours = $professorData['min_hours'] ?? 0;

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

        // Calculate total hours
        foreach ($selectedModuleIds as $moduleId) {
            $module = $moduleModel->getModuleById($moduleId);
            if ($module) {
                $totalHours += $module['volume_horaire'];
            }
        }

        $result = $moduleModel->assignModulesToProfessor($professorId, $selectedModuleIds);

        if ($result === false) {
            $info = [
                "msg" => "Une erreur est survenue lors de l'enregistrement de vos choix.",
                "type" => "danger"
            ];
        } else {

            // Notification for normal success
            $moduleNames = [];
            foreach ($selectedModuleIds as $moduleId) {
                $module = $moduleModel->getModuleById($moduleId);
                if ($module) {
                    $moduleNames[] = $module['title']; 
                }
            }

            $moduleList = implode(", ", $moduleNames);
            $message = "Vos choix de modules ont bien été enregistrés : " . $moduleList;

            // Check hour limits
            if ($totalHours < $minHours) {
                $message .= ". ⚠️ Attention : votre charge horaire ($totalHours h) est inférieure au minimum requis ($minHours h).";
            } elseif ($totalHours > $maxHours) {
                $message .= ". ⚠️ Attention : votre charge horaire ($totalHours h) dépasse le maximum autorisé ($maxHours h).";
            }

            $notificationId = $notificationModel->createNotification(
                $professorId,
                "Affectation enregistree",
                $message,
                null
            );

            $info = [
                "msg" => $notificationId ? "Vos choix ont été enregistrés avec succès." : "Modules enregistrés, mais erreur lors de la notification.",
                "type" => $notificationId ? "success" : "warning"
            ];

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
$content = chooseUnitsFormView($availableModules, $selectedModules, $errors, $info, $totalHours,$minHours,$maxHours);
$dashboard = new DashBoard();
$dashboard->view("professor", "chooseUnits", $content);
?>
