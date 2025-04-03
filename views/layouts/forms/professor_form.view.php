<div class="mb-4 col-lg-6 col-xxl-6 col-md-6 col-sm-12 col-12">
    <label for="departement" class="form-label">departement</label>
    <select class="form-select <?= isset($errors["departement"]) ? "is-invalid" : "" ?>" name="departement" id="departement">
    <?php foreach($departements as $departement){?>
        <option value="<?=$departement["id_deparetement"]?>"><?=$departement["title"]?></option>
    <?php }?>
    </select>
    <?php if (isset($errors["departement"])) {?>
        <div class="invalid-feedback">
            <?= $errors["departement"] ?>
        </div>
    <?php }?>
</div>
<div class="mb-4 col-lg-6 col-xxl-6 col-md-6 col-sm-12 col-12">
    <label class="form-label">work hours</label>
    <div class="d-flex">
        <input type="number" class="form-control <?= isset($errors["work_hours_min"]) ? "is-invalid" : "" ?>" name="work_hours_min" id="work_hours_min" placeholder="minimum" style="margin-right:10px;" min="0">
        <?php if (isset($errors["work_hours_min"])) {?>
            <div class="invalid-feedback">
                <?= $errors["work_hours_min"] ?>
            </div>
        <?php }?>
        <input type="number" class="form-control <?= isset($errors["work_hours_max"]) ? "is-invalid" : "" ?>" name="work_hours_max" id="work_hours_max" placeholder="maximum" min="0">
        <?php if (isset($errors["work_hours_max"])) {?>
            <div class="invalid-feedback">
                <?= $errors["work_hours_max"] ?>
            </div>
        <?php }?>
    </div>
</div>