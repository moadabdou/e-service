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
            "title" => "Generale", 
            "menu" => 
            [
                "profile" => [
                    "title" => "Profile", 
                    "icon" => "ti-user",
                    "url" => "/e-service/internal/members/common/profile.php"
                ],
                "logout" => [
                    "title" => "Logout", 
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
                    "title" => "main", 
                    "icon" => "ti-atom",
                    "url" => "/e-service/internal/members/admin"
                ],
                "newProfessor" => [
                    "title" => "new professor", 
                    "icon" => "ti-user-plus",
                    "url" => "/e-service/internal/members/admin/newProfessor.php"
                ]
            ]
        ],

        "professor/chef_deparetement" => 
        [
            "title"=> "gere deperetement",
            "menu" =>[
                "main" => [
                    "title" => "main", 
                    "icon" => "ti-atom",
                    "url" => "/e-service/internal/members/chef_deparetement"
                ]
            ]
        ],

        "professor/coordonnateur" => 
        [
            "title"=> "gere filiere",
            "menu" =>[
                "main" => [
                    "title" => "main", 
                    "icon" => "ti-atom",
                    "url" => "/e-service/internal/members/coordonnateur"
                ]
            ]
        ],

        "professor" => 
        [
            "title"=> "professor panel",
            "menu" =>[
                "main" => [
                    "title" => "main", 
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
            "title"=> "vacataire panel",
            "menu" =>[
                "main" => [
                    "title" => "main", 
                    "icon" => "ti-atom",
                    "url" => "/e-service/internal/members/vacataire"
                ]
            ]
        ]

    ];

}

?>