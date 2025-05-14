<style>
        /* --- Custom Modern Design --- */
        :root {
            --primary-blue: var(--bs-primary); /* Your primary blue - IMPORTANT: Adjust this! */
            --light-blue-bg: #edf2f9; /* A very light blue for backgrounds */
            --text-dark: #2c3e50;
            --text-medium: #555;
            --text-light: #777;
            --border-color: #e0e6ed;
            --white-color: #ffffff;
            --body-bg: #f4f7fc; /* Lighter, slightly blue-ish overall page background */
        }

        body {
            background-color: var(--body-bg);
            font-family: 'Inter', 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; /* Using Inter as a modern font, fallback */
            color: var(--text-dark);
            line-height: 1.6;
        }

        /* Import Inter font (optional, but recommended for the modern feel) */
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap');

        .main-content-area {
            padding: 20px 30px; /* More horizontal padding */
        }

        /* Welcome Header */
        .welcome-header {
            /* Keep your blue banner style, or adapt this one */
            background: linear-gradient(135deg, var(--primary-blue) 0%, #3b5998 100%);
            color: var(--white-color);
            padding: 2rem 2.5rem;
            border-radius: 12px;
            margin-bottom: 2.5rem;
            /* box-shadow: 0 6px 20px rgba(74, 105, 189, 0.25); */
        }
        .welcome-header h1 {
            font-weight: 600;
            font-size: 2rem; /* Slightly smaller for better balance */
            margin-bottom: 0.5rem;
        }
        .welcome-header p {
            font-size: 1.05rem;
            opacity: 0.9;
            font-weight: 300;
        }

        /* Section Titles */
        .section-heading {
            font-size: 1.5rem; /* Slightly smaller for a cleaner look */
            font-weight: 600;
            color: var(--text-dark);
            margin-bottom: 1.5rem;
            padding-bottom: 0.6rem;
            position: relative;
        }
        .section-heading::after { /* Subtle underline accent */
            content: '';
            position: absolute;
            left: 0;
            bottom: 0;
            width: 40px;
            height: 3px;
            background-color: var(--primary-blue);
            border-radius: 3px;
        }

        /* Key Statistics - Redesigned */
        .stats-container {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(360px, 1fr));
            gap: 1.5rem;
            margin-bottom: 2.5rem;
        }
        .stat-item {
            background-color: var(--white-color);
            padding: 1.25rem 1.5rem; /* Balanced padding */
            border-radius: 10px;
            text-align: left; /* Align text to left for a more conventional stat look */
            box-shadow: 0 3px 10px rgba(0, 0, 0, 0.05);
            transition: transform 0.2s ease-out, box-shadow 0.2s ease-out;
            display: flex;
            align-items: center; /* Vertically align icon and text */
        }
        .stat-item:hover {
            transform: translateY(-3px);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.08);
        }
        .stat-item .stat-icon-wrapper {
            font-size: 1.6rem; /* Icon size */
            color: var(--primary-blue);
            background-color: var(--light-blue-bg); /* Light background for icon */
            border-radius: 8px;
            padding: 0.75rem;
            margin-right: 1rem;
            display: inline-flex;
            justify-content: center;
            align-items: center;
            width: 50px; /* Fixed size for icon wrapper */
            height: 50px;
        }
        .stat-item .stat-content .stat-number {
            font-size: 1.75rem;
            font-weight: 700;
            color: var(--text-dark);
            line-height: 1.2;
            display: block; /* Make it block to sit above label */
        }
        .stat-item .stat-content .stat-label {
            font-size: 0.85rem;
            color: var(--text-medium);
            font-weight: 500;
        }

        /* Quick Access - Redesigned */
        .quick-access-container {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 1.5rem;
            margin-bottom: 2.5rem;
        }
        .access-tile {
            background-color: var(--white-color);
            border-radius: 10px;
            padding: 1.5rem;
            text-decoration: none;
            color: var(--text-dark);
            box-shadow: 0 3px 10px rgba(0, 0, 0, 0.05);
            transition: all 0.25s ease-out;
            display: flex;
            flex-direction: column;
            border: 1px solid transparent; /* For hover effect */
        }
        .access-tile:hover {
            transform: translateY(-4px);
            box-shadow: 0 6px 18px rgba(74, 105, 189, 0.15);
            border-color: var(--primary-blue);
        }
        .access-tile-header {
            display: flex;
            align-items: center;
            margin-bottom: 0.75rem;
        }
        .access-tile .tile-icon {
            font-size: 1.5rem;
            color: var(--primary-blue);
            background-color: var(--light-blue-bg);
            border-radius: 8px;
            padding: 0.6rem;
            margin-right: 0.85rem;
            display: inline-flex;
            justify-content: center;
            align-items: center;
            width: 44px;
            height: 44px;
        }
        .access-tile .tile-title {
            font-size: 1.1rem;
            font-weight: 600;
            color: var(--text-dark);
        }
        .access-tile .tile-description {
            font-size: 0.875rem;
            color: var(--text-medium);
            line-height: 1.5;
            margin-bottom: 1rem;
            flex-grow: 1;
        }
        .access-tile .tile-link {
            font-size: 0.9rem;
            color: var(--primary-blue);
            font-weight: 500;
            text-decoration: none;
            align-self: flex-start; /* Align to the start */
            display: inline-flex; /* For icon alignment */
            align-items: center;
        }
        .access-tile .tile-link i {
            margin-left: 0.3rem;
            transition: transform 0.2s ease;
        }
        .access-tile:hover .tile-link i {
            transform: translateX(3px);
        }


        /* Activity Feed - Redesigned */
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
            color: var(--primary-blue);
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

        /* Responsive adjustments */
        @media (max-width: 991px) { /* Tablet */
            .stats-container {
                grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
            }
        }
        @media (max-width: 767px) { /* Mobile */
            .main-content-area { padding: 15px 20px; }
            .welcome-header { padding: 1.5rem; text-align: center; }
            .welcome-header h1 { font-size: 1.75rem; }
            .welcome-header p { font-size: 0.95rem; }

            .stats-container {
                grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); /* Full width for clarity */
                gap: 1rem;
            }
            .stat-item .stat-icon-wrapper { width: 45px; height: 45px; font-size: 1.4rem;}
            .stat-item .stat-content .stat-number { font-size: 1.5rem; }

            .quick-access-container {
                grid-template-columns: 1fr; /* Stack on smaller screens */
                gap: 1rem;
            }
            .access-tile { padding: 1.25rem; }

        }

    </style>
</head>
<body>

    <div class="main-content-area">
        <div class="container px-0"> <!-- No padding for container-fluid, handled by main-content-area -->

            <!-- 1. Welcome Header (using your blue style) -->
            <div class="welcome-header">
                <div class="row align-items-center">
                    <div class="col-md-9">
                        <h1>Bienvenue, Administrateur !</h1>
                        <p class="mb-0">Aperçu général du système universitaire et accès rapides aux fonctionnalités clés.</p>
                    </div>
                    <div class="col-md-3 text-center text-md-end d-none d-md-block">
                         <i class="fas fa-shield-alt fa-4x opacity-50"></i> <!-- Example different icon -->
                    </div>
                </div>
            </div>

            <!-- 2. Statistiques Clés -->
            <section id="statistiques" class="mb-4">
                <h2 class="section-heading">Aperçu Rapide</h2>
                <div class="stats-container">
                    <!-- Stat Item 1: Utilisateurs -->
                    <div class="stat-item">
                        <div class="stat-icon-wrapper"><i class="fas fa-users"></i></div>
                        <div class="stat-content">
                            <span class="stat-number" data-target="<?=  $data['allUsers_Active_count']?>">0</span>
                            <span class="stat-label">Utilisateurs Actifs</span>
                        </div>
                    </div>
                    <!-- Stat Item 2: Départements -->
                    <div class="stat-item">
                        <div class="stat-icon-wrapper"><i class="fas fa-building"></i></div>
                        <div class="stat-content">
                            <span class="stat-number" data-target="<?=  $data['departement_count']?>">0</span>
                            <span class="stat-label">Départements</span>
                        </div>
                    </div>
                    <!-- Stat Item 3: Filières -->
                    <div class="stat-item">
                        <div class="stat-icon-wrapper"><i class="fas fa-sitemap"></i></div>
                        <div class="stat-content">
                            <span class="stat-number" data-target="<?=  $data['filiere_count']?>">0</span>
                            <span class="stat-label">Filières</span>
                        </div>
                    </div>
                    <!-- Stat Item 4: Enseignants Permanents -->
                    <div class="stat-item">
                        <div class="stat-icon-wrapper"><i class="fas fa-chalkboard-teacher"></i></div>
                        <div class="stat-content">
                            <span class="stat-number" data-target="<?=  $data['prof_count']?>">0</span>
                            <span class="stat-label">Enseignants Permanents</span>
                        </div>
                    </div>
                    <!-- Stat Item 5: Enseignants Vacataires -->
                    <div class="stat-item">
                        <div class="stat-icon-wrapper"><i class="fas fa-user-clock"></i></div>
                        <div class="stat-content">
                            <span class="stat-number" data-target="<?=  $data['vacataire_count']?>">0</span>
                            <span class="stat-label">Enseignants Vacataires</span>
                        </div>
                    </div>
                    <!-- Stat Item 6: Demandes en attente -->
                    <div class="stat-item">
                        <div class="stat-icon-wrapper" style="color: #ffc107; background-color: #fff8e1;"><i class="fa-solid fa-user-large-slash"></i></div>
                        <div class="stat-content">
                            <span class="stat-number" data-target="<?=  $data['allUsers_Disabled_count']?>">0</span>
                            <span class="stat-label">Utilisateurs Désactivés</span>
                        </div>
                    </div>
                </div>
            </section>

            <!-- 3. Accès Rapides -->
            <section id="acces-rapides" class="mb-4">
                <h2 class="section-heading">Actions Principales</h2>
                <div class="quick-access-container">
                    <!-- Tile 1 -->
                    <a href="/e-service/internal/members/admin/AllUsers.php" class="access-tile">
                        <div class="access-tile-header">
                            <span class="tile-icon"><i class="fas fa-user-cog"></i></span>
                            <h5 class="tile-title mb-0">Gérer les Utilisateurs</h5>
                        </div>
                        <p class="tile-description">Ajouter, modifier, consulter les comptes et attribuer des rôles.</p>
                        <span class="tile-link mt-auto">Accéder <i class="fas fa-arrow-right"></i></span>
                    </a>
                    <!-- Tile 2 -->
                    <a href="/e-service/internal/members/admin/Departements.php" class="access-tile">
                        <div class="access-tile-header">
                            <span class="tile-icon"><i class="fas fa-building-user"></i></span>
                            <h5 class="tile-title mb-0">Gérer les Départements</h5>
                        </div>
                        <p class="tile-description">Créer et administrer les départements, assigner les chefs.</p>
                        <span class="tile-link mt-auto">Accéder <i class="fas fa-arrow-right"></i></span>
                    </a>
                    <!-- Tile 3 -->
                    <a href="/e-service/internal/members/admin/filieres.php?id_dep=1&filter=0" class="access-tile">
                        <div class="access-tile-header">
                            <span class="tile-icon"><i class="fas fa-book-reader"></i></span>
                            <h5 class="tile-title mb-0">Gérer les Filières</h5>
                        </div>
                        <p class="tile-description">Définir les filières, affecter les coordinateurs, gérer les modules.</p>
                        <span class="tile-link mt-auto">Accéder <i class="fas fa-arrow-right"></i></span>
                    </a>
                    <!-- Tile 4 -->
                    <a href="/e-service/internal/members/admin/createAnnounce.php" class="access-tile">
                        <div class="access-tile-header">
                            <span class="tile-icon"><i class="fas fa-bullhorn"></i></span>
                            <h5 class="tile-title mb-0">Annonces</h5>
                        </div>
                        <p class="tile-description">Publier des communications importantes pour le personnel et les étudiants.</p>
                        <span class="tile-link mt-auto">Gérer <i class="fas fa-arrow-right"></i></span>
                    </a>
                     <!-- Tile 6 -->
                    <a href="/e-service/internal/members/admin/newProfessor.php" class="access-tile">
                        <div class="access-tile-header">
                            <span class="tile-icon"><i class="fas fa-user-plus"></i></span>
                            <h5 class="tile-title mb-0">Ajouter Professeur</h5>
                        </div>
                        <p class="tile-description">Créer un nouveau compte professeur et définir ses attributions.</p>
                        <span class="tile-link mt-auto">Créer <i class="fas fa-arrow-right"></i></span>
                    </a>
                </div>
            </section>

            <!-- 4. Activité Récente -->
            <section id="activite-recente" class="mb-4">
                <h2 class="section-heading">Activité Récente</h2>
                <div class="activity-section">
                    <ul class="activity-list">
                    <?php foreach ($data['recent_activity'] as $activity): ?>
                        <li class="activity-item">
                            <i class="activity-icon fas <?= htmlspecialchars($activity["icon"]) ?>"></i>
                            <span class="activity-text"><?= htmlspecialchars($activity["content"]) ?></span>
                            <span class="activity-time"><?= htmlspecialchars($activity["create_time"]) ?></span>
                        </li>
                    <?php endforeach; ?>
                    
                    </ul>
                    <a href="/e-service/internal/members/admin/activities.php" class="view-all-link">Voir toute l'activité <i class="fas fa-angle-right"></i></a>
                </div>
            </section>

        </div> <!-- /.container-fluid -->
    </div> <!-- /.main-content-area -->

    <!-- Bootstrap JS Bundle (Popper.js included) -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Animated Number Counters for Stat Cards
            const counters = document.querySelectorAll('.stat-number');
            const speed = 100; // Adjust speed: lower is faster

            counters.forEach(counter => {
                const animate = () => {
                    const target = +counter.getAttribute('data-target');
                    let count = +counter.innerText.replace(/,/g, ''); // Remove commas if any

                    const inc = Math.ceil(target / speed);

                    if (count < target) {
                        counter.innerText = Math.min(count + inc, target);
                        setTimeout(animate, 15); // Adjust timing for smoother/faster animation
                    } else {
                        counter.innerText = target.toLocaleString('fr-FR'); // Format with French locale if needed
                    }
                }
                animate();
            });
        });
    </script>