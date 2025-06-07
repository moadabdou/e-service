<?php
session_start();

$success = $_SESSION['success'] ?? null;
$error = $_SESSION['error'] ?? null;

unset($_SESSION['success'], $_SESSION['error']);

require_once $_SERVER['DOCUMENT_ROOT'] . "/e-service/controllers/entity/user.php";
require_once $_SERVER['DOCUMENT_ROOT'] . "/e-service/views/pages/dashboard/dashboard.php";
require_once $_SERVER['DOCUMENT_ROOT'] . "/e-service/views/pages/chef_deparetement/manage_deadlines_view.php";
require_once $_SERVER['DOCUMENT_ROOT'] . "/e-service/models/univeristy/deadline.php";

$userController = new UserController();
$userController->checkCurrentUserAuthority(["professor/chef_deparetement"]);

$deadlineModel = new DeadlineModel();

$deadlines = $deadlineModel->getAllDeadlines();




if (isset($_GET['action'], $_GET['id'])) {
    $id = (int)$_GET['id'];

    switch ($_GET['action']) {
        case 'delete':
            $success = $deadlineModel->deleteDeadline($id) ? "Deadline supprimée." : null;
            $error = $success ? null : "Erreur lors de la suppression.";
            break;

        case 'toggle':
            $deadline = $deadlineModel->getDeadlineById($id);
            if ($deadline) {
                $newStatus = $deadline['status'] === 'open' ? 'closed' : 'open';
                if ($deadlineModel->updateDeadlineStatus($id, $newStatus)) {
                    $success = "Statut mis à jour.";
                } else {
                    $error = "Aucune mise à jour effectuée. Le statut était peut-être déjà '$newStatus'.";
                }
                
                $error = $success ? null : "Erreur de mise à jour du statut.";
            }

            header("Location: manage_deadlines.php");
            exit;

        case 'edit':
            $editing = $deadlineModel->getDeadlineById($id);
            if (!$editing) {
                $error = "Deadline introuvable pour modification.";
            }
            break;
    }
}


if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_deadline_id'])) {
    $id = (int)$_POST['update_deadline_id'];
    $feature = $_POST['feature'] ?? '';
    $startDate = $_POST['start_date'] ?? '';
    $endDate = $_POST['end_date'] ?? '';

    if ($deadlineModel->updateDeadline($id, $feature, $startDate, $endDate)) {
        $success = "Deadline mise à jour avec succès.";
    } else {
        $error = "Erreur lors de la mise à jour.";
    }
    header("Location: manage_deadlines.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_deadline_id'])) {
    $id = (int)$_POST['delete_deadline_id'];
    $success = $deadlineModel->deleteDeadline($id) ? "Deadline supprimée." : null;
    $error = $success ? null : "Erreur lors de la suppression.";
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && !isset($_POST['update_deadline_id']) && !isset($_POST['delete_deadline_id'])) {
    $feature = $_POST['feature'] ?? '';
    $start = $_POST['start_date'] ?? '';
    $end = $_POST['end_date'] ?? '';

    if (!empty($feature) && !empty($start) && !empty($end)) {
        $created = $deadlineModel->createDeadline($feature, $start, $end);
        $success = $created ? "Deadline ajoutée avec succès." : "Erreur lors de l'ajout.";
    } else {
        $error = "Veuillez remplir tous les champs.";
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['create_announce'])) {
    $chefId = $_SESSION['id_user'];
    $createTime = date('Y-m-d H:i:s');

    $features = ['choose_modules', 'upload_notes'];

    $deadlines = [];

    foreach ($features as $feature) {
        $totalMinutes = $deadlineModel->getRemainingMinutesForFeature($feature);
        if ($totalMinutes !== null) {

            $days = floor($totalMinutes / (24 * 60));
            $hours = floor(($totalMinutes % (24 * 60)) / 60);
            $minutes = $totalMinutes % 60;
            
            $deadlines[$feature] = [
                'remaining' => [
                    'days' => $days,
                    'hours' => $hours,
                    'minutes' => $minutes
                ],
                'total_minutes' => $totalMinutes,
            ];
        }
    }
    $expiredDeadlines = $deadlineModel->getDeadlinesExpiredWithinLastDay();

        if (empty($deadlines) && empty($expiredDeadlines)) {
            $error = "Aucune échéance active, impossible de créer une annonce.";
        } elseif (!empty($expiredDeadlines)) {
            $expiredFeatures = array_column($expiredDeadlines, 'feature');
        
            $title = "📢 Fin d'échéance";
            $content = "⚠️ La période suivante est terminée :\n\n";
        
            if (in_array('choose_modules', $expiredFeatures)) {
                $content .= "📘 Choix des unités : le délai est passé.\n";
            }
        
            if (in_array('upload_notes', $expiredFeatures)) {
                $content .= "📝 Dépôt des notes : le délai est passé.\n";
            }
        
            $content .= "\nIl n'est plus possible de modifier ou déposer vos choix/notes.\n";
            $content .= "Pour toute question, merci de contacter l'administration universitaire.\n";
            $content .= "Merci pour votre compréhension.";
        
            if ($deadlineModel->create($title, $content, $createTime, $chefId)) {
                $_SESSION['success'] = "Annonce intitulée « <strong>" . htmlspecialchars($title) . "</strong> » créée avec succès.";
            } else {
                $_SESSION['error'] = "Erreur lors de la création de l'annonce.";
            }
        
            header("Location: " . $_SERVER['REQUEST_URI']);
            exit;
        } else {
            
        $title = "";
        $content = "";

        if (count($deadlines) === 2 && $deadlines['choose_modules']['total_minutes'] < 2880 && $deadlines['upload_notes']['total_minutes'] < 2880) {
            // Both are urgent
            $title = "⏰ Urgence : Choix des unités & Dépôt des notes";
        
            $cu = $deadlines['choose_modules']['remaining'];
            $un = $deadlines['upload_notes']['remaining'];
        
            $content = "⚠️ Attention, les échéances arrivent bientôt !\n\n";
        
            $content .= "📘 Choix des unités : il reste ". formatRemainingTime($cu) ."\n";
            $content .= "📝 Dépôt des notes : il reste ". formatRemainingTime($un) ."\n\n";
        
            $content .= "Merci de ne pas tarder à finaliser ces étapes importantes pour assurer le bon déroulement de l’année universitaire.\n";
            $content .= "En cas de retard, des sanctions ou pénalités pourraient s'appliquer.\n";
        } else {
            // Pick the soonest one
            uasort($deadlines, fn($a, $b) => $a['total_minutes'] <=> $b['total_minutes']);
            $soonest = array_key_first($deadlines);
            $remaining = $deadlines[$soonest]['remaining'];
        
            $timeLeft = formatRemainingTime($remaining);
        
            if ($soonest === 'choose_modules') {
                $title = "📌 Rappel : Choix des unités";
                $content = "Il vous reste $timeLeft pour choisir vos unités.\n\n";
                $content .= "N’oubliez pas que ce choix est crucial pour la validation de votre parcours.\n";
                $content .= "Assurez-vous de respecter ce délai pour éviter tout désagrément.";
            } elseif ($soonest === 'upload_notes') {
                $title = "📌 Rappel : Dépôt des notes";
                $content = "Il vous reste $timeLeft pour déposer vos notes.\n\n";
                $content .= "Merci de bien vouloir soumettre vos notes dans les délais afin de garantir une bonne gestion administrative.\n";
                $content .= "Tout retard pourrait impacter les résultats finaux.";
            }
        }
        

        if ($deadlineModel->create($title, $content, $createTime, $chefId)) {
            $_SESSION['success'] = "Annonce intitulée « <strong>" . htmlspecialchars($title) . "</strong> » créée avec succès.";
        } else {
            $_SESSION['error'] = "Erreur lors de la création de l'annonce.";
        }
        
        header("Location: " . $_SERVER['REQUEST_URI']);
        exit;
        
    }
}



$content = manageDeadlinesView($deadlineModel->getAllDeadlines(), $success, $error, $editing ?? null);

$dashboard = new DashBoard();
$dashboard->view("Deadlines", $content);
