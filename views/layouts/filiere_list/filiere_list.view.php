<!-- Start of Filiere List -->
<div class="card shadow-sm mb-4">
    <div class="card-header bg-light d-flex justify-content-between align-items-center">
        <h5 class="mb-0">Toutes les filières</h5>
        <a href="<?= htmlspecialchars($_SERVER["PHP_SELF"])?>?id_dep=<?= $dep_id ?>&filter=1" class="btn btn-primary btn-sm">
            <i class="ti ti-plus me-1"></i> Ajouter une filière
        </a>
    </div>
    <div class="card-body p-0"> 
        <div class="table-responsive"> 
            <table class="table table-hover table-striped mb-0">
                <thead class="table-light">
                    <tr>
                        <th scope="col" style="width: 60%;">Titre de la Filière</th>
                        <th scope="col" style="width: 40%;" class="text-end">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (count($data) > 0): ?>
                        <?php foreach ($data as $filiere): ?>
                            <tr>
                                <td><?= htmlspecialchars($filiere['filiere_name']) ?></td>
                                <td class="text-end">
                                    <a href="/e-service/internal/members/admin/view_filiere.php?view=<?= htmlspecialchars($filiere['id_filiere']) ?>" class="btn btn-sm btn-outline-primary">
                                        Plus <i class="ti ti-arrow-right ms-1"></i>
                                    </a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="2" class="text-center">Aucune filière trouvée</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div> <!-- End table-responsive -->
    </div> 
</div>
