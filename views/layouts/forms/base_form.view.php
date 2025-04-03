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
                <form>
                    <div class="form-inner d-flex flex-wrap justify-between">
                        <div class="mb-3 col-lg-6 col-xxl-6 col-md-6 col-sm-12 col-12">
                            <label for="first-name" class="form-label">first Name</label>
                            <input type="text" class="form-control" id="first-name" placeholder="your first name" pattern="[a-zA-Z]+['\-]?[a-zA-Z]+" maxlength="30" required>
                        </div>
                        <div class="mb-3 col-lg-6 col-xxl-6 col-md-6 col-sm-12 col-12">
                            <label for="last-name" class="form-label">last Name</label>
                            <input type="text" class="form-control" id="last-name" placeholder="your last name" pattern="[a-zA-Z]+['\-]?[a-zA-Z]+" maxlength="30" required>
                        </div>
                        <div class="mb-3 col-lg-6 col-xxl-6 col-md-6 col-sm-12 col-12">
                            <label for="email" class="form-label">Email Address</label>
                            <input type="email" class="form-control" id="email" placeholder="your email" maxlength="30" required>
                        </div>
                        <div class="mb-3 col-lg-6 col-xxl-6 col-md-6 col-sm-12 col-12">
                            <label for="phone" class="form-label">phone Number</label>
                            <input type="tel" class="form-control" id="phone" placeholder="+123-45-678" pattern="+[0-9]{3}-[0-9]{2}-[0-9]{3}" required>
                        </div>
                        <div class="w-0"></div> <!-- this for  keeping the padding be  applyed  correctly -->
                        <div class="mb-3 w-100">
                            <label for="address" class="form-label">address</label>
                            <input type="text" class="form-control" id="address" placeholder="your address" maxlength="150">
                        </div>
                        <div class="mb-4 col-lg-6 col-xxl-6 col-md-6 col-sm-12 col-12">
                            <label for="CIN" class="form-label">CIN</label>
                            <input type="text" class="form-control" id="CIN" placeholder="your CIN" pattern="[A-Z]{1,2}[0-9]{5,6}">
                        </div>
                        <div class="mb-4 col-lg-6 col-xxl-6 col-md-6 col-sm-12 col-12">
                            <label for="birth-date" class="form-label">birth date</label>
                            <input type="date" class="form-control" id="birth-date" placeholder="your birth-date"
                                max="<?= date('Y-m-d', strtotime('-24 years')) ?>"
                                min="<?= date('Y-m-d', strtotime('-100 years')) ?>"
                                required>
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