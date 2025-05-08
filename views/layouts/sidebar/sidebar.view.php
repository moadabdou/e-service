<?php 
require $_SERVER['DOCUMENT_ROOT']."/e-service/core/resources.php";
?>
<aside class="left-sidebar">
    <!-- Sidebar scroll-->
    <div>
    <div class="brand-logo d-flex align-items-center justify-content-between px-3 py-2 shadow-sm bg-white rounded-1">
    <a href="#" class="d-flex align-items-center text-decoration-none">
        <img src="/e-service/storage/image/logo/logo.png" alt="E-Services Logo" class="logo-img me-2 rounded-circle" style="height: 50px; width: 50px; object-fit: cover;" />
        <span class="fs-5 fw-bold text-primary">E-Services</span>
    </a>
    <div class="close-btn d-xl-none d-block sidebartoggler cursor-pointer" id="sidebarCollapse">
        <i class="ti ti-x fs-4 text-secondary"></i>
    </div>
</div>

    <!-- Sidebar navigation-->
    <nav class="sidebar-nav scroll-sidebar" data-simplebar="">
        <ul id="sidebarnav">
            <?php foreach($nav  as  $section => $vals){?>
                <li class="nav-small-cap">
                    <i class="ti ti-dots nav-small-cap-icon fs-4"></i>
                    <span class="hide-menu"><?=htmlspecialchars($vals["title"])?></span>
                </li>
                <?php foreach($vals["menu"]  as  $key => $comps){?>
                    <li class="sidebar-item">
                        <a class="sidebar-link <?=($key == $active)? "active" :  ""?> " href="<?=htmlspecialchars($comps["url"])?>" aria-expanded="false">
                            <i class="<?=htmlspecialchars($comps["icon"])?>"></i>
                            <span class="hide-menu"><?=htmlspecialchars($comps["title"])?></span>
                        </a>
                    </li>
                <?php } ?>
            <?php } ?>
        </ul>
    </nav>
    <!-- End Sidebar navigation -->
    </div>
    <!-- End Sidebar scroll-->
</aside>
