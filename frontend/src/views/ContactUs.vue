<template>
  <section class="contact">
    <div class="container mx-auto max-w-screen-xl px-4 sm:px-6 lg:px-8">
      <div class="form-container bg-white shadow-lg rounded-lg p-6 sm:p-8">
        <h1 class="text-3xl font-bold text-[#265985] mb-6 text-center">{{ $t('contact.title') }}</h1>
        <p class="text-gray-600 mb-8 text-center">{{ $t('contact.description') }}</p>
        <form @submit.prevent="submitForm" class="space-y-6">
          <div>
            <label for="name" class="block text-sm font-medium text-gray-700">{{ $t('contact.name') }}</label>
            <input
              id="name"
              v-model="form.name"
              type="text"
              :placeholder="$t('contact.namePlaceholder')"
              class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-[#265985] focus:ring-[#265985] transition-all duration-200"
              required
            />
          </div>
          <div>
            <label for="email" class="block text-sm font-medium text-gray-700">{{ $t('contact.email') }}</label>
            <input
              id="email"
              v-model="form.email"
              type="email"
              :placeholder="$t('contact.emailPlaceholder')"
              class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-[#265985] focus:ring-[#265985] transition-all duration-200"
              required
            />
          </div>
          <div>
            <label for="subject" class="block text-sm font-medium text-gray-700">{{ $t('contact.subject') }}</label>
            <input
              id="subject"
              v-model="form.subject"
              type="text"
              :placeholder="$t('contact.subjectPlaceholder')"
              class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-[#265985] focus:ring-[#265985] transition-all duration-200"
              required
            />
          </div>
          <div>
            <label for="message" class="block text-sm font-medium text-gray-700">{{ $t('contact.message') }}</label>
            <textarea
              id="message"
              v-model="form.message"
              :placeholder="$t('contact.messagePlaceholder')"
              rows="5"
              class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-[#265985] focus:ring-[#265985] transition-all duration-200"
              required
            ></textarea>
          </div>
          <div class="text-center">
            <button
              type="submit"
              class="inline-block bg-[#265985] text-white font-semibold px-6 py-3 rounded-md shadow-md hover:bg-[#1e4a6e] hover:scale-105 transition-all duration-200"
            >
              {{ $t('contact.submit') }}
            </button>
          </div>
        </form>
      </div>
    </div>
  </section>
</template>

<script setup>
import { ref } from 'vue';
import ContactService from '@/services/ContactService';

const form = ref({
  name: '',
  email: '',
  subject: '',
  message: ''
});

const submitForm = async () => {
  console.log('Form submitted:', form.value);
  const response = await ContactService.addContact(form.value);
  console.log(response);

  form.value = { name: '', email: '', subject: '', message: '' }; // Reset form
};
</script>

<style scoped>
.contact {
  font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
  background: #f9fafb;
  padding: 2rem 0;
  position: relative;
  z-index: 10; /* Below navbar (z-index: 50) */
}

.form-container {
  max-width: 600px;
  margin: 0 auto;
}

.shadow-lg {
  box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
}

input,
textarea {
  font-size: 1rem;
  padding: 0.75rem;
  border: 1px solid #d1d5db;
}

input:focus,
textarea:focus {
  outline: none;
  box-shadow: 0 0 0 3px rgba(38, 89, 133, 0.3);
}

@media (max-width: 768px) {
  .contact {
    padding: 1rem 0;
  }

  .form-container {
    padding: 1.5rem;
  }

  h1 {
    font-size: 2rem;
  }

  p {
    font-size: 0.9rem;
  }

  input,
  textarea {
    font-size: 0.9rem;
    padding: 0.5rem;
  }

  button {
    padding: 0.5rem 1.5rem;
    font-size: 0.9rem;
  }
}
</style>