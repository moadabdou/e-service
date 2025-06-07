<?php
require_once __DIR__ . "../../../layouts/sidebar/sidebar.php";
require_once $_SERVER['DOCUMENT_ROOT'] . "/e-service/models/content/notification.php";
require_once $_SERVER['DOCUMENT_ROOT'] . "/e-service/models/entity/user.php";


class DashBoard
{

    public function view(string $role, string $active, string $content)
    {
        $user = (new UserModel())->getUser($_SESSION['id_user']);
        $Role = getRole($role);
        $sidebar = new SideBar($role);
        $sidebar_view = $sidebar->view($active);
        $notificatioModel = new NotificationModel();
        $unread_notifications_count = $notificatioModel->getUnreadNotificationCountByUserId($_SESSION["id_user"]);
        require __DIR__ . "/dashboard.view.php";
    }
}
function getRole(string $role): string
{
    $roles = [
        "admin" => "Administrateur",
        "professor" => "Professeur",
        "professor/chef_deparetement" => "Chef de Département",
        "professor/coordonnateur" => "Coordonnateur",
        "vacataire" => "Vacataire",
        "general" => "Général"
    ];

    return $roles[$role] ?? "Utilisateur";
}
