<!-- Start Department View Content -->
<div class="card shadow-sm">
  <div class="card-body p-4 p-md-5">

    <!-- Department Title -->
    <div class="mb-5">
      <h3 class="card-title mb-2">
        <i class="ti ti-building-skyscraper text-primary me-2"></i>
        <!-- Placeholder: Replace with dynamic department title -->
        <?= htmlspecialchars(  $dep_data["title"]  ) ?>
      </h3>
    </div>

    <!-- Department Description -->
    <div class="mb-5">
      <h6 class="text-muted fw-semibold mb-3">Description</h6>
      <!-- Placeholder: Replace with dynamic department description -->
      <p class="card-text lead">
            <?= htmlspecialchars(  $dep_data["description"]  ) ?>
      </p>
    </div>

    <hr class="my-5" />

    <!-- Head of Department Section -->
    <div class="mb-4">
      <h4 class="mb-4">Chef de Département</h4>

    <?php if (is_array($head_data) && count($head_data) > 0): ?>
      <!-- Case 1: Head of Department Assigned -->
      <div class="d-flex align-items-center mb-3 p-3 bg-light rounded border" id="current-head-info">
        <img
        src="<?= $head_data['img'] ?>" alt="Avatar Chef"
        class="rounded-circle me-4" width="60" height="60"
        id="head-avatar"
         />
        <div class="flex-grow-1">
        <h6 class="mb-1" id="head-name"> Dr.<?= htmlspecialchars($head_data['firstName']." ".$head_data['lastName']) ?></h6>
        <small class="text-muted d-block" id="head-email"><?= htmlspecialchars($head_data['email']) ?></small>
        </div>
        <div class="ms-3 d-flex flex-column flex-sm-row gap-3">
        <a href="/e-service/internal/members/common/view_profile.php?id=<?= $head_data['id_user'] ?>" class="btn btn-outline-secondary">
          <i class="ti ti-user me-1"></i> Voir Profil
        </a>
        <button
          type="button"
          class="btn btn-outline-primary"
          data-bs-toggle="modal"
          data-bs-target="#changeHeadModal"
         >
           <i class="ti ti-edit me-1"></i> Modifier
        </button>
        </div>
      </div>
    <?php else: ?>
      <!-- Case 2: No Head of Department Assigned -->
      <div class="alert alert-warning d-flex justify-content-between align-items-center p-3" role="alert" id="no-head-info">
         <span class="me-3">Aucun chef de département n'est actuellement assigné.</span>
         <button type="button" class="btn btn-primary text-nowrap" data-bs-toggle="modal" data-bs-target="#changeHeadModal">
           <i class="ti ti-user-plus me-1"></i> Assigner un Chef
         </button>
      </div>
    <?php endif; ?>

    </div>

  </div> <!-- End card-body -->
</div> <!-- End card -->

<!-- ###################################################### -->
<!-- ##      Modal for Changing Head of Department       ## -->
<!-- ###################################################### -->
<div class="modal fade" id="changeHeadModal" tabindex="-1" aria-labelledby="changeHeadModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="changeHeadModalLabel">Changer le Chef de Département</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body p-4">
        <p class="text-muted mb-4">Sélectionnez le nouvel utilisateur qui sera désigné comme chef de ce département.</p>

        <!-- Optional: Search/Filter Input -->
         <!-- <input type="text" class="form-control mb-4" placeholder="Filtrer les utilisateurs..."> -->

        <!-- User list should be populated dynamically via JavaScript -->
        <div class="list-group list-group-flush" id="user-candidate-list">
          <!-- Example Structure (to be replaced/populated by JS) -->

          <!-- Current Head Example -->
          <div class="list-group-item d-flex justify-content-between align-items-center py-3">
            <div class="d-flex align-items-center">
               <img src="placeholder-avatar.png" alt="Avatar" class="rounded-circle me-4" width="48" height="48"/>
               <div>
                 <h6 class="mb-0">Dr. Alice Dupont</h6>
                 <small class="text-muted">alice.dupont@example.com</small>
               </div>
            </div>
            <span class="badge bg-success rounded-pill px-3 py-1">Chef Actuel</span>
          </div>

          <!-- Candidate User Example 1 -->
          <div class="list-group-item d-flex justify-content-between align-items-center py-3">
            <div class="d-flex align-items-center">
               <img src="placeholder-avatar2.png" alt="Avatar" class="rounded-circle me-4" width="48" height="48"/>
               <div>
                 <h6 class="mb-0">Prof. Bernard Lambert</h6>
                 <small class="text-muted">bernard.lambert@example.com</small>
               </div>
            </div>
            <!-- Ensure data-user-id holds the actual user ID -->
            <button type="button" class="btn btn-primary set-head-button" data-user-id="456">
               <i class="ti ti-check me-1"></i> Définir comme Chef
            </button>
          </div>

           <!-- Candidate User Example 2 -->
           <div class="list-group-item d-flex justify-content-between align-items-center py-3">
             <div class="d-flex align-items-center">
                <img src="placeholder-avatar3.png" alt="Avatar" class="rounded-circle me-4" width="48" height="48"/>
                <div>
                  <h6 class="mb-0">Mme. Chloé Martin</h6>
                  <small class="text-muted">chloe.martin@example.com</small>
                </div>
             </div>
             <!-- Ensure data-user-id holds the actual user ID -->
             <button type="button" class="btn btn-primary set-head-button" data-user-id="789">
                <i class="ti ti-check me-1"></i> Définir comme Chef
             </button>
           </div>

           <!-- Placeholder if no other candidates (potentially add via JS) -->
           <!--
           <div class="list-group-item text-center text-muted py-3">
               Aucun autre utilisateur éligible trouvé.
           </div>
           -->
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
        <!-- Potentially add a primary save button here if the selection isn't immediate -->
      </div>
    </div>
  </div>
</div>
<!-- End Modal -->

<script>
    // Listen for modal open event
    document.getElementById('changeHeadModal').addEventListener('show.bs.modal', function () {
        // Get current URL
        const currentUrl = window.location.href;
        
        // Get and clear the list element
        $('#user-candidate-list').empty();
        
        // Show loading state
        $('#user-candidate-list').html('<div class="text-center p-3"><div class="spinner-border text-primary" role="status"></div></div>');

        // Fetch users via POST
        $.ajax({
            url: currentUrl,
            method: 'POST',
            data: { action: 'get_users' },
            success: function(response) {
                // Clear loading state
                $('#user-candidate-list').empty();
                
                if (response && typeof response === 'object' && response.length > 0) {
                    // Populate users
                    response.forEach(user => {
                        const userHtml = `
                            <div class="list-group-item d-flex justify-content-between align-items-center py-3">
                                <div class="d-flex align-items-center">
                                    <img src="${user.img}" alt="Avatar" class="rounded-circle me-4" width="48" height="48"/>
                                    <div>
                                        <h6 class="mb-0">Dr. ${user.firstName} ${user.lastName}</h6>
                                        <small class="text-muted">${user.email}</small>
                                    </div>
                                </div>
                                <button type="button" class="btn btn-primary set-head-button" data-user-id="${user.id_user}">
                                    <i class="ti ti-check me-1"></i> Définir comme Chef
                                </button>
                            </div>`;
                        $('#user-candidate-list').append(userHtml);
                    });
                } else {
                    // Show message when no candidates found
                    $('#user-candidate-list').html('<div class="text-center text-muted py-3">Aucun candidat trouvé.</div>');
                }
            },
            error: function() {
                $('#user-candidate-list').html('<div class="alert alert-danger">Erreur lors du chargement des utilisateurs</div>');
            }
        });
    });
</script>