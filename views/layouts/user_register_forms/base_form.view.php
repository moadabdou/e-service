<style>
    @media (min-width: 768px) {
        .form-inner>div:nth-child(2n+1) {
            padding-right: 10px;
        }
    }
</style>
<div class="row justify-content-center w-100">
    <div style="width: 80%;">
        <div class="card mb-0">
            <div class="card-body">
                <p class="text-center">professor's Social Campaigns</p>
                <?php if ($info) {?>
                    <div class="alert alert-<?= $info["type"] ?> text-center mb-5" role="alert">
                        <?= $info["msg"] ?>
                    </div>
                <?php }?>
                <form action="<?= $_SERVER["PHP_SELF"]?>" method="post">
                    <div class="form-inner d-flex flex-wrap justify-between">

                        <!-- First Name -->
                        <div class="mb-3 col-lg-6 col-xxl-6 col-md-6 col-sm-12 col-12">
                            <label for="first-name" class="form-label">First Name</label>
                            <input type="text" class="form-control <?= isset($errors["firstName"]) ? "is-invalid" : "" ?>" id="first-name" name="firstName" placeholder="first name" pattern="[a-zA-Z]+['\-]?[a-zA-Z]+" maxlength="30" required value="<?= isset($_POST['firstName']) ? $_POST['firstName'] : "" ?>">
                            <?php if (isset($errors["firstName"])) {?>
                                <div class="invalid-feedback"><?= $errors["firstName"] ?></div>
                            <?php }?>
                        </div>

                        <!-- Last Name -->
                        <div class="mb-3 col-lg-6 col-xxl-6 col-md-6 col-sm-12 col-12">
                            <label for="last-name" class="form-label">Last Name</label>
                            <input type="text" class="form-control <?= isset($errors["lastName"]) ? "is-invalid" : "" ?>" id="last-name" name="lastName" placeholder="last name" pattern="[a-zA-Z]+['\-]?[a-zA-Z]+" maxlength="30" required value="<?= isset($_POST['lastName']) ? $_POST['lastName'] : "" ?>">
                            <?php if (isset($errors["lastName"])) {?>
                                <div class="invalid-feedback"><?= $errors["lastName"] ?></div>
                            <?php }?>
                        </div>

                        <!-- Email -->
                        <div class="mb-3 col-lg-6 col-xxl-6 col-md-6 col-sm-12 col-12">
                            <label for="email" class="form-label">Email Address</label>
                            <input type="email" class="form-control <?= isset($errors["email"]) ? "is-invalid" : "" ?>" id="email" name="email" placeholder="email" maxlength="30" required value="<?= isset($_POST['email']) ? $_POST['email'] : "" ?>">
                            <?php if (isset($errors["email"])) {?>
                                <div class="invalid-feedback"><?= $errors["email"] ?></div>
                            <?php }?>
                        </div>

                        <!-- Phone -->
                        <div class="mb-3 col-lg-6 col-xxl-6 col-md-6 col-sm-12 col-12">
                            <label for="phone" class="form-label">Phone Number</label>
                            <input type="tel" class="form-control <?= isset($errors["phone"]) ? "is-invalid" : "" ?>" id="phone" name="phone" placeholder="0612345678" pattern="[0-9]{10}" required value="<?= isset($_POST['phone']) ? $_POST['phone'] : "" ?>">
                            <?php if (isset($errors["phone"])) {?>
                                <div class="invalid-feedback"><?= $errors["phone"] ?></div>
                            <?php }?>
                        </div>

                        <div class="w-0"></div>

                        <!-- Address -->
                        <div class="mb-3 w-100">
                            <label for="address" class="form-label">Address</label>
                            <input type="text" class="form-control <?= isset($errors["address"]) ? "is-invalid" : "" ?>" id="address" name="address" placeholder="address" maxlength="150" value="<?= isset($_POST['address']) ? $_POST['address'] : "" ?>">
                            <?php if (isset($errors["address"])) {?>
                                <div class="invalid-feedback"><?= $errors["address"] ?></div>
                            <?php }?>
                        </div>

                        <!-- CIN -->
                        <div class="mb-4 col-lg-6 col-xxl-6 col-md-6 col-sm-12 col-12">
                            <label for="CIN" class="form-label">CIN</label>
                            <input type="text" class="form-control <?= isset($errors["CIN"]) ? "is-invalid" : "" ?>" id="CIN" name="CIN" placeholder="CIN" pattern="[A-Z]{1,2}[0-9]{5,6}" value="<?= isset($_POST['CIN']) ? $_POST['CIN'] : "" ?>">
                            <?php if (isset($errors["CIN"])) {?>
                                <div class="invalid-feedback"><?= $errors["CIN"] ?></div>
                            <?php }?>
                        </div>

                        <!-- Birth Date -->
                        <div class="mb-4 col-lg-6 col-xxl-6 col-md-6 col-sm-12 col-12">
                            <label for="birth-date" class="form-label">Birth Date</label>
                            <input type="date" class="form-control <?= isset($errors["birth_date"]) ? "is-invalid" : "" ?>" id="birth-date" name="birth_date"
                                max="<?= date('Y-m-d', strtotime('-24 years')) ?>"
                                min="<?= date('Y-m-d', strtotime('-100 years')) ?>"
                                required
                                value="<?= isset($_POST['birth_date']) ? $_POST['birth_date'] : "" ?>">
                            <?php if (isset($errors["birth_date"])) {?>
                                <div class="invalid-feedback"><?= $errors["birth_date"] ?></div>
                            <?php }?>
                        </div>

                        <?= $role_based_fields ?>

                        <!-- Submit Button -->
                        <div class="w-100">
                            <button type="submit" class="btn btn-primary w-25 py-8 fs-4 mb-4 rounded-2 mx-auto d-block" style="max-width: 200px;">Register</button>
                        </div>
                    </div>
                </form>

            </div>
        </div>
    </div>
</div>