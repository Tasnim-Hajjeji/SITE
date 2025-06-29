<template>
  <div class="registration-container">
    <h1 class="title">
      Inscription <span class="highlight">réussie !</span>
    </h1>
    <p class="subtitle">
      Imprimez et apportez le formulaire d'inscription généré le jour de l'accueil de SITE 2025 pour faciliter votre admission
    </p>

    <div class="form-card" ref="pdfContent">
      <!-- Header with Logos -->
      <div class="pdf-header">
        <img src="@/assets/iset.png" alt="Logo ISET" class="logo" />
        <img src="@/assets/adt.png" alt="Logo ADT" class="logo" />
        <img src="@/assets/logosite.png" alt="Logo SITE 2025" class="logo" />
      </div>

      <!-- Title and Subtitle -->
      <h2 class="pdf-title">Fiche d'Inscription - SITE 2025</h2>
      <p class="pdf-subtitle">Confirmation d'Inscription | Date : {{ new Date().toLocaleDateString('fr-FR') }}</p>

      <!-- Conference Content Section -->
      <div class="pdf-section">
        <h3 class="section-title">Aperçu de la Conférence SITE 2025</h3>
        <p class="section-text">
          La conférence SITE 2025 réunira des leaders académiques et industriels pour des interventions plénières de haut niveau, des panels d’experts reconnus, ainsi que des présentations de propositions de projets innovants. Des invités de marque enrichiront les débats, offrant une plateforme d’échange d’idées et de perspectives avancées.
        </p>
      </div>

      <!-- Form Data Grid -->
      <div class="form-grid">
        <div class="form-field">
          <label>Nom complet</label>
          <input type="text" :value="formData.firstName + ' ' + formData.lastName" readonly />
        </div>
        <div class="form-field">
          <label>Email</label>
          <input type="text" :value="formData.email" readonly />
        </div>
        <div class="form-field">
          <label>Téléphone</label>
          <input type="text" :value="formData.phone" readonly />
        </div>
        <div class="form-field flag">
          <label>Pays</label>
          <div class="flag-field">
            <img :src="`https://flagcdn.com/${formData.country}.svg`" alt="Drapeau du pays" />
            <span>{{ formData.full_country }}</span>
          </div>
        </div>
        <div class="form-field">
          <label>Profession</label>
          <input type="text" :value="formData.profession" readonly />
        </div>
        <div class="form-field">
          <label>Établissement</label>
          <input type="text" :value="formData.institution" readonly />
        </div>
        <div class="form-field">
          <label>Participation</label>
          <input type="text" :value="formData.participation" readonly />
        </div>
        <div class="form-field">
          <label>Hébergement</label>
          <input type="text" :value="formData.hebergement" readonly />
        </div>
        <div class="form-field">
          <label>Nombre d'adultes accompagnants</label>
          <input type="text" :value="formData.adultCompanions" readonly />
        </div>
        <div class="form-field">
          <label>Nombre d'enfants accompagnants</label>
          <input type="text" :value="formData.childCompanions" readonly />
        </div>
        <div class="form-field">
          <label>Supplément single</label>
          <input type="text" :value="formData.singleSupplement" readonly />
        </div>
        <div class="form-field">
          <label>Nuit supplémentaire</label>
          <input type="text" :value="formData.extraNights" readonly />
        </div>
        <div class="form-field">
          <label>Méthode de paiement</label>
          <input type="text" :value="formData.paymentMethod" readonly />
        </div>
        <div class="form-field">
          <label>Montant total</label>
          <input type="text" :value="`${formData.totalPrice} ${currency}`" readonly />
        </div>
      </div>

      <!-- Accommodation Extensions Section -->
      <div class="pdf-section">
        <h3 class="section-title">Les Extensions d'Hébergement</h3>
        <p class="section-text">
          Les extensions d'hébergement offrent un accès prolongé aux ateliers spécialisés, à la présence de figures influentes du secteur, à des discours stimulants, ainsi qu’à des propositions de projets novateurs et des idées d’excellence. Les options d'hébergement garantissent un confort optimal, adapté aux besoins des participants, avec des chambres single disponibles sous réserve de la capacité de l’hôtel. Pour toute demande spéciale, veuillez contacter l'ADT - Association  de Developpement Technologique au (+216) 62 362 232 ou 71 995 577, en présentant une copie de l'ordre de virement lors du séminaire.
        </p>
      </div>

      <!-- Signature Section -->
      <div class="pdf-section signature-section">
        <h3 class="section-title">Signature du Participant</h3>
        <p class="section-text">Veuillez signer ci-dessous pour confirmer votre inscription :</p>
        <div class="signature-space" style="height: 50px; border-bottom: 1px solid #dcdcdc; margin-top: 0.5rem;"></div>
      </div>

      <!-- Footer -->
      <div class="pdf-footer">
        <p>Détails de Paiement : ADT - Association  de Developpement Technologique, ISET Bizerte, Tunisie | ID Fiscal : xxxxxxx/1</p>
        <p>Contact : (+216) 62 362 232 | Email : info@adt.tn</p>
      </div>
    </div>

    <br />

    <div class="buttons">
      <button class="download  animated-image" @click="downloadPDF">
        <i class="fas fa-download"></i> Télécharger la fiche d'inscription
      </button>
      <button class="home" @click="handleReturnHome">Retour à l'Accueil</button>
    </div>
  </div>
</template>

<script setup>
import ParticipantService from '@/services/ParticipantService'
import cookieUtils from '@/utils/cookieUtils'
import { reactive, onMounted, ref } from 'vue'
import { useRouter } from 'vue-router'
import jsPDF from 'jspdf'
import axios from 'axios'
import html2canvas from 'html2canvas'

const router = useRouter()
const pdfContent = ref(null)
const currency = ref('')

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
  currency.value = formType === 'tunisian' ? "DT" : "€";
  const personalInfo = formType === 'tunisian' ? JSON.parse(localStorage.getItem('tunisian_form')) : JSON.parse(localStorage.getItem('stranger_form'))
  const accommodationInfo = JSON.parse(localStorage.getItem('accommodation_form') || '{}')
  const totalPrice = parseInt(localStorage.getItem('totalPrice') || '0', 10)
  Object.assign(formData, personalInfo, accommodationInfo)
  formData.totalPrice = totalPrice
  formData.paymentMethod = localStorage.getItem("paymentMethod") || ''
  fetchCountryName(formData.country)

  const blobUrl = localStorage.getItem("recu_blob_url")
  if(blobUrl){
  fetch(blobUrl).then(res => res.blob()).then(blob => {
    const file = new File([blob],"receipt_file",{type: blob.type})
    const form_req = form_request(formData)
    ParticipantService.createParticipant(form_req, file).then((response) => {
      console.log("Succès !!" + response.data)
    }).catch((error) => {
      console.error('Erreur lors de la création du participant :', error);
    });
  })}
  else{
    const form_req = form_request(formData)
    ParticipantService.createParticipant(form_req).then((response) => {
      console.log("Succès !!" + response.data)
    }).catch((error) => {
      console.error('Erreur lors de la création du participant :', error);
    });
  }
})

function form_request(formData){
  const form = new FormData();
  form.append("nom",formData.lastName); form.append("prenom", formData.firstName);
  form.append("email", formData.email); form.append("fonction", formData.profession);
  form.append("tel", formData.phone); form.append("pays", formData.country);
  form.append("est_tunisien", formData.country === "tn" ? true : false);
  form.append("participation", formData.participation);
  form.append("accomodation", formData.hebergement === "yes" ? true : false)
  form.append("etablissement", formData.institution);
  form.append("num_enfant", formData.childCompanions);
  form.append("num_adulte", formData.adultCompanions);
  form.append("supp_single", formData.singleSupplement === "no" ? false : true)
  form.append("supp_nuit", formData.extraNights);
  form.append("prix_total", formData.totalPrice);
  let edition = cookieUtils.getCookie("editionId");
  form.append("edition_id", (typeof edition) === "string" ? parseInt(edition,10) : edition );
  form.append("methode_paie", formData.paymentMethod);
  return form;
}

function handleReturnHome() {
  localStorage.clear()
  router.push('/')
}

async function fetchCountryName(countryCode) {
  try {
    const response = await axios.get(`https://restcountries.com/v3.1/alpha/${countryCode}`);
    formData.full_country = response.data[0].name.common; // Accéder au nom complet
  } catch (error) {
    console.error(error);
  }
}

async function downloadPDF() {
  const element = pdfContent.value;

  // Vérifier si l'élément a une taille
  if (!element || element.offsetWidth === 0 || element.offsetHeight === 0) {
    alert('Le formulaire d\'inscription n\'est pas visible ou n\'a pas de taille !');
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
  pdf.addImage(imgData, 'PNG', x, 25, pdfWidth, pdfHeight);
  pdf.save(`SITE2025_${formData.firstName}_${formData.lastName}.pdf`);
}
</script>

<style scoped>
.registration-container {
  max-width: 1000px;
  margin: 0 auto;
  padding: 2rem;
  font-family: 'Arial', sans-serif; /* Changed to a more professional font */
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

.pdf-header {
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin-bottom: 1.5rem;
}

.logo {
  height: 80px;
  width: auto;
  object-fit: contain;
}

.pdf-title {
  font-size: 1.5rem;
  font-weight: 700;
  color: #051d37;
  margin-bottom: 0.5rem;
}

.pdf-subtitle {
  font-size: 0.9rem;
  color: #666;
  margin-bottom: 1.5rem;
}

.pdf-section {
  margin-bottom: 1.5rem;
  padding: 1rem;
  background-color: #f5f9ff;
  border-radius: 10px;
}

.section-title {
  font-size: 1.2rem;
  font-weight: 600;
  color: #051d37;
  margin-bottom: 0.5rem;
}

.section-text {
  font-size: 0.95rem; /* Slightly increased for readability */
  color: #444;
  line-height: 1.6; /* Improved line spacing for professionalism */
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
.animated-image {
  animation: pulse 1s ease-in-out infinite alternate;
}

.form-field label {
  display: block;
  font-size: 0.95rem; /* Slightly increased for professionalism */
  margin-bottom: 0.3rem;
  color: #666;
  font-weight: 500; /* Added weight for a formal look */
}

.form-field input {
  width: 90%;
  padding: 0.6rem;
  border: 1px solid #dcdcdc;
  border-radius: 6px;
  background-color: #f5f9ff;
  color: #333;
  font-size: 0.95rem; /* Consistent with labels */
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

.signature-section {
  margin-bottom: 1rem;
}

.signature-space {
  height: 50px;
  border-bottom: 1px solid #dcdcdc;
  margin-top: 0.5rem;
}

.pdf-footer {
  margin-top: 1.5rem;
  padding-top: 1rem;
  border-top: 1px solid #dcdcdc;
  font-size: 0.85rem; /* Slightly adjusted for consistency */
  color: #666;
  text-align: center;
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