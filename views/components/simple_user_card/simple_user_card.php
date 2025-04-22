<?php 

class SimpleUserCard{

    public function view(int $id, string $profile_picture, string $full_name, string $join_date): string{
        ob_start();
        require __DIR__."/simple_user_card.view.php";
        $content = ob_get_clean();
        return $content;
    }

}

?>