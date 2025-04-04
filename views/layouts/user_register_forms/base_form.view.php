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
                        <div class="mb-3 col-lg-6 col-xxl-6 col-md-6 col-sm-12 col-12">
                            <label for="first-name" class="form-label">first Name</label>
                            <input type="text" class="form-control <?= isset($errors["first_name"]) ? "is-invalid" : "" ?>" id="first-name" name="first_name" placeholder="first name"  pattern="[a-zA-Z]+['\-]?[a-zA-Z]+" maxlength="30" required>
                            <?php if (isset($errors["first_name"])) {?>
                                <div class="invalid-feedback">
                                    <?= $errors["first_name"] ?>
                                </div>
                            <?php }?>
                        </div>
                        <div class="mb-3 col-lg-6 col-xxl-6 col-md-6 col-sm-12 col-12">
                            <label for="last-name" class="form-label">last Name</label>
                            <input type="text" class="form-control <?= isset($errors["last_name"]) ? "is-invalid" : "" ?>" id="last-name" name="last_name" placeholder="last name" pattern="[a-zA-Z]+['\-]?[a-zA-Z]+" maxlength="30" required>
                            <?php if (isset($errors["last_name"])) {?>
                                <div class="invalid-feedback">
                                    <?= $errors["last_name"] ?>
                                </div>
                            <?php }?>
                        </div>
                        <div class="mb-3 col-lg-6 col-xxl-6 col-md-6 col-sm-12 col-12">
                            <label for="email" class="form-label">Email Address</label>
                            <input type="email" class="form-control <?= isset($errors["email"]) ? "is-invalid" : "" ?>" id="email" name="email" placeholder="email" maxlength="30" required>
                            <?php if (isset($errors["email"])) {?>
                                <div class="invalid-feedback">
                                    <?= $errors["email"] ?>
                                </div>
                            <?php }?>
                        </div>
                        <div class="mb-3 col-lg-6 col-xxl-6 col-md-6 col-sm-12 col-12">
                            <label for="phone" class="form-label">phone Number</label>
                            <input type="tel" class="form-control <?= isset($errors["phone"]) ? "is-invalid" : "" ?>" id="phone" name="phone" placeholder="0612345678" pattern="[0-9]{10}" required>
                            <?php if (isset($errors["phone"])) {?>
                                <div class="invalid-feedback">
                                    <?= $errors["phone"] ?>
                                </div>
                            <?php }?>
                        </div>
                        <div class="w-0"></div> <!-- this for  keeping the padding be  applyed  correctly -->
                        <div class="mb-3 w-100">
                            <label for="address" class="form-label">address</label>
                            <input type="text" class="form-control <?= isset($errors["address"]) ? "is-invalid" : "" ?>" id="address" name="address" placeholder="address" maxlength="150">
                            <?php if (isset($errors["address"])) {?>
                                <div class="invalid-feedback">
                                    <?= $errors["address"] ?>
                                </div>
                            <?php }?>
                        </div>
                        <div class="mb-4 col-lg-6 col-xxl-6 col-md-6 col-sm-12 col-12">
                            <label for="CIN" class="form-label">CIN</label>
                            <input type="text" class="form-control <?= isset($errors["cin"]) ? "is-invalid" : "" ?>" id="CIN" name="cin" placeholder="CIN" pattern="[A-Z]{1,2}[0-9]{5,6}">
                            <?php if (isset($errors["cin"])) {?>
                                <div class="invalid-feedback">
                                    <?= $errors["cin"] ?>
                                </div>
                            <?php }?>
                        </div>
                        <div class="mb-4 col-lg-6 col-xxl-6 col-md-6 col-sm-12 col-12">
                            <label for="birth-date" class="form-label">birth date</label>
                            <input type="date" class="form-control <?= isset($errors["birth_date"]) ? "is-invalid" : "" ?>" id="birth-date" name="birth_date" placeholder="birth-date"
                                max="<?= date('Y-m-d', strtotime('-24 years')) ?>"
                                min="<?= date('Y-m-d', strtotime('-100 years')) ?>"
                                required>
                            <?php if (isset($errors["birth_date"])) {?>
                                <div class="invalid-feedback">
                                    <?= $errors["birth_date"] ?>
                                </div>
                            <?php }?>
                        </div>
                        <?= $role_based_fields ?>
                        <div class="w-100">
                            <button type="submit" class="btn btn-primary w-25 py-8 fs-4 mb-4 rounded-2 mx-auto d-block" style="max-width: 200px;">register</a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>