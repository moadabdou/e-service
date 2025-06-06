<div class="container mt-4">
    <h2 class="fw-bold text-primary mb-4">
        <i class="ti ti-user-plus"></i> Affecter un Module à un Vacataire
    </h2>

    <!-- Message -->
    <?php if (!empty($message)) : ?>
        <div class="alert alert-<?= htmlspecialchars($message['type']) ?>">
            <?= htmlspecialchars($message['text']) ?>
        </div>
    <?php endif; ?>

    <!-- Formulaire d’affectation -->
    <div class="card mb-4 shadow-sm">
        <div class="card-body">
            <form method="POST" class="row g-3 align-items-end">
                <div class="col-md-4">
                    <label class="form-label">Vacataire</label>
                    <select name="vacataire_id" class="form-select" required>
                        <option value="" disabled selected>-- Choisir un vacataire --</option>
                        <?php foreach ($vacataires as $v) : ?>
                            <option value="<?= (int)$v['id_user'] ?>">
                                <?= htmlspecialchars($v['name']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="col-md-4">
                    <label class="form-label">Module</label>
                    <select name="module_id" class="form-select" required>
                        <option value="" disabled selected>-- Choisir un module --</option>
                        <?php foreach ($modules as $m) : ?>
                            <option value="<?= (int)$m['id_module'] ?>">
                                <?= htmlspecialchars($m['code_module'] . ' - ' . $m['title']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="col-md-2">
                    <label class="form-label">Année</label>
                    <input type="number" name="annee" class="form-control" value="<?= date('Y') ?>" required min="2000" max="<?= date('Y') + 1 ?>">
                </div>

                <div class="col-md-2">
                    <button type="submit" class="btn btn-primary w-100">
                        <i class="ti ti-device-floppy"></i> Affecter
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Historique des affectations -->
    <h4 class="mb-3">Affectations déjà réalisées</h4>
    <?php if (empty($affectations)) : ?>
        <div class="alert alert-info">Aucune affectation enregistrée pour le moment.</div>
    <?php else : ?>
        <ul class="list-group">
            <?php foreach ($affectations as $a) : ?>
                <li class="list-group-item d-flex justify-content-between align-items-center">
                    <div>
                        <strong><?= htmlspecialchars($a['module_title']) ?> (<?= htmlspecialchars($a['code_module']) ?>)</strong><br>
                        <small class="text-muted">
                            Vacataire : <?= htmlspecialchars($a['firstName'] . ' ' . $a['lastName']) ?> —
                            Année : <?= (int)$a['annee'] ?>
                        </small>
                    </div>
                </li>
            <?php endforeach; ?>
        </ul>
    <?php endif; ?>
</div>