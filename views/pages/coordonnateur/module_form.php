<div class="card">
    <div class="card-body">
        <h5 class="card-title fw-semibold mb-4">Créer un module</h5>

        <form method="POST" action="/e-service/internal/members/professor/coordonnateur/module_descriptif.php">
            <div class="mb-3">
                <label for="title" class="form-label">Titre du module</label>
                <input type="text" name="title" class="form-control" required>
            </div>

            <div class="mb-3">
                <label for="description" class="form-label">Description</label>
                <textarea name="description" class="form-control" rows="3" required></textarea>
            </div>

            <div class="mb-3">
                <label for="volume_horaire" class="form-label">Volume horaire</label>
                <input type="number" name="volume_horaire" class="form-control" required>
            </div>

            <div class="mb-3">
                <label for="semestre" class="form-label">Semestre</label>
                <select name="semestre" class="form-select" required>
                    <option value="s1">s1</option>
                    <option value="s2">s2</option>
                    <option value="s3">s3</option>
                    <option value="s4">s4</option>
                    <option value="s5">s5</option>
                    <option value="s6">s6</option>
                </select>
            </div>

            <div class="mb-3">
                <label for="credits" class="form-label">Crédits</label>
                <input type="number" name="credits" class="form-control" required>
            </div>

            <div class="mb-3">
                <label for="speciality" class="form-label">Spécialité</label>
                <select name="speciality" class="form-select" required>
                    <?php if (!empty($specialities)) : ?>
                        <?php foreach ($specialities as $speciality) : ?>
                            <option value="<?= htmlspecialchars($speciality['id_speciality']) ?>">
                                <?= htmlspecialchars($speciality['title']) ?>
                            </option>
                        <?php endforeach; ?>
                    <?php else : ?>
                        <option disabled selected>Aucune spécialité disponible</option>
                    <?php endif; ?>
                </select>
            </div>

    </div>

</div>

<div class="mb-3">
    <label for="responsable" class="form-label">Responsable du module</label>
    <select name="responsable" class="form-select" required>
        <?php foreach ($professors as $professor) : ?>
            <option value="<?= htmlspecialchars($professor['id_user']) ?>">
                <?= htmlspecialchars($professor['full_name']) ?>
            </option>
        <?php endforeach; ?>
    </select>
</div>

</div>

</div>

<input type="hidden" name="id_filiere" value="<?= htmlspecialchars($filiereId ?? '') ?>">

<button type="submit" class="btn btn-primary">Enregistrer</button>
</form>
</div>
</div>