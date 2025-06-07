<div class="container mt-4 px-4">
    <h2 class="fw-bold text-primary mb-3">
        <i class="ti ti-clipboard-text"></i> Affectations validées (année <?= htmlspecialchars($year) ?>)
    </h2>

    <!-- Sélecteur d'année -->
    <form method="GET" class="mb-4">
        <div class="input-group w-25">
            <span class="input-group-text"><i class="ti ti-calendar"></i></span>
            <select name="year" class="form-select" onchange="this.form.submit()">
                <?php for ($y = date('Y') - 1; $y <= date('Y') + 1; $y++): ?>
                    <option value="<?= $y ?>" <?= $year == $y ? 'selected' : '' ?>>
                        <?= $y ?>
                    </option>
                <?php endfor; ?>
            </select>
        </div>
    </form>

    <?php if (empty($assignments)): ?>
        <div class="alert alert-info rounded-4">
            <i class="ti ti-info-circle me-2"></i> Aucune affectation pour cette année.
        </div>
    <?php else: ?>
        <?php foreach ($assignments as $sem => $mods): ?>
            <div class="card mb-4 shadow-sm border-0 rounded-4">
                <div class="card-header bg-primary text-white rounded-top-4">
                    <h5 class="mb-0">
                        <i class="ti ti-calendar-event"></i> Semestre <?= strtoupper($sem) ?>
                    </h5>
                </div>
                <div class="card-body p-3">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle">
                            <thead class="table-light">
                                <tr class="text-center">
                                    <th>Code</th>
                                    <th>Titre</th>
                                    <th>Professeur</th>
                                    <th>Volume Total</th>
                                    <th>Crédits</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($mods as $m):
                                    $vt = ($m['volume_cours'] + $m['volume_td'] + $m['volume_tp'] + $m['volume_autre']);
                                ?>
                                    <tr class="text-center">
                                        <td><?= htmlspecialchars($m['code_module']) ?></td>
                                        <td><?= htmlspecialchars($m['title']) ?></td>
                                        <td><?= htmlspecialchars($m['professor_name']) ?></td>
                                        <td><?= $vt ?>h</td>
                                        <td><?= htmlspecialchars($m['credits']) ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>
</div>