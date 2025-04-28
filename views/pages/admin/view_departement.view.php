<!-- Start Department View Content -->
<div class="card shadow-sm">
  <div class="card-body p-4 p-md-5">

    <!-- Department Title -->
    <div class="mb-5">
      <h3 class="card-title mb-2">
        <i class="ti ti-building-skyscraper text-primary me-2"></i>
        <!-- Placeholder: Replace with dynamic department title -->
        <?= htmlspecialchars($dep_data["title"]) ?>
      </h3>
    </div>

    <!-- Department Description -->
    <div class="mb-5">
      <h6 class="text-muted fw-semibold mb-3">Description</h6>
      <!-- Placeholder: Replace with dynamic department description -->
      <p class="card-text lead">
        <?= htmlspecialchars($dep_data["description"]) ?>
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
            id="head-avatar" />
          <div class="flex-grow-1">
            <h6 class="mb-1" id="head-name"> Dr.<?= htmlspecialchars($head_data['firstName'] . " " . $head_data['lastName']) ?></h6>
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
              data-bs-target="#changeHeadModal">
              <i class="ti ti-edit me-1"></i> Modifier
            </button>
            <button
              type="button"
              class="btn btn-outline-danger delete-head"
              data-user-id="<?= $head_data['id_user'] ?>">
              <i class="ti ti-trash me-1"></i> Supprimer
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
    <!-- Filiere de ce departement -->
    <div class="divider"></div>

    <div class="filiere-section d-flex">
      <h2 class="section-title me-auto">Filières</h2>
      <a class=" btn btn-outline-secondary" href="/e-service/internal/members/admin/filieres.php?id_dep=<?= $dep_data["id_deparetement"] ?>">
        Voir toutes les filières de ce département
      </a>
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
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
        <!-- Potentially add a primary save button here if the selection isn't immediate -->
      </div>
    </div>
  </div>
</div>
<!-- Toast Notification -->
<div class="toast-container position-fixed bottom-0 end-0 p-3">
  <div id="notification-toast" class="toast" role="alert" aria-live="polite" aria-atomic="true">
    <div class="toast-header">
      <i id="toast-icon" class="ti ti-info-circle me-2"></i>
      <strong class="me-auto" id="toast-title">Notification</strong>
      <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
    </div>
    <div class="toast-body" id="toast-message">
      <!-- Message will be inserted here -->
    </div>
  </div>
</div>

<!-- End Modal -->

<script>
  // Function to show notifications
  function showNotification(message, type = 'info') {
    const toast = document.getElementById('notification-toast');
    const toastMessage = document.getElementById('toast-message');
    const toastIcon = document.getElementById('toast-icon');
    const toastTitle = document.getElementById('toast-title');

    // Set message
    toastMessage.textContent = message;

    // Set type-specific properties
    switch (type) {
      case 'success':
        toastIcon.className = 'ti ti-check-circle text-success me-2';
        toastTitle.textContent = 'Succès';
        break;
      case 'error':
        toastIcon.className = 'ti ti-alert-circle text-danger me-2';
        toastTitle.textContent = 'Erreur';
        break;
      default:
        toastIcon.className = 'ti ti-info-circle text-primary me-2';
        toastTitle.textContent = 'Information';
    }

    // Show toast
    const bsToast = new bootstrap.Toast(toast);
    bsToast.show();
  }

  // Listen for modal open event
  document.getElementById('changeHeadModal').addEventListener('show.bs.modal', function() {
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
      data: {
        action: 'get_users'
      },
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

          setUpSetHeadListeners();

        } else {
          // Show message when no candidates found
          $('#user-candidate-list').html('<div class="text-center text-muted py-3">Aucun candidat trouvé.</div>');
        }
      },
      error: function() {
        $('#user-candidate-list').html('<div class="alert alert-danger">Erreur lors du chargement des utilisateurs</div>');
      },
    });
  });

  function setUpSetHeadListeners() {

    document.querySelectorAll(".set-head-button").forEach(btn => {
      if(btn === null) return;

      $(btn).click(e => {

        $.ajax({
          url: window.location.href,
          method: "UPDATE",
          data: JSON.stringify({
            id_prof: e.target.getAttribute("data-user-id")
          }),
          success: function(response) {
            window.location.reload();
          },
          error: function() {
            showNotification("Échec de la mise à jour du chef de département", "error");
          }
        })

      })

    })

  }

  const deleteHeadBtn = document.querySelector("button.delete-head");
  if (deleteHeadBtn){
    deleteHeadBtn.addEventListener("click", e => {
    $.ajax({
      url: window.location.href,
      method: "DELETE",
      success: function(response) {
        window.location.reload();
      },
      error: function() {
        showNotification("Échec de la suppression du chef de département", "error");
      }
    })
  })
  }

</script>