<?php 

class MiniNav{

    private array $elements;
    private int $active;

    public function __construct(array $elements, int $active = 0)
    {
        $this->elements = $elements;
        $this->active = $active;

    }

    public function view(): string
    {

        $elements = $this->elements;
        $active = $this->active;

        ob_start();
        require __DIR__."/miniNavbar.view.php";
        $content = ob_get_clean();
        return $content;

    }

}


?>