<?php 

class SideBar {

    private array $nav;
    private string $logo = "test";

    public function __construct(string $role)
    {
        switch($role) {
            case "Admin":
                $this->nav["Admin"] = self::$NAVIGATION["Admin"];
                $this->nav["User"] = self::$NAVIGATION["User"];  
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
        "User" => [
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
        "Admin" => [
            "AllUsers" => [
                "title" => "All Users", 
                "icon" => "ti-atom",
                "url" => "."
            ],
            "NewUsers" => [
                "title" => "New Users", 
                "icon" => "ti-power",
                "url" => "newUsers.php"
            ]
        ]
    ];

}

?>