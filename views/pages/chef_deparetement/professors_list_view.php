<?php
require_once $_SERVER['DOCUMENT_ROOT'] . "/e-service/views/components/search_filter_component.php";

function chefDepProfessorsListView(array $professors): string {
    ob_start();
?>
<div class="container mt-4 px-4 px-md-5">
    <!-- Page Header with improved styling -->
    <div class="d-flex justify-content-between align-items-center flex-wrap mb-4 border-bottom pb-3">
        <h2 class="fw-bold text-primary mb-0">
            <i class="ti ti-users me-2"></i>Enseignants du département
        </h2>
        <div class="badge bg-primary-subtle text-primary fs-6 px-3 py-2 rounded-pill">
            <span id="profCount"><?= count($professors) ?></span> enseignant(s)
        </div>
    </div>

            <?= createSearchFilterComponent(
                "Rechercher un enseignant...",
                [
                    "role" => [
                        "label" => "Rôle",
                        "icon" => "ti-user",
                        "allLabel" => "Tous les rôles",
                        "options" => [
                            ["label" => "Chef de Département", "value" => "chef_deparetement"],
                            ["label" => "Coordonnateur", "value" => "coordonnateur"],
                            ["label" => "Normal", "value" => "normal"]
                        ]
                    ]
                ],
                "profList",
                "prof-card",
                "profCount"
            ); ?>


    <?php if (empty($professors)) : ?>
        <div class="alert alert-warning text-center shadow-sm rounded-4 p-4">
            <i class="ti ti-alert-circle fs-1 d-block mb-3"></i>
            <h5>Aucun enseignant disponible pour le moment</h5>
            <p class="text-muted mb-0">La liste des enseignants apparaîtra ici</p>
        </div>
    <?php else : ?>
        <div class="row g-4" id="profList">
            <?php foreach ($professors as $professor) : 
                $prole = htmlspecialchars($professor['p_role']);
                $urole = htmlspecialchars($professor['u_role']);
                $img = $professor['image_url'] 
                        ? "/e-service/storage/image/users_pp/" . htmlspecialchars($professor['image_url']) 
                        : "/e-service/storage/image/users_pp/default.webp";

                $roleColor = $prole === 'chef_deparetement' ? 'bg-primary-subtle text-primary' : 
                            ($prole === 'coordonnateur' ? 'bg-success-subtle text-success' : 
                            'bg-secondary-subtle text-secondary');
                $roleIconClass = $prole === 'chef_deparetement' ? 'ti ti-crown' : 
                                ($prole === 'coordonnateur' ? 'ti ti-certificate' : 
                                'ti ti-user');
            ?>
                <div class="col-12 col-md-6 col-lg-4 prof-card" data-role="<?= $prole ?>">
                    <div class="card h-80 shadow-sm rounded-4 border-0 overflow-hidden hover-shadow transition-300 mb-3">
                        <div class="card-header bg-white border-0 pt-4 pb-0 px-4 text-center position-relative">
                            <div class="position-relative mb-1">
                                <img src="<?= $img ?>" alt="Photo de profil" 
                                     class="rounded-circle shadow-sm object-fit-cover" 
                                     width="110" height="110">
                                <div class="  bottom-0 end-0 start-0 d-flex justify-content-center">
                                    <span class="badge <?= $roleColor ?> rounded-pill px-3 py-2 d-flex align-items-center">
                                        <i class="<?= $roleIconClass ?> me-1"></i>
                                        <?= ucfirst($prole) ?>
                                    </span>
                                </div>
                            </div>
                            <h5 class="mt-3 fw-bold text-primary">
                                <?= htmlspecialchars($professor['firstName'] . ' ' . $professor['lastName']) ?>
                            </h5>
                            <p class="text-muted small mb-0"><?= ucfirst($urole) ?></p>
                        </div>
                        <div class="card-body px-4 pt-2 pb-4">
                            <div class="mt-3">
                                <div class="d-flex align-items-center mb-2">
                                    <div class="icon-box me-3 text-center flex-shrink-0">
                                        <i class="ti ti-credit-card text-secondary"></i>
                                    </div>
                                    <div class="text-muted small"><?= htmlspecialchars($professor['CIN']) ?></div>
                                </div>
                                <div class="d-flex align-items-center mb-2">
                                    <div class="icon-box me-3 text-center flex-shrink-0">
                                        <i class="ti ti-mail text-secondary"></i>
                                    </div>
                                    <div class="text-muted small text-truncate"><?= htmlspecialchars($professor['email']) ?></div>
                                </div>
                                <div class="d-flex align-items-center mb-2">
                                    <div class="icon-box me-3 text-center flex-shrink-0">
                                        <i class="ti ti-phone text-secondary"></i>
                                    </div>
                                    <div class="text-muted small"><?= htmlspecialchars($professor['phone'] ?? 'Non spécifié') ?></div>
                                </div>
                                <div class="d-flex align-items-center mb-2">
                                    <div class="icon-box me-3 text-center flex-shrink-0">
                                        <i class="ti ti-clock text-secondary"></i>
                                    </div>
                                    <div class="text-muted small">
                                        <strong>Charge:</strong> <?= htmlspecialchars($professor['min_hours'] ?? '0') ?>h - <?= htmlspecialchars($professor['max_hours'] ?? '0') ?>h
                                    </div>
                                </div>
                            </div>
                            <div class="mt-3 pt-3 border-top">
                                <a href="/e-service/internal/members/common/view_profile.php?id=<?= $professor['id_user'] ?>" 
                                   class="btn btn-outline-primary w-100">
                                    <i class="ti ti-user-circle me-1"></i> Voir profil
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>

<style>
    .transition-300 {
        transition: all 0.3s ease;
    }
    .hover-shadow:hover {
        transform: translateY(-3px);
        transition: transform 0.3s ease;
    }
    .icon-box {
        width: 24px;
    }
    .object-fit-cover {
        object-fit: cover;
    }
    .btn {
        border-radius: 4px;
        padding: 0.5rem 1rem;
        font-weight: 500;
        transition: all 0.2s;
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        document.querySelectorAll('.hover-shadow').forEach(card => {
            card.addEventListener('mouseenter', () => {
                card.classList.add('shadow');
                card.classList.remove('shadow-sm');
            });
            card.addEventListener('mouseleave', () => {
                card.classList.remove('shadow');
                card.classList.add('shadow-sm');
            });
        });
    });
</script>
<?php
    return ob_get_clean();
}
?>