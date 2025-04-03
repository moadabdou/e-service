<?php 
require $_SERVER['DOCUMENT_ROOT']."/e-service/core/resources.php";
?>
<aside class="left-sidebar">
    <!-- Sidebar scroll-->
    <div>
    <div class="brand-logo d-flex align-items-center justify-content-between">
        <a href="./index.html" class="text-nowrap logo-img">
        <img src="<?=$RESOURCES_PATH?>/assets/images/logos/logo.svg" alt="" />
        </a>
        <div class="close-btn d-xl-none d-block sidebartoggler cursor-pointer" id="sidebarCollapse">
        <i class="ti ti-x fs-6"></i>
        </div>
    </div>
    <!-- Sidebar navigation-->
    <nav class="sidebar-nav scroll-sidebar" data-simplebar="">
        <ul id="sidebarnav">
            <?php foreach($nav  as  $section => $vals){?>
                <li class="nav-small-cap">
                    <i class="ti ti-dots nav-small-cap-icon fs-4"></i>
                    <span class="hide-menu"><?=htmlspecialchars($section)?></span>
                </li>
                <?php foreach($vals  as  $key => $comps){?>
                    <li class="sidebar-item">
                        <a class="sidebar-link <?=($key == $active)? "active" :  ""?> " href="<?=htmlspecialchars($comps["url"])?>" aria-expanded="false">
                            <i class="ti <?=htmlspecialchars($comps["icon"])?>"></i>
                            <span class="hide-menu"><?=htmlspecialchars($comps["title"])?></span>
                        </a>
                    </li>
                <?php } ?>
            <?php } ?>
        </ul>
        <div class="unlimited-access hide-menu bg-light-secondary position-relative mb-7 mt-5 rounded">
        <div class="d-flex">
            <div class="unlimited-access-title me-3">
            <h6 class="fw-semibold fs-4 mb-6 text-dark w-85">Check Pro Version</h6>
            <a href="https://adminmart.com/product/modernize-bootstrap-5-admin-template/?ref=56" target="_blank"
                class="btn btn-secondary fs-2 fw-semibold">Check</a>
            </div>
            <div class="unlimited-access-img">
            <img src="<?=$RESOURCES_PATH?>/assets/images/backgrounds/rocket.png" alt="" class="img-fluid">
            </div>
        </div>
        </div>
    </nav>
    <!-- End Sidebar navigation -->
    </div>
    <!-- End Sidebar scroll-->
</aside>
