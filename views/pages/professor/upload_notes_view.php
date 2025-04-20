<?php
function uploadNotesView(array $assignedModules, ?array $info = null): string {
    ob_start();
?>
<div class="container mt-5 px-4 px-md-5">
    <div class="d-flex justify-content-between align-items-center flex-wrap mb-4">
        <h2 class="fw-bold text-primary mb-2 mb-md-0">
            <i class="ti ti-upload"></i> Uploader les notes</h2>
    </div>

    <?php if ($info): ?>
        <div class="alert alert-<?= htmlspecialchars($info['type']) ?> text-center fw-semibold">
            <?= htmlspecialchars($info['msg']) ?>
        </div>
    <?php endif; ?>

    <form method="POST" enctype="multipart/form-data" class="card shadow-sm p-4 rounded-4">
        <div class="mb-3">
            <label for="selected_module" class="form-label fw-semibold">Module :</label>
            <select class="form-select" name="selected_module" id="selected_module" required>
                <option value="" disabled selected>-- Choisissez un module --</option>
                <?php foreach ($assignedModules as $module): ?>
                    <option value="<?= $module['id_module'] ?>">
                        <?= htmlspecialchars($module['title']) ?> - <?= htmlspecialchars($module['filiere_name']) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="mb-3">
            <label for="session_type" class="form-label fw-semibold">Type de session :</label>
            <select class="form-select" name="session_type" id="session_type" required>
                <option value="normal">Session normale</option>
                <option value="ratt">Rattrapage</option>
            </select>
        </div>

        <div class="mb-3">
            <label for="notes_file" class="form-label fw-semibold">Fichier de notes (PDF ou Excel) :</label>
            <input type="file" class="form-control" name="notes_file" id="notes_file" accept=".pdf,.xls,.xlsx" required>
        </div>

        <div class="text-center mt-4">
            <button type="submit" class="btn btn-primary px-5 py-2 rounded-pill">
                <i class="ti ti-send"></i> Envoyer
            </button>
        </div>
    </form>
</div>
<?php
    return ob_get_clean();
}
?>