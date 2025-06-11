<?php 
require $_SERVER['DOCUMENT_ROOT']."/e-service/core/resources.php";
?>

<!doctype html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>E-Services Dashboard</title>
  <link rel="shortcut icon" type="image/png" href="/e-service/storage/image/logo/logo.png" />
  <link rel="stylesheet" href="<?=$RESOURCES_PATH?>/assets/css/styles.min.css" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" integrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
</head>
<style>
  nav i{
    padding-left: 5px;
  }
</style>
<body>
  <!--  Body Wrapper -->
  <div class="page-wrapper" id="main-wrapper" data-layout="vertical" data-navbarbg="skin6" data-sidebartype="full"
    data-sidebar-position="fixed" data-header-position="fixed">
    
    <!-- Sidebar Start -->
    <?= $sidebar_view ?>
    <!--  Sidebar End -->
    <!--  Main wrapper -->
    <div class="body-wrapper">
      <!--  Header Start -->
      <header class="app-header">
        <nav class="navbar navbar-expand-lg navbar-light">
          <ul class="navbar-nav">
            <li class="nav-item d-block d-xl-none">
              <a class="nav-link sidebartoggler " id="headerCollapse" href="javascript:void(0)">
                <i class="ti ti-menu-2"></i>
              </a>
            </li>
            <li class="nav-item">
              <a class="nav-link " href="/e-service/internal/members/common/notifications.php">
                <iconify-icon icon="solar:bell-linear" <?= $active=="notifications"? 'style="color: #000ea4"': "" ?> class="fs-6"></iconify-icon>
                <?php if($unread_notifications_count !== 0) {?> 
                  <div class="notification bg-primary rounded-circle"><?=htmlspecialchars($unread_notifications_count)?></div>
                <?php }?>
              </a>
            </li>
            <li class="nav-item">
              <a class="nav-link " href="/e-service/internal/members/common/announces.php">
                <i class="fas fa-bullhorn" style="    font-size: 17px;"></i>
              </a>
            </li>
          </ul>
          <div class="navbar-collapse justify-content-end px-0" id="navbarNav">
  <ul class="navbar-nav flex-row ms-auto align-items-center justify-content-end">
    <li class="nav-item dropdown">
      <a class="nav-link nav-profile d-flex align-items-center" href="javascript:void(0)" id="drop2" data-bs-toggle="dropdown"
        aria-expanded="false">
        <img src="<?= $user['img'] ?>" alt="Profile" width="38" height="38" class="rounded-circle border shadow-sm">
      </a>
      <div class="dropdown-menu dropdown-menu-end dropdown-menu-animate-up shadow-sm" aria-labelledby="drop2">
        <div class="py-2 px-1">
          <div class="d-flex align-items-center border-bottom pb-3 mb-2 px-3">
            <img src="<?= $user['img'] ?>" alt="" width="45" height="45" class="rounded-circle me-3">
            <div>
              <h6 class="mb-0 fw-semibold">Dr. <?= ucfirst($user['name']) ?></h6>
              <small class="text-muted"><?= ucfirst(string: $Role) ?></small>
            </div>
          </div>
          <a href="/e-service/internal/members/common/profile.php" class="d-flex align-items-center gap-2 dropdown-item py-2" style="color: #000ea4">
            <i class="ti ti-user fs-6"></i>
            <p class="mb-0 fs-3">Mon Profil</p>
          </a>
          <a href="/e-service/internal/members/common/notifications.php" class="d-flex align-items-center gap-2 dropdown-item py-2"  style="color: #000ea4">
            <iconify-icon icon="solar:bell-linear" <?= $active=="notifications"? : "" ?> class="fs-6"></iconify-icon>
            <p class="mb-0 fs-3">Notifications</p>
          </a>
          <div class="pt-2">
            <a href="/e-service/internal/members/common/logout.php" class="btn btn-outline-primary mx-3 mt-2 d-block">
              <i class="fas fa-sign-out-alt"></i> Déconnexion
            </a>
          </div>
        </div>
      </div>
    </li>
  </ul>
</div>
        </nav>
      </header>
      <!--  Header End -->
      <div class="body-wrapper-inner">
        <div class="container-fluid">
          <?= $content ?>
        </div>
      </div>
    </div>
  </div>
  <script src="<?=$RESOURCES_PATH?>/assets/libs/jquery/dist/jquery.min.js"></script>
  <script src="<?=$RESOURCES_PATH?>/assets/libs/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
  <script src="<?=$RESOURCES_PATH?>/assets/js/sidebarmenu.js"></script>
  <script src="<?=$RESOURCES_PATH?>/assets/js/app.min.js"></script>
  <script src="<?=$RESOURCES_PATH?>/assets/libs/simplebar/dist/simplebar.js"></script>
  <!-- solar icons -->
  <script src="https://cdn.jsdelivr.net/npm/iconify-icon@1.0.8/dist/iconify-icon.min.js"></script>
</body>

</html>