<template>
    <div class="payment-wrapper">
      <h2 class="title">
        Fill in the form below to complete your registration for
        <span class="highlight">SITE 2025</span>
      </h2>
  
      <form class="payment-form">
        <div class="form-center">
          <div class="total-box">
            <span>Total Amount:</span>
            <span class="amount">200 TND</span>
          </div>
  
          <div class="input-group">
            <label class="fixed-label">Payment Method *</label>
            <select>
              <option disabled selected>Select a method</option>
              <option>Bank Transfer</option>
              <option>Cash</option>
              <option>Card</option>
            </select>
          </div>
  
          <div class="input-group">
            <label class="fixed-label">Proof of Payment *</label>
            <input type="file" />
          </div>
  
          <router-link to="/reg-success" ><button type="submit" class="submit-btn">Submit</button></router-link>
          
        </div>
      </form>
  
      <ul class="notes">
        <li>✔️ After completing the online registration, please save/print the registration form.</li>
        <li>✔️ The printed registration form can help you when preparing the purchase order.</li>
        <li>✔️ Please bring the printed registration form on the SNTS welcome day to facilitate your admission.</li>
      </ul>
  
      <div class="navigation">
        <router-link to="/registration" class="arrow-btn active">
          <span class="arrow-icon">←</span>
        </router-link>
        <button class="arrow-btn disabled" disabled>
          <span class="arrow-icon">→</span>
        </button>
      </div>
    </div>
  </template>
  
  <style scoped>
  .payment-wrapper {
    max-width: 800px;
    margin: auto;
    padding: 1.5rem;
    font-family: Arial, sans-serif;
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
  
  .payment-form {
    background: white;
    border: 1px solid #d0d0ff;
    border-radius: 12px;
    padding: 2rem;
    margin: 2rem 0;
    box-shadow: 0 0 10px rgba(60, 90, 255, 0.2);
    display: flex;
    justify-content: center;
  }
  
  .form-center {
    width: 100%;
    max-width: 400px;
  }
  
  .total-box {
    background: #f1f9ff;
    border: 2px solid #1fa2ff;
    border-radius: 25px;
    padding: 0.75rem 1.5rem;
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    font-size: 1.2rem;
    font-weight: normal;
    color: #333;
    margin-bottom: 1.5rem;
  }
  
  .amount {
    font-weight: bold;
    color: #1fa2ff;
  }
  
  .input-group {
    margin-bottom: 1.25rem;
    text-align: left;
  }
  
  .input-group label {
    display: block;
    margin-bottom: 0.5rem;
    font-weight: normal;
  }
  
  .fixed-label {
    width: 200px;
    display: inline-block;
  }
  
  select,
  input[type="file"] {
    width: 95%;
    max-width: 100%;
    padding: 0.4rem;
    border-radius: 8px;
    border: 1px solid #ccc;
  }
  
  .submit-btn {
    background: linear-gradient(to right, #4CAF50, #388E3C);
    color: white;
    padding: 0.6rem 2rem;
    font-size: 1rem;
    font-weight: 500;
    border: none;
    border-radius: 8px;
    margin-top: 1rem;
    cursor: pointer;
    transition: background 0.3s ease, transform 0.2s;
    box-shadow: 0 4px 10px rgba(0, 0, 0, 0.15);
  }
  
  .submit-btn:hover {
    background: linear-gradient(to right, #388E3C, #2E7D32);
    transform: scale(1.02);
  }
  
  .notes {
    font-size: 0.9rem;
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
  
  .arrow-btn.active:hover {
    background-color: #145a7d;
    color: white;
    border-color: #145a7d;
  }
  
  .arrow-icon {
    font-size: 1.5rem;
    color: #333;
  }
  
  .arrow-btn.active:hover .arrow-icon {
    color: white;
  }
  
  .arrow-btn.disabled {
    opacity: 0.5;
    cursor: not-allowed;
  }
  
  @media screen and (max-width: 600px) {
    .payment-form {
      padding: 1rem;
    }
    .form-center {
      width: 95%;
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