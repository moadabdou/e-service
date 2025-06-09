<?php
session_start();
require_once $_SERVER['DOCUMENT_ROOT'] . "/e-service/views/pages/dashboard/dashboard.php";
require_once $_SERVER['DOCUMENT_ROOT'] . "/e-service/controllers/entity/user.php";
require_once $_SERVER['DOCUMENT_ROOT'] . "/e-service/models/univeristy/module.php";
require_once $_SERVER['DOCUMENT_ROOT'] . "/e-service/models/univeristy/filiere.php";

$errors = [];
$success = null;

$userController = new UserController();
$userController->checkCurrentUserAuthority(["professor/coordonnateur"]);

$departmentId = $_SESSION['id_deparetement'] ?? null;

if (!$departmentId) {
    throw new Exception("ID du département non trouvé. Veuillez vous reconnecter.");
}

$moduleModel = new ModuleModel();
$FiliereModel = new FiliereModel();

// Modules parents disponibles pour éléments
$availableParents = $moduleModel->getModulesWithUniqueEvaluationOnly();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $requiredFields = ['title', 'credits',];
    foreach ($requiredFields as $field) {
        if (empty($_POST[$field])) {
            $errors[$field] = "Le champ " . ucfirst(str_replace('_', ' ', $field)) . " est obligatoire.";
        }
    }

    $title = trim($_POST['title'] ?? '');
    if (strlen($title) < 3) {
        $errors['title'] = "Le titre doit contenir au moins 3 caractères.";
    }

    $description = trim($_POST['description'] ?? '');

    $numericFields = [
        'volume_cours' => 'Volume cours', 
        'volume_td' => 'Volume TD', 
        'volume_tp' => 'Volume TP', 
        'volume_autre' => 'Volume autre',
        'credits' => 'Crédits'
    ];
    foreach ($numericFields as $field => $label) {
        if (!isset($_POST[$field]) || $_POST[$field] === '') {
            $_POST[$field] = 0;
        } elseif (!is_numeric($_POST[$field]) || $_POST[$field] < 0) {
            $errors[$field] = "Le champ $label doit être un nombre positif.";
        }
    }

    $volume_cours = (int)$_POST['volume_cours'];
    $volume_td = (int)$_POST['volume_td'];
    $volume_tp = (int)$_POST['volume_tp'];
    $volume_autre = (int)$_POST['volume_autre'];
    $credits = (int)$_POST['credits'];

    $semester = $_POST['semester'] ?? '';
    $validSemesters = ['S1', 'S2', 'S3', 'S4', 'S5', 'S6'];
    if (!in_array($semester, $validSemesters)) {
        $errors['semester'] = "Semestre invalide.";
    }

    $id_filiere = $FiliereModel->getFiliereIdByCoordinator($_SESSION['id_user']);

    $isFirst = isset($_POST['is_first_element']);
    $isElement = isset($_POST['is_element']);

    if ($isElement) {
        $parentId = $_POST['parent_module'] ?? '';
        if (empty($parentId)) {
            $errors['parent_module'] = "Veuillez sélectionner un module parent.";
        } else {
            $parentExists = array_filter($availableParents, function($parent) use ($parentId) {
                return $parent['id_module'] == $parentId;
            });
            if (!$parentExists) {
                $errors['parent_module'] = "Module parent invalide.";
            }
        }
    }

    if (empty($errors)) {
        try {
            if ($isFirst) {
                $evaluation = $moduleModel->generateUniqueEvaluationId();
            } elseif ($isElement) {
                $parentId = $_POST['parent_module'];
                $evaluation = $moduleModel->getEvaluationIdByModuleId($parentId);
                if (!$evaluation) {
                    throw new Exception("Impossible de récupérer l'ID d'évaluation du module parent.");
                }
            } else {
                $evaluation = 0;
            }

            $id_module = $moduleModel->getNextModuleId();
            if (!$id_module) {
                throw new Exception("Impossible de générer l'ID du module.");
            }

            if ($evaluation === 0) {
                $code_module = 'M' . $id_module;
            } elseif ($isFirst) {
                $code_module = 'M' . $id_module . '.1';
            } else {
                $elementCount = $moduleModel->countElementsByEvaluation($evaluation);
                $parentId = $moduleModel->getParentModuleIdByEvaluation($evaluation);
                $code_module = 'M' . $parentId . '.' . ($elementCount + 1);
            }

            $result = $moduleModel->insertModule([
                'title' => $title,
                'description' => $description,
                'volume_cours' => $volume_cours,
                'volume_td' => $volume_td,
                'volume_tp' => $volume_tp,
                'volume_autre' => $volume_autre,
                'evaluation' => $evaluation,
                'semester' => $semester,
                'credits' => $credits,
                'id_filiere' => $id_filiere,
                'code_module' => $code_module
            ]);


            if ($result) {
                $moduleType = $isFirst ? "premier élément" : ($isElement ? "élément" : "Module indépendant");
                $success = [
                    'type' => 'success',
                    'msg' => "Le module '$title' a été ajouté avec succès en tant que $moduleType."
                ];
                $_POST = []; // Réinitialiser les champs après succès
            } else {
                throw new Exception("Erreur lors de l'insertion du module.");
            }
        } catch (Exception $e) {
            $errors['database'] = "Erreur de base de données: " . $e->getMessage();
        }
    }
}

ob_start();
require_once $_SERVER['DOCUMENT_ROOT'] . "/e-service/views/pages/coordonnateur/ajouter_module.php";
$content = ob_get_clean();

$dashboard = new DashBoard();
$dashboard->view("AjouterModule", $content);
?>
