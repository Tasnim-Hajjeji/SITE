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

        <div :class="['input-group', {disabled: paymentMethod === 'Cash'}]">
          <label class="fixed-label">Proof of Payment *</label>
          <input type="file" accept="image/png, image/jpeg, image/jpg, .png, .jpeg, .jpg" @change="handleFileUpload($event)" :disabled="paymentMethod === 'Cash'" />
        </div>

          <button type="submit" class="submit-btn" @click.prevent="postFile()">Submit</button>
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
import { reactive, onMounted, ref, watch } from 'vue'
import { useRouter } from 'vue-router'
import priceService from '@/services/FormPrices'
import cookieUtils from '@/utils/cookieUtils'

const activeStep = 3
const paymentMethod = ref('')
const prices = ref({})
const totalPrice = ref(0)
const recu_paie = ref(null)
const router = useRouter()

const formData = reactive({
  hebergement: '',
  adultCompanions: 0,
  childCompanions: 0,
  singleSupplement: '',
  extraNights: 0
})

onMounted(() => {
  const formType = localStorage.getItem("form_type");
  let editionId = cookieUtils.getCookie('editionId')
  priceService.getPrizesByEdition(editionId).then(dataArr => {
    if (Array.isArray(dataArr) && dataArr.length > 0) {
      if (formType === 'tunisian') {
        prices.value = {
          prix_initial: dataArr[0].prix_tun || 0,
          prix_hebergement: dataArr[0].prix_tun_hebergement || 0,
          prix_acc_adulte: dataArr[0].prix_acc_adulte_tun || 0,
          prix_acc_enfant: dataArr[0].prix_acc_enfant_tun || 0,
          prix_single_supp: dataArr[0].prix_single_supp_tun || 0,
          prix_nuit_supp: dataArr[0].prix_nuit_supp_tun || 0,
          prix_article: dataArr[0].prix_article_tun || 0
        }
      } else if (formType === 'stranger') {
        prices.value = {
          prix_initial: dataArr[0].prix_international || 0,
          prix_hebergement: dataArr[0].prix_eur_hebergement || 0,
          prix_acc_adulte: dataArr[0].prix_acc_adulte_eur || 0,
          prix_acc_enfant: dataArr[0].prix_acc_enfant_eur || 0,
          prix_single_supp: dataArr[0].prix_single_supp_eur || 0,
          prix_nuit_supp: dataArr[0].prix_nuit_supp_eur || 0,
          prix_article: dataArr[0].prix_article_eur || 0
        }
      } else {
        console.error('Unknown form type:', formType);
        prices.value = {};
      }
    } else {
      prices.value = {}
      console.error('Price data not found in response:', dataArr)
    }
  }).catch(error => {
    console.error('Error fetching Price:', error)
  });
  const saved = JSON.parse(localStorage.getItem('accommodation_form') || '{}')
  Object.assign(formData, saved)
})

watch(
  paymentMethod,
  (newVal) => {
    localStorage.setItem("paymentMethod", newVal);
  },
  { deep: true }
);

function handleFileUpload(event){
  recu_paie.value = event.target.files[0]
}

function postFile(){
  if(recu_paie.value){
    const file = recu_paie.value
    const blobUrl = URL.createObjectURL(file)
    localStorage.setItem("recu_blob_url", blobUrl)
  }
  router.push("/reg-success");
}

function calculateTotal() {
  let price = prices.value;
  var total = price.prix_initial // Base price

  let form = localStorage.getItem("form_type") === 'tunisian' ? JSON.parse(localStorage.getItem('tunisian_form')) : JSON.parse(localStorage.getItem('stranger_form'))
  let participation = form.participation
  total += participation === "With paper" ? price.prix_article : 0;

  if (formData.hebergement === 'yes') {
    const adults = formData.adultCompanions
    const children = formData.childCompanions
    const persons = adults + children

    total += price.prix_hebergement;
    total += adults * price.prix_acc_adulte;
    total += children * price.prix_acc_enfant;
    total += formData.extraNights * price.prix_nuit_supp * persons;

    if (formData.singleSupplement === 'yes') {
      total += price.prix_single_supp * persons;
    }
  }
  let currency = localStorage.getItem("form_type") === 'tunisian' ? 'TND' : '€';
  totalPrice.value = total;
  localStorage.setItem('totalPrice', total);
  return total + ' ' + currency;
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

.input-group.disabled{
  pointer-events: none;
  color: #666;
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
