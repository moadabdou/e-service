<style>
    .activity-section {
            background-color: var(--white-color);
            padding: 1.5rem 1.75rem;
            border-radius: 10px;
            box-shadow: 0 3px 10px rgba(0, 0, 0, 0.05);

        }
        .activity-list {
            list-style: none;
            padding-left: 0;
            margin-bottom: 0;
        }
        .activity-list .activity-item {
            display: flex;
            padding: 0.85rem 0;
            border-bottom: 1px solid var(--border-color);
            font-size: 0.9rem;
        }
        .activity-list .activity-item:last-child {
            border-bottom: none;
            padding-bottom: 0;
        }
        .activity-list .activity-item:first-child {
            padding-top: 0;
        }
        .activity-list .activity-icon {
            font-size: 0.9rem; /* Smaller icon for a cleaner look */
            color: #6587FF;;
            margin-right: 1rem;
            min-width: 18px;
            margin-top: 0.2em; /* Fine-tune vertical alignment */
        }
        .activity-list .activity-text {
            flex-grow: 1;
            color: var(--text-dark);
        }
        .activity-list .activity-time {
            font-size: 0.8rem;
            color: var(--text-light);
            margin-left: 1rem;
            white-space: nowrap;
            font-weight: 300;
        }
        .activity-section .view-all-link {
            display: block;
            text-align: center;
            margin-top: 1.25rem;
            color: var(--primary-blue);
            text-decoration: none;
            font-weight: 500;
        }
        .activity-section .view-all-link:hover {
            text-decoration: underline;
        }
        @media (max-width: 767px) { /* Mobile */
   
            .activity-item {
                /* flex-direction: column;
                align-items: flex-start; */
            }
            .activity-time {
                /* margin-left: 0;
                margin-top: 0.25rem; */
            }
        }
</style>

<section id="activite-recente" class="mb-4">
    <h2 class="section-heading">Toutes les Activités</h2>
    <div class="activity-section">
        <ul class="activity-list">
        <?php if (!empty($activities)): ?>
            <?php foreach ($activities as $activity): ?>
                <li class="activity-item">
                    <i class="activity-icon fas <?= htmlspecialchars($activity["icon"]) ?>"></i>
                    <span class="activity-text"><?= htmlspecialchars($activity["content"]) ?></span>
                    <span class="activity-time"><?= htmlspecialchars($activity["create_time"]) ?></span>
                </li>
            <?php endforeach; ?>
        <?php else: ?>
            <li class="activity-item">Aucune activité à afficher</li>
        <?php endif; ?>
        </ul>
    </div>
</section>