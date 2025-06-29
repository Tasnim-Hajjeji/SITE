<template>
  <section class="voices-header relative w-full h-64 md:h-80 lg:h-96">
    <div class="absolute inset-0 flex">
      <!-- Left side with gradient and text -->
      <div class="w-1/2 bg-gradient-to-r from-gray-600 to-transparent flex items-center justify-start">
        <div class="text-white text-left px-4 md:px-8">
          <h2 class="text-4xl md:text-4xl lg:text-5xl font-bold">
            {{ $t('voices.title') }} <span class="text-blue-800">{{ $t('voices.conf') }}!</span>
          </h2>
          <p class="text-base md:text-lg lg:text-xl mt-4 max-w-md">
            {{ $t('voices.subtitle') }}
          </p>
        </div>
      </div>
      <!-- Right side with image -->
      <div class="w-1/2 flex items-center justify-end">
        <img src="../assets/intervenant2.png" alt="Conference experts" class="h-full object-cover">
      </div>
    </div>
  </section>

  <!-- New title section below voices-header with translations -->
  <section class="page-title-section py-8 bg-gradient-to-b from-white to-gray-50 text-center">
    <h1 class="text-3xl md:text-4xl lg:text-5xl font-semibold text-gray-800 relative inline-block">
      {{ $t('page.title') }}
      <span class="absolute bottom-[-10px] left-1/2 transform -translate-x-1/2 w-16 h-1 bg-blue-600 rounded-full"></span>
    </h1>
    <p class="text-sm md:text-base text-gray-600 mt-2">{{ $t('page.subtitle') }}</p>
  </section>
</template>

<style scoped>
.voices-header {
  font-family: 'Segoe UI', sans-serif;
  box-shadow: 0 12px 24px -8px rgba(59, 62, 73, 0.5);
  margin-bottom: 50px;
}

.bg-gradient-to-r {
  height: 100%;
  width: 50%;
}

img {
  max-height: 100%;
  width: auto;
}

.page-title-section {
  position: relative;
  z-index: 10;
}

h1 .bg-blue-600 {
  transition: all 0.3s ease;
}

h1:hover .bg-blue-600 {
  width: 20%;
}
</style>