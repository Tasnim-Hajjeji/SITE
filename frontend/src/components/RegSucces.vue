<template>
  <div class="registration-container">
    <h1 class="title">
      Registration <span class="highlight">successful!</span>
    </h1>
    <p class="subtitle">
      Print and bring the generated registration form on the day of the SITE 2025 welcome to facilitate your admission
    </p>

    <div class="form-card" ref="pdfContent">
      <div class="form-grid">
        <div class="form-field">
          <label>Full name</label>
          <input type="text" :value="formData.firstName + ' ' + formData.lastName" readonly />
        </div>
        <div class="form-field">
          <label>Email</label>
          <input type="text" :value="formData.email" readonly />
        </div>
        <div class="form-field">
          <label>Phone</label>
          <input type="text" :value="formData.phone" readonly />
        </div>
        <div class="form-field flag">
          <label>Country</label>
          <div class="flag-field">
            <img :src="`https://flagcdn.com/${formData.country}.svg`" alt="Tunisia flag" />
            <span>{{ formData.full_country }}</span>
          </div>
        </div>
        <div class="form-field">
          <label>Profession</label>
          <input type="text" :value="formData.profession" readonly />
        </div>
        <div class="form-field">
          <label>Institution</label>
          <input type="text" :value="formData.institution" readonly />
        </div>
        <div class="form-field">
          <label>Participation</label>
          <input type="text" :value="formData.participation" readonly />
        </div>
        <div class="form-field">
          <label>Accommodation</label>
          <input type="text" :value="formData.hebergement" readonly />
        </div>
        <div class="form-field">
          <label>Number of accompanying adults</label>
          <input type="text" :value="formData.adultCompanions" readonly />
        </div>
        <div class="form-field">
          <label>Number of accompanying children</label>
          <input type="text" :value="formData.childCompanions" readonly />
        </div>
        <div class="form-field">
          <label>Single supplement</label>
          <input type="text" :value="formData.singleSupplement" readonly />
        </div>
        <div class="form-field">
          <label>Extra night</label>
          <input type="text" :value="formData.extraNights" readonly />
        </div>
        <div class="form-field">
          <label>Payment method</label>
          <input type="text" :value="formData.paymentMethod" readonly />
        </div>
        <div class="form-field">
          <label>Total amount</label>
          <input type="text" :value="formData.totalPrice" readonly />
        </div>
      </div>
    </div>

    <br />

    <div class="buttons">
      <button class="download" @click="downloadPDF">
        <i class="fas fa-download"></i> Download registration form
      </button>
      <button class="edit">Edit information</button>
      <button class="home" @click="handleReturnHome">Return to Home</button>
    </div>
  </div>
</template>

<script setup>
import { reactive, onMounted, ref } from 'vue'
import { useRouter } from 'vue-router'
import jsPDF from 'jspdf'
import axios from 'axios'
import html2canvas from 'html2canvas'

const router = useRouter()
const pdfContent = ref(null)

const formData = reactive({
  firstName: '',
  lastName: '',
  email: '',
  phone: '',
  profession: '',
  institution: '',
  participation: '',
  hebergement: '',
  childCompanions: 0,
  adultCompanions: 0,
  singleSupplement: '',
  extraNights: 0,
  totalPrice: 0,
  paymentMethod: '',
  country: 'tn',
  full_country: ''
})

onMounted(() => {
  let formType = localStorage.getItem("form_type")
  const personalInfo = formType === 'tunisian' ? JSON.parse(localStorage.getItem('tunisian_form')) : JSON.parse(localStorage.getItem('stranger_form'))
  const accommodationInfo = JSON.parse(localStorage.getItem('accommodation_form') || '{}')
  const totalPrice = parseInt(localStorage.getItem('totalPrice') || '0', 10)
  Object.assign(formData, personalInfo, accommodationInfo)
  formData.totalPrice = totalPrice
  formData.paymentMethod = localStorage.getItem("paymentMethod") || ''
  fetchCountryName(formData.country)
})

function handleReturnHome() {
  localStorage.removeItem('stranger_form')
  localStorage.removeItem('tunisian_form')
  localStorage.removeItem('accommodation_form')
  router.push('/')
}

async function fetchCountryName(countryCode) {
  try {
    const response = await axios.get(`https://restcountries.com/v3.1/alpha/${countryCode}`);
    formData.full_country = response.data[0].name.common; // Accessing the full name
  } catch (error) {
    console.error(error);
  }
}

async function downloadPDF() {
  const element = pdfContent.value;

  // Check if element has size
  if (!element || element.offsetWidth === 0 || element.offsetHeight === 0) {
    alert('Registration form is not visible or has no size!');
    return;
  }

  const canvas = await html2canvas(element, {
    scale: 2,
    useCORS: true
  });
  const imgData = canvas.toDataURL('image/png');

  const pdf = new jsPDF('p', 'mm', 'a4');
  const pageWidth = pdf.internal.pageSize.getWidth();
  const imgProps = pdf.getImageProperties(imgData);
  const pdfWidth = pageWidth - 20;
  const pdfHeight = (imgProps.height * pdfWidth) / imgProps.width;

  const x = (pageWidth - pdfWidth) / 2;

  pdf.setFontSize(20);
  pdf.setTextColor('#051d37');
  pdf.text('SITE 2025 - Registration Form', pageWidth / 2, 15, { align: 'center' });
  pdf.addImage(imgData, 'PNG', x, 25, pdfWidth, pdfHeight);
  pdf.save(`SITE2025_${formData.firstName}_${formData.lastName}.pdf`);
}
</script>

<style scoped>
.registration-container {
  max-width: 1000px;
  margin: 0 auto;
  padding: 2rem;
  font-family: 'Segoe UI', sans-serif;
  color: #222;
  text-align: center;
}

.title {
  font-size: 2rem;
  font-weight: 700;
}

.title .highlight {
  color: #3b5998;
}

.subtitle {
  font-size: 1rem;
  color: #555;
  margin-top: 0.5rem;
  margin-bottom: 2rem;
}

.form-card {
  background-color: #ffffff;
  padding: 2rem;
  border-radius: 15px;
  box-shadow: 0 4px 16px rgba(0, 0, 0, 0.1);
}

.form-grid {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
  gap: 1rem;
  margin-bottom: 2rem;
}

.form-field {
  text-align: left;
}

.form-field label {
  display: block;
  font-size: 0.9rem;
  margin-bottom: 0.3rem;
  color: #666;
}

.form-field input {
  width: 90%;
  padding: 0.6rem;
  border: 1px solid #dcdcdc;
  border-radius: 6px;
  background-color: #f5f9ff;
  color: #333;
}

.flag img {
  width: 22px;
  height: auto;
  margin-right: 0.5rem;
}

.flag-field {
  width: 90%;
  display: flex;
  align-items: center;
  padding: 0.6rem;
  border: 1px solid #dcdcdc;
  border-radius: 6px;
  background-color: #f5f9ff;
}

.buttons {
  display: flex;
  flex-direction: column;
  align-items: center;
  gap: 1rem;
}

.download,
.edit,
.home {
  padding: 0.8rem 1.5rem;
  border-radius: 10px;
  font-size: 1rem;
  font-weight: bold;
  cursor: pointer;
  transition: all 0.3s ease;
}

.download {
  background-color: #051d37;
  color: white;
  border: none;
}

.download:hover {
  background-color: #0d2b54;
  transform: scale(1.05);
}

.edit {
  background-color: white;
  color: #4e4545;
  border: 2px solid #4e4545;
}

.edit:hover {
  background-color: #4e4545;
  color: white;
  transform: scale(1.05);
}

.home {
  background-color: white;
  color: #265985;
  border: 2px solid #265985;
}

.home:hover {
  background-color: #265985;
  color: white;
  transform: scale(1.05);
}

@media (min-width: 600px) {
  .buttons {
    flex-direction: row;
    justify-content: center;
  }
}
</style>
