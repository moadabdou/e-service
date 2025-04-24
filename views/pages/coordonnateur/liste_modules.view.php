<?php

?>

<div class="card mt-4 shadow-sm">
    <div class="card-body">
        <h5 class="card-title fw-semibold mb-4">Modules de la filière</h5>

        <?php if (!empty($modules)): ?>
            <table class="table table-bordered table-hover align-middle">
                <thead class="bg-primary text-white">
                    <tr>
                        <th scope="col">ID</th>
                        <th scope="col">Titre</th>
                        <th scope="col">Volume horaire</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($modules as $module): ?>
                        <tr>
                            <td><?= htmlspecialchars($module['id_module']) ?></td>
                            <td><?= htmlspecialchars($module['title']) ?></td>
                            <td><?= htmlspecialchars($module['volume_horaire']) ?>h</td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php else: ?>
            <div class="alert alert-warning">Aucun module trouvé pour votre filière.</div>
        <?php endif; ?>
    </div>
</div>