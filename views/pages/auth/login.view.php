<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>E-Services Login</title>
  <link rel="shortcut icon" type="image/png" href="/e-service/storage/image/logo/logo.png" />
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
</head>
<body>
  <!-- Particles Animation -->
  <div class="particles" id="particles"></div>
  
  <!-- 3D Shapes -->
  <div class="shape shape-1"></div>
  <div class="shape shape-2"></div>
  <div class="shape shape-3"></div>
  <div class="shape shape-4"></div>
  
  <div class="login-container">
    <!-- Left Side - Brand -->
    <div class="login-brand">
      <div class="brand-content">
        <div class="brand-logo-container">
          <img src="/e-service/storage/image/logo/logo.png" alt="E-Services Logo" class="brand-logo">
        </div>
        <h1 class="brand-title">E-Services</h1>
        <p class="brand-description">Votre plateforme de gestion des services académiques</p>
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
          <?php if (isset($errors["invalid"]) && !isset($errors["email"])) { ?>
            <div class="invalid-feedback">
              Identifiants incorrects. Veuillez réessayer.
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
    });
    
    // Create particles
    function createParticles() {
      const particlesContainer = document.getElementById('particles');
      const particleCount = 30;
      
      for (let i = 0; i < particleCount; i++) {
        const particle = document.createElement('div');
        particle.className = 'particle';
        
        // Random size between 5px and 20px
        const size = Math.random() * 15 + 5;
        particle.style.width = `${size}px`;
        particle.style.height = `${size}px`;
        
        // Random position
        particle.style.left = `${Math.random() * 100}%`;
        particle.style.top = `${Math.random() * 100}%`;
        
        // Random opacity
        particle.style.opacity = Math.random() * 0.5 + 0.1;
        
        // Random animation duration between 15s and 40s
        const duration = Math.random() * 25 + 15;
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
        this.parentElement.querySelector('.input-icon').style.color = 'var(--primary)';
        this.parentElement.classList.add('focused');
      });
      
      input.addEventListener('blur', function() {
        if (!this.value) {
          this.parentElement.querySelector('.input-icon').style.color = 'var(--text-muted)';
          this.parentElement.classList.remove('focused');
        }
      });
    });
  </script>
  <style>
    :root {
      --primary: #6366f1;
      --primary-dark: #4f46e5;
      --primary-light: #a5b4fc;
      --primary-lighter: #e0e7ff;
      --secondary: #0ea5e9;
      --secondary-dark: #0284c7;
      --accent: #8b5cf6;
      --success: #10b981;
      --warning: #f59e0b;
      --danger: #ef4444;
      --background: #ffffff;
      --surface: #f9fafb;
      --text: #1e293b;
      --text-muted: #64748b;
      --border: #e2e8f0;
      --shadow-sm: 0 1px 2px 0 rgba(0, 0, 0, 0.05);
      --shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
      --shadow-lg: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
      --shadow-xl: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
      --shadow-inner: inset 0 2px 4px 0 rgba(0, 0, 0, 0.06);
      --shadow-outline: 0 0 0 3px rgba(99, 102, 241, 0.2);
      --radius-sm: 0.25rem;
      --radius: 0.5rem;
      --radius-md: 0.75rem;
      --radius-lg: 1rem;
      --radius-xl: 1.5rem;
      --radius-2xl: 2rem;
      --radius-full: 9999px;
    }
    
    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
    }
    
    body {
      font-family: 'Plus Jakarta Sans', sans-serif;
      background: linear-gradient(135deg, #6366f1 0%, #8b5cf6 100%);
      min-height: 100vh;
      display: flex;
      align-items: center;
      justify-content: center;
      padding: 1.5rem;
      position: relative;
      overflow-x: hidden;
      color: var(--text);
    }
    
    /* Particles */
    .particles {
      position: fixed;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      z-index: 0;
      pointer-events: none;
    }
    
    .particle {
      position: absolute;
      background-color: rgba(255, 255, 255, 0.6);
      border-radius: var(--radius-full);
      animation: float-particle 20s infinite linear;
    }
    
    @keyframes float-particle {
      0% {
        transform: translateY(100vh) rotate(0deg);
      }
      100% {
        transform: translateY(-20vh) rotate(360deg);
      }
    }
    
    /* 3D Shapes */
    .shape {
      position: fixed;
      border-radius: var(--radius-2xl);
      background: linear-gradient(135deg, rgba(255, 255, 255, 0.1), rgba(255, 255, 255, 0.05));
      backdrop-filter: blur(10px);
      -webkit-backdrop-filter: blur(10px);
      border: 1px solid rgba(255, 255, 255, 0.1);
      box-shadow: 0 8px 32px 0 rgba(0, 0, 0, 0.05);
      z-index: 0;
    }
    
    .shape-1 {
      width: 200px;
      height: 200px;
      top: -100px;
      right: -100px;
      animation: rotate-shape 25s infinite linear;
    }
    
    .shape-2 {
      width: 150px;
      height: 150px;
      bottom: -75px;
      left: -75px;
      animation: rotate-shape 20s infinite linear reverse;
    }
    
    .shape-3 {
      width: 100px;
      height: 100px;
      top: 20%;
      left: 10%;
      animation: float-shape 15s infinite ease-in-out;
    }
    
    .shape-4 {
      width: 120px;
      height: 120px;
      bottom: 15%;
      right: 10%;
      animation: float-shape 18s infinite ease-in-out reverse;
    }
    
    @keyframes rotate-shape {
      0% {
        transform: rotate(0deg);
      }
      100% {
        transform: rotate(360deg);
      }
    }
    
    @keyframes float-shape {
      0%, 100% {
        transform: translateY(0) rotate(0deg);
      }
      50% {
        transform: translateY(-30px) rotate(10deg);
      }
    }
    
    /* Main container */
    .login-container {
      width: 100%;
      max-width: 900px;
      min-height: 500px;
      display: flex;
      border-radius: var(--radius-xl);
      overflow: hidden;
      box-shadow: var(--shadow-xl);
      position: relative;
      z-index: 1;
      background-color: var(--background);
      animation: container-appear 0.8s cubic-bezier(0.26, 0.53, 0.74, 1.48);
    }
    
    @keyframes container-appear {
      0% {
        opacity: 0;
        transform: scale(0.95);
      }
      100% {
        opacity: 1;
        transform: scale(1);
      }
    }
    
    /* Left side - Brand */
    .login-brand {
      flex: 0.8;
      background: linear-gradient(135deg, var(--primary) 0%, var(--accent) 100%);
      padding: 2rem;
      display: flex;
      flex-direction: column;
      justify-content: center;
      position: relative;
      overflow: hidden;
      color: white;
    }
    
    .login-brand::before {
      content: '';
      position: absolute;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      background-image: url("data:image/svg+xml,%3Csvg width='100' height='100' viewBox='0 0 100 100' xmlns='http://www.w3.org/2000/svg'%3E%3Cpath d='M11 18c3.866 0 7-3.134 7-7s-3.134-7-7-7-7 3.134-7 7 3.134 7 7 7zm48 25c3.866 0 7-3.134 7-7s-3.134-7-7-7-7 3.134-7 7 3.134 7 7 7zm-43-7c1.657 0 3-1.343 3-3s-1.343-3-3-3-3 1.343-3 3 1.343 3 3 3zm63 31c1.657 0 3-1.343 3-3s-1.343-3-3-3-3 1.343-3 3 1.343 3 3 3zM34 90c1.657 0 3-1.343 3-3s-1.343-3-3-3-3 1.343-3 3 1.343 3 3 3zm56-76c1.657 0 3-1.343 3-3s-1.343-3-3-3-3 1.343-3 3 1.343 3 3 3zM12 86c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm28-65c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm23-11c2.76 0 5-2.24 5-5s-2.24-5-5-5-5 2.24-5 5 2.24 5 5 5zm-6 60c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm29 22c2.76 0 5-2.24 5-5s-2.24-5-5-5-5 2.24-5 5 2.24 5 5 5zM32 63c2.76 0 5-2.24 5-5s-2.24-5-5-5-5 2.24-5 5 2.24 5 5 5zm57-13c2.76 0 5-2.24 5-5s-2.24-5-5-5-5 2.24-5 5 2.24 5 5 5zm-9-21c1.105 0 2-.895 2-2s-.895-2-2-2-2 .895-2 2 .895 2 2 2zM60 91c1.105 0 2-.895 2-2s-.895-2-2-2-2 .895-2 2 .895 2 2 2zM35 41c1.105 0 2-.895 2-2s-.895-2-2-2-2 .895-2 2 .895 2 2 2zM12 60c1.105 0 2-.895 2-2s-.895-2-2-2-2 .895-2 2 .895 2 2 2z' fill='%23ffffff' fill-opacity='0.1' fill-rule='evenodd'/%3E%3C/svg%3E");
      opacity: 0.3;
    }
    
    .brand-content {
      display: flex;
      flex-direction: column;
      align-items: center;
      text-align: center;
      z-index: 1;
    }
    
    .brand-logo-container {
      position: relative;
      width: 90px;
      height: 90px;
      background-color: white;
      border-radius: var(--radius-full);
      display: flex;
      align-items: center;
      justify-content: center;
      margin-bottom: 1.5rem;
      box-shadow: var(--shadow-lg);
      animation: logo-pulse 3s infinite;
    }
    
    @keyframes logo-pulse {
      0% {
        box-shadow: 0 0 0 0 rgba(255, 255, 255, 0.7);
      }
      70% {
        box-shadow: 0 0 0 15px rgba(255, 255, 255, 0);
      }
      100% {
        box-shadow: 0 0 0 0 rgba(255, 255, 255, 0);
      }
    }
    
    .brand-logo {
      width: 60px;
      height: 60px;
      object-fit: contain;
    }
    
    .brand-title {
      font-size: 2rem;
      font-weight: 700;
      margin-bottom: 0.75rem;
      background: linear-gradient(to right, #ffffff, #e0e7ff);
      -webkit-background-clip: text;
      -webkit-text-fill-color: transparent;
      animation: slide-up 0.8s cubic-bezier(0.26, 0.53, 0.74, 1.48);
    }
    
    .brand-description {
      font-size: 0.9rem;
      line-height: 1.6;
      max-width: 90%;
      opacity: 0.9;
      animation: slide-up 0.8s cubic-bezier(0.26, 0.53, 0.74, 1.48) 0.2s backwards;
    }
    
    /* Right side - Form */
    .login-form-container {
      flex: 1.2;
      padding: 2rem;
      display: flex;
      flex-direction: column;
      position: relative;
      background-color: var(--background);
    }
    
    .login-header {
      margin-bottom: 1.5rem;
      animation: slide-down 0.8s cubic-bezier(0.26, 0.53, 0.74, 1.48);
    }
    
    @keyframes slide-down {
      0% {
        opacity: 0;
        transform: translateY(-20px);
      }
      100% {
        opacity: 1;
        transform: translateY(0);
      }
    }
    
    .login-title {
      font-size: 1.75rem;
      font-weight: 700;
      color: var(--text);
      margin-bottom: 0.5rem;
    }
    
    .login-subtitle {
      font-size: 0.9rem;
      color: var(--text-muted);
    }
    
    .form-group {
      margin-bottom: 1.25rem;
      position: relative;
      animation: slide-right 0.8s cubic-bezier(0.26, 0.53, 0.74, 1.48);
      animation-fill-mode: both;
    }
    
    @keyframes slide-right {
      0% {
        opacity: 0;
        transform: translateX(-20px);
      }
      100% {
        opacity: 1;
        transform: translateX(0);
      }
    }
    
    .form-group:nth-child(2) {
      animation-delay: 0.1s;
    }
    
    .form-group:nth-child(3) {
      animation-delay: 0.2s;
    }
    
    .form-label {
      display: block;
      margin-bottom: 0.5rem;
      font-size: 0.875rem;
      font-weight: 500;
      color: var(--text);
      transition: all 0.3s ease;
    }
    
    .form-control-wrapper {
      position: relative;
      transition: all 0.3s ease;
    }
    
    .form-control-wrapper.focused {
      transform: translateY(-2px);
    }
    
    .form-control {
      width: 100%;
      padding: 0.75rem 1rem;
      padding-right: 2.5rem;
      font-size: 0.9375rem;
      border: 1px solid var(--border);
      border-radius: var(--radius-lg);
      background-color: var(--background);
      transition: all 0.3s ease;
      font-family: 'Plus Jakarta Sans', sans-serif;
    }
    
    .form-control:focus {
      outline: none;
      border-color: var(--primary);
      box-shadow: var(--shadow-outline);
    }
    
    .form-control.is-invalid {
      border-color: var(--danger);
      box-shadow: 0 0 0 3px rgba(239, 68, 68, 0.2);
    }
    
    .invalid-feedback {
      color: var(--danger);
      font-size: 0.8125rem;
      margin-top: 0.5rem;
      animation: shake 0.5s cubic-bezier(0.36, 0.07, 0.19, 0.97) both;
    }
    
    @keyframes shake {
      10%, 90% {
        transform: translateX(-1px);
      }
      20%, 80% {
        transform: translateX(2px);
      }
      30%, 50%, 70% {
        transform: translateX(-4px);
      }
      40%, 60% {
        transform: translateX(4px);
      }
    }
    
    .input-icon {
      position: absolute;
      top: 50%;
      right: 1rem;
      transform: translateY(-50%);
      color: var(--text-muted);
      cursor: pointer;
      transition: all 0.2s;
      display: flex;
      align-items: center;
      justify-content: center;
      width: 24px;
      height: 24px;
    }
    
    .input-icon:hover {
      color: var(--primary);
    }
    
    .login-actions {
      display: flex;
      justify-content: space-between;
      align-items: center;
      margin-bottom: 1.5rem;
      animation: slide-right 0.8s cubic-bezier(0.26, 0.53, 0.74, 1.48) 0.3s backwards;
    }
    
    .form-check {
      display: flex;
      align-items: center;
    }
    
    .form-check-input {
      appearance: none;
      width: 18px;
      height: 18px;
      border: 1px solid var(--border);
      border-radius: var(--radius-sm);
      margin-right: 0.5rem;
      position: relative;
      cursor: pointer;
      transition: all 0.2s;
    }
    
    .form-check-input:checked {
      background-color: var(--primary);
      border-color: var(--primary);
    }
    
    .form-check-input:checked::after {
      content: '';
      position: absolute;
      top: 3px;
      left: 6px;
      width: 4px;
      height: 8px;
      border: solid white;
      border-width: 0 2px 2px 0;
      transform: rotate(45deg);
    }
    
    .form-check-label {
      font-size: 0.875rem;
      color: var(--text-muted);
    }
    
    .forgot-link {
      font-size: 0.875rem;
      color: var(--primary);
      text-decoration: none;
      transition: all 0.2s;
      position: relative;
    }
    
    .forgot-link::after {
      content: '';
      position: absolute;
      bottom: -2px;
      left: 0;
      width: 0;
      height: 1px;
      background-color: var(--primary);
      transition: width 0.3s ease;
    }
    
    .forgot-link:hover::after {
      width: 100%;
    }
    
    .btn-login {
      width: 100%;
      padding: 0.75rem 1rem;
      background-color: var(--primary);
      color: white;
      border: none;
      border-radius: var(--radius-lg);
      font-size: 0.9375rem;
      font-weight: 600;
      cursor: pointer;
      transition: all 0.3s ease;
      position: relative;
      overflow: hidden;
      box-shadow: var(--shadow);
      animation: slide-up 0.8s cubic-bezier(0.26, 0.53, 0.74, 1.48) 0.4s backwards;
      font-family: 'Plus Jakarta Sans', sans-serif;
    }
    
    @keyframes slide-up {
      0% {
        opacity: 0;
        transform: translateY(20px);
      }
      100% {
        opacity: 1;
        transform: translateY(0);
      }
    }
    
    .btn-login:hover {
      background-color: var(--primary-dark);
      transform: translateY(-2px);
      box-shadow: var(--shadow-lg);
    }
    
    .btn-login:active {
      transform: translateY(-1px);
      box-shadow: var(--shadow);
    }
    
    .btn-login::before {
      content: '';
      position: absolute;
      top: 0;
      left: -100%;
      width: 100%;
      height: 100%;
      background: linear-gradient(
        90deg,
        transparent,
        rgba(255, 255, 255, 0.2),
        transparent
      );
      transition: all 0.6s;
    }
    
    .btn-login:hover::before {
      left: 100%;
    }
    
    .btn-login .btn-text {
      position: relative;
      z-index: 1;
      display: flex;
      align-items: center;
      justify-content: center;
    }
    
    .btn-login .btn-icon {
      margin-left: 0.5rem;
      transition: transform 0.3s ease;
    }
    
    .btn-login:hover .btn-icon {
      transform: translateX(5px);
    }
    
    .btn-login .btn-loader {
      position: absolute;
      top: 50%;
      left: 50%;
      transform: translate(-50%, -50%);
      width: 24px;
      height: 24px;
      border: 3px solid rgba(255, 255, 255, 0.3);
      border-radius: 50%;
      border-top-color: white;
      animation: spin 1s linear infinite;
      opacity: 0;
      z-index: 0;
      transition: opacity 0.2s;
    }
    
    @keyframes spin {
      to {
        transform: translate(-50%, -50%) rotate(360deg);
      }
    }
    
    .btn-login.loading .btn-text {
      opacity: 0;
    }
    
    .btn-login.loading .btn-loader {
      opacity: 1;
    }
    
    .alert {
      padding: 0.875rem;
      border-radius: var(--radius-lg);
      margin-bottom: 1.25rem;
      font-size: 0.875rem;
      display: flex;
      align-items: center;
      animation: slide-down 0.5s cubic-bezier(0.26, 0.53, 0.74, 1.48);
    }
    
    .alert svg {
      width: 20px;
      height: 20px;
      margin-right: 0.75rem;
      flex-shrink: 0;
    }
    
    .alert-success {
      background-color: rgba(16, 185, 129, 0.1);
      color: var(--success);
      border-left: 4px solid var(--success);
    }
    
    .alert-danger {
      background-color: rgba(239, 68, 68, 0.1);
      color: var(--danger);
      border-left: 4px solid var(--danger);
    }
    
    .copyright {
      margin-top: 1.5rem;
      text-align: center;
      font-size: 0.8125rem;
      color: var(--text-muted);
      animation: fade-in 0.8s ease 0.8s backwards;
    }
    
    @keyframes fade-in {
      from {
        opacity: 0;
      }
      to {
        opacity: 1;
      }
    }
    
    /* Responsive */
    @media (max-width: 768px) {
      body {
        padding: 1rem;
      }
      
      .login-container {
        flex-direction: column;
        max-height: none;
        max-width: 400px;
      }
      
      .login-brand {
        padding: 1.5rem;
        min-height: 200px;
      }
      
      .brand-logo-container {
        width: 70px;
        height: 70px;
        margin-bottom: 1rem;
      }
      
      .brand-logo {
        width: 45px;
        height: 45px;
      }
      
      .brand-title {
        font-size: 1.5rem;
        margin-bottom: 0.5rem;
      }
      
      .brand-description {
        font-size: 0.875rem;
      }
      
      .login-form-container {
        padding: 1.5rem;
      }
      
      .login-title {
        font-size: 1.5rem;
      }
      
      .login-subtitle {
        font-size: 0.875rem;
      }
      
      .shape {
        display: none;
      }
    }
    
    @media (max-width: 480px) {
      .login-container {
        max-width: 100%;
      }
      
      .login-brand {
        min-height: 180px;
        padding: 1.25rem;
      }
      
      .brand-logo-container {
        width: 60px;
        height: 60px;
      }
      
      .brand-logo {
        width: 40px;
        height: 40px;
      }
      
      .brand-title {
        font-size: 1.25rem;
      }
      
      .brand-description {
        font-size: 0.8125rem;
      }
      
      .login-form-container {
        padding: 1.25rem;
      }
      
      .login-title {
        font-size: 1.25rem;
      }
      
      .login-subtitle {
        font-size: 0.8125rem;
      }
      
      .form-label {
        font-size: 0.8125rem;
      }
      
      .form-control {
        padding: 0.625rem 0.875rem;
        font-size: 0.875rem;
      }
    }
  </style>
</body>
</html>