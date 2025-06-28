<template>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
  <div class="form-container">
    <h2 class="title">
      Fill in the form below to complete your registration for
      <span class="site-title">SITE 2025</span>
    </h2>

    <div class="form-card">
      <h3 class="card-title">Tunisian <span class="price">{{ tunisian_price }} TND</span></h3>

      <form class="registration-form" ref="formElement" novalidate>
        <div class="form-group">
          <label>First Name *</label>
          <input type="text" v-model="form.firstName" placeholder="Samantha" />
          <p class="error" v-if="errors.firstName">{{ errors.firstName }}</p>
        </div>

        <div class="form-group">
          <label>Last Name *</label>
          <input type="text" v-model="form.lastName" placeholder="William" />
          <p class="error" v-if="errors.lastName">{{ errors.lastName }}</p>
        </div>

        <div class="form-group">
          <label>Email *</label>
          <input type="email" v-model="form.email" placeholder="william@mail.com" />
          <p class="error" v-if="errors.email">{{ errors.email }}</p>
        </div>

        <div class="form-group">
          <label>Phone *</label>
          <input type="tel" v-model="form.phone" placeholder="+1234567890" />
          <p class="error" v-if="errors.phone">{{ errors.phone }}</p>
        </div>

        <div class="form-group">
          <label>Profession *</label>
          <input type="text" v-model="form.profession" placeholder="Teacher, Student ..." />
          <p class="error" v-if="errors.profession">{{ errors.profession }}</p>
        </div>

        <div class="form-group">
          <label>Institution *</label>
          <input type="text" v-model="form.institution" placeholder="Your institution?" />
          <p class="error" v-if="errors.institution">{{ errors.institution }}</p>
        </div>

        <div class="form-group">
          <label>Participation *</label>
          <div class="checkbox-group">
            <label>
              <input type="radio" value="With paper" v-model="form.participation" name="participation" />
              With paper
            </label>
            <label>
              <input type="radio" value="Without paper" v-model="form.participation" name="participation" />
              Without paper
            </label>
          </div>
          <p class="error" v-if="errors.participation">{{ errors.participation }}</p>
        </div>
      </form>
    </div>

    <div class="note">
      <p>✅ After completing the online registration, please save/print the registration form.</p>
      <p>✅ The printed form can be useful for preparing your purchase order.</p>
      <p>✅ Please bring the printed form on the reception day to facilitate your admission.</p>
    </div>

    <div class="nav-buttons">
      <a href="#" @click.prevent="backToPrevious()">
        <i class="fas fa-arrow-left"></i>
      </a>

      <div class="dots">
        <span :class="{ active: activeStep === 1 }"></span>
        <span :class="{ active: activeStep === 2 }"></span>
        <span :class="{ active: activeStep === 3 }"></span>
      </div>

      <a href="#" @click.prevent="proceedIfValid">
        <i class="fas fa-arrow-right"></i>
      </a>
    </div>
  </div>
</template>

<script setup>
import { reactive, ref, onMounted, watch } from 'vue'
import { useRouter } from 'vue-router'
import priceService from '@/services/FormPrices'
import cookieUtils from '@/utils/cookieUtils'

const activeStep = 1
const router = useRouter()
const formElement = ref(null)
const tunisian_price = ref(0)

const form = reactive({
  firstName: '',
  lastName: '',
  email: '',
  phone: '',
  profession: '',
  institution: '',
  participation: ''
})

const errors = reactive({
  firstName: '',
  lastName: '',
  email: '',
  phone: '',
  profession: '',
  institution: '',
  participation: ''
})

function validateForm() {
  let valid = true

  if (!form.firstName.trim()) {
    errors.firstName = 'First name is required.'
    valid = false
  } else {
    errors.firstName = ''
  }

  if (!form.lastName.trim()) {
    errors.lastName = 'Last name is required.'
    valid = false
  } else {
    errors.lastName = ''
  }

  const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/
  if (!emailRegex.test(form.email)) {
    errors.email = 'Enter a valid email address.'
    valid = false
  } else {
    errors.email = ''
  }

  const phoneRegex = /^\+?\d{8,15}$/
  if (!phoneRegex.test(form.phone)) {
    errors.phone = 'Enter a valid phone number (8–15 digits).'
    valid = false
  } else {
    errors.phone = ''
  }

  if (!form.profession.trim()) {
    errors.profession = 'Profession is required.'
    valid = false
  } else {
    errors.profession = ''
  }

  if (!form.institution.trim()) {
    errors.institution = 'Institution is required.'
    valid = false
  } else {
    errors.institution = ''
  }

  if (!form.participation) {
    errors.participation = 'Please select a participation type.'
    valid = false
  } else {
    errors.participation = ''
  }

  return valid
}

function proceedIfValid() {
  if (validateForm()) {
    router.push('/registration')
  } else {
    formElement.value.reportValidity()
  }
}

function backToPrevious() {
  localStorage.removeItem("tunisian_form");
  localStorage.removeItem("form_type");
  router.push('/profile-selection');
}

// Save to localStorage
onMounted(() => {
  localStorage.setItem('form_type', 'tunisian')
  const saved = localStorage.getItem('tunisian_form')
  if (saved) Object.assign(form, JSON.parse(saved))
  let editionId = cookieUtils.getCookie('editionId')
  priceService.getPrizesByEdition(editionId).then(dataArr => {
    if (Array.isArray(dataArr) && dataArr.length > 0) {
      tunisian_price.value = dataArr[0].prix_tun
    } else {
      tunisian_price.value = 0
      console.error('Tunisian price data not found in response:', dataArr)
    }
  }).catch(error => {
    console.error('Error fetching Tunisian price:', error)
  })
})

watch(form, (newVal) => {
  localStorage.setItem('tunisian_form', JSON.stringify(newVal))
}, { deep: true })
</script>

<style scoped>
.form-container {
  padding: 2rem;
  max-width: 700px;
  margin: 0 auto;
  text-align: center;
  font-family: 'Segoe UI', sans-serif;
}

.title {
  font-size: 1.7rem;
  margin-bottom: 1rem;
  color: #000;
  font-weight: bold;
}

.site-title {
  font-weight: bold;
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
  margin-left: 0.2rem;
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
input[type="tel"] {
  width: 95%;
  padding: 0.4rem 0.6rem;
  border-radius: 8px;
  border: 1px solid #ccc;
  font-size: 0.9rem;
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

.error {
  color: #c62828;
  font-size: 0.85rem;
  margin-top: 0.3rem;
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
  text-decoration: none;
  display: flex;
  align-items: center;
  justify-content: center;
}

.nav-buttons a:hover {
  background-color: #f0f0f0;
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

  .form-group input {
    padding: 0.3rem 0.5rem;
    font-size: 0.8rem;
  }

  .title {
    font-size: 1rem;
  }

  .nav-buttons {
    justify-content: center;
    gap: 2rem;
  }
}
</style>
