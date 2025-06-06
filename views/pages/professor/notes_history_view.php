<?php
require_once $_SERVER['DOCUMENT_ROOT'] . "/e-service/views/components/search_filter_component.php";

function notesHistoryView(array $filliers, array $notes, ?string $success = null, ?string $error = null): string {
    ob_start();
    $modules = array_unique(array_map(fn($n) => $n['module_title'], $notes));
    $sessions = array_unique(array_map(fn($n) => $n['session'], $notes));
    
    // Statistiques pour le tableau de bord
    $totalNotes = count($notes);
    $notesParModule = array_count_values(array_map(fn($n) => $n['module_title'], $notes));
    $notesParFiliere = array_count_values(array_map(fn($n) => $n['filiere_name'], $notes));
    $notesParSession = array_count_values(array_map(fn($n) => $n['session'], $notes));
    
    // Date la plus récente
    $datesDenvoi = array_map(fn($n) => strtotime($n['date_upload']), $notes);
    $dateDerniereNote = !empty($datesDenvoi) ? max($datesDenvoi) : null;
?>
            <div class="container mt-2 px-3 px-md-5">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h2 class="fw-bold text-primary">
                        <i class="ti ti-archive"></i> Historique des notes envoyées
                    </h2>
                </div>
                <?php if ($success): ?>
                <div class="alert alert-success alert-dismissible fade show text-center" role="alert">
                    <?= htmlspecialchars($success) ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            <?php elseif ($error): ?>
                <div class="alert alert-danger alert-dismissible fade show text-center" role="alert">
                    <?= htmlspecialchars($error) ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            <?php endif; ?>
    <?php if (!empty($notes)) : ?>
        <!-- Dashboard Cards -->
    <div class="row g-3 mb-4">
        <div class="col-md-3">
            <div class="card border-0 shadow-sm rounded-4 bg-primary-subtle h-40">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <h6 class="text-primary fw-semibold">Total des notes</h6>
                            <h3 class="fw-bold mb-0"><?= $totalNotes ?></h3>
                        </div>
                        <div class="bg-primary text-white rounded-circle p-2 d-flex align-items-center justify-content-center" style="width: 48px; height: 48px">
                            <i class="ti ti-file-text fs-4"></i>
                        </div>
                    </div>

                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm rounded-4 bg-success-subtle h-40">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <h6 class="text-success fw-semibold">Modules couverts</h6>
                            <h3 class="fw-bold mb-0"><?= count($modules) ?></h3>
                        </div>
                        <div class="bg-success text-white rounded-circle p-2 d-flex align-items-center justify-content-center" style="width: 48px; height: 48px">
                            <i class="ti ti-book fs-4"></i>
                        </div>
                    </div>
                    <?php if (!empty($notesParModule)): 
                        arsort($notesParModule);
                        $topModule = array_key_first($notesParModule);
                    ?>
                    <?php endif; ?>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm rounded-4 bg-warning-subtle h-40">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <h6 class="text-warning fw-semibold">Filières</h6>
                            <h3 class="fw-bold mb-0"><?= count($notesParFiliere) ?></h3>
                        </div>
                        <div class="bg-warning text-white rounded-circle p-2 d-flex align-items-center justify-content-center" style="width: 48px; height: 48px">
                            <i class="ti ti-bookmark fs-4"></i>
                        </div>
                    </div>
                    <?php if (!empty($notesParFiliere)): 
                        arsort($notesParFiliere);
                        $topFiliere = array_key_first($notesParFiliere);
                    ?>
                    <?php endif; ?>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm rounded-4 bg-info-subtle h-40">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <h6 class="text-info fw-semibold">Sessions</h6>
                            <h3 class="fw-bold mb-0"><?= count($sessions) ?></h3>
                        </div>
                        <div class="bg-info text-white rounded-circle p-2 d-flex align-items-center justify-content-center" style="width: 48px; height: 48px">
                            <i class="ti ti-calendar-time fs-4"></i>
                        </div>
                    </div>
                    <?php if (!empty($notesParSession)): 
                        arsort($notesParSession);
                        $topSession = array_key_first($notesParSession);
                    ?>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
    <?= createSearchFilterComponent(
        "Rechercher un module...",
        [
            "module" => [
                "label" => "Module",
                "icon" => "ti-book",
                "allLabel" => "Tous les modules",
                "options" => array_map(fn($m) => [
                    "value" => strtolower(str_replace(' ', '_', $m)),
                    "label" => $m
                ], $modules)
            ],
            "filiere" => [
                "label" => "Filière",
                "icon" => "ti-bookmark",
                "allLabel" => "Toutes les filières",
                "options" => array_map(fn($f) => [
                    "value" => strtolower(str_replace(' ', '_', $f['filiere_name'])),
                    "label" => $f['filiere_name']
                ], $filliers)
            ],
            "session" => [
                "label" => "Session",
                "icon" => "ti-calendar-time",
                "allLabel" => "Toutes les sessions",
                "options" => array_map(fn($s) => [
                    "value" => strtolower($s),
                    "label" => ucfirst($s)
                ], $sessions)
            ]
        ],
        "listContainer",
        "filterable-item",
        "itemCount"
    ); 
    endif;?>

    <?php if (empty($notes)) : ?>
        <div class="alert alert-info text-center shadow-sm">
            <i class="ti ti-info-circle"></i> Aucune note envoyée pour le moment.
        </div>
    <?php else : ?>
        <div class="card shadow rounded-4 border-0">
            <div class="card-body p-0">
                <div class="table-responsive rounded-4">
                    <table class="table table-hover align-middle mb-0" id="listContainer">
                        <thead class="table-light">
                            <tr class="text-nowrap">
                                <th>Module</th>
                                <th>Filière</th>
                                <th>Session</th>
                                <th>Date d'envoi</th>
                                <th>Fichier</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($notes as $note) :
                                $moduleValue = strtolower(str_replace(' ', '_', $note['module_title']));
                                $filiereValue = strtolower(str_replace(' ', '_', $note['filiere_name']));
                                $sessionValue = strtolower($note['session']);
                            ?>
                                <tr class="filterable-item"
                                    data-module="<?= $moduleValue ?>"
                                    data-filiere="<?= $filiereValue ?>"
                                    data-session="<?= $sessionValue ?>">
                                    <td class="fw-semibold text-primary"><?= htmlspecialchars($note['module_title']) ?></td>
                                    <td><?= htmlspecialchars($note['filiere_name']) ?></td>
                                    <td><span class="badge bg-secondary text-white"><?= ucfirst($note['session']) ?></span></td>
                                    <td><?= date('d/m/Y', strtotime($note['date_upload'])) ?></td>
                                    <td>
                                        <a href="/e-service/internal/members/common/getResource.php?type=pdfs&path=notes/<?=$note['file_id']?>"
                                           target="_blank"
                                           class="btn btn-sm btn-outline-primary d-flex align-items-center gap-1">
                                            <i class="ti ti-eye"></i> Voir
                                        </a>
                                    </td>
                                    <td>
                                        <button type="button" class="btn btn-sm btn-outline-warning edit-btn"
                                            data-bs-toggle="modal" data-bs-target="#editModal"
                                            data-note-id="<?= htmlspecialchars($note['file_id']) ?>"
                                            data-note-title="<?= htmlspecialchars($note['module_title']) ?>"
                                            data-note-session="<?= htmlspecialchars($note['session']) ?>">
                                            <i class="ti ti-edit"></i> Modifier
                                        </button>
                                        <button type="button" class="btn btn-sm btn-outline-danger delete-btn"
                                            data-bs-toggle="modal" data-bs-target="#deleteModal"
                                            data-note-id="<?= htmlspecialchars($note['file_id']) ?>"
                                            data-note-title="<?= htmlspecialchars($note['module_title']) ?>">
                                            <i class="ti ti-trash"></i> Supprimer
                                        </button>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    <?php endif; ?>
</div>

<!-- Modal de confirmation pour la suppression -->
<div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <form method="POST" action="/e-service/internal/members/professor/delete_note.php" class="modal-content shadow rounded-4">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title" id="deleteModalLabel">Confirmer la suppression</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Fermer"></button>
            </div>
            <div class="modal-body">
                <p>Voulez-vous vraiment supprimer la note du module <strong id="modalNoteTitle"></strong> ?</p>
                <input type="hidden" name="file_id" id="modalFileId">
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                <button type="submit" class="btn btn-danger">Supprimer</button>
            </div>
        </form>
    </div>
</div>
        <script>
        document.addEventListener("DOMContentLoaded", function () {
            const deleteButtons = document.querySelectorAll('.delete-btn');
            const modalNoteTitle = document.getElementById('modalNoteTitle');
            const modalFileId = document.getElementById('modalFileId');

            deleteButtons.forEach(btn => {
                btn.addEventListener('click', () => {
                    const noteId = btn.getAttribute('data-note-id');
                    const noteTitle = btn.getAttribute('data-note-title');

                    modalFileId.value = noteId;
                    modalNoteTitle.textContent = noteTitle;
                });
            });
        });
        </script>

<!-- Modal de modification pour la modification des fichiers -->
<div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <form method="POST" action="/e-service/internal/members/professor/update_note.php" enctype="multipart/form-data" class="modal-content shadow rounded-4">
            <div class="modal-header bg-warning text-white">
                <h5 class="modal-title" id="editModalLabel">Modifier la note du module <strong id="editModalTitle"></strong></h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Fermer"></button>
            </div>
            <div class="modal-body">
                <label for="new_file" class="form-label">Choisir un nouveau fichier (PDF ou Excel) :</label>
                <input type="file" name="new_file" id="newFile" class="form-control" required>
                <input type="hidden" name="file_id" id="editFileId">
                <input type="hidden" name="note_id" id="noteId">
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                <button type="submit" class="btn btn-warning">Modifier</button>
            </div>
        </form>
    </div>
</div>
<script>
document.addEventListener("DOMContentLoaded", function () {
    const editButtons = document.querySelectorAll('.edit-btn');
    const editModalTitle = document.getElementById('editModalTitle');
    const editFileId = document.getElementById('editFileId');
    const noteId = document.getElementById('noteId');

    editButtons.forEach(btn => {
        btn.addEventListener('click', () => {
            const fileId = btn.getAttribute('data-note-id');
            const noteTitle = btn.getAttribute('data-note-title');
            const session = btn.getAttribute('data-note-session');

            // Remplir les champs cachés et le titre de la modale
            editFileId.value = fileId;
            noteId.value = fileId;  // ou une autre valeur si différente
            editModalTitle.textContent = noteTitle + " - " + session;
        });
    });
});
</script>

<?php
    return ob_get_clean();
}
?>
