
<style>
    .modal-coorer{
        display: flex;
        justify-content: space-between;
        padding: 10px 20px;
    }
</style>
<!-- Start Department View Content -->
<div class="card-body p-4 p-md-5">
    <!-- Add Edit Button -->
    <div class="d-flex justify-content-end mb-4">
        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#editFiliereModal">
            <i class="ti ti-edit me-2"></i>Modifier la filière
        </button>
    </div>

    <!-- Edit Modal -->
    <div class="modal fade" id="editFiliereModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" style="max-width: 700px;">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Modifier la filière</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="editFiliereForm">
                        <div class="mb-3">
                            <label class="form-label">Titre</label>
                            <input type="text" class="form-control" id="editTitle" value="<?= htmlspecialchars($filiere_data['title']) ?>">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Description</label>
                            <textarea class="form-control" id="editDescription" rows="3"><?= htmlspecialchars($filiere_data['description']) ?></textarea>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                    <button type="button" class="btn btn-primary" id="saveFiliere">Enregistrer</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Department Title -->
    <div class="mb-5">
      <h3 class="card-title mb-2">
        <i class="ti ti-building-skyscraper text-primary me-2"></i>
        <!-- Placeholder: Replace with dynamic department title -->
        <?= htmlspecialchars(  $filiere_data["title"]  ) ?>
      </h3>
    </div>

    <!-- Department Description -->
    <div class="mb-5">
      <h6 class="text-muted fw-semibold mb-3">Description</h6>
      <!-- Placeholder: Replace with dynamic department description -->
      <p class="card-text lead">
            <?= htmlspecialchars(  $filiere_data["description"]  ) ?>
      </p>
    </div>

    <hr class="my-5" />

    <!-- coor of Department Section -->
    <div class="mb-4">
    <h4 class="mb-4">Coordinateur de Filière</h4>

    <?php if (is_array($coor_data) && count($coor_data) > 0): ?>
      <!-- Case 1: coor of Department Assigned -->
      <div class="d-flex align-items-center mb-3 p-3 bg-light rounded border" id="current-coor-info">
        <img
        src="<?= $coor_data['img'] ?>" alt="Avatar Chef"
        class="rounded-circle me-4" width="60" height="60"
        id="coor-avatar"
         />
        <div class="flex-grow-1">
        <h6 class="mb-1" id="coor-name"> Dr.<?= htmlspecialchars($coor_data['firstName']." ".$coor_data['lastName']) ?></h6>
        <small class="text-muted d-block" id="coor-email"><?= htmlspecialchars($coor_data['email']) ?></small>
        </div>
        <div class="ms-3 d-flex flex-column flex-sm-row gap-3">
        <a href="/e-service/internal/members/common/view_profile.php?id=<?= $coor_data['id_user'] ?>" class="btn btn-outline-secondary">
          <i class="ti ti-user me-1"></i> Voir Profil
        </a>
        <button
          type="button"
          class="btn btn-outline-primary"
          data-bs-toggle="modal"
          data-bs-target="#changeCoorModal"
         >
           <i class="ti ti-edit me-1"></i> Modifier
        </button>
        <button
            type="button"
            class="btn btn-outline-danger delete-coor">
            <i class="ti ti-trash me-1"></i> Supprimer
        </button>
        </div>
      </div>
    <?php else: ?>
      <!-- Case 2: No Coordinator Assigned -->
      <div class="alert alert-warning d-flex justify-content-between align-items-center p-3" role="alert" id="no-coor-info">
         <span class="me-3">Aucun coordinateur de filière n'est actuellement assigné.</span>
         <button type="button" class="btn btn-primary text-nowrap" data-bs-toggle="modal" data-bs-target="#changeCoorModal">
           <i class="ti ti-user-plus me-1"></i> Assigner un Coordinateur
         </button>
      </div>
    <?php endif; ?>
    </div>
  </div> <!-- End card-body -->
</div> <!-- End card -->

<!-- ###################################################### -->
<!-- ##      Modal for Changing coor of Department       ## -->
<!-- ###################################################### -->
<div class="modal fade" id="changeCoorModal" tabindex="-1" aria-labelledby="changeCoorModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-coorer">
                <h5 class="modal-title" id="changeCoorModalLabel">Changer le Coordinateur de Filière</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4">
                <p class="text-muted mb-4">Sélectionnez le nouvel utilisateur qui sera désigné comme coordinateur de cette filière.</p>

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
        <div class="toast-coorer">
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
  switch(type) {
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
    document.getElementById('changeCoorModal').addEventListener('show.bs.modal', function () {
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
                                <button type="button" class="btn btn-primary set-coor-button" data-user-id="${user.id_user}">
                                    <i class="ti ti-check me-1"></i> Définir comme Coordinateur
                                </button>
                            </div>`;
                        $('#user-candidate-list').append(userHtml);
                    });

                    setUpSetcoorListeners();

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

    function setUpSetcoorListeners() {
        document.querySelectorAll(".set-coor-button").forEach(btn => {
            if(!btn) return; 
            $(btn).click(e => {
                $.ajax({
                    url: window.location.href,
                    method: 'UPDATE',
                    data: JSON.stringify({ 
                        id_prof: e.target.getAttribute("data-user-id") 
                    }),
                    success: function(response) {
                        window.location.reload();
                    },
                    error: function() {
                        showNotification("Échec de la mise à jour du coordinateur de filière", "error");
                    }
                });
            });
        });
    }
  
    const deletecoorBtn = document.querySelector("button.delete-coor");
    if (deletecoorBtn){
        deletecoorBtn.addEventListener("click", e => {
        $.ajax({
        url: window.location.href,
        method: "DELETE",
        success: function(response) {
            window.location.reload();
        },
        error: function() {
            showNotification("Échec de la suppression du coordinateur de filière", "error");
        }
        })
    })
  }

  document.getElementById('saveFiliere').addEventListener('click',async function() {
        const data = {
            title: document.getElementById('editTitle').value,
            description: document.getElementById('editDescription').value
        };

        const response = await fetch(window.location.href, {
            method: 'UPDATE',
            headers: {
              'Content-Type': 'application/json'
            },
            body: JSON.stringify(data)
          });
          
        
        if (response.ok) {
            showNotification("Filière mise à jour avec succès", "success");
            window.location.reload();
        } else {    
            const errorData = await response.json();
            showNotification("Échec de la mise à jour de la filière", "error");
        }

    });

</script>