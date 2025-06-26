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
          <span class="amount">{{ calculateTotal() }}</span>
        </div>

        <div class="input-group">
          <label class="fixed-label">Payment Method *</label>
          <select v-model="paymentMethod">
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

        <router-link to="/reg-success">
          <button type="submit" class="submit-btn">Submit</button>
        </router-link>
      </div>
    </form>

    <ul class="notes">
      <li>✔️ After completing the online registration, please save/print the registration form.</li>
      <li>✔️ The printed registration form can help you when preparing the purchase order.</li>
      <li>✔️ Please bring the printed registration form on the SNTS welcome day to facilitate your admission.</li>
    </ul>

    <div class="nav-buttons">
      <router-link to="/registration">
        <i class="fas fa-arrow-left"></i>
      </router-link>
      <div class="dots">
        <span :class="{ active: activeStep === 1 }"></span>
        <span :class="{ active: activeStep === 2 }"></span>
        <span :class="{ active: activeStep === 3 }"></span>
      </div>
      <button class="disabled-arrow-btn" disabled>
        <i class="fas fa-arrow-right"></i>
      </button>
    </div>
  </div>
</template>

<script setup>
import { reactive, onMounted, ref } from 'vue'

const activeStep = 3
const paymentMethod = ref('')

const formData = reactive({
  hebergement: '',
  adultCompanions: 0,
  childCompanions: 0,
  singleSupplement: '',
  extraNights: 0,
})

onMounted(() => {
  const saved = JSON.parse(localStorage.getItem('site2025_accommodation_form') || '{}')
  Object.assign(formData, saved)
})

function calculateTotal() {
  let total = 650
  if (formData.hebergement === 'yes') {
    const adults = formData.adultCompanions
    const children = formData.childCompanions
    const persons = adults + children

    total += adults * 220
    total += children * (adults === 2 ? 110 : 155)
    total += formData.extraNights * 220 * persons

    if (formData.singleSupplement === 'yes') {
      total += 70 * persons
    }
  }
  return total + ' TND'
}
</script>

<style scoped>
.payment-wrapper {
  max-width: 800px;
  margin: auto;
  padding: 1.5rem;
  font-family: Arial, sans-serif;
  text-align: center;
}

.title {
  font-size: 1.7rem;
  margin-bottom: 1rem;
  color: #000;
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

.nav-buttons {
  display: flex;
  justify-content: space-between;
  align-items: center;
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

.disabled-arrow-btn {
  background-color: #eee;
  border: 1px solid #ccc;
  padding: 0.6rem 1rem;
  border-radius: 50%;
  font-size: 1.2rem;
  color: #aaa;
  cursor: not-allowed;
}

.dots {
  display: flex;
  align-items: center;
  justify-content: center;
  gap: 0.5rem;
}

.dots span {
  width: 10px;
  height: 10px;
  background-color: #ccc;
  border-radius: 50%;
  transition: background-color 0.3s ease;
}

.dots span.active {
  background-color: #3d5a80;
}

@media screen and (max-width: 600px) {
  .payment-form {
    padding: 1rem;
  }

  .form-center {
    width: 95%;
  }

  .title {
    font-size: 1rem;
  }

  .highlight {
    font-size: 1.5rem;
  }

  .nav-buttons {
    justify-content: center;
    gap: 2rem;
  }
}
</style>
