<div class="container mt-4">
    <h3 class="fw-bold text-primary mb-4">
        <i class="ti ti-book-2"></i> Mes modules affectés
    </h3>

    <?php if (empty($modules)): ?>
        <div class="alert alert-info">Aucun module ne vous a été affecté pour le moment.</div>
    <?php else: ?>
        <div class="table-responsive">
            <table class="table table-bordered align-middle">
                <thead class="table-light">
                    <tr>
                        <th>Code</th>
                        <th>Titre</th>
                        <th>Semestre</th>
                        <th>Année</th>
                        <th>Crédits</th>
                        <th>Volume total</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($modules as $mod):
                        $volume_total = ($mod['volume_cours'] ?? 0) + ($mod['volume_td'] ?? 0) + ($mod['volume_tp'] ?? 0) + ($mod['volume_autre'] ?? 0);
                    ?>
                        <tr>
                            <td><?= htmlspecialchars($mod['code_module']) ?></td>
                            <td><?= htmlspecialchars($mod['title']) ?></td>
                            <td><?= strtoupper($mod['semester']) ?></td>
                            <td><?= htmlspecialchars($mod['annee']) ?></td>
                            <td><?= htmlspecialchars($mod['credits']) ?></td>
                            <td><?= $volume_total ?> h</td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>
</div>