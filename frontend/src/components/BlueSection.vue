<template>
  <section class="blue-section">
    <div class="top-curve"></div>
    <br><br><br><br>
    <h2 class="section-title">{{ $t('blueSection.title') }}</h2><br>
    <div class="info-grid">
      <div class="info-item">
        <i class="fas fa-calendar-alt"></i>
        <p>{{ formattedDateRange }}</p>
      </div>
      <div class="info-item">
        <i class="fas fa-map-marker-alt"></i>
        <p>{{ editionData.place }}</p>
      </div>
      <div class="info-item">
        <i class="fas fa-users"></i>
        <p>{{ $t('blueSection.items.participants') }}</p>
      </div>
      <div class="info-item">
        <i class="fas fa-lightbulb"></i>
        <p>{{ $t('blueSection.items.purpose') }}</p>
      </div>
      <div class="info-item">
        <i class="fas fa-microphone"></i>
        <p>{{ $t('blueSection.items.activities') }}</p>
      </div>
      <div class="info-item">
        <i class="fas fa-globe"></i>
        <p>{{ $t('blueSection.items.goal') }}</p>
      </div>
    </div>
  </section>
</template>

<script>
export default {
  name: 'BlueSection',
  props: {
    editionData: {
      type: Object,
      required: true
    }
  },
  computed: {
    formattedDateRange() {
      const start = new Date(this.editionData.start_date);
      const end = new Date(this.editionData.end_date);
      const locale = this.$i18n.locale; // Get current locale

      // For French locale
      if (locale === 'fr') {
        return this.formatDateRangeFR(start, end);
      }
      // Default to English
      return this.formatDateRangeEN(start, end);

    },
  },
  methods: {
    formatDateRangeEN(start, end) {
      if (start.getMonth() === end.getMonth() && start.getFullYear() === end.getFullYear()) {
        return `${this.formatMonthEN(start)} ${start.getDate()}-${end.getDate()}, ${start.getFullYear()}`;
      }
      else if (start.getFullYear() === end.getFullYear()) {
        return `${this.formatMonthEN(start)} ${start.getDate()}-${this.formatMonthEN(end)} ${end.getDate()}, ${start.getFullYear()}`;
      }
      return `${this.formatMonthEN(start)} ${start.getDate()}, ${start.getFullYear()} - ${this.formatMonthEN(end)} ${end.getDate()}, ${end.getFullYear()}`;
    },

    formatDateRangeFR(start, end) {
      if (start.getMonth() === end.getMonth() && start.getFullYear() === end.getFullYear()) {
        return `${start.getDate()}-${end.getDate()} ${this.formatMonthFR(start)} ${start.getFullYear()}`;
      }
      else if (start.getFullYear() === end.getFullYear()) {
        return `${start.getDate()} ${this.formatMonthFR(start)}-${end.getDate()} ${this.formatMonthFR(end)} ${start.getFullYear()}`;
      }
      return `${start.getDate()} ${this.formatMonthFR(start)} ${start.getFullYear()} - ${end.getDate()} ${this.formatMonthFR(end)} ${end.getFullYear()}`;
    },

    formatMonthEN(date) {
      return date.toLocaleString('en', { month: 'long' });
    },

    formatMonthFR(date) {
      // French month names (lowercase as per French typography rules)
      const months = [
        'janvier', 'février', 'mars', 'avril', 'mai', 'juin',
        'juillet', 'août', 'septembre', 'octobre', 'novembre', 'décembre'
      ];
      return months[date.getMonth()];
    }
  }

}
</script>

<style scoped>
.blue-section {
  background-color: #265985;
  font-family: 'Segoe UI', sans-serif;
  color: white;
  text-align: center;
  padding-bottom: 3%;
  position: relative;
  overflow: hidden;
}

.top-curve {
  position: absolute;
  top: -100px;
  left: 0;
  width: 100%;
  height: 200px;
  background-color: white;
  border-bottom-left-radius: 100% 100px;
  border-bottom-right-radius: 100% 100px;
  z-index: 1;
}

.section-title {
  position: relative;
  z-index: 2;
  font-size: 1.7rem;
  font-weight: bold;
  margin: 3rem 0 2rem;
}

.info-grid {
  position: relative;
  z-index: 2;
  display: flex;
  flex-wrap: wrap;
  justify-content: center;
  gap: 1.7rem;
  max-width: 95%;
  margin: 0 auto;
  padding: 0 1rem;
}

.info-item {
  width: 160px;
  display: flex;
  flex-direction: column;
  align-items: center;
  font-size: 0.95rem;
  text-align: center;
}

.info-item i {
  font-size: 1rem;
  /* Réduit depuis 1.8rem */
  margin-bottom: 0.5rem;
  background-color: #0f3b72;
  padding: 0.7rem;
  /* Réduit depuis 1rem */
  border-radius: 10px;
}
@media (max-width: 768px) {
  .section-title {
    font-size: 1.3rem; /* صغرنا العنوان من 1.7rem إلى 1.3rem */
    margin: 2rem 0 1.5rem;
  }

  .info-grid {
    gap: 1rem; /* نقصنا الفجوة بين العناصر */
    margin-bottom: 3rem;
  }

  .info-item {
    width: 120px; /* نقصنا عرض العنصر */
    font-size: 0.8rem; /* صغرنا الخط */
  }

  .info-item i {
    font-size: 0.85rem; /* صغرنا الأيقونات */
    padding: 0.4rem; /* نقصنا padding الأيقونات */
    border-radius: 8px; /* خففنا شوية الفيرجن */
  }

  .blue-section {
    padding-bottom: 2%;
  }

  .top-curve {
    height: 150px; /* نقصنا ارتفاع الجزء الأبيض العلوي */
    top: -75px;
    border-bottom-left-radius: 100% 75px;
    border-bottom-right-radius: 100% 75px;
  }
}

</style>