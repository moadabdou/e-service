<link rel="stylesheet" href="/e-service/resources/assets/css/addunit.css">

  <div class="container mt-2 px-4 px-md-5">
    <div class="row justify-content-center">
      <div class="col">
        <!-- Header -->
        <div class="d-flex justify-content-between align-items-center flex-wrap mb-4">
          <h2 class="fw-bold text-primary mb-0">
            <i class="ti ti-book-2"></i>
            Ajouter un Module
          </h2>
        </div>
        
        <!-- Success Message -->
        <?php if (isset($success) && is_array($success)): ?>
        <div class="alert alert-success alert-dismissible fade show rounded-4 shadow-sm border-start border-4 border-success mb-4 fade-in" role="alert">
          <div class="d-flex align-items-center">
            <i class="ti ti-circle-check me-2 fs-4"></i>
            <div><?= htmlspecialchars($success['msg']) ?></div>
          </div>
          <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        <?php endif; ?>
        
        <!-- System Error Message -->
        <?php if (isset($errors['system'])): ?>
        <div class="alert alert-danger alert-dismissible fade show rounded-4 shadow-sm border-start border-4 border-danger mb-4 fade-in" role="alert">
          <div class="d-flex align-items-center">
            <i class="ti ti-alert-circle me-2 fs-4"></i>
            <div>
              <strong>Erreur système :</strong> <?= htmlspecialchars($errors['system']) ?>
              <p class="mb-0 mt-1">Veuillez contacter l'administrateur si le problème persiste.</p>
            </div>
          </div>
          <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        <?php endif; ?>
        
        <!-- Database Error Message -->
        <?php if (isset($errors['database'])): ?>
        <div class="alert alert-danger alert-dismissible fade show rounded-4 shadow-sm border-start border-4 border-danger mb-4 fade-in" role="alert">
          <div class="d-flex align-items-center">
            <i class="ti ti-database-off me-2 fs-4"></i>
            <div>
              <strong>Erreur de base de données :</strong> <?= htmlspecialchars($errors['database']) ?>
            </div>
          </div>
          <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        <?php endif; ?>
        
        <!-- No Filière Error -->
        <?php if (isset($errors['filiere']) && $errors['filiere'] === "Aucune filière trouvée pour ce département. Veuillez d'abord créer une filière."): ?>
        <div class="alert alert-warning alert-dismissible fade show rounded-4 shadow-sm border-start border-4 border-warning mb-4 fade-in" role="alert">
          <div class="d-flex align-items-center">
            <i class="ti ti-alert-triangle me-2 fs-4"></i>
            <div>
              <strong>Attention :</strong> <?= htmlspecialchars($errors['filiere']) ?>
              <div class="mt-2">
                <a href="#" class="btn btn-sm btn-warning rounded-pill">
                  <i class="ti ti-plus me-1"></i> Créer une filière
                </a>
              </div>
            </div>
          </div>
          <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        <?php endif; ?>
        
        <!-- Form Card -->
        <div class="card shadow-sm border-0 rounded-4 mb-4">
          <div class="card-header bg-transparent">
            <h4 class="fw-bold mb-0">
              <i class="ti ti-file-plus text-primary me-2"></i>
              Informations du module
            </h4>
          </div>
          <div class="card-body p-4">
            <form method="POST" class="needs-validation" >
              <!-- Title and Description -->
              <div class="form-group">
                <label for="title" class="form-label">Titre <span class="text-danger">*</span></label>
                <input type="text" 
                       class="form-control form-control-lg rounded-3 <?= isset($errors['title']) ? 'is-invalid' : '' ?>" 
                       id="title" 
                       name="title" 
                       value="<?= htmlspecialchars($_POST['title'] ?? '') ?>"
                       required>
                <?php if (isset($errors['title'])): ?>
                  <div class="invalid-feedback"><?= htmlspecialchars($errors['title']) ?></div>
                <?php endif; ?>
              </div>
              
              <div class="form-group">
                <label for="description" class="form-label">Description</label>
                <textarea class="form-control rounded-3 <?= isset($errors['description']) ? 'is-invalid' : '' ?>" 
                          id="description" 
                          name="description" 
                          rows="4"><?= htmlspecialchars($_POST['description'] ?? '') ?></textarea>
                <?php if (isset($errors['description'])): ?>
                  <div class="invalid-feedback"><?= htmlspecialchars($errors['description']) ?></div>
                <?php endif; ?>
              </div>
              
              <!-- Volume Hours -->
              <div class="form-group">
                <label class="form-label">Volumes horaires</label>
                <div class="module-volumes">
                  <div class="card rounded-3 border-0 shadow-sm">
                    <div class="card-body p-3">
                      <div class="d-flex align-items-center gap-3 mb-2">
                        <div class="icon-circle bg-primary-subtle text-primary">
                          <i class="ti ti-presentation"></i>
                        </div>
                        <label for="volume_cours" class="form-label mb-0">Volume Cours</label>
                      </div>
                      <input type="number" 
                             class="form-control rounded-3 <?= isset($errors['volume_cours']) ? 'is-invalid' : '' ?>" 
                             id="volume_cours" 
                             name="volume_cours" 
                             value="<?= htmlspecialchars($_POST['volume_cours'] ?? '0') ?>" 
                             min="0">
                      <?php if (isset($errors['volume_cours'])): ?>
                        <div class="invalid-feedback"><?= htmlspecialchars($errors['volume_cours']) ?></div>
                      <?php endif; ?>
                    </div>
                  </div>
                  
                  <div class="card rounded-3 border-0 shadow-sm">
                    <div class="card-body p-3">
                      <div class="d-flex align-items-center gap-3 mb-2">
                        <div class="icon-circle bg-info-subtle text-info">
                          <i class="ti ti-clipboard"></i>
                        </div>
                        <label for="volume_td" class="form-label mb-0">Volume TD</label>
                      </div>
                      <input type="number" 
                             class="form-control rounded-3 <?= isset($errors['volume_td']) ? 'is-invalid' : '' ?>" 
                             id="volume_td" 
                             name="volume_td" 
                             value="<?= htmlspecialchars($_POST['volume_td'] ?? '0') ?>" 
                             min="0">
                      <?php if (isset($errors['volume_td'])): ?>
                        <div class="invalid-feedback"><?= htmlspecialchars($errors['volume_td']) ?></div>
                      <?php endif; ?>
                    </div>
                  </div>
                  
                  <div class="card rounded-3 border-0 shadow-sm">
                    <div class="card-body p-3">
                      <div class="d-flex align-items-center gap-3 mb-2">
                        <div class="icon-circle bg-success-subtle text-success">
                          <i class="ti ti-tool"></i>
                        </div>
                        <label for="volume_tp" class="form-label mb-0">Volume TP</label>
                      </div>
                      <input type="number" 
                             class="form-control rounded-3 <?= isset($errors['volume_tp']) ? 'is-invalid' : '' ?>" 
                             id="volume_tp" 
                             name="volume_tp" 
                             value="<?= htmlspecialchars($_POST['volume_tp'] ?? '0') ?>" 
                             min="0">
                      <?php if (isset($errors['volume_tp'])): ?>
                        <div class="invalid-feedback"><?= htmlspecialchars($errors['volume_tp']) ?></div>
                      <?php endif; ?>
                    </div>
                  </div>
                  
                  <div class="card rounded-3 border-0 shadow-sm">
                    <div class="card-body p-3">
                      <div class="d-flex align-items-center gap-3 mb-2">
                        <div class="icon-circle bg-warning-subtle text-warning">
                          <i class="ti ti-clock"></i>
                        </div>
                        <label for="volume_autre" class="form-label mb-0">Volume Autre</label>
                      </div>
                      <input type="number" 
                             class="form-control rounded-3 <?= isset($errors['volume_autre']) ? 'is-invalid' : '' ?>" 
                             id="volume_autre" 
                             name="volume_autre" 
                             value="<?= htmlspecialchars($_POST['volume_autre'] ?? '0') ?>" 
                             min="0">
                      <?php if (isset($errors['volume_autre'])): ?>
                        <div class="invalid-feedback"><?= htmlspecialchars($errors['volume_autre']) ?></div>
                      <?php endif; ?>
                    </div>
                  </div>
                </div>
              </div>
              
              <!-- Semester and Credits -->
              <div class="row">
                <div class="col-md-6">
                  <div class="form-group">
                    <label for="semester" class="form-label">Semestre <span class="text-danger">*</span></label>
                    <div class="input-group">
                      <span class="input-group-text bg-light border-0">
                        <i class="ti ti-calendar text-primary"></i>
                      </span>
                      <select class="form-select rounded-end <?= isset($errors['semester']) ? 'is-invalid' : '' ?>" 
                              id="semester" 
                              name="semester">
                        <?php
                        $semesters = ['S1', 'S2', 'S3', 'S4', 'S5', 'S6'];
                        $selectedSemester = $_POST['semester'] ?? 'S1';
                        foreach ($semesters as $sem): 
                        ?>
                          <option value="<?= $sem ?>" <?= $selectedSemester === $sem ? 'selected' : '' ?>><?= $sem ?></option>
                        <?php endforeach; ?>
                      </select>
                      <?php if (isset($errors['semester'])): ?>
                        <div class="invalid-feedback"><?= htmlspecialchars($errors['semester']) ?></div>
                      <?php endif; ?>
                    </div>
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="form-group">
                    <label for="credits" class="form-label">Crédits <span class="text-danger">*</span></label>
                    <div class="input-group">
                      <span class="input-group-text bg-light border-0">
                        <i class="ti ti-award text-primary"></i>
                      </span>
                      <input type="number" 
                             class="form-control rounded-end <?= isset($errors['credits']) ? 'is-invalid' : '' ?>" 
                             id="credits" 
                             name="credits" 
                             value="<?= htmlspecialchars($_POST['credits'] ?? '') ?>" 
                             min="1" 
                             required>
                      <?php if (isset($errors['credits'])): ?>
                        <div class="invalid-feedback"><?= htmlspecialchars($errors['credits']) ?></div>
                      <?php endif; ?>
                    </div>
                  </div>
                </div>
              </div>
              
              <!-- Filière -->
              <div class="form-group">
                <label for="id_filiere" class="form-label">Filière</label>
                <div class="input-group">
                  <span class="input-group-text bg-light border-0">
                    <i class="ti ti-school text-primary"></i>
                  </span>
                  <select class="form-select rounded-end" id="id_filiere" name="id_filiere" required>
                    <?php foreach ($filieres as $filiere): ?>
                      <option value="<?= $filiere['id_filiere'] ?>"><?= htmlspecialchars($filiere['filiere_name']) ?></option>
                    <?php endforeach; ?>
                  </select>
                </div>
              </div>
              
              <!-- Module Type Selection -->
              <div class="card rounded-4 border-0 shadow-sm mt-4 mb-3">
                <div class="card-body p-4">
                  <h5 class="fw-bold mb-3">Type du module</h5>
                  
                  <div class="d-flex flex-column gap-3">
                    <div class="form-check">
                      <input class="form-check-input" type="checkbox" name="is_first_element" id="is_first_element" onchange="toggleFirst()">
                      <label class="form-check-label d-flex align-items-center gap-2" for="is_first_element">
                        <div class="icon-circle bg-primary-subtle text-primary" style="width: 32px; height: 32px;">
                          <i class="ti ti-folder"></i>
                        </div>
                        <div>
                          <span class="fw-medium">Premier Élément (Parent)</span>
                          <p class="text-muted small mb-0">Module principal pouvant contenir des sous-modules</p>
                        </div>
                      </label>
                    </div>
                    
                    <div class="form-check">
                      <input class="form-check-input" type="checkbox" name="is_element" id="is_element" onchange="toggleElement()">
                      <label class="form-check-label d-flex align-items-center gap-2" for="is_element">
                        <div class="icon-circle bg-info-subtle text-info" style="width: 32px; height: 32px;">
                          <i class="ti ti-file"></i>
                        </div>
                        <div>
                          <span class="fw-medium">Élément (Sous-module)</span>
                          <p class="text-muted small mb-0">Sous-module appartenant à un module parent</p>
                        </div>
                      </label>
                    </div>
                  </div>
                </div>
              </div>
              
              <!-- Parent Module Selection (Hidden by default) -->
              <div id="parent_module_section" style="display: none;">
                <div class="form-group mb-0">
                  <label for="parent_module" class="form-label">Choisir un module parent</label>
                  <select class="form-select" id="parent_module" name="parent_module">
                    <option value="">-- Choisir un module parent --</option>

                    <?php foreach ($availableParents as $mod): ?>
                      <option value="<?= $mod['id_module'] ?>"><?= htmlspecialchars($mod['title']) ?></option>
                    <?php endforeach; ?>
                  </select>
                </div>
              </div>
              
              <!-- Submit Button -->
              <div class="d-flex justify-content-center mt-4">
                <button type="submit" class="btn btn-primary btn-lg px-5 py-3 rounded-pill shadow-sm">
                  <i class="ti ti-device-floppy me-2"></i>
                  Enregistrer le module
                </button>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>
  
  <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
  <script>
    function toggleElement() {
      const check = document.getElementById('is_element').checked;
      document.getElementById('parent_module_section').style.display = check ? 'block' : 'none';
      if (check) document.getElementById('is_first_element').checked = false;
    }

    function toggleFirst() {
      if (document.getElementById('is_first_element').checked) {
        document.getElementById('is_element').checked = false;
        document.getElementById('parent_module_section').style.display = 'none';
      }
    }
    
    document.querySelectorAll('.form-control, .form-select').forEach(element => {
      element.addEventListener('focus', () => {
        element.closest('.form-group')?.classList.add('was-validated');
      });
    });
  </script>