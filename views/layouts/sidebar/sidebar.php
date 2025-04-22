<?php 

class SideBar {

    private array $nav;
    private string $logo = "test";

    public function __construct(string $role)
    {
        $this->nav[$role] = self::$NAVIGATION[$role];
        $this->nav["General"] = self::$NAVIGATION["General"];  
    }

    public function view(string $active)
    {
        $nav = $this->nav;
        ob_start();
        require __DIR__."/sidebar.view.php";
        $content = ob_get_clean();
        return $content;
    }

    private static array $NAVIGATION =[
        "General" => [
            "title" => "Générale", 
            "menu" => 
            [
                "profile" => [
                    "title" => "Profil", 
                    "icon" => "ti-user",
                    "url" => "/e-service/internal/members/common/profile.php"
                ],
                "logout" => [
                    "title" => "Déconnexion", 
                    "icon" => "ti-power",
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
                    "icon" => "ti-atom",
                    "url" => "/e-service/internal/members/admin"
                ],
                "newProfessor" => [
                    "title" => "Nouveau Professeur", 
                    "icon" => "ti-user-plus",
                    "url" => "/e-service/internal/members/admin/newProfessor.php"
                ],
                "allUsers" => [
                    "title" => "Tous les Utilisateurs",
                    "icon" => "ti-users",
                    "url" => "/e-service/internal/members/admin/AllUsers.php"
                ],
                "deperatements"=>[
                    "title" => "Départements",
                    "icon" => "ti-building",
                    "url" => "/e-service/internal/members/admin/Departements.php"
                ] 
            ]
        ],

        "professor/chef_deparetement" => 
        [
            "title"=> "Gérer Département",
            "menu" =>[
                "main" => [
                    "title" => "Accueil", 
                    "icon" => "ti-atom",
                    "url" => "/e-service/internal/members/professor/chef_deparetement"
                ],        "modules" => [
                    "title" => "Unités d’enseignement",
                    "icon" => "ti-book",
                    "url" => "/e-service/internal/members/professor/chef_deparetement/dep_units.php"
                ],
                "professors" => [
                    "title" => "Professeurs & Charges",
                    "icon" => "ti-users",
                    "url" => "/e-service/internal/members/professor/chef_deparetement/professors_list.php"
                ],
                "pendingModules" => [
                    "title" => "Affectation des modules",
                    "icon" => "ti-link",
                    "url" => "/e-service/internal/members/professor/chef_deparetement/assign_modules.php"
                ]
            ]
        ],

        "professor/coordonnateur" => 
        [
            "title"=> "Gérer Filière",
            "menu" =>[
                "main" => [
                    "title" => "Accueil", 
                    "icon" => "ti-atom",
                    "url" => "/e-service/internal/members/coordonnateur"
                ]
            ]
        ],

        "professor" => 
        [
            "title"=> "Panneau Professeur",
            "menu" =>[
                "main" => [
                    "title" => "Accueil", 
                    "icon" => "ti-atom",
                    "url" => "/e-service/internal/members/professor"
                ],
                "chooseUnits" => [
                    "title" => "Choisir Des Modules", 
                    "icon" => "ti-book",
                    "url" => "/e-service/internal/members/professor/choose_units.php"
                ],
                "assignedModules" => [
                    "title" => "Mes modules affectéss", 
                    "icon" => "ti-list-check",
                    "url" => "/e-service/internal/members/professor/AssignedModules.php"
                ],
                "UploadNotes" => [
                    "title" => "Uploader les notes", 
                    "icon" => "ti-upload",
                    "url" => "/e-service/internal/members/professor/UploadNotes.php"
                ]

            ]
        ],

        "vacataire" => 
        [
            "title"=> "Panneau Vacataire",
            "menu" =>[
                "main" => [
                    "title" => "Accueil", 
                    "icon" => "ti-atom",
                    "url" => "/e-service/internal/members/vacataire"
                ]
            ]
        ]

    ];

}

?>