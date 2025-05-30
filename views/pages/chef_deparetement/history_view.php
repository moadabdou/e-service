<?php
require_once $_SERVER['DOCUMENT_ROOT'] . "/e-service/views/components/search_filter_component.php";
// Include the export functionality
require_once $_SERVER['DOCUMENT_ROOT'] . "/e-service/utils/Export/export_history_excel.php";

function yearHistoryView(array $historicalData): string {
    // Check if an export request was made
    if (isset($_POST['export']) && $_POST['export'] == '1') {
        exportHistoryToExcel($historicalData);
        exit; // The export function will handle the response
    }
    
    ob_start();
    ?>
    
    <div class="container mt-2 px-4 px-md-5">
    <div class="row mb-3">
            <div class="col-12">
                <header class="d-flex justify-content-between align-items-center flex-wrap mb-2">
                    <h2 class="fw-bold text-primary">
                        <i class="ti ti-history me-2" aria-hidden="true"></i> Historique des affectations
                    </h2>
                    <?php if (!empty($historicalData)): ?>
                        <button class="btn btn-primary btn-lg rounded-3 shadow-sm" id="exportToExcel">
                            <i class="ti ti-file-spreadsheet me-2"></i> Exporter vers Excel
                        </button>
                    <?php endif; ?>
                </header>
                <p class="text-muted">Historique des affectations de modules aux professeurs par année académique</p>
                <hr class="opacity-25">
            </div>
        </div>

        <?php if (empty($historicalData)): ?>
            <div class="alert alert-info rounded-4 shadow-sm border-0 d-flex align-items-center" role="alert">
                <i class="ti ti-info-circle fs-3 me-3"></i>
                <div>
                    <h5 class="mb-1">Aucun historique disponible</h5>
                    <p class="mb-0">Il n'y a pas d'enregistrements d'affectations dans l'historique pour ce département.</p>
                </div>
            </div>
        <?php else: ?>
            
            <?php
            $filterOptions = [
                'year' => [
                    'label' => 'Année académique',
                    'allLabel' => 'Toutes les années',
                    'icon' => 'ti-calendar',
                    'options' => []
                ]
            ];
            
            foreach (array_keys($historicalData) as $year) {
                $filterOptions['year']['options'][] = [
                    'value' => $year,
                    'label' => $year
                ];
            }
            
            echo createSearchFilterComponent(
                "Rechercher un professeur...",
                $filterOptions,
                "historyContainer",
                "professor-card",
                "professor"
            );
            ?>
            
            <!-- Stats overview -->
            <div class="row g-4 mb-4">
                <div class="col-md-4">
                    <div class="card border-0 rounded-4 shadow-sm bg-primary bg-opacity-10 h-100">
                        <div class="card-body p-4">
                            <div class="d-flex align-items-center">
                                <div class="rounded-circle bg-primary p-3 me-3">
                                    <i class="ti ti-calendar-stats text-white"></i>
                                </div>
                                <div>
                                    <h6 class="text-muted mb-1">Années académiques</h6>
                                    <h3 class="fw-bold mb-0"><?= count($historicalData) ?></h3>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card border-0 rounded-4 shadow-sm bg-success bg-opacity-10 h-100">
                        <div class="card-body p-4">
                            <div class="d-flex align-items-center">
                                <div class="rounded-circle bg-success p-3 me-3">
                                    <i class="ti ti-users text-white"></i>
                                </div>
                                <div>
                                    <h6 class="text-muted mb-1">Total des professeurs</h6>
                                    <h3 class="fw-bold mb-0">
                                        <?php 
                                        $uniqueProfessors = [];
                                        foreach ($historicalData as $yearData) {
                                            foreach ($yearData as $profId => $prof) {
                                                $uniqueProfessors[$profId] = true;
                                            }
                                        }
                                        echo count($uniqueProfessors);
                                        ?>
                                    </h3>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card border-0 rounded-4 shadow-sm bg-info bg-opacity-10 h-100">
                        <div class="card-body p-4">
                            <div class="d-flex align-items-center">
                                <div class="rounded-circle bg-info p-3 me-3">
                                    <i class="ti ti-book text-white"></i>
                                </div>
                                <div>
                                    <h6 class="text-muted mb-1">Total des modules</h6>
                                    <h3 class="fw-bold mb-0">
                                        <?php 
                                        $totalModules = 0;
                                        foreach ($historicalData as $yearData) {
                                            foreach ($yearData as $profId => $prof) {
                                                $totalModules += count($prof['modules']);
                                            }
                                        }
                                        echo $totalModules;
                                        ?>
                                    </h3>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div id="historyContainer">
                <?php foreach ($historicalData as $year => $professors): ?>
                    <div class="year-section mb-5" data-year="<?= $year ?>">
                        <div class="d-flex align-items-center mb-3">
                            <h3 class="year-heading fw-bold mb-0">
                                <i class="ti ti-calendar-event me-2"></i> Année académique <?= $year ?>
                            </h3>
                            <div class="ms-3 px-3 py-1 bg-light rounded-pill">
                                <span class="fw-semibold"><?= count($professors) ?> professeurs</span>
                            </div>
                            <hr class="flex-grow-1 ms-4">
                        </div>

                        <div class="row g-4 professor-cards">

                            <?php foreach ($professors as $profId => $professor): ?>
                                <?php 
                                $totalHours = 0;
                                $modulesByFiliere = [];

                                foreach ($professor['modules'] as $module) {
                                    $totalHours += $module['hours'] ?? 0;
                                    $modulesByFiliere[$module['filiere']][] = $module;
                                }
                                ?>
                                <div class="col-md-6 col-xl-4 professor-card" 
                                     data-name="<?= strtolower($professor['firstName'] . ' ' . $professor['lastName']) ?>"
                                     data-year="<?= $year ?>">
                                    <div class="card shadow-sm border-0 rounded-4 h-100">
                                        <div class="card-body p-0">
                                            <div class="d-flex p-3">
                                                <div class="professor-avatar me-3">
                                                    <?php if (!empty($professor['img'])): 
                                                                        $img = $professor['img'] 
                                                                        ? "/e-service/storage/image/users_pp/" . htmlspecialchars($professor['img']) 
                                                                        : "/e-service/storage/image/users_pp/default.webp";?>
                                                        <img src="<?= $img ?>" alt="<?= htmlspecialchars($professor['firstName']) ?>" class="rounded-circle" width="60" height="60">
                                                    <?php else: ?>
                                                        <div class="rounded-circle bg-secondary d-flex align-items-center justify-content-center text-white" style="width: 60px; height: 60px">
                                                            <i class="ti ti-user fs-3"></i>
                                                        </div>
                                                    <?php endif; ?>
                                                </div>
                                                <div>
                                                    <h5 class="fw-bold mb-1"><?= htmlspecialchars($professor['firstName'] . ' ' . $professor['lastName']) ?></h5>
                                                    <p class="text-muted mb-0 d-flex align-items-center">
                                                        <i class="ti ti-mail me-1"></i> <?= htmlspecialchars($professor['email']) ?>
                                                    </p>
                                                    <div class="mt-2 d-flex align-items-center">
                                                        <span class="badge bg-primary-subtle text-primary px-3 py-2">
                                                            <i class="ti ti-clock me-1"></i> <?= $totalHours ?> heures
                                                        </span>
                                                    </div>
                                                </div>
                                            </div>
                                            
                                            <div class="border-top px-3 py-3">
                                                <h6 class="fw-bold mb-2 d-flex align-items-center">
                                                    <i class="ti ti-book me-2 text-primary"></i> 
                                                    Modules assignés (<?= count($professor['modules']) ?>)
                                                </h6>
                                                <ul class="list-group list-group-flush">
                                                    <?php foreach ($modulesByFiliere as $filiere => $modules): ?>
                                                        <li class="list-group-item border-0 px-0 py-2">
                                                            <h6 class="fw-bold text-primary fs-4"><?= htmlspecialchars($filiere) ?></h6>
                                                            <ul>
                                                                <?php foreach ($modules as $module): ?>
                                                                    <li><?= htmlspecialchars($module['title']) ?>
                                                                        <?php if (isset($module['hours']) && $module['hours'] > 0): ?>
                                                                            <span class="ms-2 badge bg-info-subtle text-info"><?= $module['hours'] ?>h</span>
                                                                        <?php endif; ?>                                                                    
                                                                    </li>
                                                                <?php endforeach; ?>
                                                            </ul>
                                                        </li>
                                                    <?php endforeach; ?>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>

    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const exportBtn = document.getElementById('exportToExcel');

        if (exportBtn) {
            exportBtn.addEventListener('click', function() {
                // Show loading indicator
                const originalText = exportBtn.innerHTML;
                exportBtn.innerHTML = '<i class="ti ti-loader ti-spin me-2"></i>Exportation en cours...';
                exportBtn.disabled = true;

                // Create a form to submit the request
                const form = document.createElement('form');
                form.method = 'POST';
                form.action = window.location.href;

                const input = document.createElement('input');
                input.type = 'hidden';
                input.name = 'export';
                input.value = '1';
                form.appendChild(input);

                document.body.appendChild(form);
                form.submit();

                // Optional: Reset button state after timeout (in case server fails)
                setTimeout(() => {
                    exportBtn.innerHTML = originalText;
                    exportBtn.disabled = false;
                }, 5000);
            });
        }

        // Animate professor cards
        const professorCards = document.querySelectorAll('.professor-card');
        professorCards.forEach((card, index) => {
            card.style.animationDelay = `${index * 0.05}s`;
            card.classList.add('animate__animated', 'animate__fadeIn');
        });
    });
    </script>


    <style>
    .year-heading {
        color: var(--bs-primary);
    }
    
    .professor-card .card {
        transition: all 0.3s ease;
    }
    
    .professor-card .card:hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1) !important;
    }
    
    .animate__animated {
        animation-duration: 0.6s;
        animation-fill-mode: both;
    }
    
    .animate__fadeIn {
        animation-name: fadeIn;
    }
    
    @keyframes fadeIn {
        from {
            opacity: 0;
            transform: translate3d(0, 20px, 0);
        }
        to {
            opacity: 1;
            transform: translate3d(0, 0, 0);
        }
    }
    
    .list-group-item:nth-child(odd) {
        background-color: rgba(0, 0, 0, 0.02);
    }
    
    .notification-toast {
        position: fixed;
        top: 20px;
        right: 20px;
        z-index: 9999;
        transition: all 0.3s ease;
    }
    
    .notification-hide {
        opacity: 0;
        transform: translateY(-20px);
    }
    </style>

    <?php
    return ob_get_clean();
}
