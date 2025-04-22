<!-- Start of Add Department Form -->
<div class="card shadow-sm">
  <div class="card-header bg-light">
    <h5 class="mb-0">Ajouter un nouveau département</h5>
  </div>
  <div class="card-body">
    <!-- 
      Replace 'process_add_department.php' with the actual path 
      to your server-side script that handles the form submission.
    -->
    <form action="process_add_department.php" method="POST">

      <!-- Department Title Field -->
      <div class="mb-3">
        <label for="departmentTitle" class="form-label">Titre du département <span class="text-danger">*</span></label>
        <input
          type="text"
          class="form-control"
          id="departmentTitle"
          name="title"
          placeholder="Ex: Informatique, Ressources Humaines"
          required
          maxlength="50"
          aria-describedby="titleHelp"
        />
        <div id="titleHelp" class="form-text">
          Le nom principal du département (max 50 caractères).
        </div>
         <!-- Optional: Add div for validation feedback -->
         <!-- <div class="invalid-feedback">Veuillez fournir un titre.</div> -->
      </div>

      <!-- Department Description Field -->
      <div class="mb-4">
        <label for="departmentDescription" class="form-label">Description</label>
        <textarea
          class="form-control"
          id="departmentDescription"
          name="description"
          rows="4"
          placeholder="Décrivez brièvement le rôle ou la fonction de ce département..."
          maxlength="400"
          aria-describedby="descriptionHelp"
        ></textarea>
         <div id="descriptionHelp" class="form-text">
          Une description plus détaillée (optionnel, max 400 caractères).
        </div>
         <!-- Optional: Add div for validation feedback -->
         <!-- <div class="invalid-feedback">La description est trop longue.</div> -->
      </div>

      <!-- Action Buttons -->
      <div class="d-flex justify-content-end gap-2">
         <!-- Optional: Add a Cancel button/link -->
         <a href="departements.php" class="btn btn-secondary">Annuler</a> 
         <!-- Replace departements.php with the actual page to return to -->
        
        <button type="submit" class="btn btn-primary">
           <i class="ti ti-plus me-1"></i> Ajouter le département
        </button>
      </div>

    </form>
  </div> <!-- End card-body -->
</div> <!-- End card -->
<!-- End of Add Department Form -->