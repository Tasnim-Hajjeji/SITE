<template>
  <section class="login-section">
    <div class="container max-w-md mx-auto">
      <h2 class="text-2xl font-bold text-blue-800 mb-6 text-center">Connexion Admin</h2>
      <form @submit.prevent="handleSubmit" class="space-y-6 bg-white p-8 rounded-xl shadow-2xl transform transition-all duration-300 hover:shadow-3xl">
        <div>
          <label for="email" class="block text-sm text-gray-600">Adresse e-mail</label>
          <input
            id="email"
            type="email"
            v-model="form.email"
            placeholder="Entrez votre e-mail"
            required
            class="w-full px-4 py-3 mt-1 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-600 focus:border-blue-600 outline-none transition-all duration-200 bg-gray-50 text-gray-800 placeholder-gray-400"
          />
        </div>
        <div>
          <label for="password" class="block text-sm text-gray-600">Mot de passe</label>
          <input
            id="password"
            type="password"
            v-model="form.password"
            placeholder="Entrez votre mot de passe"
            required
            class="w-full px-4 py-3 mt-1 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-600 focus:border-blue-600 outline-none transition-all duration-200 bg-gray-50 text-gray-800 placeholder-gray-400"
          />
        </div>
       
        <div class="flex justify-center">
          <button
            type="submit"
            class="w-full px-6 py-3 bg-blue-800 text-white font-semibold rounded-lg hover:bg-blue-900 transform hover:-translate-y-1 transition-all duration-200"
            :disabled="isSubmitting"
          >
            <span v-if="isSubmitting" class="flex items-center justify-center">
              <svg class="animate-spin h-5 w-5 mr-2 text-white" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
              </svg>
              Connexion en cours...
            </span>
            <span v-else>Se connecter</span>
          </button>
        </div>
      </form>
      <p v-if="error" class="mt-4 text-center text-sm text-red-600">{{ error }}</p>
    </div>
  </section>
</template>

<script setup>
import { ref } from 'vue';

const form = ref({
  email: '',
  password: '',
});
const isSubmitting = ref(false);
const error = ref(null);

const handleSubmit = async () => {
  isSubmitting.value = true;
  error.value = null;
  try {
    // Placeholder for authentication logic (e.g., API call)
    console.log('Form submitted:', form.value);
    // Simulate API call
    await new Promise(resolve => setTimeout(resolve, 1000));
    // Reset form on success
    form.value = { email: '', password: '' };
  } catch (err) {
    error.value = 'Erreur lors de la connexion. Veuillez vérifier vos identifiants.';
    console.error('Login error:', err);
  } finally {
    isSubmitting.value = false;
  }
};


</script>

<style scoped>
@import url("https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css");

.login-section {
  font-family: 'Segoe UI', sans-serif;
  @apply min-h-screen flex items-center justify-center bg-gray-100 py-12;
}

.container {
  @apply w-full;
}

.shadow-3xl {
  box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
}
</style>