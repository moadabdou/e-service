<?php 
require_once __DIR__."../../../layouts/sidebar/sidebar.php";


class DashBoard{

    public function view(string $role, string $side_bar_active, string $content){
        $sidebar = new SideBar($role);
        $sidebar_view = $sidebar->view($side_bar_active);
        require __DIR__."/dashboard.view.php";
    }

}


?>