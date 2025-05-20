<?php

class SideBar
{

    private array $nav;
    private string $logo = "test";

    public function __construct(string $role)
    {

        $this->nav[$role] = self::$NAVIGATION[$role];
        if ($role === "professor/chef_deparetement" || $role === "professor/coordonnateur") {
            $this->nav["professor"] = self::$NAVIGATION["professor"];
        }

        $this->nav["General"] = self::$NAVIGATION["General"];
    }

    public function view(string $active)
    {
        $nav = $this->nav;
        ob_start();
        require __DIR__ . "/sidebar.view.php";
        $content = ob_get_clean();
        return $content;
    }

    private static array $NAVIGATION = [
        "General" => [
            "title" => "Generale",
            "menu" =>
            [
                "profile" => [
                    "title" => "Profile",
                    "icon" => "ti ti-user",
                    "url" => "/e-service/internal/members/common/profile.php"
                ],
                "logout" => [
                    "title" => "Déconnexion",
                    "icon" => "ti ti-power",
                    "url" => "/e-service/internal/members/common/logout.php"
                ]
            ]
        ],

        "admin" =>
        [
            "title" => "Administration",
            "menu" => [
                "main" => [
                    "title" => "Accueil",
                    "icon" => "ti ti-atom",
                    "url" => "/e-service/internal/members/admin"
                ],
                "newProfessor" => [
                    "title" => "Nouveau Professeur",
                    "icon" => "ti ti-user-plus",
                    "url" => "/e-service/internal/members/admin/newProfessor.php"
                ],
                "allUsers" => [
                    "title" => "Tous les Utilisateurs",
                    "icon" => "ti ti-users",
                    "url" => "/e-service/internal/members/admin/AllUsers.php"
                ],
                "deperatements" => [
                    "title" => "Départements",
                    "icon" => "ti ti-building",
                    "url" => "/e-service/internal/members/admin/Departements.php"
                ],
                "filieres" => [
                    "title" =>  "Filières",
                    "icon" => "ti ti-book",
                    "url" => "/e-service/internal/members/admin/filieres.php?id_dep=1&filter=0"
                ],
                "createAnnounce" => [
                    "title" => "Créer une annonce",
                    "icon" => "fas fa-bullhorn",
                    "url" => "/e-service/internal/members/admin/createAnnounce.php"
                ]
            ]
        ],
        "professor/chef_deparetement" =>
        [
            "title" => "Gérer Département",
            "menu" => [
                "main_chef" => [
                    "title" => "Accueil",
                    "icon" => "ti ti-atom",
                    "url" => "/e-service/internal/members/professor/chef_deparetement"
                ],
                "modules" => [
                    "title" => "Unités d’enseignement",
                    "icon" => "ti ti-book",
                    "url" => "/e-service/internal/members/professor/chef_deparetement/dep_units.php"
                ],
                "professors" => [
                    "title" => "Professeurs & Charges",
                    "icon" => "ti ti-users",
                    "url" => "/e-service/internal/members/professor/chef_deparetement/professors_list.php"
                ],
                "pendingModules" => [
                    "title" => "Affectation des modules",
                    "icon" => "ti ti-link",
                    "url" => "/e-service/internal/members/professor/chef_deparetement/assign_modules.php"
                ],
                "workload" => [
                    "title" => "Charge des professeurs",
                    "icon" => "ti ti-calendar-stats",
                    "url" => "/e-service/internal/members/professor/chef_deparetement/workload.php"
                ],
                "professorChoices" => [
                    "title" => "Choix des professeurs",
                    "icon" => "ti ti-list-check",
                    "url" => "/e-service/internal/members/professor/chef_deparetement/professor_module_choices.php"
                ],
                "vacantModules" => [
                    "title" => "Modules vacants",
                    "icon" => "ti ti-notebook",
                    "url" => "/e-service/internal/members/professor/chef_deparetement/vacant_modules.php"
                ],
                "yearHistory" => [
                    "title" => "Historique des années",
                    "icon" => "ti ti-calendar-stats",
                    "url" => "/e-service/internal/members/professor/chef_deparetement/history.php"
                ]

            ]
        ],

        "professor/coordonnateur" =>
        [
            "title" => "Gérer Filière",
            "menu" => [
                "main_coor" => [
                    "title" => "Accueil",
                    "icon" => "ti ti-atom",
                    "url" => "/e-service/internal/members/professor/coordonnateur"
                ],
                "ModuleListing" => [
                    "title" => "Liste Des Modules",
                    "icon" => "ti ti-book",
                    "url" => "/e-service/internal/members/professor/coordonnateur/liste_modules.php"
                ],
                "AjouterModule" => [
                    "title" => "Ajouter Des Modules",
                    "icon" => "ti ti-list",
                    "url" => "/e-service/internal/members/professor/coordonnateur/ajouterModule.php"
                ],
                "addVacataire" => [
                    "title" => "Ajouter Vacataire",
                    "icon" => "ti ti-user-plus",
                    "url" => "/e-service/internal/members/professor/coordonnateur/addVacataire.php"
                ],
                "affectations" => [
                    "title" => "Consulter affectations",
                    "icon" => "ti ti-link",
                    "url" => "/e-service/internal/members/professor/coordonnateur/affectations.php"
                ],
                "emploiTemps" => [
                    "title" => "Emploi du temps",
                    "icon" => "ti ti-calendar",
                    "url" => "/e-service/internal/members/professor/coordonnateur/emploi_temps.php"
                ],
            ]
        ],

        "professor" =>
        [
            "title" => "Panneau Professeur",
            "menu" => [
                "main" => [
                    "title" => "Accueil",
                    "icon" => "ti ti-atom",
                    "url" => "/e-service/internal/members/professor"
                ],
                "chooseUnits" => [
                    "title" => "Choisir Des Modules",
                    "icon" => "ti ti-book",
                    "url" => "/e-service/internal/members/professor/choose_units.php"
                ],
                "assignedModules" => [
                    "title" => "Mes modules affectéss",
                    "icon" => "ti ti-list-check",
                    "url" => "/e-service/internal/members/professor/AssignedModules.php"
                ],
                "UploadNotes" => [
                    "title" => "Uploader les notes",
                    "icon" => "ti ti-upload",
                    "url" => "/e-service/internal/members/professor/uploadNotes.php"
                ],
                "NotesHistory" => [
                    "title" => "Mes notes envoyées",
                    "icon" => "ti ti-files",
                    "url" => "/e-service/internal/members/professor/notes_history.php"
                ],
                "ProfHistory" => [
                    "title" => "Historique des années",
                    "icon" => "ti ti-calendar-stats",
                    "url" => "/e-service/internal/members/professor/historique.php"
                ]

            ]
        ],

        "vacataire" =>
        [
            "title" => "Panneau Vacataire",
            "menu" => [
                "main" => [
                    "title" => "Accueil",
                    "icon" => "ti ti-atom",
                    "url" => "/e-service/internal/members/vacataire"
                ]
            ]
        ]

    ];
}
