<?php
function uploadNotesView(array $assignedModules, ?array $info = null, ?array $deadline = null): string {
    ob_start();
?>

<div class="container mt-2 px-4 px-md-5">
    <!-- Page Header with improved styling -->
    <div class="d-flex justify-content-between align-items-center flex-wrap mb-4 border-bottom pb-3">
        <h2 class="fw-bold text-primary mb-0">
            <i class="ti ti-upload me-2"></i>Uploader les notes
        </h2>
        <?php if (!$deadline): ?>
            <span class="badge bg-primary-subtle text-primary px-3 py-2 rounded-pill">
                <i class="ti ti-file-upload me-1"></i> <?= count($assignedModules) ?> module(s) assigné(s)
            </span>
        <?php endif; ?>
    </div>

        <?php if ($deadline): ?>
                        <div class="alert alert-<?= htmlspecialchars($deadline['type']) ?> text-center shadow-sm rounded-4 p-4">
                            <i class="ti ti-alert-circle fs-6 d-block mb-3"></i>
                            <h5><?= htmlspecialchars($deadline['msg']) ?></h5>
                            <p class="text-muted mb-0"><?= htmlspecialchars($deadline['desc']) ?></p>
                        </div>
                <?php else : ?>
                    
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
            <div class="col-lg-12 mx-auto">
                <div class="card shadow-sm border-0 rounded-4">
                    <div class="card-header bg-primary text-white py-3 px-4 rounded-top-4">
                        <div class="d-flex align-items-center">
                            <i class="ti ti-file-upload fs-5 me-2"></i>
                            <h5 class="mb-0 fw-semibold">Formulaire d'upload des notes</h5>
                        </div>
                    </div>
                    
                    <div class="card-body p-4">
                        <form method="POST" enctype="multipart/form-data" id="uploadForm">
                            <div class="mb-4">
                                <label for="selected_module" class="form-label fw-medium mb-2">Module</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light">
                                        <i class="ti ti-book"></i>
                                    </span>
                                    <select class="form-select form-select-lg" name="selected_module" id="selected_module" required>
                                        <option value="" disabled selected>-- Choisissez un module --</option>
                                        <?php foreach ($assignedModules as $module): ?>
                                            <option value="<?= $module['id_module'] ?>">
                                                <?= htmlspecialchars($module['title']) ?> - <?= htmlspecialchars($module['filiere_name']) ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                <div class="form-text">Sélectionnez le module pour lequel vous souhaitez uploader les notes</div>
                            </div>

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
                                                <i class="ti ti-file-upload text-primary fs-6"></i>
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

                            <div class="d-grid mt-4">
                                <button type="submit" class="btn btn-primary btn-lg" id="submitBtn">
                                    <i class="ti ti-send me-2"></i>Soumettre les notes
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    <?php endif; ?>
</div>

<style>
    /* Custom styling */
    .cursor-pointer {
        cursor: pointer;
    }
    
    .card {
        transition: all 0.3s ease;
    }
    
    /* Progress indicator styling */
    .progress-bar {
        height: 4px;
        width: 0%;
        background-color: var(--bs-primary);
        transition: width 0.3s ease;
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
    
    /* Button hover effects */
    .btn {
        transition: all 0.3s ease;
    }
    
    .btn-primary:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    }
    
    /* Table styles */
    .table {
        font-size: 0.95rem;
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
        const selectedModuleSelect = document.getElementById('selected_module');
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
        
        submitBtn.addEventListener('click', function(e) {
            e.preventDefault();

            if (!uploadForm.checkValidity()) {
                uploadForm.reportValidity();
                return;
            }

            uploadForm.submit(); // directly submit the form
        });
        
        // Initialize any tooltips
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
        var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl);
        });
    });
    
    // Function to view note details
    function viewNoteDetails(noteId) {
        // Placeholder function - in real implementation, this would fetch note details via AJAX
        const noteDetails = {
            1: {
                module: "Programmation Web",
                session: "Normale",
                date: "12/05/2025",
                students: 32,
                avgGrade: 14.5,
                fileName: "notes_prog_web_2025.pdf"
            },
            2: {
                module: "Base de Données",
                session: "Rattrapage",
                date: "10/05/2025",
                students: 18,
                avgGrade: 12.8,
                fileName: "notes_bdd_ratt_2025.xlsx"
            }
        };
        
        const details = noteDetails[noteId];
        if (!details) return;
        
        const content = `
            <div class="text-center mb-4">
                <div class="rounded-circle mx-auto bg-primary-subtle text-primary d-flex align-items-center justify-content-center" style="width: 80px; height: 80px">
                    <i class="ti ti-file-text fs-1"></i>
                </div>
            </div>
            <div class="row g-3 mb-3">
                <div class="col-6">
                    <div class="border rounded p-3 h-100">
                        <div class="small text-muted">Module</div>
                        <div class="fw-bold">${details.module}</div>
                    </div>
                </div>
                <div class="col-6">
                    <div class="border rounded p-3 h-100">
                        <div class="small text-muted">Session</div>
                        <div class="fw-bold">${details.session}</div>
                    </div>
                </div>
                <div class="col-6">
                    <div class="border rounded p-3 h-100">
                        <div class="small text-muted">Date d'upload</div>
                        <div class="fw-bold">${details.date}</div>
                    </div>
                </div>
                <div class="col-6">
                    <div class="border rounded p-3 h-100">
                        <div class="small text-muted">Nombre d'étudiants</div>
                        <div class="fw-bold">${details.students}</div>
                    </div>
                </div>
            </div>
            <div class="d-flex justify-content-between align-items-center border rounded p-3">
                <div>
                    <div class="small text-muted">Fichier</div>
                    <div class="fw-bold">${details.fileName}</div>
                </div>
                <a href="#" class="btn btn-sm btn-outline-primary">
                    <i class="ti ti-download me-1"></i> Télécharger
                </a>
            </div>
        `;
        
        document.getElementById('noteDetailsContent').innerHTML = content;
        document.getElementById('modalNoteTitle').textContent = details.module;
        document.getElementById('modalFileId').value = noteId;
        
        const detailsModal = new bootstrap.Modal(document.getElementById('noteDetailsModal'));
        detailsModal.show();
    }
</script>
<?php
    return ob_get_clean();
}
?>