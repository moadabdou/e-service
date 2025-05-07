<?php

function professorHistoryView(array $notesHistory, array $moduleChoicesHistory, string $professorName): string {
    ob_start(); 
?>
<div class="container-fluid py-4 px-3 px-lg-5">
    <!-- Header Section -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card shadow-sm border-0 rounded-3">
                <div class="card-body d-flex justify-content-between align-items-center">
                    <div>
                        <h2 class="fw-bold text-primary mb-0">
                            <i class="ti ti-history me-2"></i>Historique de <?= htmlspecialchars($professorName) ?>
                        </h2>
                        <p class="text-muted mb-0">Visualisez l'historique des activités par année</p>
                    </div>
                    <div class="d-none d-md-block">
                        <span class="badge bg-primary rounded-pill fs-6">
                            <?= count(array_unique(array_merge(array_keys($notesHistory), array_keys($moduleChoicesHistory)))) ?> années
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content Section -->
    <div class="row">
        <div class="col-12">
            <?php if (empty($notesHistory) && empty($moduleChoicesHistory)) : ?>
                <div class="card shadow-sm border-0 rounded-3">
                    <div class="card-body text-center py-5">
                        <i class="ti ti-clipboard-x text-muted" style="font-size: 3rem;"></i>
                        <h4 class="mt-3 text-muted">Aucune activité enregistrée</h4>
                        <p class="text-muted">Les activités apparaîtront ici une fois qu'elles seront enregistrées.</p>
                    </div>
                </div>
            <?php else : ?>
                <div class="accordion accordion-flush" id="historySections">
                    <?php 
                    $years = array_unique(array_merge(array_keys($notesHistory), array_keys($moduleChoicesHistory)));
                    rsort($years);

                    foreach ($years as $index => $year): 
                        $isOpen = $index === 0;
                    ?>
                    <div class="accordion-item history-year shadow-sm mb-3 rounded-3 border" data-year="<?= $year ?>">
                        <h2 class="accordion-header" id="heading<?= $year ?>">
                            <button class="accordion-button <?= $isOpen ? '' : 'collapsed' ?> fw-bold" type="button" 
                                    data-bs-toggle="collapse" data-bs-target="#collapse<?= $year ?>" 
                                    aria-expanded="<?= $isOpen ? 'true' : 'false' ?>" aria-controls="collapse<?= $year ?>">
                                <i class="ti ti-calendar me-2"></i> <?= $year ?>
                                <span class="ms-2 badge bg-light text-dark border rounded-pill">
                                    <?= (!empty($moduleChoicesHistory[$year]) ? count($moduleChoicesHistory[$year]) : 0) + 
                                        (!empty($notesHistory[$year]) ? count($notesHistory[$year]) : 0) ?> activités
                                </span>
                            </button>
                        </h2>
                        <div id="collapse<?= $year ?>" class="accordion-collapse collapse <?= $isOpen ? 'show' : '' ?>" 
                             aria-labelledby="heading<?= $year ?>" data-bs-parent="#historySections">
                            <div class="accordion-body p-4">
                                <div class="row">
                                    <?php if (!empty($moduleChoicesHistory[$year])): ?>
                                    <div class="col-12 <?= !empty($notesHistory[$year]) ? 'col-xl-6 mb-4 mb-xl-0' : '' ?>">
                                        <div class="card h-100 border-0 shadow-sm category-modules">
                                            <div class="card-header bg-transparent border-bottom-0">
                                                <h5 class="text-primary mb-0">
                                                    <i class="ti ti-book me-2"></i>Modules Choisis
                                                    <span class="badge bg-primary rounded-pill ms-2"><?= count($moduleChoicesHistory[$year]) ?></span>
                                                </h5>
                                            </div>
                                            <div class="card-body pt-0">
                                                <ul class="list-group list-group-flush">
                                                    <?php foreach ($moduleChoicesHistory[$year] as $choice): ?>
                                                        <li class="list-group-item d-flex justify-content-between align-items-center px-0 py-3 border-bottom">
                                                            <div>
                                                                <h6 class="mb-1"><?= htmlspecialchars($choice['title']) ?></h6>
                                                                <small class="text-muted">
                                                                    <i class="ti ti-calendar-event me-1"></i><?= date('d/m/Y', strtotime($choice['date'] ?? 'now')) ?>
                                                                </small>
                                                            </div>
                                                            <span class="badge rounded-pill bg-<?= $choice['status'] === 'validated' ? 'success' : 
                                                                ($choice['status'] === 'declined' ? 'danger' : 'warning') ?> px-3 py-2">
                                                                <?= $choice['status'] === 'validated' ? 'Validé' : 
                                                                    ($choice['status'] === 'declined' ? 'Refusé' : 'En attente') ?>
                                                            </span>
                                                        </li>
                                                    <?php endforeach; ?>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                    <?php endif; ?>

                                    <?php if (!empty($notesHistory[$year])): ?>
                                    <div class="col-12 <?= !empty($moduleChoicesHistory[$year]) ? 'col-xl-6' : '' ?>">
                                        <div class="card h-100 border-0 shadow-sm category-notes">
                                            <div class="card-header bg-transparent border-bottom-0">
                                                <h5 class="text-primary mb-0">
                                                    <i class="ti ti-file-text me-2"></i>Notes Envoyées
                                                    <span class="badge bg-primary rounded-pill ms-2"><?= count($notesHistory[$year]) ?></span>
                                                </h5>
                                            </div>
                                            <div class="card-body pt-0">
                                                <ul class="list-group list-group-flush">
                                                    <?php foreach ($notesHistory[$year] as $note): ?>
                                                        <li class="list-group-item px-0 py-3 border-bottom">
                                                            <div class="d-flex justify-content-between align-items-center">
                                                                <h6 class="mb-1"><?= htmlspecialchars($note['title']) ?></h6>
                                                                <span class="badge bg-info text-white rounded-pill"><?= ucfirst($note['session']) ?></span>
                                                            </div>
                                                            <div class="d-flex align-items-center text-muted small">
                                                                <i class="ti ti-calendar me-1"></i>
                                                                <span><?= date('d/m/Y', strtotime($note['date_upload'])) ?></span>
                                                                <?php if (!empty($note['file_path'])): ?>
                                                                <a href="<?= htmlspecialchars($note['file_path']) ?>" class="ms-3 text-decoration-none">
                                                                    <i class="ti ti-download me-1"></i>Télécharger
                                                                </a>
                                                                <?php endif; ?>
                                                            </div>
                                                        </li>
                                                    <?php endforeach; ?>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <!-- Export/Print Options -->
    <div class="row mt-4">
        <div class="col-12">
            <div class="card shadow-sm border-0 rounded-3">
                <div class="card-body d-flex justify-content-end">
                    <button class="btn btn-outline-primary me-2" id="btnExportPDF">
                        <i class="ti ti-file-export me-1"></i>Exporter (PDF)
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Add JS for functionality -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Handle export PDF functionality
    document.getElementById('btnExportPDF').addEventListener('click', function() {
        // Get professor name for the filename
        const professorName = document.querySelector('h2.fw-bold.text-primary').textContent
            .replace('Historique de ', '')
            .trim()
            .replace(/\s+/g, '_');
        
        // Create a filename with professor name and date
        const now = new Date();
        const dateStr = now.toISOString().slice(0,10);
        const filename = `historique_${professorName}_${dateStr}.pdf`;
        
        // Show loading overlay
        const loadingOverlay = document.createElement('div');
        loadingOverlay.className = 'position-fixed top-0 start-0 w-100 h-100 d-flex justify-content-center align-items-center bg-white bg-opacity-75';
        loadingOverlay.style.zIndex = '9999';
        loadingOverlay.innerHTML = `
            <div class="text-center">
                <div class="spinner-border text-primary mb-3" role="status">
                    <span class="visually-hidden">Génération du PDF...</span>
                </div>
                <h5>Génération du PDF en cours...</h5>
            </div>
        `;
        document.body.appendChild(loadingOverlay);
        
        const contentToExport = document.querySelector('.container-fluid');
        
        const contentClone = contentToExport.cloneNode(true);
        
        contentClone.querySelectorAll('#btnExportPDF').forEach(el => {
            if (el && el.parentNode) {
                el.parentNode.removeChild(el);
            }
        });
        
        contentClone.querySelectorAll('.accordion-collapse').forEach(section => {
            section.classList.add('show');
        });
        
        html2pdf().from(contentClone).set({
            margin: 10,
            filename: filename,
            image: { type: 'jpeg', quality: 0.98 },
            html2canvas: { scale: 2, useCORS: true },
            jsPDF: { unit: 'mm', format: 'a4', orientation: 'portrait' }
        }).save()
        .then(() => {
            document.body.removeChild(loadingOverlay);
            
            const successNotification = document.createElement('div');
                successNotification.className = 'position-fixed bottom-0 start-50 translate-middle-x mb-4 p-3 alert alert-success alert-dismissible fade show';
                successNotification.style.zIndex = '1050';
                successNotification.innerHTML = `
                    <i class="ti ti-check me-2"></i>  PDF généré avec succès.
                    <button type="button"></button>
                `;
                document.body.appendChild(successNotification);

            
            setTimeout(() => {
                successNotification.classList.remove('show');
                setTimeout(() => {
                    if (successNotification.parentNode) {
                        successNotification.parentNode.removeChild(successNotification);
                    }
                }, 300);
            }, 5000);
        })
        .catch(error => {
            document.body.removeChild(loadingOverlay);
            console.error('Error generating PDF:', error);
            
            const errorNotification = document.createElement('div');
                errorNotification.className = 'position-fixed bottom-0 start-50 translate-middle-x mb-4 p-3 alert alert-danger alert-dismissible fade show';
                errorNotification.style.zIndex = '1050';
                errorNotification.innerHTML = `
                    <i class="ti ti-circle-alert me-2"></i>Erreur lors de la génération du PDF. Veuillez réessayer.
                    <button type="button" ></button>
                `;
                document.body.appendChild(errorNotification);

            
            setTimeout(() => {
                errorNotification.classList.remove('show');
                setTimeout(() => {
                    if (errorNotification.parentNode) {
                        errorNotification.parentNode.removeChild(errorNotification);
                    }
                }, 300);
            }, 5000);
        });
    });
    
    
    const filterButtons = document.querySelectorAll('.filter-option');
    filterButtons.forEach(button => {
        button.addEventListener('click', function() {
            const filterValue = this.dataset.filter;
            
            if (filterValue === 'all') {
                document.querySelectorAll('.category-modules, .category-notes').forEach(el => {
                    el.closest('.history-year').style.display = 'block';
                });
            } else {
                document.querySelectorAll('.history-year').forEach(year => {
                    const hasCategory = year.querySelector(`.category-${filterValue}`);
                    year.style.display = hasCategory ? 'block' : 'none';
                });
            }
        });
    });
});
</script>
<!-- Include html2pdf.js library -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js"></script>

<?php return ob_get_clean(); 
} 
?>