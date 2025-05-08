<!-- Start of Add Filiere Form -->
<div class="card shadow-sm p-3">
    <?php if ($info) {?>
        <div class="alert alert-<?= $info["type"] ?> text-center mb-3 w-50 mx-auto " role="alert">
            <?= $info["msg"] ?>
        </div>
    <?php }?>
  <div class="card-header bg-light">
    <h5 class="mb-0">Ajouter une nouvelle filière</h5>
  </div>
  <div class="card-body">
    <form action="<?= htmlspecialchars($_SERVER["PHP_SELF"]) ?>?id_dep=<?= $dep_id ?>&filter=1" method="POST">
      
      <!-- Filiere Title Field -->
      <div class="mb-3">
        <label for="filiereTitle" class="form-label">Titre de la filière <span class="text-danger">*</span></label>
        <input
          type="text"
          class="form-control"
          id="filiereTitle"
          name="title"
          placeholder="Ex: Génie Logiciel, Réseaux et Télécommunications"
          required
          maxlength="50"
          aria-describedby="titleHelp"
        />
        <div id="titleHelp" class="form-text">
          Le nom principal de la filière (max 50 caractères).
        </div>
      </div>

      <!-- Filiere Description Field -->
      <div class="mb-4">
        <label for="filiereDescription" class="form-label">Description</label>
        <textarea
          class="form-control"
          id="filiereDescription"
          name="description"
          rows="4"
          placeholder="Décrivez brièvement les objectifs et le contenu de cette filière..."
          maxlength="400"
          aria-describedby="descriptionHelp"
        ></textarea>
         <div id="descriptionHelp" class="form-text">
          Une description plus détaillée (min 200 , max 400 caractères).
        </div>
      </div>

      <!-- Action Buttons -->
      <div class="d-flex justify-content-end gap-2">
         <a href="<?= htmlspecialchars($_SERVER["PHP_SELF"]) ?>?id_dep=<?= $dep_id ?>" class="btn btn-secondary">Annuler</a>
        <button type="submit" class="btn btn-primary">
           <i class="ti ti-plus me-1"></i> Ajouter la filière
        </button>
      </div>
    </form>
  </div>
</div>
<!-- End of Add Filiere Form -->
