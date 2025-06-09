<div class="container mt-4">
    <h2 class="fw-bold text-primary mb-4">
        <i class="ti ti-dashboard"></i> Tableau de bord Vacataire
    </h2>

    <!-- Carte profil -->
    <div class="card mb-4 shadow-sm">
        <div class="card-body d-flex align-items-center">
            <img src="<?= htmlspecialchars($user['img']) ?>" alt="Profil" class="rounded-circle me-3" style="width: 60px; height: 60px; object-fit: cover;">
            <div>
                <h5 class="mb-0 fw-semibold text-primary">Bonjour, <?= htmlspecialchars($user['name']) ?> 👋</h5>
                <small class="text-muted">Statut : Actif</small><br>
                <small class="text-muted">Rôle : Vacataire</small>
            </div>
        </div>
    </div>

    <!-- Statistiques -->
    <div class="row">
        <div class="col-md-6">
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-body text-center">
                    <h3 class="fw-bold text-primary">
                        <i class="ti ti-book me-2"></i><?= $totalModules ?>
                    </h3>
                    <p class="mb-0 text-muted">Modules affectés</p>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-body text-center">
                    <h3 class="fw-bold text-success">
                        <i class="ti ti-calendar-check me-2"></i><?= date('Y') ?>
                    </h3>
                    <p class="mb-0 text-muted">Année en cours</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Message d’information -->
    <div class="alert alert-info mt-4 shadow-sm">
        <i class="ti ti-info-circle me-2"></i>
        Consultez la section <strong>"Mes modules affectés"</strong> pour visualiser vos unités d’enseignement.
    </div>
</div>