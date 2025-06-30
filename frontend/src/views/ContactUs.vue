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

    <!-- Modal -->
    <div v-if="showModal" class="modal-overlay" @click.self="closeModal">
      <div class="modal-content">
        <h2 v-if="isSuccess" class="modal-title text-[#265985]">{{ $t('contact.successTitle') }}</h2>
        <h2 v-else class="modal-title text-red-600">{{ $t('contact.errorTitle') }}</h2>
        <p v-if="isSuccess" class="modal-message">{{ $t('contact.successMessage') }}</p>
        <p v-else class="modal-message">{{ $t('contact.errorMessage') }}</p>
        <button
          @click="closeModal"
          class="modal-btn bg-[#265985] text-white px-4 py-2 rounded-md hover:bg-[#1e4a6e] transition-all duration-200"
        >
          {{ isSuccess ? $t('contact.close') : $t('contact.retry') }}
        </button>
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

const showModal = ref(false);
const isSuccess = ref(false);
const errorMessage = ref('');

const submitForm = async () => {
  try {
    console.log('Form submitted:', form.value);
    const response = await ContactService.addContact(form.value);
    console.log(response);
    isSuccess.value = true;
    form.value = { name: '', email: '', subject: '', message: '' }; // Reset form
  } catch (error) {
    console.error('Error submitting form:', error);
    isSuccess.value = false;
    errorMessage.value = error.message || 'An unexpected error occurred.';
  } finally {
    showModal.value = true;
  }
};

const closeModal = () => {
  showModal.value = false;
  if (!isSuccess.value) {
    form.value = { name: '', email: '', subject: '', message: '' }; // Reset on retry
  }
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

.modal-overlay {
  position: fixed;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  background: rgba(0, 0, 0, 0.5);
  display: flex;
  justify-content: center;
  align-items: center;
  z-index: 1000;
}

.modal-content {
  background: white;
  padding: 2rem;
  border-radius: 8px;
  text-align: center;
  max-width: 400px;
  width: 90%;
  box-shadow: 0 4px 12px rgba(0, 0, 0, 0.2);
}

.modal-title {
  font-size: 1.5rem;
  margin-bottom: 1rem;
}

.modal-message {
  font-size: 1rem;
  color: #333;
  margin-bottom: 1.5rem;
}

.modal-btn {
  font-size: 1rem;
  cursor: pointer;
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

  .modal-content {
    padding: 1.5rem;
  }

  .modal-title {
    font-size: 1.2rem;
  }

  .modal-message {
    font-size: 0.9rem;
  }

  .modal-btn {
    font-size: 0.9rem;
    padding: 0.4rem 1rem;
  }
}
</style>