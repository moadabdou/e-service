<?php
function uploadSingleNoteView(array $module, ?array $info = null): string {
    ob_start();
    ?>
<div class="container mt-4 px-4 px-md-5">
    <h2 class="fw-bold text-primary mb-4">
        <i class="ti ti-upload"></i> Uploader les notes pour : <?= htmlspecialchars($module[0]["title"]) ?>
    </h2>

    <?php if ($info): ?>
        <div class="alert alert-<?= htmlspecialchars($info['type']) ?> text-center fw-semibold shadow-sm">
            <?= htmlspecialchars($info['msg']) ?>
        </div>
    <?php endif; ?>

    <form method="POST" enctype="multipart/form-data" class="card shadow-sm p-4 rounded-4">
        <div class="mb-3">
            <label class="form-label fw-semibold">Session :</label>
            <select name="session_type" class="form-select" required>
                <option value="normal">Session normale</option>
                <option value="ratt">Rattrapage</option>
            </select>
        </div>

        <div class="mb-3">
            <label for="notes_file" class="form-label fw-semibold">Fichier de notes (PDF ou Excel) :</label>
            <input type="file" name="notes_file" id="notes_file" class="form-control" accept=".pdf,.xls,.xlsx" required>
        </div>

        <div class="text-center">
            <button type="submit" class="btn btn-success px-5 py-2 rounded-pill">
                <i class="ti ti-send"></i> Envoyer
            </button>
            <a href="/e-service/internal/members/professor/" class="btn btn-outline-secondary px-4 py-2 rounded-pill ms-2">
                <i class="ti ti-arrow-left"></i> Retour
            </a>
        </div>
    </form>
</div>
<?php
    return ob_get_clean();
}
?>
