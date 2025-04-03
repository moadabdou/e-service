<?php 

class SideBar {

    private array $nav;
    private string $logo = "test";

    public function __construct(string $role)
    {
        switch($role) 
        {
            case "Admin":
                $this->nav["Administration"] = self::$NAVIGATION["Administration"];
                $this->nav["General"] = self::$NAVIGATION["General"];  
            break;
        }
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
            "Profile" => [
                "title" => "Profile", 
                "icon" => "ti-atom",
                "url" => "#"
            ],
            "Logout" => [
                "title" => "Logout", 
                "icon" => "ti-power",
                "url" => "#"
            ]
        ],
        "Administration" => [
            "main" => [
                "title" => "main", 
                "icon" => "ti-atom",
                "url" => "."
            ],
            "newProfessor" => [
                "title" => "new professor", 
                "icon" => "ti-user-plus",
                "url" => "newProfessor.php"
            ]
        ]
    ];

}

?>