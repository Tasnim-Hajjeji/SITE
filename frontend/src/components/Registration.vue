<template>
  <div class="registration-wrapper">
    <h2 class="title">
      Fill in the form below to complete your registration for
      <span class="highlight">SITE 2025</span>
    </h2>

    <form class="registration-form">
      <fieldset class="section">
        <legend>Accommodation *</legend>
        <label>
          <input type="radio" name="hebergement" value="yes" v-model="form.hebergement" />
          Yes
        </label>
        <label>
          <input type="radio" name="hebergement" value="no" v-model="form.hebergement" />
          No
        </label>
        <ul class="tariffs">
          <li>Adult companion 220 DT /night/person</li>
          <li>Child companion + 2 Adults 110 DT /night/child</li>
          <li>Child companion + 1 Adult 155 DT /night/child</li>
        </ul>
      </fieldset>

      <div class="input-group">
        <label>Number of child companions *</label>
        <input
          type="number"
          min="0"
          max="10"
          placeholder="0"
          v-model.number="form.childCompanions"
          required
        />
      </div>

      <div class="input-group">
        <label>Number of adult companions *</label>
        <input
          type="number"
          min="0"
          max="10"
          placeholder="0"
          v-model.number="form.adultCompanions"
          required
        />
      </div>

      <fieldset class="section">
        <legend>Single Supplement *</legend>
        <label>
          <input type="radio" name="single" value="yes" v-model="form.singleSupplement" />
          Yes
        </label>
        <label>
          <input type="radio" name="single" value="no" v-model="form.singleSupplement" />
          No
        </label>
        <p>Single supplement 70 DT /night</p>
      </fieldset>

      <div class="input-group">
        <label>Extra Night *</label>
        <input
          type="number"
          min="0"
          max="10"
          placeholder="0"
          v-model.number="form.extraNights"
          required
        />
        <p>Extra night 220 DT /night/person</p>
      </div>
    </form>

    <div class="note">
      <p>✅ After completing the online registration, please save/print the registration form.</p>
      <p>✅ The printed registration form can help you when preparing the purchase order.</p>
      <p>✅ Please bring the printed registration form on the day of SNTS reception to facilitate your admission.</p>
    </div>

    <div class="nav-buttons">
      <router-link to="/tunisian-form">
        <i class="fas fa-arrow-left"></i>
      </router-link>

      <div class="dots">
        <span :class="{ active: activeStep === 1 }"></span>
        <span :class="{ active: activeStep === 2 }"></span>
        <span :class="{ active: activeStep === 3 }"></span>
      </div>

      <router-link to="/registration-final">
        <i class="fas fa-arrow-right"></i>
      </router-link>
    </div>
  </div>
</template>

<script setup>
import { reactive, onMounted, watch } from "vue";

const activeStep = 2;

const form = reactive({
  hebergement: "",
  childCompanions: 0,
  adultCompanions: 0,
  singleSupplement: "",
  extraNights: 0,
});

// Charger les données sauvegardées à l'ouverture du composant
onMounted(() => {
  const saved = localStorage.getItem("site2025_accommodation_form");
  if (saved) {
    Object.assign(form, JSON.parse(saved));
  }
});

// Sauvegarder automatiquement les données à chaque changement
watch(
  form,
  (newVal) => {
    localStorage.setItem("site2025_accommodation_form", JSON.stringify(newVal));
  },
  { deep: true }
);
</script>

<style scoped>
.registration-wrapper {
  max-width: 800px;
  margin: auto;
  padding: 1.5rem;
  font-family: "Segoe UI", sans-serif;
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
  background: #fff;
  border: 1px solid #d0d0ff;
  border-radius: 12px;
  padding: 2rem;
  margin: 2rem 0;
  box-shadow: 0 0 10px rgba(60, 90, 255, 0.2);
  display: flex;
  flex-direction: column;
  gap: 1.5rem;
}

.section {
  padding: 1rem;
  border: 1px solid #ccc;
  border-radius: 8px;
  background: #f9fbff;
  text-align: left;
}

.section legend {
  font-size: 1rem;
  font-weight: bold;
  color: #145a7d;
  margin-bottom: 0.5rem;
}

.section label {
  display: inline-block;
  margin-right: 1rem;
  margin-top: 0.5rem;
  font-weight: normal;
  color: #333;
}

.tariffs {
  margin-top: 0.8rem;
  padding-left: 1.5rem;
  font-size: 0.9rem;
  color: #555;
}

.input-group {
  display: flex;
  flex-direction: column;
  align-items: flex-start;
  gap: 0.4rem;
  text-align: left;
}

.input-group label {
  font-weight: bold;
  color: #145a7d;
}

.input-group input[type="number"] {
  width: 100%;
  max-width: 300px;
  padding: 0.5rem;
  border-radius: 8px;
  border: 1px solid #ccc;
  font-size: 0.95rem;
}

.input-group p {
  font-size: 0.85rem;
  color: #666;
  margin-top: 0.3rem;
}

input[type="radio"] {
  margin-right: 5px;
  accent-color: #145a7d;
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

@media screen and (max-width: 600px) {
  .registration-form {
    padding: 1.2rem;
    gap: 1.2rem;
  }

  .section label {
    display: block;
    margin-bottom: 0.3rem;
  }

  .input-group input[type="number"] {
    width: 100%;
  }

  .title {
    font-size: 1.2rem;
  }

  .highlight {
    font-size: 1.4rem;
  }

  .nav-buttons {
    justify-content: center;
    gap: 2rem;
  }
}
</style>
