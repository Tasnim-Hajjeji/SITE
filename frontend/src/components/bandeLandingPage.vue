<template>
  <footer class="conference-footer">
    <div class="container">
      <div class="logos">
        <img src="@/assets/iset.png" alt="SET Logo" class="logo" />
        <img src="@/assets/adt.png" alt="ADT Logo" class="logo" />
      </div>
      <div class="contacts">
        <p class="contact-item"><i class="fas fa-phone"></i> <a href="tel:+21671123456">+216 71 123 456</a></p>
        <p class="contact-item"><i class="fas fa-phone"></i> <a href="tel:+21671789012">+216 71 789 012</a></p>
        <p class="contact-item"><i class="fas fa-envelope"></i> <a href="mailto:info@conference2025.tn">info@conference2025.tn</a></p>
      </div>
      <div class="social-links">
        <a href="https://facebook.com/conference2025" target="_blank" class="social-link">
          <i class="fab fa-facebook-f"></i>
        </a>
        <a href="https://linkedin.com/company/conference2025" target="_blank" class="social-link">
          <i class="fab fa-linkedin-in"></i>
        </a>
      </div>
    </div>
  </footer>
</template>

<script setup>
// Pas besoin de script pour l’instant
</script>

<style scoped>
@import url("https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css");

.conference-footer {
  background-color: #265985;
  color: white;
  padding: 0.8rem 2rem;
  font-family: 'Segoe UI', sans-serif;
  border-bottom: 4px solid rgba(255, 255, 255, 0.2);
}

.container {
  display: flex;
  justify-content: space-between;
  align-items: center;
  max-width: 1200px;
  margin: 0 auto;
}

.logos {
  display: flex;
  gap: 1.5rem;
}

.logo {
  height: 40px;
  width: auto;
}

.contacts {
  display: flex;
  gap: 1rem;
}

.contact-item {
  font-size: 0.9rem;
  margin: 0;
  display: flex;
  align-items: center;
  gap: 0.5rem;
}

.contact-item a {
  color: white;
  text-decoration: none;
  transition: opacity 0.3s ease;
}

.contact-item a:hover {
  opacity: 0.8;
}

.social-links {
  display: flex;
  gap: 1rem;
}

.social-link {
  color: white;
  font-size: 1.2rem;
  transition: opacity 0.3s ease;
}

.social-link:hover {
  opacity: 0.8;
}

@media (max-width: 768px) {
  .container {
    flex-direction: column;
    text-align: center;
    gap: 1.2rem;
    padding: 0.1rem;
  }

  .logos {
    flex-direction: row;
    justify-content: center;
    gap: 1rem;
  }

  .logo {
    height: 30px;
    width: auto;
  }

  .contacts {
    display: flex;
    flex-direction: row;
    justify-content: center;
    align-items: center;
    gap: 0.5rem;
    flex-wrap: nowrap;
  }

  .contact-item {
    font-size: 0.7rem;
    margin: 0;
    display: flex;
    align-items: center;
    gap: 0.3rem;
    white-space: nowrap;
  }

  .contact-item i {
    font-size: 0.75rem;
  }

  .contact-item a {
    color: white;
    text-decoration: none;
    padding: 0;
    font-size: 0.7rem;
  }

  .social-links {
    display: none;
  }
}
</style>
