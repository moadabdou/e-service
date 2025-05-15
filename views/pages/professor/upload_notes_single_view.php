<?php
function uploadSingleNoteView(array $module, ?array $info = null): string {
    ob_start();
?>

<div class="container mt-3 px-4 px-lg-5">
    <!-- Page Header with improved styling -->
    <div class="d-flex justify-content-between align-items-center flex-wrap mb-4 border-bottom pb-3">
        <h2 class="fw-bold text-primary mb-0">
            <i class="ti ti-upload me-2"></i>Uploader les notes pour : <?= htmlspecialchars($module[0]["title"]) ?>
        </h2>
    </div>

    <?php if ($info): ?>
        <div class="alert alert-<?= htmlspecialchars($info['type']) ?> alert-dismissible fade show shadow-sm border-start border-<?= htmlspecialchars($info['type']) ?> border-4 mb-4" role="alert">
            <div class="d-flex align-items-center">
                <i class="ti ti-<?= $info['type'] === 'success' ? 'circle-check' : 'alert-circle' ?> me-2 fs-5"></i>
                <div class="fw-medium"><?= htmlspecialchars($info['msg']) ?></div>
            </div>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

    <div class="row">
        <div class="col-lg-12 col-xl-12 mx-auto">
            <div class="card shadow-sm border-0 rounded-4">
                <div class="card-header bg-primary text-white py-3 px-4 rounded-top-4">
                    <div class="d-flex align-items-center">
                        <i class="ti ti-file-upload fs-5 me-2"></i>
                        <h5 class="mb-0 fw-semibold">Formulaire d'upload pour <?= htmlspecialchars($module[0]["title"]) ?></h5>
                    </div>
                </div>
                
                <div class="card-body p-4">
                    <form method="POST" enctype="multipart/form-data" id="uploadForm">
                        <div class="mb-4">
                            <label for="session_type" class="form-label fw-medium mb-2">Type de session</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light">
                                    <i class="ti ti-calendar-event"></i>
                                </span>
                                <select class="form-select form-select-lg" name="session_type" id="session_type" required>
                                    <option value="normal">Session normale</option>
                                    <option value="ratt">Rattrapage</option>
                                </select>
                            </div>
                            <div class="form-text">Précisez s'il s'agit de la session normale ou de rattrapage</div>
                        </div>

                        <div class="mb-4">
                            <label for="notes_file" class="form-label fw-medium mb-2">Fichier de notes</label>
                            <div class="file-upload-wrapper">
                                <div class="card bg-light cursor-pointer" id="dropzone">
                                    <div class="card-body text-center py-5">
                                        <div class="mb-3">
                                            <i class="ti ti-file-upload text-primary fs-1"></i>
                                        </div>
                                        <h6 class="mb-1">Glissez-déposez votre fichier ici</h6>
                                        <p class="text-muted small mb-2">ou</p>
                                        <label for="notes_file" class="btn btn-outline-primary btn-sm rounded-pill px-4">
                                            Parcourir
                                        </label>
                                        <p class="text-muted small mt-2 mb-0">Formats acceptés: PDF, Excel (.xls, .xlsx)</p>
                                        <div id="file-name-display" class="mt-2 badge bg-primary-subtle text-primary d-none"></div>
                                    </div>
                                </div>
                                <input type="file" class="form-control d-none" name="notes_file" id="notes_file" accept=".pdf,.xls,.xlsx" required>
                            </div>
                            <div class="form-text">Le fichier doit contenir toutes les notes des étudiants pour ce module</div>
                        </div>

                        <div class="alert alert-info d-flex align-items-center p-3 rounded-3 mb-4 bg-info-subtle border-0">
                            <i class="ti ti-info-circle me-2 text-info fs-4"></i>
                            <div class="small">
                                <strong>Rappel:</strong> Assurez-vous que votre fichier suit le format requis. Consultez le guide d'aide si nécessaire.
                            </div>
                        </div>

                        <div class="d-flex justify-content-center gap-3 mt-5">
                            <button type="submit" class="btn btn-primary rounded-pill px-4 py-2 shadow-sm" id="submitBtn">
                                <i class="ti ti-send me-2"></i> Soumettre les notes
                            </button>

                            <a href="/e-service/internal/members/professor/" class="btn btn-outline-secondary rounded-pill px-4 py-2 shadow-sm">
                                <i class="ti ti-arrow-left me-2"></i> Retour
                            </a>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal pour confirmer la soumission -->
<div class="modal fade" id="confirmSubmitModal" tabindex="-1" aria-labelledby="confirmSubmitModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content shadow rounded-4">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="confirmSubmitModalLabel">Confirmer l'upload</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Fermer"></button>
            </div>
            <div class="modal-body">
                <div class="text-center mb-3">
                    <i class="ti ti-file-check text-primary fs-1"></i>
                </div>
                <p>Vous êtes sur le point d'uploader les notes pour le module <strong><?= htmlspecialchars($module[0]["title"]) ?></strong>.</p>
                <p>Session: <span id="selectedSessionType" class="fw-semibold">--</span></p>
                <p>Fichier: <span id="selectedFileName" class="fw-semibold">--</span></p>
                <div class="alert alert-warning small">
                    <i class="ti ti-alert-triangle me-1"></i>
                    Une fois soumises, les notes seront immédiatement visibles par les étudiants. Veuillez vérifier que toutes les informations sont correctes.
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                <button type="button" class="btn btn-primary" id="confirmUploadBtn">
                    <i class="ti ti-upload me-1"></i> Confirmer l'upload
                </button>
            </div>
        </div>
    </div>
</div>

<style>
    /* Custom styling */
    .cursor-pointer {
        cursor: pointer;
    }
    
    .card {
        transition: all 0.3s ease;
    }
    
    /* File upload styling */
    #dropzone {
        border: 2px dashed #dee2e6;
        transition: all 0.3s ease;
    }
    
    #dropzone:hover, #dropzone.dragover {
        border-color: var(--bs-primary);
        background-color: rgba(13, 110, 253, 0.05);
    }
    
 
    .btn {
    transition: all 0.2s ease-in-out;
    }
    .btn:hover {
        transform: scale(1.03);
    }

    
    .btn-primary:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    }
    
    /* Modal animation */
    .modal.fade .modal-dialog {
        transition: transform 0.3s ease-out;
        transform: scale(0.95);
    }
    
    .modal.show .modal-dialog {
        transform: scale(1);
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // File Upload Handling
        const dropzone = document.getElementById('dropzone');
        const fileInput = document.getElementById('notes_file');
        const fileNameDisplay = document.getElementById('file-name-display');
        const uploadForm = document.getElementById('uploadForm');
        const submitBtn = document.getElementById('submitBtn');
        const sessionTypeSelect = document.getElementById('session_type');
        
        // Dropzone functionality
        ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
            dropzone.addEventListener(eventName, preventDefaults, false);
        });
        
        function preventDefaults(e) {
            e.preventDefault();
            e.stopPropagation();
        }
        
        ['dragenter', 'dragover'].forEach(eventName => {
            dropzone.addEventListener(eventName, highlight, false);
        });
        
        ['dragleave', 'drop'].forEach(eventName => {
            dropzone.addEventListener(eventName, unhighlight, false);
        });
        
        function highlight() {
            dropzone.classList.add('dragover');
        }
        
        function unhighlight() {
            dropzone.classList.remove('dragover');
        }
        
        dropzone.addEventListener('drop', handleDrop, false);
        
        function handleDrop(e) {
            const dt = e.dataTransfer;
            const files = dt.files;
            
            if (files.length > 0) {
                fileInput.files = files;
                updateFileNameDisplay(files[0].name);
            }
        }
        
        // File input change
        fileInput.addEventListener('change', function() {
            if (this.files.length > 0) {
                updateFileNameDisplay(this.files[0].name);
            } else {
                fileNameDisplay.textContent = '';
                fileNameDisplay.classList.add('d-none');
            }
        });
        
        function updateFileNameDisplay(fileName) {
            fileNameDisplay.textContent = fileName;
            fileNameDisplay.classList.remove('d-none');
        }
        
        // Click on dropzone to trigger file input
        dropzone.addEventListener('click', function() {
            fileInput.click();
        });
        
        submitBtn.addEventListener('click', function(e) {
            e.preventDefault();

            if (!uploadForm.checkValidity()) {
                uploadForm.reportValidity();
                return;
            }

            uploadForm.submit(); 
        });
        
    });
</script>
<?php
    return ob_get_clean();
}
?>