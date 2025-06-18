<template>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <div class="form-container">
      <h2 class="title">
        Fill in the form below to complete your registration for
        <span class="site-title">SITE 2025</span>
      </h2>
  
      <div class="form-card">
        <h3 class="card-title">Stranger <span class="price">400 EUR</span></h3>
  
        <form class="registration-form">
          <div class="form-group">
            <label>First Name *</label>
            <input type="text" placeholder="Samantha" required />
          </div>
  
          <div class="form-group">
            <label>Last Name *</label>
            <input type="text" placeholder="William" required />
          </div>
  
          <div class="form-group">
            <label>Email *</label>
            <input type="email" placeholder="william@gmail.com" required />
          </div>
  
          <div class="form-group">
            <label>Phone *</label>
            <input type="tel" placeholder="+1234567890" required />
          </div>
  
          <div class="form-group">
            <label>Profession *</label>
            <input type="text" placeholder="Teacher, Student ..." required />
          </div>
  
          <div class="form-group">
            <label>Institution *</label>
            <input type="text" placeholder="Your institution?" required />
          </div>
  
          <div class="form-group">
            <label>Participation *</label>
            <div class="checkbox-group">
              <label><input type="radio" name="participation" /> With paper</label>
              <label><input type="radio" name="participation" /> Without paper</label>
            </div>
          </div>
  
          <div class="form-group">
            <label>Country *</label>
            <select>
              <option value="">-- Select your country --</option>
              <option value="fr">France</option>
              <option value="de">Germany</option>
              <option value="it">Italy</option>
              <option value="us">USA</option>
              <option value="uk">UK</option>
              <!-- You can add more countries -->
            </select>
          </div>
        </form>
      </div>
  
      <div class="note">
        <p>✅ After completing the online registration, please save/print the registration form.</p>
        <p>✅ The printed registration form can help you when preparing the purchase order.</p>
        <p>✅ Please bring the printed registration form on the day of SNTS reception to facilitate your admission.</p>
      </div>
  
      <div class="nav-buttons">
        <router-link to="/profile-selection">
          <i class="fas fa-arrow-left"></i>
        </router-link>
        <router-link to="/registration">
          <i class="fas fa-arrow-right"></i>
        </router-link>
      </div>
    </div>
  </template>
  
  <script setup>
  // Nothing to declare here
  </script>
  
  <style scoped>
  .form-container {
    padding: 2rem;
    max-width: 700px;
    margin: 0 auto;
    font-family: 'Segoe UI', sans-serif;
    text-align: center;
  }
  
  .title {
    font-size: 1.7rem;
    margin-bottom: 1rem;
    color: #000;
  }
  
  .site-title {
    color: #3d5a80;
  }
  
  .form-card {
    border: 1.5px solid #3d5a80;
    border-radius: 15px;
    padding: 2rem;
    background-color: #fff;
    box-shadow: 0 0 25px rgba(61, 90, 128, 0.2);
    margin-top: 1rem;
    text-align: left;
  }
  
  .card-title {
    font-size: 1.5rem;
    color: #1a237e;
    font-weight: bold;
    margin-bottom: 1rem;
    text-align: center;
  }
  
  .price {
    margin-left: 0.5rem;
    font-size: 1.5rem;
  }
  
  .registration-form {
    display: flex;
    flex-direction: column;
    gap: 1.2rem;
  }
  
  .form-group label {
    display: block;
    font-weight: 500;
    margin-bottom: 0.3rem;
  }
  
  input[type="text"],
  input[type="email"],
  input[type="tel"],
  select {
    width: 95%;
    padding: 0.4rem 0.6rem;
    border-radius: 8px;
    border: 1px solid #ccc;
    font-size: 0.9rem;
  }
  
  select {
    appearance: none;
    background-color: #fff;
  }
  
  .checkbox-group {
    display: flex;
    gap: 1.5rem;
    margin-top: 0.5rem;
  }
  
  .checkbox-group label {
    display: flex;
    align-items: center;
    gap: 0.5rem;
  }
  
  .note {
    margin-top: 2rem;
    text-align: left;
    color: #444;
    font-size: 0.95rem;
  }
  
  .note p {
    margin-bottom: 0.5rem;
  }
  
  .nav-buttons {
    display: flex;
    justify-content: space-between;
    margin-top: 2rem;
    max-width: 700px;
    margin-inline: auto;
  }
  
  .nav-buttons a {
    background-color: #fff;
    border: 1px solid #ccc;
    padding: 0.6rem 1rem;
    border-radius: 50%;
    font-size: 1.2rem;
    color: #444;
    transition: background 0.3s;
  }
  
  .nav-buttons a:hover {
    background-color: #f0f0f0;
  }
  
  /* ✅ Responsive */
  @media (max-width: 600px) {
    .registration-form {
      gap: 1rem;
    }
  
    .checkbox-group {
      flex-direction: column;
      gap: 0.8rem;
    }
  
    .form-card {
      padding: 1.2rem;
    }
  
    .form-group input,
    .form-group select {
      padding: 0.3rem 0.5rem;
      font-size: 0.8rem;
    }
  
    .title {
      font-size: 1.2rem;
    }
  
    .nav-buttons {
      justify-content: center;
      gap: 2rem;
    }
  }
  </style>
  