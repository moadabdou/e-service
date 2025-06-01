<div class="container mt-4 px-4">
    <h2 class="fw-bold text-primary mb-4">
        <i class="ti ti-upload"></i> Importer emploi du temps
    </h2>

    <?php if ($message): ?>
        <div class="alert alert-<?= $message['type'] ?>">
            <?= htmlspecialchars($message['text']) ?>
        </div>
    <?php endif; ?>

    <div class="card mb-4 shadow-sm">
        <div class="card-body">
            <form method="POST" enctype="multipart/form-data" class="row g-3 align-items-end">
                <div class="col-md-3">
                    <label class="form-label">Année</label>
                    <input type="number" name="annee" class="form-control" value="<?= htmlspecialchars($annee) ?>">
                </div>
                <div class="col-md-3">
                    <label class="form-label">Semestre</label>
                    <select name="semestre" class="form-select">
                        <?php foreach (['s1', 's2', 's3', 's4', 's5', 's6'] as $s): ?>
                            <option value="<?= $s ?>" <?= $semestre == $s ? 'selected' : '' ?>>
                                <?= strtoupper($s) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-md-4">
                    <label class="form-label">Fichier (PDF/XLSX)</label>
                    <input type="file" name="timetable_file" class="form-control" accept=".pdf,.xls,.xlsx" required>
                </div>
                <div class="col-md-2">
                    <button type="submit" class="btn btn-primary w-100">
                        <i class="ti ti-upload"></i> Importer
                    </button>
                </div>
            </form>
        </div>
    </div>

    <h3 class="fw-semibold mb-3">Historique des imports</h3>
    <?php if (empty($uploads)): ?>
        <div class="alert alert-info">Aucun emploi du temps uploadé pour <?= strtoupper($semestre) ?> <?= $annee ?>.</div>
    <?php else: ?>
        <ul class="list-group">
            <?php foreach ($uploads as $up): ?>
                <li class="list-group-item d-flex justify-content-between align-items-center">
                    <div>
                        <strong><?= htmlspecialchars($up['nom_fichier']) ?></strong>
                        <br><small class="text-muted">
                            <?= strtoupper($up['semestre']) ?> <?= $up['annee'] ?> — <?= $up['uploaded_at'] ?>
                        </small>
                    </div>
                    <a href="<?= htmlspecialchars($up['chemin_fichier']) ?>" target="_blank"
                        class="btn btn-sm btn-outline-primary">
                        <i class="ti ti-download"></i> Télécharger
                    </a>
                </li>
            <?php endforeach; ?>
        </ul>
    <?php endif; ?>
</div>