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
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    
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

<?php return ob_get_clean(); 
} 
?>