```vue
<template>
  <footer class="bg-white shadow-inner-dark">
    <div class="mx-auto max-w-5xl px-4 py-8 sm:px-6 lg:px-8">
      <div class="absolute end-4 top-4 sm:end-6 sm:top-6 lg:end-8 lg:top-8">
        <router-link
          class="inline-block rounded-full bg-[#265985] p-2 text-white shadow-sm transition hover:bg-[#1e4a6e] sm:p-3 lg:p-4"
          to="/#MainContent"
        >
          <span class="sr-only">{{ $t('footer.backToTop') }}</span>
          <svg
            xmlns="http://www.w3.org/2000/svg"
            class="size-5"
            viewBox="0 0 20 20"
            fill="currentColor"
          >
            <path
              fill-rule="evenodd"
              d="M14.707 12.707a1 1 0 01-1.414 0L10 9.414l-3.293 3.293a1 1 0 01-1.414-1.414l4-4a1 1 0 011.414 0l4 4a1 1 0 010 1.414z"
              clip-rule="evenodd"
            />
          </svg>
        </router-link>
      </div>

      <div class="flex flex-col items-center text-[#265985]">
        <div class="flex flex-row gap-4 md:gap-6">
          <img src="../assets/logosite.png" alt="SITE Logo" class="h-12 sm:h-16" />
          <img src="../assets/iset.png" alt="ISET Logo" class="h-12 sm:h-16" />
          <img src="../assets/adt.png" alt="ADT Logo" class="h-12 sm:h-16" />
        </div>
        <span class="mt-4 font-cinzel font-bold text-2xl sm:text-3xl text-[#265985] tracking-wide">
          SITE 2025
        </span>
      </div>

      <div class="mt-6 text-center">
        <p class="mx-auto max-w-md text-sm sm:text-base leading-relaxed text-gray-500">
          {{ $t('footer.description') }}
        </p>
        <p class="mt-2 max-w-md mx-auto text-xs sm:text-sm text-gray-500 italic">
          {{ $t('footer.subtitle') }}
        </p>
      </div>

      <ul class="mt-6 flex justify-center gap-4 sm:gap-6">
        <li>
          <a
            href="https://facebook.com/SITE2025"
            rel="noreferrer"
            target="_blank"
            class="text-gray-700 transition hover:text-gray-700/75"
          >
            <span class="sr-only">Facebook</span>
            <svg class="size-6 sm:size-7" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true">
              <path
                fill-rule="evenodd"
                d="M22 12c0-5.523-4.477-10-10-10S2 6.477 2 12c0 4.991 3.657 9.128 8.438 9.878v-6.987h-2.54V12h2.54V9.797c0-2.506 1.492-3.89 3.777-3.89 1.094 0 2.238.195 2.238.195v2.46h-1.26c-1.243 0-1.63.771-1.63 1.562V12h2.773l-.443 2.89h-2.33v6.988C18.343 21.128 22 16.991 22 12z"
                clip-rule="evenodd"
              />
            </svg>
          </a>
        </li>
        <li>
          <a
            href="https://instagram.com/SITE2025"
            rel="noreferrer"
            target="_blank"
            class="text-gray-700 transition hover:text-gray-700/75"
          >
            <span class="sr-only">Instagram</span>
            <svg class="size-6 sm:size-7" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true">
              <path
                fill-rule="evenodd"
                d="M12.315 2c2.43 0 2.784.013 3.808.06 1.064.049 1.791.218 2.427.465a4.902 4.902 0 011.772 1.153 4.902 4.902 0 011.153 1.772c.247.636.416 1.363.465 2.427.048 1.067.06 1.407.06 4.123v.08c0 2.643-.012 2.987-.06 4.043-.049 1.064-.218 1.791-.465 2.427a4.902 4.902 0 01-1.153 1.772 4.902 4.902 0 01-1.772 1.153c-.636.247-1.363.416-2.427.465-1.067.048-1.407.06-4.123.06h-.08c-2.643 0-2.987-.012-4.043-.06-1.064-.049-1.791-.218-2.427-.465a4.902 4.902 0 01-1.772-1.153 4.902 4.902 0 01-1.153-1.772c-.247-.636-.416-1.363-.465-2.427-.047-1.024-.06-1.379-.06-3.808v-.63c0-2.43.013-2.784.06-3.808.049-1.064.218-1.791.465-2.427a4.902 4.902 0 011.153-1.772A4.902 4.902 0 015.45 2.525c.636-.247 1.363-.416 2.427-.465C8.901 2.013 9.256 2 11.685 2h.63zm-.081 1.802h-.468c-2.456 0-2.784.011-3.807.058-.975.045-1.504.207-1.857.344-.467.182-.8.398-1.15.748-.35.35-.566.683-.748 1.15-.137.353-.3.882-.344 1.857-.047 1.023-.058 1.351-.058 3.807v.468c0 2.456.011 2.784.058 3.807.045.975.207 1.504.344 1.857.182.466.399.8.748 1.15.35.35.683.566 1.15.748.353.137.882.3 1.857.344 1.054.048 1.37.058 4.041.058h.08c2.597 0 2.917-.01 3.96-.058.976-.045 1.505-.207 1.858-.344.466-.182.8-.398 1.15-.748.35-.35.566-.683.748-1.15.137-.353.3-.882.344-1.857.048-1.055.058-1.37.058-4.041v-.08c0-2.597-.01-2.917-.058-3.96-.045-.976-.207-1.505-.344-1.858a3.097 3.097 0 00-.748-1.15 3.098 3.098 0 00-1.15-.748c-.353-.137-.882-.3-1.857-.344-1.023-.047-1.351-.058-3.807-.058zM12 6.865a5.135 5.135 0 110 10.27 5.135 5.135 0 010-10.27zm0 1.802a3.333 3.333 0 100 6.666 3.333 3.333 0 000-6.666zm5.338-3.205a1.2 1.2 0 110 2.4 1.2 1.2 0 010-2.4z"
                clip-rule="evenodd"
              />
            </svg>
          </a>
        </li>
        <li>
          <a
            href="https://github.com/SITE2025"
            rel="noreferrer"
            target="_blank"
            class="text-gray-700 transition hover:text-gray-700/75"
          >
            <span class="sr-only">GitHub</span>
            <svg class="size-6 sm:size-7" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true">
              <path
                fill-rule="evenodd"
                d="M12 2C6.477 2 2 6.484 2 12.017c0 4.425 2.865 8.18 6.839 9.504.5.092.682-.217.682-.483 0-.237-.008-.868-.013-1.703-2.782.605-3.369-1.343-3.369-1.343-.454-1.158-1.11-1.466-1.11-1.466-.908-.62.069-.608.069-.608 1.003.07 1.531 1.032 1.531 1.032.892 1.53 2.341 1.088 2.91.832.092-.647.35-1.088.636-1.338-2.22-.253-4.555-1.113-4.555-4.951 0-1.093.39-1.988 1.029-2.688-.103-.253-.446-1.272.098-2.65 0 0 .84-.27 2.75 1.026A9.564 9.564 0 0112 6.844c.85.004 1.705.115 2.504.337 1.909-1.296 2.747-1.027 2.747-1.027.546 1.379.202 2.398.1 2.651.64.7 1.028 1.595 1.028 2.688 0 3.848-2.339 4.695-4.566 4.943.359.309.678.92.678 1.855 0 1.338-.012 2.419-.012 2.747 0 .268.18.58.688.482A10.019 10.019 0 0022 12.017C22 6.484 17.522 2 12 2z"
                clip-rule="evenodd"
              />
            </svg>
          </a>
        </li>
        <li>
          <a
            href="mailto:contact@site2025.com"
            rel="noreferrer"
            target="_blank"
            class="text-gray-700 transition hover:text-gray-700/75"
          >
            <span class="sr-only">Email</span>
            <i class="fas fa-envelope size-6 sm:size-7"></i>
          </a>
        </li>
        <li>
          <a
            href="https://linkedin.com/company/site2025"
            rel="noreferrer"
            target="_blank"
            class="text-gray-700 transition hover:text-gray-700/75"
          >
            <span class="sr-only">LinkedIn</span>
            <i class="fab fa-linkedin size-6 sm:size-7"></i>
          </a>
        </li>
      </ul>

      <div class="mt-6 text-center">
        <router-link
          to="/contactUs"
          class="text-gray-700 hover:text-gray-700/75 text-sm sm:text-base"
        >
          {{ $t('footer.contact') }}
        </router-link>
      </div>

      <p class="mt-4 text-center text-xs sm:text-sm text-gray-500">
        {{ $t('footer.copyright') }}
      </p>
    </div>
  </footer>
</template>

<script setup>
/* eslint-disable no-unused-vars */
import { useI18n } from 'vue-i18n';
</script>

<style scoped>
@import url('https://fonts.googleapis.com/css2?family=Cinzel:wght@700&display=swap');
@import url('https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css');

footer {
  font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
  position: relative;
  z-index: 10; /* Below navbar (z-index: 50) */
}

.shadow-inner-dark {
  box-shadow: inset 0 10px 15px -3px rgba(0, 0, 0, 0.2);
}

.font-cinzel {
  font-family: 'Cinzel', serif;
  text-shadow: 1px 1px 2px rgba(0, 0, 0, 0.2);
}

.sr-only {
  position: absolute;
  width: 1px;
  height: 1px;
  padding: 0;
  margin: -1px;
  overflow: hidden;
  clip: rect(0, 0, 0, 0);
  white-space: nowrap;
  border-width: 0;
}

@media (max-width: 768px) {
  footer {
    padding-bottom: 4rem; /* Extra space for Back to Top button */
  }

  .flex-row {
    flex-direction: column;
    align-items: center;
    gap: 1rem;
  }

  .flex-row img {
    max-width: 80px; /* Smaller logos on mobile */
    height: auto;
  }

  .font-cinzel {
    font-size: 1.5rem; /* Slightly smaller title */
  }

  .mt-6.text-center {
    margin-top: 1.5rem;
  }

  .mt-6.text-center p {
    max-width: none;
    padding: 0 1rem;
    font-size: 0.875rem; /* Smaller text for description */
  }

  .mt-6.text-center p.italic {
    font-size: 0.75rem; /* Smaller subtitle */
  }

  ul {
    flex-wrap: wrap;
    justify-content: center;
    gap: 1.25rem;
    padding: 0 1rem;
  }

  li a {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    width: 44px; /* Minimum touch target size */
    height: 44px;
  }

  li a i,
  li a svg {
    width: 24px;
    height: 24px;
  }

  .mt-6.text-center router-link {
    font-size: 0.875rem;
  }

  .mt-4.text-center {
    font-size: 0.75rem; /* Smaller copyright text */
  }

  .absolute.end-4.top-4 {
    right: 1rem;
    top: 1rem;
    padding: 0.5rem;
  }

  .absolute.end-4.top-4 svg {
    width: 16px;
    height: 16px;
  }
}

@media (min-width: 769px) {
  .flex-row img {
    max-width: 100%;
  }
}
</style>
```