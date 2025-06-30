<template>
  

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