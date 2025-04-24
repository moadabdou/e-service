<div class="mb-4 col-lg-6 col-xxl-6 col-md-6 col-sm-12 col-12">
    <label for="id_speciality" class="form-label">speciality</label>
    <select class="form-select <?= isset($errors["id_speciality"]) ? "is-invalid" : "" ?>" name="id_speciality" id="id_speciality">
    <?php foreach($specialities as $speciality){?>
        <option value="<?=$speciality["id_speciality"]?>"><?=$speciality["title"]?></option>
    <?php }?>
    </select>
    <?php if (isset($errors["id_speciality"])) {?>
        <div class="invalid-feedback">
            <?= $errors["id_speciality"] ?>
        </div>
    <?php }?>
</div>
<div class="mb-4 col-lg-6 col-xxl-6 col-md-6 col-sm-12 col-12">
    <label class="form-label">work hours</label>
    <div class="d-flex">
        <input type="number" class="form-control <?= isset($errors["min_hours"]) ? "is-invalid" : "" ?>" name="min_hours" id="min_hours" placeholder="minimum" style="margin-right:10px;" min="0">
        <?php if (isset($errors["min_hours"])) {?>
            <div class="invalid-feedback">
                <?= $errors["min_hours"] ?>
            </div>
        <?php }?>
        <input type="number" class="form-control <?= isset($errors["max_hours"]) ? "is-invalid" : "" ?>" name="max_hours" id="max_hours" placeholder="maximum" min="0">
        <?php if (isset($errors["max_hours"])) {?>
            <div class="invalid-feedback">
                <?= $errors["max_hours"] ?>
            </div>
        <?php }?>
    </div>
</div>