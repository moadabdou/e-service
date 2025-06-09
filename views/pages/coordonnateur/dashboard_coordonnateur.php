<?php
// dashboard_coordonnateur.php
?>
<div class="container mt-4">
    <h2 class="fw-bold text-primary mb-4">
        <i class="ti ti-dashboard"></i> Tableau de bord du coordonnateur
    </h2>

    <!-- Statistiques principales -->
    <div class="row g-4 mb-4">
        <div class="col-md-3">
            <div class="card border-0 shadow-sm rounded-4 text-center p-4">
                <div class="icon-circle bg-primary-subtle text-primary mb-3 mx-auto">
                    <i class="ti ti-users fs-4"></i>
                </div>
                <h5 class="fw-semibold">Professeurs de la filière</h5>
                <p class="fs-3 fw-bold text-primary mb-0"><?= $nb_profs ?></p>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm rounded-4 text-center p-4">
                <div class="icon-circle bg-success-subtle text-success mb-3 mx-auto">
                    <i class="ti ti-book fs-4"></i>
                </div>
                <h5 class="fw-semibold">Modules de la filière</h5>
                <p class="fs-3 fw-bold text-success mb-0"><?= $nb_modules ?></p>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm rounded-4 text-center p-4">
                <div class="icon-circle bg-info-subtle text-info mb-3 mx-auto">
                    <i class="ti ti-calendar fs-4"></i>
                </div>
                <h5 class="fw-semibold">Emplois du temps uplodés</h5>
                <p class="fs-3 fw-bold text-info mb-0"><?= $nb_emplois ?></p>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm rounded-4 text-center p-4">
                <div class="icon-circle bg-warning-subtle text-warning mb-3 mx-auto">
                    <i class="ti ti-user-check fs-4"></i>
                </div>
                <h5 class="fw-semibold">Vacataires affectés</h5>
                <p class="fs-3 fw-bold text-warning mb-0">
                    <?= $nb_vacataires ?> / <?= $nb_affectations ?>
                </p>
            </div>
        </div>
    </div>

    <!-- Graphique circulaire (Exemple de répartition) -->
    <div class="card border-0 shadow-sm rounded-4">
        <div class="card-body">
            <h5 class="fw-semibold mb-3">
                <i class="ti ti-chart-pie"></i> Répartition des volumes horaires
            </h5>
            <canvas id="volumeChart" style="max-height: 300px;"></canvas>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const ctx = document.getElementById('volumeChart').getContext('2d');
    const chart = new Chart(ctx, {
        type: 'doughnut',
        data: {
            labels: ['Cours', 'TD', 'TP', 'Autres'],
            datasets: [{
                label: 'Volumes',
                data: [200, 100, 50, 25],
                backgroundColor: [
                    'rgba(54, 162, 235, 0.7)',
                    'rgba(255, 206, 86, 0.7)',
                    'rgba(75, 192, 192, 0.7)',
                    'rgba(153, 102, 255, 0.7)'
                ],
                borderColor: '#fff',
                borderWidth: 2
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    position: 'bottom'
                }
            }
        }
    });
</script>