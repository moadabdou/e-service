<div class="mb-4 col-lg-6 col-xxl-6 col-md-6 col-sm-12 col-12">
    <label for="departement" class="form-label">departement</label>
    <select class="form-select">
    <?php foreach($departements as $departement){?>
        <option value="<?=$departement["id_deparetement"]?>"><?=$departement["title"]?></option>
    <?php }?>
    </select>
</div>
<div class="mb-4 col-lg-6 col-xxl-6 col-md-6 col-sm-12 col-12">
    <label class="form-label">work hours</label>
    <div class="d-flex">
        <input type="number" class="form-control" placeholder="minimum" style="margin-right:10px;" min="0">
        <input type="number" class="form-control" placeholder="maximum" min="0">
    </div>
</div>