<?php
function manageDeadlinesView(array $deadlines, ?string $success, ?string $error, ?array $editing = null): string {
    ob_start();
    
    // Get current status counts
    $openCount = array_reduce($deadlines, function($carry, $item) {
        return $carry + ($item['status'] === 'open' ? 1 : 0);
    }, 0);
    
    $closedCount = count($deadlines) - $openCount;
    
    // Format feature names for better display
    $featureNames = [
        'choose_modules' => 'Choix des modules',
        'upload_notes' => 'Upload des notes'
    ];
?>
<div class="container mt-4 px-4 px-md-5">

    <!-- Page Header with improved styling -->
    <div class="d-flex justify-content-between align-items-center flex-wrap mb-4 border-bottom pb-3">
        <h2 class="fw-bold text-primary mb-0">
            <i class="ti ti-calendar-time me-2"></i>Gestion des deadlines
        </h2>
        <form method="POST" class="d-flex gap-2 align-items-center">
            <button type="submit" name="create_announce" class="btn btn-primary d-flex align-items-center gap-2 shadow-sm">
                <i class="ti ti-speakerphone"></i> Autou Créer annonce
            </button>

            <div class="badge bg-success-subtle text-success fs-5 px-3 py-2 rounded-pill">
                <i class="ti ti-circle-check me-1"></i> <?= $openCount ?> active(s)
            </div>
            <div class="badge bg-danger-subtle text-danger fs-5 px-3 py-2 rounded-pill">
                <i class="ti ti-circle-x me-1"></i> <?= $closedCount ?> terminée(s)
            </div>
        </form>
    </div>

    <?php if ($success || $error): ?>
        <div class="alert alert-<?= $success ? 'success' : 'danger' ?> alert-dismissible fade show shadow-sm border-start border-<?= $success ? 'success' : 'danger' ?> border-4 mb-4" role="alert">
            <div class="d-flex align-items-center">
                <i class="ti ti-<?= $success ? 'circle-check' : 'alert-circle' ?> me-2 fs-5"></i>
                <div><?= $success ?: $error ?></div>
            </div>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

    <div class="row ">
        <!-- Add Deadline Form Section -->
        <div class="col-12 col-lg-12 order-lg-12">
            <div class="card shadow-sm border-0 rounded-4 h-80">
                <div class="card-header bg-primary text-white py-3 px-4 rounded-top-4">
                    <div class="d-flex align-items-center">
                        <i class="ti ti-plus-circle fs-5 me-2"></i>
                        <h5 class="mb-0 fw-semibold">Nouvelle deadline</h5>
                    </div>
                </div>
                <div class="card-body p-4">
                <form method="POST" id="deadlineForm">
                    <?php if ($editing): ?>
                        <input type="hidden" name="update_deadline_id" value="<?= $editing['id'] ?>">
                    <?php endif; ?>

                    <!-- Feature -->
                    <div class="mb-2">
                        <label for="feature" class="form-label fw-medium">Fonctionnalité</label>
                        <select name="feature" id="feature" class="form-select form-select-lg" required>
                            <option value="">-- Choisir une fonctionnalité --</option>
                            <?php foreach ($featureNames as $value => $label): ?>
                                <option value="<?= $value ?>" <?= $editing && $editing['feature'] === $value ? 'selected' : '' ?>>
                                    <?= $label ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <!-- Start Date -->
                    <div class="mb-2">
                        <label class="form-label fw-medium">Date de début</label>
                        <input type="datetime-local" name="start_date" id="start_date" class="form-control form-control-lg"
                            value="<?= $editing ? date('Y-m-d\TH:i', strtotime($editing['start_date'])) : '' ?>" required>
                    </div>

                    <!-- End Date -->
                    <div class="mb-4">
                        <label class="form-label fw-medium">Date de fin</label>
                        <input type="datetime-local" name="end_date" id="end_date" class="form-control form-control-lg"
                            value="<?= $editing ? date('Y-m-d\TH:i', strtotime($editing['end_date'])) : '' ?>" required>
                    </div>

                    <!-- Buttons -->
                    <div class="d-flex gap-2 justify-content-center">
                        <button type="submit" class="btn btn-<?= $editing ? 'warning' : 'primary' ?> btn-lg" id="submitBtn" disabled>
                            <i class="ti ti-<?= $editing ? 'edit' : 'device-floppy' ?> me-1"></i>
                            <?= $editing ? 'Mettre à jour' : 'Enregistrer la deadline' ?>
                        </button>

                        <?php if ($editing): ?>
                            <a href="manage_deadlines.php" class="btn btn-outline-secondary btn-lg">
                                <i class="ti ti-arrow-back-up"></i> Annuler
                            </a>
                        <?php endif; ?>
                    </div>

                </form>
                </div>
            </div>
        </div>
        
        <!-- Existing Deadlines Section -->
        <div class="col-12 col-lg-12 order-lg-12">
            <div class="card shadow-sm border-0 rounded-4">
                <div class="card-header bg-white py-3 px-4 border-0">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="mb-0 fw-semibold">
                            <i class="ti ti-list me-2"></i>Deadlines existantes
                        </h5>
                        <span class="badge bg-primary-subtle text-primary px-3 py-2 rounded-pill">
                            Total: <?= count($deadlines) ?>
                        </span>
                    </div>
                </div>
                <div class="card-body p-0 mb-4">
                    <div class="table-responsive ">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="bg-light">
                                <tr>
                                    <th class="ps-4 py-3">Fonctionnalité</th>
                                    <th class="py-3">Période</th>
                                    <th class="py-3">Status</th>
                                    <th class="pe-4 py-3 text-center">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (empty($deadlines)): ?>
                                    <tr>
                                        <td colspan="4" class="text-center py-4 text-muted">
                                            <i class="ti ti-calendar-off fs-6 d-block mb-2"></i>
                                            Aucune deadline définie
                                        </td>
                                    </tr>
                                <?php else: ?>
                                    <?php foreach ($deadlines as $d): 
                                        $isActive = $d['status'] === 'open';
                                        $now = time();
                                        $startTime = strtotime($d['start_date']);
                                        $endTime = strtotime($d['end_date']);
                                        $expired = $now > $endTime;

                                        
                                        // Determine time status
                                        $timeStatus = '';

                                        if ($now < $startTime) {
                                            $timeStatus = 'À venir';
                                            $timeStatusClass = 'bg-info-subtle text-info';
                                        } elseif ($now >= $startTime && $now <= $endTime) {
                                            $timeStatus = 'En cours';
                                            $timeStatusClass = 'bg-success-subtle text-success';
                                        } else {
                                            $timeStatus = 'Terminé';
                                            $timeStatusClass = 'bg-secondary-subtle text-secondary';
                                        }
                                    ?>
                                        <tr class="border-bottom">
                                            <td class="ps-4">
                                                <div class="d-flex align-items-center">
                                                    <div class="feature-icon rounded-circle bg-<?= $d['feature'] === 'choose_modules' ? 'primary' : 'warning' ?>-subtle text-<?= $d['feature'] === 'choose_modules' ? 'primary' : 'warning' ?> p-2 me-3">
                                                        <i class="ti ti-<?= $d['feature'] === 'choose_modules' ? 'stack-2' : 'upload' ?>"></i>
                                                    </div>
                                                    <div>
                                                        <h6 class="mb-0 fw-medium"><?= $featureNames[$d['feature']] ?? $d['feature'] ?></h6>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="small">
                                                    <div class="d-flex align-items-center mb-1">
                                                        <i class="ti ti-calendar-plus text-success me-2"></i>
                                                        <?= date('d/m/Y H:i', strtotime($d['start_date'])) ?>
                                                    </div>
                                                    <div class="d-flex align-items-center">
                                                        <i class="ti ti-calendar-off text-danger me-2"></i>
                                                        <?= date('d/m/Y H:i', strtotime($d['end_date'])) ?>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="d-flex flex-column gap-1">
                                                <?php if (!$expired): ?>
                                                <span class="badge <?= $isActive ? 'bg-success-subtle text-success' : 'bg-danger-subtle text-danger' ?> px-3 py-2 rounded-pill">
                                                    <i class="ti ti-<?= $isActive ? 'circle-check' : 'circle-x' ?> me-1"></i>
                                                    <?= $isActive ? 'Actif' : 'Inactif' ?>
                                                </span>
                                                <?php endif; ?>

                                                    <span class="badge <?= $timeStatusClass ?> px-3 py-2 rounded-pill">
                                                        <?= $timeStatus ?>
                                                        <?php if ($isActive && $now >= $startTime && $now <= $endTime): ?>
                                                            <br>
                                                            <?php
                                                                $end = new DateTime($d['end_date']);
                                                                $now = new DateTime();
                                                                $remaining = $now->diff($end);

                                                                $parts = [];

                                                                // Days
                                                                if ($remaining->d > 0 || $remaining->m > 0 || $remaining->y > 0) {
                                                                    $parts[] = $remaining->format('%a j');
                                                                }

                                                                // Hours
                                                                if ($remaining->h > 0) {
                                                                    $parts[] = $remaining->format('%h h');
                                                                }

                                                                // Minutes
                                                                if ($remaining->i > 0) {
                                                                    $parts[] = $remaining->format('%i m');
                                                                }

                                                                if (!empty($parts)) {
                                                                    echo implode(', ', $parts) . ' restantes';
                                                                } else {
                                                                    echo 'Temps écoulé';
                                                                }
                                                                ?>

                                                        <?php endif; ?>
                                                    </span>

                                                </div>
                                            </td>
                                            <td class="pe-4 text-center">
                                                <div class="dropdown">
                                                    <button class="btn btn-sm btn-light rounded-circle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                                        <i class="ti ti-dots-vertical"></i>
                                                    </button>
                                                    <ul class="dropdown-menu dropdown-menu-end shadow-sm">
                                                        <li>
                                                            <a class="dropdown-item d-flex align-items-center" href="?action=edit&id=<?= $d['id'] ?>">
                                                                <i class="ti ti-edit me-2 text-primary"></i> Modifier
                                                            </a>
                                                        </li>
                                                        <li>
                                                            <a class="dropdown-item d-flex align-items-center <?= $expired ? 'text-muted disabled' : '' ?>" 
                                                            href="<?= $expired ? '#' : "?action=toggle&id=" . $d['id'] ?>" 
                                                            <?= $expired ? 'aria-disabled="true" tabindex="-1"' : '' ?>>
                                                                <i class="ti ti-toggle-<?= $isActive ? 'left' : 'right' ?> me-2 text-warning"></i> 
                                                                <?= $isActive ? 'Désactiver' : 'Activer' ?>
                                                            </a>
                                                        </li>

                                                        <li>
                                                        <a href="#" class="dropdown-item d-flex align-items-center text-danger" onclick="event.preventDefault(); confirmDelete(<?= $d['id'] ?>, '<?= htmlspecialchars($featureNames[$d['feature']] ?? $d['feature'], ENT_QUOTES) ?>')">
                                                            <i class="ti ti-trash me-2"></i> Supprimer
                                                        </a>

                                                        </li>
                                                    </ul>

                                                </div>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal de confirmation pour la suppression -->
<div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <form method="POST" id="deleteForm" class="modal-content shadow rounded-4">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title" id="deleteModalLabel">Confirmer la suppression</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Fermer"></button>
            </div>
            <div class="modal-body">
                <p>Voulez-vous vraiment supprimer la deadline pour <strong id="modalDeadlineTitle"></strong> ?</p>
                <input type="hidden" name="delete_deadline_id" id="modalDeadlineId">
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                <button type="submit" class="btn btn-danger">
                    <i class="ti ti-trash me-1"></i> Supprimer
                </button>
            </div>
        </form>
    </div>
</div>

<style>
    /* Card hover effects */
    .card {
        transition: all 0.3s ease;
    }
    
    /* Table styles */
    .table {
        font-size: 0.95rem;
    }
    
    .table > :not(caption) > * > * {
        padding: 1rem 0.75rem;
    }
    
    /* Feature icon styles */
    .feature-icon {
        display: flex;
        align-items: center;
        justify-content: center;
        width: 2.5rem;
        height: 2.5rem;
    }
    
    /* Form control focus state */
    .form-control:focus, .form-select:focus {
        border-color: #86b7fe;
        box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, 0.15);
    }
    
    /* Button hover effects */
    .btn {
        transition: all 0.3s ease;
    }
    
    .btn-primary:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    }
    .card,
    .table-responsive {
    overflow: visible !important;
    position: relative;
    }

    .dropdown-menu {
    z-index: 1050; /* Bootstrap default for dropdowns */
    }

</style>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Date validation
        const startDate = document.getElementById('start_date');
        const endDate = document.getElementById('end_date');
        
        if (startDate && endDate) {
            startDate.addEventListener('change', function() {
                endDate.min = this.value;
            });
            
            endDate.addEventListener('change', function() {
                if (startDate.value && this.value < startDate.value) {
                    this.value = startDate.value;
                }
            });
        }
    });

    document.addEventListener("DOMContentLoaded", function () {
        const form = document.getElementById('deadlineForm');
        const submitBtn = document.getElementById('submitBtn');

        if (!form || !submitBtn) return;

        // Store initial values
        const initialValues = {
            feature: form.feature?.value,
            start_date: form.start_date?.value,
            end_date: form.end_date?.value
        };

        // Listen for changes
        form.addEventListener("input", function () {
            const changed = (
                form.feature?.value !== initialValues.feature ||
                form.start_date?.value !== initialValues.start_date ||
                form.end_date?.value !== initialValues.end_date
            );
            submitBtn.disabled = !changed;
        });
    });

    // Function to handle delete confirmation
    function confirmDelete(id, featureName) {
    // Set the deadline ID and feature name in the modal
    document.getElementById('modalDeadlineId').value = id;
    document.getElementById('modalDeadlineTitle').textContent = featureName;
    
    // Show the modal
    var deleteModal = new bootstrap.Modal(document.getElementById('deleteModal'));
    deleteModal.show();
    }

</script>
<?php
    return ob_get_clean();
}
?>
