<!-- Start of Department List -->
<div class="card shadow-sm mb-4">
  <div class="card-header bg-light d-flex justify-content-between align-items-center">
    <h5 class="mb-0">Tous les départements</h5>
    <a href="<?=  htmlspecialchars($_SERVER["PHP_SELF"]) ?>?filter=1" class="btn btn-primary btn-sm"> <!-- Link to your add department page -->
      <i class="ti ti-plus me-1"></i> Ajouter un département
    </a>
  </div>
  <div class="card-body p-3"> 
    <div class="table-responsive"> 
      <table class="table table-hover table-striped mb-0">
        <thead class="table-light">
          <tr>
            <th scope="col" style="width: 60%;">Titre du Département</th>
            <th scope="col" style="width: 40%;" class="text-end">Actions</th>
          </tr>
        </thead>
        <tbody>

          <?php if (count($data) > 0): ?>
            <?php foreach ($data as $department): ?>
              <tr>
                <td><?= htmlspecialchars($department['title']) ?></td>
                <td class="text-end">
                  <a href="/e-service/internal/members/admin/view_departement.php?view=<?= htmlspecialchars($department['id_deparetement']) ?>" class="btn btn-sm btn-outline-primary">
                    Plus <i class="ti ti-arrow-right ms-1"></i>
                  </a>
                </td>
              </tr>
            <?php endforeach; ?>
          <?php else: ?>
            <tr>
              <td colspan="2" class="text-center">Aucun département trouvé</td>
            </tr>
          <?php endif; ?>
        </tbody>
      </table>
    </div> <!-- End table-responsive -->

  </div> 
</div>