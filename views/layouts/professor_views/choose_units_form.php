<?php
function chooseUnitsFormView($availableModules, $selectedModules, $errors, $info) {
    ob_start();
    ?>

    <div class="container mt-4">
        <h2 class="mb-3">📚 Choisir mes modules</h2>

        <?php if ($info) : ?>
            <div class="alert alert-<?= htmlspecialchars($info['type']) ?>"><?= htmlspecialchars($info['msg']) ?></div>
        <?php endif; ?>

        <form method="POST">
            <div class="form-group mb-3">
                <label class="form-label">Modules disponibles :</label><br>

                <?php if (empty($availableModules)) : ?>
                    <p class="text-muted">Aucun module disponible pour votre département actuellement.</p>
                <?php else : ?>
                    <?php foreach ($availableModules as $module) : ?>
                        <div class="form-check">
                            <input 
                                class="form-check-input" 
                                type="checkbox" 
                                name="modules[]" 
                                value="<?= htmlspecialchars($module['id_module']) ?>"
                                <?= in_array($module['id_module'], array_column($selectedModules, 'id_module')) ? 'checked' : '' ?>
                            >
                            <label class="form-check-label">
                                <?= htmlspecialchars($module['title']) ?> 
                                (<?= htmlspecialchars($module['volume_horaire']) ?>h)
                            </label>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>

                <?php if (isset($errors['modules'])) : ?>
                    <div class="text-danger mt-2"><?= htmlspecialchars($errors['modules']) ?></div>
                <?php endif; ?>
            </div>

            <button type="submit" class="btn btn-primary">✅ Valider mes choix</button>
        </form>

        <?php if (!empty($selectedModules)) : ?>
            <hr>
            <h4 class="mt-4">📝 Modules déjà sélectionnés :</h4>
            <ul class="list-group">
                <?php foreach ($selectedModules as $module) : ?>
                    <li class="list-group-item">
                        <?= htmlspecialchars($module['title']) ?> (<?= htmlspecialchars($module['volume_horaire']) ?>h)
                    </li>
                <?php endforeach; ?>
            </ul>
        <?php endif; ?>

    </div>

    <?php
    return ob_get_clean();
}
?>
