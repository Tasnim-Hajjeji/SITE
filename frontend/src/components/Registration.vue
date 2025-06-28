<template>
  <div class="registration-wrapper">
    <h2 class="title">
      Fill in the form below to complete your registration for
      <span class="highlight">SITE 2025</span>
    </h2>

    <form class="registration-form" ref="formElement" @submit.prevent="proceedIfValid" novalidate>
      <fieldset class="section">
        <legend>Accommodation *</legend>
        <div class="div_sep"></div>
        <label>
          <input type="radio" name="hebergement" value="yes" v-model="form.hebergement" />
          Yes
        </label>
        <label>
          <input type="radio" name="hebergement" value="no" v-model="form.hebergement" />
          No
        </label>
        <p class="error" v-if="errors.hebergement">{{ errors.hebergement }}</p>

        <ul class="tariffs" v-if="form.hebergement === 'yes'">
          <li>Adult companion 220 DT</li>
          <li>Child companion 110 DT </li>
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
          :disabled="form.hebergement === 'no'"
        />
        <p class="error" v-if="errors.childCompanions">{{ errors.childCompanions }}</p>
      </div>

      <div class="input-group">
        <label>Number of adult companions *</label>
        <input
          type="number"
          min="0"
          max="10"
          placeholder="0"
          v-model.number="form.adultCompanions"
          :disabled="form.hebergement === 'no'"
        />
        <p class="error" v-if="errors.adultCompanions">{{ errors.adultCompanions }}</p>
      </div>

      <fieldset class="section">
        <legend>Single Supplement *</legend>
        <div class="div_sep"></div>
        <label>
          <input type="radio" name="single" value="yes" v-model="form.singleSupplement" :disabled="form.hebergement === 'no'" />
          Yes
        </label>
        <label>
          <input type="radio" name="single" value="no" v-model="form.singleSupplement" :disabled="form.hebergement === 'no'" />
          No
        </label>
        <p class="error" v-if="errors.singleSupplement">{{ errors.singleSupplement }}</p>
        <p class="mt-2 text-[#555]">Single supplement 70 DT /night</p>
      </fieldset>

      <div class="input-group">
        <label>Extra Night *</label>
        <input
          type="number"
          min="0"
          max="10"
          placeholder="0"
          v-model.number="form.extraNights"
          :disabled="form.hebergement === 'no' || form.singleSupplement === 'no'"
        />
        <p class="error" v-if="errors.extraNights">{{ errors.extraNights }}</p>
        <p>Extra night 220 DT /night/person</p>
      </div>
    </form>

    <div class="note">
      <p>✅ After completing the online registration, please save/print the registration form.</p>
      <p>✅ The printed registration form can help you when preparing the purchase order.</p>
      <p>✅ Please bring the printed registration form on the day of reception to facilitate your admission.</p>
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
import { reactive, ref, onMounted, watch } from "vue";
import { useRouter } from "vue-router";

const activeStep = 2;
const router = useRouter();
const formElement = ref(null);

const form = reactive({
  hebergement: "",
  childCompanions: 0,
  adultCompanions: 0,
  singleSupplement: "",
  extraNights: 0,
});

const errors = reactive({
  hebergement: "",
  childCompanions: "",
  adultCompanions: "",
  singleSupplement: "",
  extraNights: "",
});

function validateForm() {
  let valid = true;

  if (!form.hebergement) {
    errors.hebergement = "Please select if accommodation is needed.";
    valid = false;
  } else {
    errors.hebergement = "";
  }

  if (form.childCompanions < 0 || form.childCompanions > 10) {
    errors.childCompanions = "Enter a value between 0 and 10.";
    valid = false;
  } else {
    errors.childCompanions = "";
  }

  if (form.adultCompanions < 0 || form.adultCompanions > 10) {
    errors.adultCompanions = "Enter a value between 0 and 10.";
    valid = false;
  } else {
    errors.adultCompanions = "";
  }

  if (!form.singleSupplement) {
    errors.singleSupplement = "Please indicate if you want the single supplement.";
    valid = false;
  } else {
    errors.singleSupplement = "";
  }

  if (form.extraNights < 0 || form.extraNights > 10) {
    errors.extraNights = "Enter a value between 0 and 10.";
    valid = false;
  } else {
    errors.extraNights = "";
  }

  return valid;
}

function proceedIfValid() {
  if (validateForm()) {
    router.push("/registration-final");
  } else {
    formElement.value?.reportValidity?.();
  }
}

function backToPrevious() {
  localStorage.removeItem("accommodation_form");
  router.go(-1);
}

onMounted(() => {
  const saved = localStorage.getItem("accommodation_form");
  if (saved) {
    Object.assign(form, JSON.parse(saved));
  }
});

watch(
  form,
  (newVal) => {
    localStorage.setItem("accommodation_form", JSON.stringify(newVal));
  },
  { deep: true }
);

watch(() => form.hebergement, (val) => {
  if (val === 'no') {
    form.childCompanions = 0;
    form.adultCompanions = 0;
    form.singleSupplement = 'no';
    form.extraNights = 0;
  }
});

watch(() => form.singleSupplement, (val) => {
  if (val === 'no') {
    form.extraNights = 0;
  }
});
</script>

<style scoped>
.registration-wrapper {
  max-width: 800px;
  margin: auto;
  padding: 1.5rem;
  font-family: "Segoe UI", sans-serif;
  text-align: center;
}

.div_sep{
  visibility: hidden;
  margin-top: -1.2rem;
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
  margin-top: 0.6rem;
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
