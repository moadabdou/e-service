<?php 
require_once $_SERVER['DOCUMENT_ROOT']."/e-service/core/resources.php";
?>

<!doctype html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>eservice  login</title>
  <link rel="shortcut icon" type="image/png" href="<?=$RESOURCES_PATH?>/assets/images/logos/favicon.png" />
  <link rel="stylesheet" href="<?=$RESOURCES_PATH?>/assets/css/styles.min.css" />
</head>

<body>
  <!--  Body Wrapper -->
  <div class="page-wrapper" id="main-wrapper" data-layout="vertical" data-navbarbg="skin6" data-sidebartype="full"
    data-sidebar-position="fixed" data-header-position="fixed">
    <div
      class="position-relative overflow-hidden text-bg-light min-vh-100 d-flex align-items-center justify-content-center">
      <div class="d-flex align-items-center justify-content-center w-100">
        <div class="row justify-content-center w-100">
          <div class="col-md-8 col-lg-6 col-xxl-3">
            <div class="card mb-0">
              <div class="card-body">
                <p class="text-center">Your Social Campaigns</p>
                <?php if ($info) {?>
                    <div class="alert alert-<?= $info["type"] ?> text-center mb-5" role="alert">
                        <?= $info["msg"] ?>
                    </div>
                <?php }?>
                <form class="needs-validation" action="<?= $_SERVER["PHP_SELF"]?>" method="post">
                  <div class="mb-3">
                    <label for="email" class="form-label">your email</label>
                    <input type="email" class="form-control <?= isset($errors["email"]) ||  isset($errors["invalid"]) ? "is-invalid" : ""?>" id="email" name="email" required>
                    <?php if (isset($errors["email"])) {?>
                        <div class="invalid-feedback">
                            <?= $errors["email"] ?>
                        </div>
                    <?php }?>
                  </div>
                  <div class="mb-4">
                    <label for="password" class="form-label">Password</label>
                    <input type="password" class="form-control <?= isset($errors["invalid"]) ? "is-invalid" : ""?>" id="password" name="password" required>
                  </div>
                  <div class="d-flex align-items-center justify-content-between mb-4">
                    <div class="form-check">
                      <input class="form-check-input primary" type="checkbox" value="" id="flexCheckChecked" checked>
                      <label class="form-check-label text-dark" for="flexCheckChecked">
                        Remeber this Device
                      </label>
                    </div>
                    <a class="text-primary fw-bold" href="./index.html">Forgot Password ?</a>
                  </div>
                  <button type="submit" class="btn btn-primary w-100 py-8 fs-4 mb-4 rounded-2">Sign In</button>
                </form>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  <script src="<?=$RESOURCES_PATH?>/assets/libs/jquery/dist/jquery.min.js"></script>
  <script src="<?=$RESOURCES_PATH?>/assets/libs/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
  <!-- solar icons -->
  <script src="https://cdn.jsdelivr.net/npm/iconify-icon@1.0.8/dist/iconify-icon.min.js"></script>
</body>

</html>