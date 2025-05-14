<div class="mb-4 col-lg-6 col-xxl-6 col-md-6 col-sm-12 col-12">
    <label for="id_speciality" class="form-label">Speciality</label>
    <select class="form-select <?= isset($errors["id_speciality"]) ? "is-invalid" : "" ?>" name="id_speciality" id="id_speciality">
        <?php foreach ($specialities as $speciality) { ?>
            <option value="<?= $speciality["id_speciality"] ?>"
                <?= isset($_POST['id_speciality']) && $_POST['id_speciality'] == $speciality["id_speciality"] ? "selected" : "" ?>>
                <?= $speciality["title"] ?>
            </option>
        <?php } ?>
    </select>
    <?php if (isset($errors["id_speciality"])) { ?>
        <div class="invalid-feedback">
            <?= $errors["id_speciality"] ?>
        </div>
    <?php } ?>
</div>
