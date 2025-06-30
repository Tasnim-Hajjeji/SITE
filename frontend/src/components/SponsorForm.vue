<template>
  <section class="sponsor-form-section">
    <div class="header">
      <h1>{{ $t("sponsors.welcome") }}</h1>
      <p>{{ $t("sponsors.come_on") }}</p>
    </div>

    <form class="sponsor-form" @submit.prevent="handleSubmit">
      <div class="form-group">
        <label>{{ $t("sponsors.name") }} *</label>
        <input type="text" v-model="form.name" required />
      </div>

      <div class="form-group">
        <label>{{ $t("sponsors.address") }} *</label>
        <input type="text" v-model="form.adresse" required />
      </div>

      <div class="form-group">
        <label>Email *</label>
        <input type="email" v-model="form.email" required />
      </div>

      <div class="form-group">
        <label>{{ $t("sponsors.phone") }} *</label>
        <input type="tel" v-model="form.phone" required />
      </div>

      <div class="form-group">
        <label>{{ $t("sponsors.logo") }} *</label>
        <input type="file" @change="handleFileUpload" required />
      </div>

      <div class="form-group">
        <label>Description *</label>
        <textarea rows="4" v-model="form.description" required></textarea>
      </div>

      <button class="submit-btn" type="submit">{{ $t("sponsors.submit") }}</button>
    </form>

    <!-- MODAL -->
    <div v-if="showModal" class="modal-overlay">
      <div class="modal-content">
        <p>
          {{ $t("sponsors.appreciate") }}
        </p>
        <button @click="closeModal" class="modal-button">ok</button>
      </div>
    </div>
  </section>
</template>

<script>
import cookieUtils from '@/utils/cookieUtils';
import SponsorService from '@/services/SponsorService';
export default {
  data() {
    return {
      form: {
        name: '',
        adresse: '',
        email: '',
        phone: '',
        logo: null,
        description: '',
        etat: 'pending',
        edition_id: cookieUtils.getCookie("editionId")
      },
      showModal: false,
    };
  },
  methods: {
    handleFileUpload(event) {
      this.form.logo = event.target.files[0];
    },
    handleSubmit() {
      console.log("Form submitted:", this.form);
      // TODO: envoyer les données au backend via API
      SponsorService.createSponsor(this.form)
        .then(response => {
          console.log("Application submitted successfully:", response);
          this.showModal = true;
        })
        .catch(error => {
          console.error("Error submitting application:", error);
        });
    },
    closeModal() {
      this.showModal = false;
      this.resetForm();
    },
    resetForm() {
      this.form = {
        name: '',
        adresse: '',
        email: '',
        phone: '',
        logo: null,
        description: '',
        etat: 'pending',
        edition_id: cookieUtils.getCookie("editionId")
      };
    },
  },
};
</script>

<style scoped>
.sponsor-form-section {
  display: flex;
  flex-direction: column;
  align-items: center;
  padding: 40px 20px;
  background-color: #195872;
  min-height: 100vh;
  color: white;
  text-align: center;
  font-family: 'Segoe UI', sans-serif;
}

.header h1 {
  font-size: 2rem;
  margin-bottom: 0.5rem;
}

.header p {
  font-size: 1.1rem;
  margin-bottom: 2rem;
  color: #dceef8;
}

.sponsor-form {
  background-color: #ffffff; /* Bright white background */
  padding: 30px 25px;
  border-radius: 16px;
  box-shadow: 0 8px 15px rgba(255, 255, 255, 0.4); /* Luminous outer glow, reduced blur */
  width: 100%;
  max-width: 500px;
  color: #333;
}

.form-group {
  text-transform: capitalize;
  margin-bottom: 20px;
  text-align: left;
}

label {
  display: block;
  margin-bottom: 6px;
  font-weight: 600;
  font-size: 0.95rem;
}

input[type="text"],
input[type="email"],
input[type="tel"],
input[type="file"],
textarea {
  width: 100%;
  padding: 10px 14px;
  border: 1px solid #ccc;
  border-radius: 8px;
  font-size: 0.95rem;
}

textarea {
  resize: vertical;
}

.submit-btn {
  margin-top: 10px;
  width: 100%;
  padding: 12px;
  background-color: #195872;
  color: white;
  border: none;
  border-radius: 8px;
  font-size: 1rem;
  cursor: pointer;
  transition: background-color 0.3s;
}

.submit-btn:hover {
  background-color: #238d8b;
}

/* Modal Styling */
.modal-overlay {
  position: fixed;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  background: rgba(0, 0, 0, 0.4);
  display: flex;
  justify-content: center;
  align-items: center;
  z-index: 999;
}

.modal-content {
  background: white;
  padding: 30px 20px;
  border-radius: 12px;
  max-width: 400px;
  width: 90%;
  text-align: center;
  font-family: 'Segoe UI', sans-serif;
  color: #222;
  box-shadow: 0 0 20px rgba(0, 0, 0, 0.3);
}

.modal-button {
  margin-top: 20px;
  padding: 10px 20px;
  background-color: #195872;
  color: white;
  border: none;
  border-radius: 8px;
  font-size: 1rem;
  cursor: pointer;
}

.modal-button:hover {
  background-color: #238d8b;
}

/* Responsive */
@media (max-width: 600px) {
  .header h1 {
    font-size: 1.6rem;
  }

  .header p {
    font-size: 1rem;
  }

  .sponsor-form {
    padding: 20px;
  }
}
</style>