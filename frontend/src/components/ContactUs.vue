<template>
    <section class="contact-section">
      <h2 class="contact-title">Contact us</h2>
  
      <div class="contact-info fade-in">
        <div class="contact-item">
          <span class="icon">📞</span>
          <p>
            <a href="tel:+21672570600">72 57 06 00</a> |
            <a href="tel:+21672572455">+216 72 572 455</a>
          </p>
        </div>
  
        <div class="contact-item">
          <span class="icon">✉️</span>
          <a href="mailto:isetbz@isetbz.rnu.tn">isetbz@isetbz.rnu.tn</a>
        </div>
      </div>
  
      <div class="contact-footer">
        &copy; 2025 <strong>SITE</strong>. All rights reserved.
      </div>
    </section>
  </template>
  
  <script setup>
  // Aucun script nécessaire
  </script>
  
  <style scoped>
  .contact-section {
    background-color: #fff;
    text-align: center;
    color: #1f2937;
    font-family: 'Segoe UI', sans-serif;
  }
  
  .contact-title {
    font-size: 1.7rem;
  }
  
  .contact-info {
    display: flex;
    flex-direction: column;
    gap: 19px;
    align-items: center;
    font-size: 1rem;
  }
  
  .contact-item {
    display: flex;
    align-items: center;
    gap: 10px;
  }
  
  .contact-item a {
    color: #000;
    text-decoration: none;
    border-bottom: 1px solid transparent;
    transition: border-color 0.3s;
  }
  
  .contact-item a:hover {
    border-color: #0f4c81;
  }
  
  .icon {
    font-size: 1.5rem;
  }
  
  .contact-footer {
    margin-top: 40px;
    margin-bottom: 30px;
    font-size: 0.9rem;
    color: #555;
  }
  
  /* Animation */
  .fade-in {
    opacity: 0;
    transform: translateY(30px);
    animation: fadeInUp 1.2s ease-out forwards;
  }
  
  @keyframes fadeInUp {
    to {
      opacity: 1;
      transform: translateY(0);
    }
  }
  
  /* Responsive */
  @media (min-width: 768px) {
    .contact-info {
      flex-direction: row;
      justify-content: center;
      font-size: 1rem;
      gap: 50px;
    }
  }
  </style>
  