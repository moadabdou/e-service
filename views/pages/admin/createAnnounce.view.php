<!-- Start of Add Announcement Form -->
<div class="card shadow-sm p-3">
    <?php if ($info) {?>
        <div class="alert alert-<?= $info["type"] ?> text-center mb-3 w-50 mx-auto " role="alert">
            <?= $info["msg"] ?>
        </div>
    <?php }?>
  <div class="card-header bg-light">
    <h5 class="mb-0">Ajouter une nouvelle annonce</h5>
  </div>
  <div class="card-body">
    <form action="<?= htmlspecialchars($_SERVER["PHP_SELF"]) ?>" method="POST">
      
      <!-- Announcement Title Field -->
      <div class="mb-3">
        <label for="announceTitle" class="form-label">Titre de l'annonce <span class="text-danger">*</span></label>
        <input
          type="text"
          class="form-control"
          id="announceTitle"
          name="title"
          placeholder="Ex: Réunion importante, Changement d'horaires"
          required
          maxlength="50"
          aria-describedby="titleHelp"
        />
        <div id="titleHelp" class="form-text">
          Le titre de l'annonce (max 50 caractères).
        </div>
      </div>

      <!-- Announcement Content Field -->
      <div class="mb-4">
        <label for="announceContent" class="form-label">Contenu de l'annonce</label>
        <textarea
          class="form-control"
          id="announceContent"
          name="content"
          rows="4"
          placeholder="Écrivez le contenu de votre annonce..."
          maxlength="400"
          aria-describedby="contentHelp"
        ></textarea>
         <div id="contentHelp" class="form-text">
          Le contenu détaillé de l'annonce (min 200, max 400 caractères).
        </div>
      </div>

      <!-- Action Buttons -->
      <div class="d-flex justify-content-end gap-2">
        <button type="submit" class="btn btn-primary">
           <i class="ti ti-plus me-1"></i> Publier l'annonce
        </button>
      </div>
    </form>
  </div>
</div>
<!-- End of Add Announcement Form -->
