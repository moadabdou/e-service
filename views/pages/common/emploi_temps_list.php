<div class="container mt-4">
    <h2 class="fw-bold text-primary mb-4">
        <i class="ti ti-calendar-time"></i> Emplois du temps disponibles
    </h2>

    <?php
    $id_filiere = $id_filiere ?? '';
    $semestre   = $semestre ?? '';
    $annee      = $annee ?? date('Y');
    ?>

    <form method="GET" class="row g-3 align-items-end mb-4">
        <div class="col-md-4">
            <label class="form-label">Filière</label>
            <select name="id_filiere" class="form-select" required>
                <option value="">-- Choisir une filière --</option>
                <?php foreach ($filieres as $f): ?>
                    <option value="<?= $f['id_filiere'] ?>" <?= ($id_filiere == $f['id_filiere']) ? 'selected' : '' ?>>
                        <?= htmlspecialchars($f['title']) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="col-md-3">
            <label class="form-label">Semestre</label>
            <select name="semestre" class="form-select">
                <?php foreach (['s1', 's2', 's3', 's4', 's5', 's6'] as $s): ?>
                    <option value="<?= $s ?>" <?= ($semestre == $s) ? 'selected' : '' ?>>
                        <?= strtoupper($s) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="col-md-3">
            <label class="form-label">Année</label>
            <input type="number" name="annee" class="form-control" value="<?= htmlspecialchars($annee) ?>" required>
        </div>

        <div class="col-md-2">
            <button type="submit" class="btn btn-primary w-100">
                <i class="ti ti-search"></i> Rechercher
            </button>
        </div>
    </form>

    <?php if (!empty($uploads)): ?>
        <ul class="list-group">
            <?php foreach ($uploads as $up): ?>
                <li class="list-group-item d-flex justify-content-between align-items-center">
                    <div>
                        <strong><?= htmlspecialchars($up['nom_fichier']) ?></strong><br>
                        <small class="text-muted">
                            Semestre <?= strtoupper($up['semestre']) ?> | Année <?= $up['annee'] ?> | Uploadé le <?= $up['uploaded_at'] ?>
                        </small>
                    </div>
                    <a href="<?= htmlspecialchars($up['chemin_fichier']) ?>" target="_blank" class="btn btn-outline-primary btn-sm">
                        <i class="ti ti-download"></i> Télécharger
                    </a>
                </li>
            <?php endforeach; ?>
        </ul>
    <?php elseif ($id_filiere): ?>
        <div class="alert alert-warning">Aucun emploi du temps trouvé pour cette recherche.</div>
    <?php endif; ?>
</div>