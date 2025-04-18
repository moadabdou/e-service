<?php
session_start();

require_once $_SERVER['DOCUMENT_ROOT'] . "/e-service/views/pages/dashboard/dashboard.php";
require_once $_SERVER['DOCUMENT_ROOT'] . "/e-service/models/univeristy/module.php";
require_once $_SERVER['DOCUMENT_ROOT'] . "/e-service/controllers/entity/user.php";

$userController = new UserController();
$userController->checkCurrentUserAuthority(["professor"]);

$moduleModel = new ModuleModel();
$errors = [];

$selectedModules = $moduleModel->getSelectedModulesWithStatus($_SESSION['id_user']);

ob_start();
?>
<div class="container py-5 px-4 px-md-5">
    <a href="/e-service/internal/members/professor/choose_units.php" class="btn btn-outline-primary mb-4">
    <i class="ti ti-arrow-left"></i>  Retour à la sélection
    </a>

    <h2 class="text-center mb-5 text-primary fw-bold">
        📝 Vos modules déjà sélectionnés
    </h2>

    <?php if (empty($selectedModules)) : ?>
        <div class="alert alert-warning text-center">
            Vous n'avez sélectionné aucun module pour le moment.
        </div>
    <?php else : ?>
        <div class="row g-4">
            <?php foreach ($selectedModules as $module) : ?>
                <div class="col-md-6 col-lg-4">
                    <div class="card p-4 shadow-sm rounded-4 h-100">
                        <h5 class="card-title mb-3 text-primary fw-bold">
                            <?= htmlspecialchars($module['title']) ?>
                        </h5>
                        <p class="mb-2"><strong>Volume horaire :</strong> <?= htmlspecialchars($module['volume_horaire']) ?> heures</p>
                        <p class="mb-2"><strong>Description :</strong><br><?= htmlspecialchars($module['description'] ?? 'Aucune description disponible.') ?></p>
                        <p class="mb-2"><strong>Semestre :</strong> <?= formatSemester($module['semester'] ?? '') ?></p>
                        <p class="mb-2"><strong>Professeur :</strong> <?= htmlspecialchars($module['user_full_name'] ?? 'Non attribué') ?></p>
                        <div class="d-flex justify-content-center align-items-center mt-4">
                            <?= getStatusBadge($module['status'] ?? 'in progress') ?>
                        </div>

                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>
<?php
$content = ob_get_clean();
$dashboard = new DashBoard();
$dashboard->view("professor", "selectedUnits", $content);
?>