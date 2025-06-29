<template>
  <div class="partners-section">
    <div class="section-title">
      <h2>{{ $t('partners.title') }}</h2>
    </div>
    <div class="carousel" @mouseover="isHovered = true" @mouseleave="isHovered = false">
      <div class="carousel-track" :class="{ paused: isHovered }">
        <div v-for="(partner, index) in duplicatedPartners" :key="partner.id + '-' + index" class="logo">
          <img :src="getImageUrl(partner.image_url)" :alt="partner.name" />
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import PartenaireService from '@/services/PartenaireService';

export default {
  name: 'PartnersCarousel',
  props: {
    editionId: {
      type: Number,
      required: true
    }
  },
  data() {
    return {
      isHovered: false,
      partners: []
    };
  },
  computed: {
    duplicatedPartners() {
      // Duplicate the array for seamless looping
      return [...this.partners, ...this.partners, ...this.partners, ...this.partners];
    }
  },
  async mounted() {
    await this.fetchPartners();
  },
  methods: {
    async fetchPartners() {
      try {
        const response = await PartenaireService.getPartenairesByEdition(this.editionId);
        this.partners = response.data;
      } catch (error) {
        console.error('Error fetching partners:', error);
      }
    },
    getImageUrl(imagePath) {
      return `http://localhost:8000/storage/${imagePath}`;
    },
  },
   watch: {
    editionId: {
      immediate: true,
      handler(newId) {
        if (newId) {
          this.fetchPartners();
        }
      }
    }
  }
};
</script>

<style scoped>
.partners-section {
  padding: 2rem 0;
  background-color: #fff;
  position: relative;
  font-family: 'Segoe UI', sans-serif;
}

.section-title {
  display: flex;
  align-items: center;
  justify-content: center;
  margin-bottom: 2rem;
  position: relative;
  text-align: center;
}

.section-title::before,
.section-title::after {
  content: "";
  flex: 1;
  height: 1px;
  background-color: #0c5fa6;
  margin: 0 1rem;
}

.section-title h2 {
  font-size: 1.2rem;
  color: #0c5fa6;
  font-weight: bold;
  border: 1px solid #0c5fa6;
  border-radius: 999px;
  padding: 0.5rem 2rem;
  background-color: #fff;
  white-space: nowrap;
}

.carousel {
  overflow: hidden;
  width: 100%;
}

.carousel-track {
  display: flex;
  animation: scroll 30s linear infinite;
  align-items: center;
}

.carousel-track.paused {
  animation-play-state: paused;
}

.logo {
  flex: 0 0 auto;
  padding: 0 1.5rem;
  display: flex;
  align-items: center;
  justify-content: center;
}

.logo img {
  height: 60px;
  width: auto;
  object-fit: contain;
  transition: transform 0.3s ease;
}

.logo img:hover {
  transform: scale(1.05);
}

@keyframes scroll {
  0% {
    transform: translateX(0);
  }
  100% {
    transform: translateX(-50%);
  }
}

@media (max-width: 768px) {
  .logo img {
    height: 40px;
  }

  .section-title h2 {
    font-size: 1rem;
    padding: 0.3rem 1.2rem;
  }
}
</style>