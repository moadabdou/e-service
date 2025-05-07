<?php
function professorDashboard(
    array $chosenModules = [],
    array $assignedModules = [],
    array $uploadedNotes = [],
    array $activityHistory = [],
    array $pendingNotes = [],
    array $upcomingDeadlines = [],
    string $professorName = '',
    string $department = '',
    string $academicYear = ''
): string {
    
    // Data validation
    $chosenModules = is_array($chosenModules) ? $chosenModules : [];
    $assignedModules = is_array($assignedModules) ? $assignedModules : [];
    $uploadedNotes = is_array($uploadedNotes) ? $uploadedNotes : [];
    $activityHistory = is_array($activityHistory) ? $activityHistory : [];
    $pendingNotes = is_array($pendingNotes) ? $pendingNotes : [];
    $upcomingDeadlines = is_array($upcomingDeadlines) ? $upcomingDeadlines : [];
    
    // Calculate statistics
    $totalChosenModules = count($chosenModules);
    $totalAssignedModules = count($assignedModules);
    $totalUploadedNotes = count($uploadedNotes);
    $totalActivities = count($activityHistory);
    $pendingNotesCount = count($pendingNotes);
    
    // Calculate completion rates
    $notesCompletionRate = $totalAssignedModules > 0 ? round(($totalUploadedNotes / $totalAssignedModules) * 100) : 0;
    $assignmentRate = $totalChosenModules > 0 ? round(($totalAssignedModules / $totalChosenModules) * 100) : 0;
    
    // Calculate total teaching hours
    $totalHours = 0;
    foreach ($assignedModules as $module) {
        $totalHours += isset($module['volume_cours']) ? (int)$module['volume_cours'] : 0;
    }
    
    // Get current semester based on date
    $currentMonth = (int)date('m');
    $currentSemester = ($currentMonth >= 2 && $currentMonth <= 7) ? 'S2' : 'S1';
    
    // Start building the HTML output
    ob_start();
?>
<link rel="stylesheet" href="/e-service/resources/assets/css/prof_dashboard.css">
<div class="professor-dashboard">
    <!-- Modern Welcome Header Section -->
    <div class="welcome-header">
        <div class="welcome-content">
            <h1>Bienvenue, <?= htmlspecialchars($professorName) ?></h1>
            <p>Gérez vos enseignements pour l'année académique <?= htmlspecialchars($academicYear ?: date('Y')) ?> • <?= htmlspecialchars($department) ?></p>
            
            <div class="stats-overview">
                <div class="stat-card-header">
                    <div class="stat-icon-header">
                        <i class="ti ti-book"></i>
                    </div>
                    <div>
                        <h2><?= $totalChosenModules ?></h2>
                        <p>Modules choisis</p>
                    </div>
                </div>
                
                <div class="stat-card-header">
                    <div class="stat-icon-header">
                        <i class="ti ti-clock"></i>
                    </div>
                    <div>
                        <h2><?= $totalHours ?> h</h2>
                        <p>Volume horaire total</p>
                    </div>
                </div>
            </div>
            
            <div class="semester-badge">
                <i class="ti ti-calendar"></i>
                <span>Semestre actuel: <?= $currentSemester ?></span>
            </div>
        </div>
        
        <div class="welcome-decoration">
            <div class="decoration-circle"></div>
            <div class="decoration-square"></div>
        </div>
    </div>

    <!-- Key Metrics Section -->
    <div class="metrics-grid">
        <div class="metric-card">
            <div class="metric-header">
                <div class="metric-icon blue">
                    <i class="ti ti-book"></i>
                </div>
                <div>
                    <p>Modules choisis</p>
                    <h3><?= $totalChosenModules ?></h3>
                </div>
            </div>
            <div class="progress-container">
                <div class="progress-bar">
                    <div class="progress-fill" style="width: 100%;"></div>
                </div>
                <div class="progress-info">
                    <span><?= $totalChosenModules ?> modules proposés</span>
                </div>
            </div>
        </div>
        
        <div class="metric-card">
            <div class="metric-header">
                <div class="metric-icon green">
                    <i class="ti ti-school"></i>
                </div>
                <div>
                    <p>Modules affectés</p>
                    <h3><?= $totalAssignedModules ?></h3>
                </div>
            </div>
            <div class="progress-container">
                <div class="progress-bar">
                    <div class="progress-fill success" style="width: <?= $assignmentRate ?>%;"></div>
                </div>
                <div class="progress-info">
                    <span><?= $totalAssignedModules ?> affectés</span>
                    <span>sur <?= $totalChosenModules ?> choisis</span>
                </div>
            </div>
        </div>
        
        <div class="metric-card">
            <div class="metric-header">
                <div class="metric-icon purple">
                    <i class="ti ti-file-upload"></i>
                </div>
                <div>
                    <p>Notes uploadées</p>
                    <h3><?= $totalUploadedNotes ?></h3>
                </div>
            </div>
            <div class="progress-container">
                <div class="progress-bar">
                    <div class="progress-fill purple" style="width: <?= $notesCompletionRate ?>%;"></div>
                </div>
                <div class="progress-info">
                    <span><?= $totalUploadedNotes ?> notes</span>
                    <span>sur <?= $totalAssignedModules ?> modules</span>
                </div>
            </div>
        </div>
        
        <div class="metric-card">
            <div class="metric-header">
                <div class="metric-icon orange">
                    <i class="ti ti-alert-triangle"></i>
                </div>
                <div>
                    <p>Actions requises</p>
                    <h3><?= $pendingNotesCount ?></h3>
                </div>
            </div>
            <?php if ($pendingNotesCount > 0): ?>
                <div class="alert-message">
                    <p>Notes en attente à uploader</p>
                </div>
                <a href="/e-service/internal/members/professor/uploadNotes.php" class="btn-view-requests">
                    <i class="ti ti-eye"></i> Voir les modules
                </a>
            <?php else: ?>
                <div class="success-message">
                    <p>Toutes les notes sont à jour</p>
                </div>
            <?php endif; ?>
        </div>
    </div>
    
    <!-- Main Content Grid -->
    <div class="dashboard-grid">
        <!-- Assigned Modules Section -->
        <div class="dashboard-card">
            <div class="card-header">
                <div>
                    <h3><i class="ti ti-book-2"></i> Derniers Modules Affectés</h3>
                    <p>Modules assignés pour ce semestre</p>
                </div>
                <a href="/e-service/internal/members/professor/AssignedModules.php" class="card-link">
                    <i class="ti ti-external-link"></i> Voir tout
                </a>
            </div>
            <div class="card-body">
                <?php if (!empty($assignedModules)): ?>
                    <div class="modules-list">
                        <?php foreach(array_slice($assignedModules, 0, 3) as $module): ?>
                            <div class="module-item">
                                <div class="module-icon <?= strtolower($module['semester'] ?? 's1') ?>">
                                    <i class="ti ti-book-2"></i>
                                </div>
                                <div class="module-info">
                                    <h4><?= htmlspecialchars($module['title'] ?? 'Module') ?></h4>
                                    <div class="module-meta">
                                        <span class="module-semester">
                                            <i class="ti ti-calendar"></i> <?= htmlspecialchars($module['semester'] ?? 'S1') ?>
                                        </span>
                                        <span class="module-hours">
                                            <i class="ti ti-clock"></i> <?= htmlspecialchars($module['volume_cours'] ?? '0') ?>h
                                        </span>
                                        <span class="module-department">
                                            <i class="ti ti-building"></i> <?= htmlspecialchars($module['filiere_name'] ?? 'Département') ?>
                                        </span>
                                    </div>
                                </div>
                                <div class="module-status">
                                    <?php if (isset($module['notes_uploaded']) && $module['notes_uploaded']): ?>
                                        <span class="status-badge success">
                                            <i class="ti ti-check"></i> Notes envoyées
                                        </span>
                                    <?php else: ?>
                                        <span class="status-badge warning">
                                            <i class="ti ti-alert-triangle"></i> Notes à envoyer
                                        </span>
                                    <?php endif; ?>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php else: ?>
                    <div class="empty-state">
                        <i class="ti ti-book-off"></i>
                        <p>Aucun module affecté pour le moment</p>
                        <a href="/e-service/internal/members/professor/choose_units.php" class="btn-primary">
                            <i class="ti ti-plus"></i> Choisir des modules
                        </a>
                    </div>
                <?php endif; ?>
            </div>
        </div>
        
        <!-- Alerts & Reminders Section -->
        <div class="dashboard-card">
            <div class="card-header">
                <div>
                    <h3><i class="ti ti-bell"></i> Rappels & Alertes</h3>
                    <p>Actions importantes à effectuer</p>
                </div>
            </div>
            <div class="card-body">
                <?php if ($pendingNotesCount > 0 || !empty($upcomingDeadlines)): ?>
                    <div class="alerts-list">
                        <?php if ($pendingNotesCount > 0): ?>
                            <div class="alert-item warning">
                                <div class="alert-icon">
                                    <i class="ti ti-file-alert"></i>
                                </div>
                                <div class="alert-content">
                                    <h4>Notes manquantes !</h4>
                                    <p>Vous avez <?= $pendingNotesCount ?> module(s) sans notes uploadées.</p>
                                    <a href="/e-service/internal/members/professor/uploadNotes.php" class="btn-outline">
                                        <i class="ti ti-upload"></i> Uploader maintenant
                                    </a>
                                </div>
                            </div>
                        <?php endif; ?>
                        
                        <?php if (!empty($upcomingDeadlines)): ?>
                            <div class="alert-item info">
                                <div class="alert-icon">
                                    <i class="ti ti-calendar-time"></i>
                                </div>
                                <div class="alert-content">
                                    <h4>Échéance à venir</h4>
                                    <p>Date limite pour choisir les modules: <?= htmlspecialchars($upcomingDeadlines[0]['date'] ?? 'Bientôt') ?></p>
                                    <a href="/e-service/internal/members/professor/choose_units.php" class="btn-outline">
                                        <i class="ti ti-book"></i> Choisir des modules
                                    </a>
                                </div>
                            </div>
                        <?php endif; ?>
                    </div>
                <?php else: ?>
                    <div class="success-state">
                        <i class="ti ti-circle-check"></i>
                        <p>Bravo ! Vous êtes à jour avec toutes vos tâches.</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>
        
        <!-- Notes to Upload Section -->
        <div class="dashboard-card">
            <div class="card-header">
                <div>
                    <h3><i class="ti ti-upload"></i> Notes à Téléverser</h3>
                    <p>Modules nécessitant un upload de notes</p>
                </div>
                <a href="/e-service/internal/members/professor/uploadNotes.php" class="card-link">
                    <i class="ti ti-upload"></i> Uploader
                </a>
            </div>
            <div class="card-body">
                <?php if (!empty($pendingNotes)): ?>
                    <div class="pending-notes-list">
                        <?php foreach(array_slice($pendingNotes, 0, 3) as $note): ?>
                            <div class="pending-note-item">
                                <div class="note-module-info">
                                    <h4><?= htmlspecialchars($note['title'] ?? 'Module') ?></h4>
                                    <div class="note-meta">
                                        <span class="note-semester">
                                            <i class="ti ti-calendar"></i> <?= htmlspecialchars($note['semester'] ?? 'S1') ?>
                                        </span>
                                        <span class="note-deadline <?= (isset($note['deadline_close']) && $note['deadline_close']) ? 'urgent' : '' ?>">
                                            <i class="ti ti-alarm"></i> Échéance: <?= htmlspecialchars($note['deadline'] ?? 'N/A') ?>
                                        </span>
                                    </div>
                                </div>
                                <a href="/e-service/internal/members/professor/uploadNotes.php?module=<?= htmlspecialchars($note['id_module'] ?? '') ?>" class="btn-upload">
                                    <i class="ti ti-upload"></i>
                                </a>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php else: ?>
                    <div class="success-state">
                        <i class="ti ti-file-check"></i>
                        <p>Toutes les notes ont été téléversées. Excellent travail !</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>
        
        <!-- Recent Activity Section -->
        <div class="dashboard-card">
            <div class="card-header">
                <div>
                    <h3><i class="ti ti-history"></i> Historique Récent</h3>
                    <p>Vos dernières actions</p>
                </div>
            </div>
            <div class="card-body">
                <?php if (!empty($activityHistory)): ?>
                    <div class="activity-timeline">
                        <?php foreach(array_slice($activityHistory, 0, 5) as $activity): ?>
                            <?php
                                $activityType = $activity['type'] ?? '';
                                $activityClass = 'default';
                                $activityIcon = 'ti-circle';
                                
                                if ($activityType === 'note_upload') {
                                    $activityClass = 'success';
                                    $activityIcon = 'ti-file-upload';
                                } elseif ($activityType === 'module_choice') {
                                    $activityClass = 'info';
                                    $activityIcon = 'ti-book';
                                } elseif ($activityType === 'module_assigned') {
                                    $activityClass = 'primary';
                                    $activityIcon = 'ti-school';
                                }
                            ?>
                            <div class="activity-item">
                                <div class="activity-icon <?= $activityClass ?>">
                                    <i class="ti <?= $activityIcon ?>"></i>
                                </div>
                                <div class="activity-content">
                                    <p><?= $activity['description'] ?? '' ?></p>
                                    <span class="activity-date">
                                        <i class="ti ti-calendar"></i> <?= htmlspecialchars($activity['date'] ?? date('Y-m-d')) ?>
                                    </span>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php else: ?>
                    <div class="empty-state">
                        <i class="ti ti-history"></i>
                        <p>Aucune activité récente enregistrée</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
    
    <!-- Quick Actions Section -->
    <div class="quick-actions-section">
        <h3><i class="ti ti-bolt"></i> Actions Rapides</h3>
        <div class="quick-actions-grid">
            <a href="/e-service/internal/members/professor/choose_units.php" class="quick-action">
                <div class="action-icon blue">
                    <i class="ti ti-book"></i>
                </div>
                <span>Choisir un Module</span>
            </a>
            
            <a href="/e-service/internal/members/professor/AssignedModules.php" class="quick-action">
                <div class="action-icon green">
                    <i class="ti ti-school"></i>
                </div>
                <span>Voir Mes Modules Affectés</span>
            </a>
            
            <a href="/e-service/internal/members/professor/uploadNotes.php" class="quick-action">
                <div class="action-icon purple">
                    <i class="ti ti-upload"></i>
                </div>
                <span>Uploader Notes</span>
            </a>
            
            <a href="/e-service/internal/members/professor/historique.php" class="quick-action">
                <div class="action-icon orange">
                    <i class="ti ti-history"></i>
                </div>
                <span>Voir Historique</span>
            </a>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
        
    
    // Initialize any tooltips
    const initTooltips = () => {
        const tooltips = document.querySelectorAll('[data-tooltip]');
        tooltips.forEach(tooltip => {
            tooltip.addEventListener('mouseenter', function() {
                const tooltipText = this.getAttribute('data-tooltip');
                const tooltipEl = document.createElement('div');
                tooltipEl.className = 'tooltip';
                tooltipEl.textContent = tooltipText;
                document.body.appendChild(tooltipEl);
                
                const rect = this.getBoundingClientRect();
                tooltipEl.style.top = `${rect.top - tooltipEl.offsetHeight - 10}px`;
                tooltipEl.style.left = `${rect.left + (rect.width / 2) - (tooltipEl.offsetWidth / 2)}px`;
                tooltipEl.style.opacity = '1';
                
                this.addEventListener('mouseleave', function() {
                    tooltipEl.remove();
                }, { once: true });
            });
        });
    };
    
    // Show success notifications
    const showNotification = (message, type = 'success') => {
        const notification = document.createElement('div');
        notification.className = `notification ${type}`;
        notification.innerHTML = `
            <div class="notification-icon">
                <i class="ti ti-${type === 'success' ? 'check' : 'alert-circle'}"></i>
            </div>
            <div class="notification-content">${message}</div>
            <button class="notification-close">
                <i class="ti ti-x"></i>
            </button>
        `;
        
        document.body.appendChild(notification);
        
        // Auto-hide after 5 seconds
        setTimeout(() => {
            notification.classList.add('hide');
            setTimeout(() => notification.remove(), 300);
        }, 5000);
        
        // Close button
        notification.querySelector('.notification-close').addEventListener('click', () => {
            notification.classList.add('hide');
            setTimeout(() => notification.remove(), 300);
        });
    };
    
    // Check for URL parameters to show notifications
    const urlParams = new URLSearchParams(window.location.search);
    if (urlParams.has('success')) {
        showNotification(urlParams.get('success'));
    }
    if (urlParams.has('error')) {
        showNotification(urlParams.get('error'), 'error');
    }

    initTooltips();
});
</script>

<?php
    return ob_get_clean();
}
?>
