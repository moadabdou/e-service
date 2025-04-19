<?php
session_start();

require_once $_SERVER['DOCUMENT_ROOT'] . "/e-service/views/pages/dashboard/dashboard.php";
require_once $_SERVER['DOCUMENT_ROOT'] . "/e-service/models/univeristy/module.php";
require_once $_SERVER['DOCUMENT_ROOT'] . "/e-service/controllers/entity/user.php";

$userController = new UserController();
$userController->checkCurrentUserAuthority(["professor"]);

$moduleModel = new ModuleModel();
$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_module_id'])) {
    $moduleIdToDelete = intval($_POST['delete_module_id']);
    $userId = $_SESSION['id_user'] ?? null;

    if ($userId && $moduleIdToDelete) {
        $moduleModel->deleteModuleChoice($userId, $moduleIdToDelete);
        $_SESSION['success_message'] = "Module supprimé avec succès";
        header("Location: " . $_SERVER['REQUEST_URI']);
        exit;
    }
}

$selectedModules = $moduleModel->getSelectedModulesWithStatus($_SESSION['id_user']);

ob_start();
?>
<div class="container py-5 px-4 px-md-5">
    <a href="/e-service/internal/members/professor/choose_units.php" class="btn btn-outline-primary mb-4">
    <i class="ti ti-arrow-back-up"></i> Retour à la sélection
    </a>
    <div class="d-flex justify-content-between align-items-center flex-wrap mb-4">
        <h2 class="fw-bold text-primary mb-2 mb-md-0">
            <i class="ti ti-checklist"></i> Vos modules déjà sélectionnés
        </h2>
    </div>

    <?php if (isset($_SESSION['success_message'])) : ?>
        <div class="alert alert-success text-center fw-semibold"><?= htmlspecialchars($_SESSION['success_message']) ?></div>
        <?php unset($_SESSION['success_message']); ?>
    <?php endif; ?>

    <?php if (empty($selectedModules)) : ?>
        <div class="alert alert-warning text-center">
            Vous n'avez sélectionné aucun module pour le moment.
        </div>
    <?php else : ?>
        <div class="row g-4">
            <?php foreach ($selectedModules as $module) : ?>
                <div class="col-md-6 col-lg-4">
                    <div class="card p-4 shadow-sm rounded-4 h-80">
                        <h5 class="card-title mb-3 text-primary fw-bold">
                            <?= htmlspecialchars($module['title']) ?>
                        </h5>
                        <p class="mb-2"><strong>Volume horaire :</strong> <?= htmlspecialchars($module['volume_horaire']) ?> heures</p>
                        <p class="mb-2"><strong>Description :</strong><br><?= htmlspecialchars($module['description'] ?? 'Aucune description disponible.') ?></p>
                        <p><strong>Filière :</strong> <?= htmlspecialchars($module['filiere_name'] ?? 'Aucune') ?></p>
                        <p class="mb-2"><strong>Semestre :</strong> <?= formatSemester($module['semester'] ?? '') ?></p>
                        <div class="d-flex flex-column align-items-center mt-4 gap-2">
                            <?= getStatusBadge($module['status'] ?? 'in progress') ?>

                            <?php if (($module['status'] ?? '') !== 'validated') : ?>
                                <button 
                                    type="button" 
                                    class="btn btn-outline-danger btn-sm delete-btn" 
                                    data-bs-toggle="modal" 
                                    data-bs-target="#deleteModal" 
                                    data-module-id="<?= htmlspecialchars($module['id_module']) ?>"
                                    data-module-title="<?= htmlspecialchars($module['title']) ?>"
                                >
                                    <i class="ti ti-trash"></i> Supprimer
                                </button>
                            <?php endif; ?>
                        </div>

                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>

<!-- Modal de confirmation -->
<div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <form method="POST" class="modal-content shadow rounded-4">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title" id="deleteModalLabel">Confirmer la suppression</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Fermer"></button>
            </div>
            <div class="modal-body">
                <p>Voulez-vous vraiment supprimer le module <strong id="modalModuleTitle"></strong> ?</p>
                <input type="hidden" name="delete_module_id" id="modalModuleId">
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                <button type="submit" class="btn btn-danger">Supprimer</button>
            </div>
        </form>
    </div>
</div>

<script>
    const deleteButtons = document.querySelectorAll('.delete-btn');
    const modalModuleId = document.getElementById('modalModuleId');
    const modalModuleTitle = document.getElementById('modalModuleTitle');

    deleteButtons.forEach(btn => {
        btn.addEventListener('click', () => {
            const moduleId = btn.getAttribute('data-module-id');
            const moduleTitle = btn.getAttribute('data-module-title');
            modalModuleId.value = moduleId;
            modalModuleTitle.textContent = moduleTitle;
        });
    });
</script>
<?php
$content = ob_get_clean();
$dashboard = new DashBoard();
$dashboard->view("professor", "selectedUnits", $content);
?>
