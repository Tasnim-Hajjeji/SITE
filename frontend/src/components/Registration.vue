<template>
    <div class="registration-wrapper">
      <h2 class="title">
        Fill in the form below to complete your registration for
        <span class="highlight">SITE 2025</span>
      </h2>
  
      <form class="registration-form">
        <fieldset class="section">
          <legend>Accommodation *</legend>
          <label><input type="radio" name="hebergement" value="yes" /> Yes</label>
          <label><input type="radio" name="hebergement" value="no" /> No</label>
          <ul class="tariffs">
            <li>Adult companion 220 DT /night/person</li>
            <li>Child companion + 2 Adults 110 DT /night/child</li>
            <li>Child companion + 1 Adult 155 DT /night/child</li>
          </ul>
        </fieldset>
  
        <div class="input-group">
          <label>Number of child companions *</label>
          <select>
            <option>0</option>
            <option>1</option>
            <option>2</option>
            <option>3</option>
          </select>
        </div>
  
        <div class="input-group">
          <label>Number of adult companions *</label>
          <select>
            <option>0</option>
            <option>1</option>
            <option>2</option>
            <option>3</option>
          </select>
        </div>
  
        <fieldset class="section">
          <legend>Single Supplement *</legend>
          <label><input type="radio" name="single" value="yes" /> Yes</label>
          <label><input type="radio" name="single" value="no" /> No</label>
          <p>Single supplement 70 DT /night</p>
        </fieldset>
  
        <div class="input-group">
          <label>Extra Night *</label>
          <select>
            <option>0</option>
            <option>1</option>
            <option>2</option>
            <option>3</option>
          </select>
          <p>Extra night 220 DT /night/person</p>
        </div>
      </form>
      <ul class="notes">
          <li><span class="check-icon">✅</span> After completing the online registration, please save/print the registration form.</li>
          <li><span class="check-icon">✅</span> The printed registration form can help you when preparing the purchase order.</li>
          <li><span class="check-icon">✅</span> Please bring the printed registration form on the day of SNTS reception to facilitate your admission.</li>
        </ul>
      <div class="navigation">
          <router-link to="/stranger-form" class="arrow-btn">
            <span class="arrow-icon">←</span>
          </router-link>
          <router-link to="/registration-final" class="arrow-btn">
            <span class="arrow-icon">→</span>
          </router-link>
        </div>
    </div>
  </template>
  
  <style scoped>
  .registration-wrapper {
    max-width: 800px;
    margin: auto;
    padding: 1.5rem;
    font-family: 'Segoe UI', sans-serif;
    text-align: center;
  }
  
  .title {
    font-size: 1.5rem;
    font-weight: bold;
  }
  
  .highlight {
    color: #145a7d;
    display: block;
    font-size: 1.75rem;
    margin-top: 0.25rem;
    font-weight: bold;
  }
  
  
  .registration-form {
    background: white;
    border: 1px solid #d0d0ff;
    border-radius: 12px;
    padding: 2rem;
    margin: 2rem 0;
    box-shadow: 0 0 10px rgba(60, 90, 255, 0.2);
  }
  
  .section {
    margin-bottom: 1.5rem;
    text-align: left;
  }
  
  .input-group {
    margin-bottom: 1.25rem;
    text-align: left;
  }
  
  .input-group label,
  .section legend {
    font-weight: bold;
    color: #145a7d;
  }
  
  select {
    margin-top: 0.25rem;
    padding: 0.4rem;
    width: 100%;
    max-width: 300px;
    border-radius: 8px;
    border: 1px solid #ccc;
  }
  
  .tariffs {
    font-size: 0.9rem;
    margin-top: 0.5rem;
    padding-left: 1.5rem;
  }
  
  .notes {
    font-size: 0.95rem;
    margin-top: 2rem;
    text-align: left;
    list-style: none;
    padding-left: 0;
    color: #333;
  }
  
  .notes li {
    margin-bottom: 0.75rem;
    display: flex;
    align-items: center;
    gap: 0.5rem;
  }
  
  .check-icon {
    background-color: #4caf50;
    color: white;
    border-radius: 4px;
    padding: 0.1rem 0.4rem;
    font-size: 0.9rem;
    display: inline-block;
  }
  
  .navigation {
    display: flex;
    justify-content: center;
    align-items: center;
    gap: 2rem;
    margin-top: 2.5rem;
    flex-wrap: wrap;
  }
  
  .arrow-btn {
    width: 48px;
    height: 48px;
    background-color: white;
    border: 1px solid #ccc;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    text-decoration: none;
    transition: all 0.3s ease;
  }
  
  .arrow-btn:hover {
    background-color: #145a7d;
    color: white;
    border-color: #145a7d;
  }
  
  .arrow-icon {
    font-size: 1.5rem;
    color: #333;
  }
  
  .arrow-btn:hover .arrow-icon {
    color: white;
  }
  
  @media screen and (max-width: 600px) {
    .registration-form {
      padding: 1rem;
    }
    .title {
      font-size: 1.2rem;
    }
    .highlight {
      font-size: 1.5rem;
    }
    .arrow-btn {
      width: 40px;
      height: 40px;
    }
    .arrow-icon {
      font-size: 1.2rem;
    }
  }
  </style>
  