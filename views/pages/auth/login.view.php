<?php 
require_once $_SERVER['DOCUMENT_ROOT']."/e-service/core/resources.php";
?>

<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>E-Services Login</title>
  <link rel="shortcut icon" type="image/png" href="/e-service/storage/image/logo/logo.png" />
  <link rel="stylesheet" href="<?=$RESOURCES_PATH?>/assets/css/styles.min.css" />
  <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="/e-service/resources/assets/css/login_view.css">
</head>
<body>
  <!-- Particles Animation -->
  <div class="particles" id="particles"></div>
  
  <!-- Wave Animation -->
  <div class="wave-container">
    <div class="wave"></div>
    <div class="wave"></div>
    <div class="wave"></div>
  </div>
  
  <div class="login-container">
    <!-- Floating Elements -->
    <div class="floating-element floating-1"></div>
    <div class="floating-element floating-2"></div>
    
    <!-- Left Side - Brand -->
    <div class="login-brand">
      <div class="brand-logo-container">
        <img src="/e-service/storage/image/logo/logo.png" alt="E-Services Logo" class="brand-logo">
      </div>
      <h1 class="brand-title">E-Services</h1>
      <p class="brand-description">Votre plateforme de gestion des services académiques. Accédez à tous vos outils en un seul endroit.</p>
      
      <div class="brand-features">
        <div class="feature-item">
          <div class="feature-icon">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
              <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm0 18c-4.41 0-8-3.59-8-8s3.59-8 8-8 8 3.59 8 8-3.59 8-8 8zm-1-13h2v6h-2zm0 8h2v2h-2z"/>
            </svg>
          </div>
          <div class="feature-text">Gestion des affectations d'enseignements</div>
        </div>
        
        <div class="feature-item">
          <div class="feature-icon">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
              <path d="M19 3h-4.18C14.4 1.84 13.3 1 12 1c-1.3 0-2.4.84-2.82 2H5c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2zm-7 0c.55 0 1 .45 1 1s-.45 1-1 1-1-.45-1-1 .45-1 1-1zm-2 14l-4-4 1.41-1.41L10 14.17l6.59-6.59L18 9l-8 8z"/>
            </svg>
          </div>
          <div class="feature-text">Suivi des modules et des charges</div>
        </div>
        
        <div class="feature-item">
          <div class="feature-icon">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
              <path d="M19 3h-4.18C14.4 1.84 13.3 1 12 1c-1.3 0-2.4.84-2.82 2H5c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2zm-7 0c.55 0 1 .45 1 1s-.45 1-1 1-1-.45-1-1 .45-1 1-1zm2 14H7v-2h7v2zm3-4H7v-2h10v2zm0-4H7V7h10v2z"/>
            </svg>
          </div>
          <div class="feature-text">Gestion des rapports et statistiques</div>
        </div>
      </div>
    </div>
    
    <!-- Right Side - Form -->
    <div class="login-form-container">
      <div class="login-header">
        <h2 class="login-title">Connexion</h2>
        <p class="login-subtitle">Entrez vos identifiants pour accéder à votre compte</p>
      </div>
      
      <?php if (isset($info)) { ?>
        <div class="alert alert-<?= $info["type"] ?>">
          <?php if ($info["type"] === "success") { ?>
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor">
              <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-2 15l-5-5 1.41-1.41L10 14.17l7.59-7.59L19 8l-9 9z"/>
            </svg>
          <?php } else { ?>
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor">
              <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm1 15h-2v-2h2v2zm0-4h-2V7h2v6z"/>
            </svg>
          <?php } ?>
          <?= $info["msg"] ?>
        </div>
      <?php } ?>
      
      <form class="needs-validation" action="<?= $_SERVER["PHP_SELF"]?>" method="post" id="loginForm">
        <div class="form-group">
          <label for="email" class="form-label">Adresse email</label>
          <div class="form-control-wrapper">
            <input type="email" class="form-control <?= isset($errors["email"]) || isset($errors["invalid"]) ? "is-invalid" : ""?>" 
                  id="email" name="email" placeholder="Entrez votre adresse email" required>
            <span class="input-icon">
              <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M20 4H4a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2V6a2 2 0 0 0-2-2z"></path>
                <polyline points="22,6 12,13 2,6"></polyline>
              </svg>
            </span>
          </div>
          <?php if (isset($errors["email"])) { ?>
            <div class="invalid-feedback">
              <?= $errors["email"] ?>
            </div>
          <?php } ?>
        </div>
        
        <div class="form-group">
          <label for="password" class="form-label">Mot de passe</label>
          <div class="form-control-wrapper">
            <input type="password" class="form-control <?= isset($errors["invalid"]) ? "is-invalid" : ""?>" 
                  id="password" name="password" placeholder="Entrez votre mot de passe" required>
            <span class="input-icon" id="togglePassword">
              <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>
                <circle cx="12" cy="12" r="3"></circle>
              </svg>
            </span>
          </div>
          <?php if (isset($errors["invalid"])) { ?>
            <div class="invalid-feedback">
              <?= $errors["invalid"] ?>
            </div>
          <?php } ?>
        </div>
        
        <div class="login-actions">
          <div class="form-check">
            <input class="form-check-input" type="checkbox" id="remember" name="remember" checked>
            <label class="form-check-label" for="remember">
              Se souvenir de moi
            </label>
          </div>
          <a href="#" class="forgot-link">Mot de passe oublié ?</a>
        </div>
        
        <button type="submit" class="btn-login" id="loginButton">
          <span class="btn-text">
            Se connecter
            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="btn-icon">
              <path d="M5 12h14"></path>
              <path d="m12 5 7 7-7 7"></path>
            </svg>
          </span>
          <span class="btn-loader"></span>
        </button>
        
        <div class="divider">ou connectez-vous avec</div>
        
        <div class="social-login">
          <button type="button" class="social-btn">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="#4285F4">
              <path d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z" />
              <path d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z" fill="#34A853" />
              <path d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z" fill="#FBBC05" />
              <path d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z" fill="#EA4335" />
            </svg>
          </button>
          <button type="button" class="social-btn">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="#1877F2">
              <path d="M24 12.073c0-5.8-4.702-10.5-10.5-10.5s-10.5 4.7-10.5 10.5c0 5.24 3.84 9.584 8.86 10.373v-7.337h-2.666v-3.037h2.666V9.98c0-2.63 1.568-4.085 3.97-4.085 1.15 0 2.35.205 2.35.205v2.584h-1.322c-1.304 0-1.71.81-1.71 1.64v1.97h2.912l-.465 3.036H15.16v7.337c5.02-.788 8.84-5.131 8.84-10.373z"/>
            </svg>
          </button>
          <button type="button" class="social-btn">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="#000000">
              <path d="M12 0C5.373 0 0 5.373 0 12s5.373 12 12 12 12-5.373 12-12S18.627 0 12 0zm6.066 9.645c.183 4.04-2.83 8.544-8.164 8.544A8.127 8.127 0 0 1 5.5 16.898a5.778 5.778 0 0 0 4.252-1.189 2.879 2.879 0 0 1-2.684-1.995 2.88 2.88 0 0 0 1.298-.049c-1.381-.278-2.335-1.522-2.304-2.853.388.215.83.344 1.301.359a2.877 2.877 0 0 1-.889-3.835 8.153 8.153 0 0 0 5.92 3.001 2.876 2.876 0 0 1 4.895-2.62 5.73 5.73 0 0 0 1.824-.697 2.884 2.884 0 0 1-1.263 1.589 5.73 5.73 0 0 0 1.649-.453 5.765 5.765 0 0 1-1.433 1.489z"/>
            </svg>
          </button>
        </div>
        
        <div class="help-links">
          <a href="#">Questions ?</a>
          <a href="#">Support</a>
          <a href="#">Contact</a>
        </div>
        
        <div class="copyright">
          Copyright © 2025 - Tous droits réservés
        </div>
      </form>
    </div>
  </div>

  <script src="<?=$RESOURCES_PATH?>/assets/libs/jquery/dist/jquery.min.js"></script>
  <script src="<?=$RESOURCES_PATH?>/assets/libs/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
  
  <script>
    // Toggle password visibility
    document.getElementById('togglePassword').addEventListener('click', function() {
      const passwordInput = document.getElementById('password');
      const icon = this.querySelector('svg');
      
      if (passwordInput.type === 'password') {
        passwordInput.type = 'text';
        icon.innerHTML = `
          <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>
          <line x1="1" y1="1" x2="23" y2="23"></line>
        `;
      } else {
        passwordInput.type = 'password';
        icon.innerHTML = `
          <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>
          <circle cx="12" cy="12" r="3"></circle>
        `;
      }
    });
    
    // Login button animation
    document.getElementById('loginForm').addEventListener('submit', function(e) {
      const button = document.getElementById('loginButton');
      button.classList.add('loading');
      
      setTimeout(() => {
        button.classList.remove('loading');
      }, 2000);
    });
    
    // Create particles
    function createParticles() {
      const particlesContainer = document.getElementById('particles');
      const particleCount = 50;
      
      for (let i = 0; i < particleCount; i++) {
        const particle = document.createElement('div');
        particle.className = 'particle';
        
        // Random size between 5px and 15px
        const size = Math.random() * 10 + 5;
        particle.style.width = `${size}px`;
        particle.style.height = `${size}px`;
        
        // Random position
        particle.style.left = `${Math.random() * 100}%`;
        particle.style.top = `${Math.random() * 100}%`;
        
        // Random color
        const colors = ['#4361ee', '#4895ef', '#4cc9f0', '#3a0ca3'];
        const color = colors[Math.floor(Math.random() * colors.length)];
        particle.style.backgroundColor = color;
        
        // Random animation duration between 15s and 30s
        const duration = Math.random() * 15 + 15;
        particle.style.animationDuration = `${duration}s`;
        
        // Random delay
        const delay = Math.random() * 5;
        particle.style.animationDelay = `${delay}s`;
        
        particlesContainer.appendChild(particle);
      }
    }
    
    // Initialize particles
    createParticles();
    
    // Input focus effects
    const inputs = document.querySelectorAll('.form-control');
    inputs.forEach(input => {
      input.addEventListener('focus', function() {
        this.parentElement.querySelector('.input-icon').style.color = '#4361ee';
      });
      
      input.addEventListener('blur', function() {
        if (!this.value) {
          this.parentElement.querySelector('.input-icon').style.color = '#6c757d';
        }
      });
    });
  </script>
</body>
</html>
