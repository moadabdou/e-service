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
    string $academicYear = '',
    $deadlineModel
): string {

    
    // Data validation
    $chosenModules = is_array($chosenModules) ? $chosenModules : [];
    $assignedModules = is_array($assignedModules) ? $assignedModules : [];
    $uploadedNotes = is_array($uploadedNotes) ? $uploadedNotes : [];
    $activityHistory = is_array($activityHistory) ? $activityHistory : [];
    $pendingNotes = is_array($pendingNotes) ? $pendingNotes : [];
    $upcomingDeadlines = is_array($upcomingDeadlines) ? $upcomingDeadlines : [];
    
    // Get active deadlines for key features
    $features = ['choose_modules', 'upload_notes'];
    $deadlines = [];
    $hasUrgentDeadlines = false;
    
    foreach ($features as $feature) {
        $totalMinutes = $deadlineModel->getRemainingMinutesForFeature($feature);
        if ($totalMinutes !== null) {
            $days = floor($totalMinutes / (24 * 60));
            $hours = floor(($totalMinutes % (24 * 60)) / 60);
            $minutes = $totalMinutes % 60;
            
            $deadlines[$feature] = [
                'remaining' => [
                    'days' => $days,
                    'hours' => $hours,
                    'minutes' => $minutes
                ],
                'total_minutes' => $totalMinutes,
                'formatted' => formatRemainingTime([
                    'days' => $days,
                    'hours' => $hours,
                    'minutes' => $minutes
                ])
            ];
            
            // Check if any deadline is urgent (less than 48 hours)
            if ($totalMinutes < 2880) {
                $hasUrgentDeadlines = true;
            }
        }
    }
    
    // Calculate statistics
    $totalChosenModules = count($chosenModules);
    $totalAssignedModules = count($assignedModules);
    $totalUploadedNotes = count($uploadedNotes);
    $pendingNotesCount = count($pendingNotes);
    
    // Calculate completion rates
    $notesCompletionRate = $totalAssignedModules > 0 ? round(($totalUploadedNotes / $totalAssignedModules) * 100) : 0;
    $assignmentRate = $totalChosenModules > 0 ? round(($totalAssignedModules / $totalChosenModules) * 100) : 0;
    
    // Calculate total teaching hours
    $totalHours = 0;
    foreach ($assignedModules as $module) {
        $totalHours += isset($module['volume_total']) ? (int)$module['volume_total'] : 0;
    }
    
    $currentMonth = (int)date('m');
    $currentSemester = ($currentMonth >= 2 && $currentMonth <= 7) ? 'S2' : 'S1';

    ob_start();
?>
<link rel="stylesheet" href="/e-service/resources/assets/css/prof_dashboard.css">
<div class="professor-dashboard">
    <!-- Deadline Alert Banner - Only shown if there are urgent deadlines -->
    <?php if ($hasUrgentDeadlines): ?>
    <div class="deadline-alert-banner">
        <div class="alert alert-warning border-0 shadow-sm mb-4 rounded-4">
            <div class="d-flex align-items-center">
                <div class="alert-icon bg-warning-subtle rounded-circle p-3 me-3">
                    <i class="ti ti-clock-hour-4 fs-4 text-warning"></i>
                </div>
                <div class="flex-grow-1">
                    <h5 class="alert-heading mb-1">Échéances importantes à venir</h5>
                    <p class="mb-0">Des délais importants approchent de leur fin. Veuillez vérifier et prendre les mesures nécessaires.</p>
                </div>
                <div class="ms-3">
                    <?php if (isset($deadlines['choose_modules']) && $deadlines['choose_modules']['total_minutes'] < 2880): ?>
                    <a href="/e-service/internal/members/professor/choose_units.php" class="btn btn-sm btn-warning">
                        <i class="ti ti-book me-1"></i> Choisir des modules
                    </a>
                    <?php elseif (isset($deadlines['upload_notes']) && $deadlines['upload_notes']['total_minutes'] < 2880): ?>
                    <a href="/e-service/internal/members/professor/uploadNotes.php" class="btn btn-sm btn-warning">
                        <i class="ti ti-upload me-1"></i> Uploader des notes
                    </a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
    <?php endif; ?>

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
                
                <?php if (!empty($deadlines)): ?>
                <div class="stat-card-header ">
                    <div class="stat-icon-header">
                        <i class="ti ti-calendar-time "></i>
                    </div>
                    <div>
                        <h2><?= count($deadlines) ?></h2>
                        <p>Échéances actives</p>
                    </div>
                </div>
                <?php endif; ?>
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
        
        <!-- Deadline Card - New Addition -->
        <div class="metric-card">
            <div class="metric-header">
                <div class="metric-icon orange">
                    <i class="ti ti-calendar-time"></i>
                </div>
                <div>
                    <p>Échéances actives</p>
                    <h3><?= count($deadlines) ?></h3>
                </div>
            </div>
            
            <?php if (!empty($deadlines)): ?>
                <div class="deadlines-list">
                    <?php foreach ($deadlines as $feature => $deadline): ?>
                        <?php 
                            $featureName = $feature === 'choose_modules' ? 'Choix des modules' : 'Dépôt des notes';
                            $iconClass = $feature === 'choose_modules' ? 'ti-book' : 'ti-file-upload';
                            $urgencyClass = $deadline['total_minutes'] < 1440 ? 'text-danger' : 
                                           ($deadline['total_minutes'] < 2880 ? 'text-warning' : 'text-primary');
                            $actionUrl = $feature === 'choose_modules' ? 
                                        '/e-service/internal/members/professor/choose_units.php' : 
                                        '/e-service/internal/members/professor/uploadNotes.php';
                        ?>
                        <div class="deadline-item">
                            <div class="d-flex align-items-center">
                                <i class="ti <?= $iconClass ?> me-2 <?= $urgencyClass ?>"></i>
                                <span class="fw-medium"><?= $featureName ?></span>
                            </div>
                            <div class="countdown <?= $urgencyClass ?> fw-bold">
                                <?= $deadline['formatted'] ?>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
                
                <div class="mt-3 text-center">
                    <?php 
                    // Determine which action to prioritize
                    $priorityFeature = '';
                    $priorityUrl = '';
                    
                    if (isset($deadlines['upload_notes']) && isset($deadlines['choose_modules'])) {
                        if ($deadlines['upload_notes']['total_minutes'] < $deadlines['choose_modules']['total_minutes']) {
                            $priorityFeature = 'upload_notes';
                            $priorityUrl = '/e-service/internal/members/professor/uploadNotes.php';
                        } else {
                            $priorityFeature = 'choose_modules';
                            $priorityUrl = '/e-service/internal/members/professor/choose_units.php';
                        }
                    } elseif (isset($deadlines['upload_notes'])) {
                        $priorityFeature = 'upload_notes';
                        $priorityUrl = '/e-service/internal/members/professor/uploadNotes.php';
                    } elseif (isset($deadlines['choose_modules'])) {
                        $priorityFeature = 'choose_modules';
                        $priorityUrl = '/e-service/internal/members/professor/choose_units.php';
                    }
                    
                    if ($priorityFeature === 'upload_notes'):
                    ?>
                    <a href="<?= $priorityUrl ?>" class="btn btn-sm btn-primary w-100">
                        <i class="ti ti-upload"></i> Uploader des notes
                    </a>
                    <?php else: ?>
                    <a href="<?= $priorityUrl ?>" class="btn btn-sm btn-primary w-100">
                        <i class="ti ti-book"></i> Choisir des modules
                    </a>
                    <?php endif; ?>
                </div>
            <?php else: ?>
                <div class="empty-state text-center py-3">
                    <i class="ti ti-calendar-off text-muted fs-4 mb-2"></i>
                    <p class="text-muted mb-0">Aucune échéance active</p>
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
                                        <?php
                                        $uploadDeadlineClass = '';
                                        $uploadDeadlineText = 'Notes à envoyer';
                                        
                                        if (isset($deadlines['upload_notes'])) {
                                            if ($deadlines['upload_notes']['total_minutes'] < 1440) {
                                                $uploadDeadlineClass = 'danger';
                                                $uploadDeadlineText = 'Urgent: Notes à envoyer';
                                            } elseif ($deadlines['upload_notes']['total_minutes'] < 2880) {
                                                $uploadDeadlineClass = 'warning';
                                                $uploadDeadlineText = 'Bientôt: Notes à envoyer';
                                            }
                                        }
                                        ?>
                                        <span class="status-badge <?= $uploadDeadlineClass ? $uploadDeadlineClass : 'warning' ?>">
                                            <i class="ti ti-alert-triangle"></i> <?= $uploadDeadlineText ?>
                                        </span>
                                    <?php endif; ?>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php else: ?>
                    <div class="d-flex flex-column align-items-center justify-content-center text-center p-4 rounded-3 shadow-sm bg-light border" style="max-width: 400px; margin: auto;">
                        <i class="ti ti-book-off display-4 text-muted mb-3"></i>
                        <p class="mb-3 fs-5 text-secondary">Aucun module affecté pour le moment</p>
                        <a href="/e-service/internal/members/professor/choose_units.php" class="btn btn-primary d-flex align-items-center gap-2">
                            <i class="ti ti-plus"></i>
                            Choisir des modules
                        </a>
                    </div>

                <?php endif; ?>
            </div>
        </div>
        
        <!-- Deadlines & Alerts Section (Enhanced) -->
        <div class="dashboard-card">
            <div class="card-header">
                <div>
                    <h3><i class="ti ti-calendar-time"></i> Échéances & Alertes</h3>
                    <p>Dates limites importantes à surveiller</p>
                </div>
            </div>
            <div class="card-body">
                <?php if (!empty($deadlines)): ?>
                    <div class="deadlines-alerts-list">
                        <?php foreach ($deadlines as $feature => $deadline): ?>
                            <?php 
                                $featureName = $feature === 'choose_modules' ? 'Choix des modules' : 'Dépôt des notes';
                                $featureDesc = $feature === 'choose_modules' ? 
                                    'Date limite pour choisir vos modules d\'enseignement.' : 
                                    'Date limite pour uploader les notes de vos étudiants.';
                                $iconClass = $feature === 'choose_modules' ? 'ti-book' : 'ti-file-upload';
                                $actionUrl = $feature === 'choose_modules' ? 
                                    '/e-service/internal/members/professor/choose_units.php' : 
                                    '/e-service/internal/members/professor/uploadNotes.php';
                                $actionText = $feature === 'choose_modules' ? 'Choisir des modules' : 'Uploader des notes';
                                $actionIcon = $feature === 'choose_modules' ? 'ti-book' : 'ti-upload';
                                
                                // Determine urgency level
                                $urgencyClass = '';
                                $urgencyLabel = '';
                                
                                if ($deadline['total_minutes'] < 1440) {
                                    $urgencyClass = 'danger';
                                    $urgencyLabel = 'Très urgent';
                                } elseif ($deadline['total_minutes'] < 2880) {
                                    $urgencyClass = 'warning';
                                    $urgencyLabel = 'Urgent';
                                } else {
                                    $urgencyClass = 'info';
                                    $urgencyLabel = 'À venir';
                                }
                            ?>
                            <div class="deadline-alert-item <?= $urgencyClass ?>">
                                <div class="deadline-alert-header">
                                    <div class="deadline-alert-icon">
                                        <i class="ti <?= $iconClass ?>"></i>
                                    </div>
                                    <div class="deadline-alert-info">
                                        <h4><?= $featureName ?></h4>
                                        <p><?= $featureDesc ?></p>
                                    </div>
                                    <div class="deadline-alert-badge">
                                        <?= $urgencyLabel ?>
                                    </div>
                                </div>
                                
                                <div class="deadline-countdown">
                                    <div class="countdown-timer">
                                        <?php if ($deadline['remaining']['days'] > 0): ?>
                                        <div class="countdown-unit">
                                            <span class="countdown-value"><?= $deadline['remaining']['days'] ?></span>
                                            <span class="countdown-label">jours</span>
                                        </div>
                                        <?php endif; ?>
                                        
                                        <div class="countdown-unit">
                                            <span class="countdown-value"><?= $deadline['remaining']['hours'] ?></span>
                                            <span class="countdown-label">heures</span>
                                        </div>
                                        
                                        <div class="countdown-unit">
                                            <span class="countdown-value"><?= $deadline['remaining']['minutes'] ?></span>
                                            <span class="countdown-label">min</span>
                                        </div>
                                    </div>
                                    
                                    <a href="<?= $actionUrl ?>" class="btn-deadline-action">
                                        <i class="ti <?= $actionIcon ?>"></i> <?= $actionText ?>
                                    </a>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php elseif ($pendingNotesCount > 0): ?>
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
                <?php else: ?>
                    <div class="success-state">
                        <i class="ti ti-circle-check"></i>
                        <p>Bravo ! Vous êtes à jour avec toutes vos tâches et échéances.</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>
        
        <!-- Notes to Upload Section (Enhanced) -->
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
                <?php if (!empty($pendingNotes) && !empty($deadlines['upload_notes'])): ?>
                    <div class="pending-notes-list">
                        <?php 
                        // Sort pending notes by urgency if upload_notes deadline exists
                        if (isset($deadlines['upload_notes'])) {
                            // Add deadline info to each note
                            foreach ($pendingNotes as &$note) {
                                $note['deadline_minutes'] = $deadlines['upload_notes']['total_minutes'];
                                $note['deadline_formatted'] = $deadlines['upload_notes']['formatted'];
                            }
                        }
                        
                        foreach(array_slice($pendingNotes, 0, 3) as $note): 
                            $noteUrgencyClass = '';
                            $noteUrgencyIcon = 'ti-calendar';
                            
                            if (isset($note['deadline_minutes'])) {
                                if ($note['deadline_minutes'] < 1440) {
                                    $noteUrgencyClass = 'urgent';
                                    $noteUrgencyIcon = 'ti-alert-circle';
                                } elseif ($note['deadline_minutes'] < 2880) {
                                    $noteUrgencyClass = 'warning';
                                    $noteUrgencyIcon = 'ti-alert-triangle';
                                }
                            }
                        ?>   
                            <div class="pending-note-item <?= $noteUrgencyClass ?>">
                                <div class="note-module-info">
                                    <h4><?= htmlspecialchars($note['title'] ?? 'Module') ?></h4>
                                    <div class="note-meta">
                                        <span class="note-semester">
                                            <i class="ti ti-calendar"></i> <?= htmlspecialchars($note['semester'] ?? 'S1') ?>
                                        </span>
                                        <span class="note-deadline <?= $noteUrgencyClass ?>">
                                            <i class="ti <?= $noteUrgencyIcon ?>"></i> 
                                            <?php if (isset($note['deadline_formatted'])): ?>
                                                Reste: <?= $note['deadline_formatted'] ?>
                                            <?php else: ?>
                                                Échéance: <?= htmlspecialchars($note['deadline'] ?? 'N/A') ?>
                                            <?php endif; ?>
                                        </span>
                                    </div>
                                </div>
                                <a href="/e-service/internal/members/professor/uploadNotes.php?module=<?= htmlspecialchars($note['id_module'] ?? '') ?>" class="btn-upload <?= $noteUrgencyClass ?>">
                                    <i class="ti ti-upload"></i>
                                </a>
                            </div>
                            
                        <?php endforeach; ?>
                        
                        <?php if (count($pendingNotes) > 3): ?>
                        <div class="view-all-notes text-center mt-3">
                            <a href="/e-service/internal/members/professor/uploadNotes.php" class="btn-view-all">
                                <i class="ti ti-list"></i> Voir tous les modules (<?= count($pendingNotes) ?>)
                            </a>
                        </div>
                        <?php endif; ?>
                    </div>
                <?php elseif(empty($pendingNotes) && !isset($deadlines['upload_notes'])): ?>
                    <div class="success-state">
                        <i class="ti ti-file-check"></i>
                        <p>Toutes les notes ont été téléversées. Excellent travail !</p>
                    </div>

                <?php else: ?>
                    <div class="danger-state">
                        <i class="ti ti-alert-triangle"></i>
                        <p>La date limite pour le dépôt des notes est dépassée et certaines notes n'ont pas été téléversées.<br>
                        Veuillez contacter l'administration pour régulariser la situation.</p>
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

<style>
/* Deadline specific styles */
.deadline-alert-banner {
    animation: fadeIn 0.5s ease-in-out;
}

.deadline-item {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 10px 0;
    border-bottom: 1px solid #eee;
}

.deadline-item:last-child {
    border-bottom: none;
}

.deadlines-list {
    margin-bottom: 15px;
}

.countdown {
    font-size: 0.9rem;
}

.deadline-stat .stat-icon-header {
    background-color: #f59e0b;
}

.deadline-icon {
    background-color: #f59e0b;
}

/* Enhanced deadline alerts */
.deadlines-alerts-list {
    display: flex;
    flex-direction: column;
    gap: 15px;
}

.deadline-alert-item {
    border-radius: 8px;
    overflow: hidden;
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    border-left: 4px solid #4361ee;
}

.deadline-alert-item.danger {
    border-left-color: #ef4444;
}

.deadline-alert-item.warning {
    border-left-color: #f59e0b;
}

.deadline-alert-item.info {
    border-left-color: #3b82f6;
}

.deadline-alert-header {
    display: flex;
    align-items: center;
    padding: 12px 15px;
    background-color: #f8fafc;
    border-bottom: 1px solid #e2e8f0;
}

.deadline-alert-icon {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    margin-right: 12px;
    font-size: 18px;
}

.deadline-alert-item.danger .deadline-alert-icon {
    background-color: rgba(239, 68, 68, 0.1);
    color: #ef4444;
}

.deadline-alert-item.warning .deadline-alert-icon {
    background-color: rgba(245, 158, 11, 0.1);
    color: #f59e0b;
}

.deadline-alert-item.info .deadline-alert-icon {
    background-color: rgba(59, 130, 246, 0.1);
    color: #3b82f6;
}

.deadline-alert-info {
    flex: 1;
}

.deadline-alert-info h4 {
    margin: 0 0 4px 0;
    font-size: 16px;
    font-weight: 600;
}

.deadline-alert-info p {
    margin: 0;
    font-size: 13px;
    color: #64748b;
}

.deadline-alert-badge {
    padding: 4px 10px;
    border-radius: 20px;
    font-size: 12px;
    font-weight: 600;
}

.deadline-alert-item.danger .deadline-alert-badge {
    background-color: #ef4444;
    color: white;
}

.deadline-alert-item.warning .deadline-alert-badge {
    background-color: #f59e0b;
    color: white;
}

.deadline-alert-item.info .deadline-alert-badge {
    background-color: #3b82f6;
    color: white;
}

.deadline-countdown {
    padding: 15px;
    background-color: white;
    display: flex;
    flex-direction: column;
    gap: 12px;
}

.countdown-timer {
    display: flex;
    justify-content: center;
    gap: 15px;
}

.countdown-unit {
    display: flex;
    flex-direction: column;
    align-items: center;
    min-width: 60px;
}

.countdown-value {
    font-size: 24px;
    font-weight: 700;
    line-height: 1;
}

.deadline-alert-item.danger .countdown-value {
    color: #ef4444;
}

.deadline-alert-item.warning .countdown-value {
    color: #f59e0b;
}

.deadline-alert-item.info .countdown-value {
    color: #3b82f6;
}

.countdown-label {
    font-size: 12px;
    color: #64748b;
    margin-top: 4px;
}

.btn-deadline-action {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
    padding: 8px 16px;
    border-radius: 6px;
    font-weight: 600;
    text-decoration: none;
    transition: all 0.2s ease;
}

.deadline-alert-item.danger .btn-deadline-action {
    background-color: #ef4444;
    color: white;
}

.deadline-alert-item.warning .btn-deadline-action {
    background-color: #f59e0b;
    color: white;
}

.deadline-alert-item.info .btn-deadline-action {
    background-color: #3b82f6;
    color: white;
}

.btn-deadline-action:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 6px rgba(0,0,0,0.1);
}

/* Enhanced pending notes */
.pending-note-item {
    border-left: 3px solid #9333ea;
}

.pending-note-item.urgent {
    border-left-color: #ef4444;
}

.pending-note-item.warning {
    border-left-color: #f59e0b;
}

.note-deadline.urgent {
    color: #ef4444;
    font-weight: 600;
}

.note-deadline.warning {
    color: #f59e0b;
    font-weight: 600;
}

.btn-upload.urgent {
    background-color: #ef4444;
}

.btn-upload.warning {
    background-color: #f59e0b;
}

.btn-view-all {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    padding: 8px 16px;
    background-color: #f1f5f9;
    color: #334155;
    border-radius: 6px;
    text-decoration: none;
    font-weight: 500;
    transition: all 0.2s ease;
}

.btn-view-all:hover {
    background-color: #e2e8f0;
}

@keyframes fadeIn {
    from { opacity: 0; transform: translateY(-10px); }
    to { opacity: 1; transform: translateY(0); }
}

.danger-state {
    text-align: center;
    padding: var(--spacing-xl) 0;
    color: var(--danger, #d32f2f); /* fallback red */
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    flex: 1;
}

.danger-state i {
    font-size: 2.5rem;
    margin-bottom: var(--spacing-md);
    color: var(--danger, #d32f2f); /* fallback red */
    opacity: 0.8;
}

.danger-state p {
    font-weight: 500;
    margin: 0 0 var(--spacing-md);
    color: var(--danger, #d32f2f); /* ensure text is also danger-colored */
}


</style>

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