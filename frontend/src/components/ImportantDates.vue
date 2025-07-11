<template>
  <div class="important-dates">
    <h2 class="title">{{ $t('importantDates.title') }}</h2>
    <div class="timeline">
      <div class="timeline-item" v-for="(dateItem, index) in formattedDates" :key="dateItem.id || index"
        :class="{ left: index % 2 === 0, right: index % 2 !== 0 }">
        <div class="content">
          <div class="date-circle">{{ dateItem.formattedDate }}</div>
          <div class="text-box">{{ currentLocale === 'fr' ? dateItem.title_fr : dateItem.title_en }}</div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import ImportantDateService from '@/services/ImportantDatesService';
export default {
  name: 'ImportantDates',
  props: {
    editionId: {
      type: Number,
      required: true
    }
  },
  data() {
    return {
      dates: []
    };
  },
  computed: {
    formattedDates() {
      return this.dates.map(item => {
        const dateObj = new Date(item.date);
        return {
          ...item,
          formattedDate: this.formatDayMonth(dateObj)
        };
      }).sort((a, b) => new Date(a.date) - new Date(b.date)); // Sort by date
    },

    currentLocale() {
      return this.$i18n.locale;
    },
  },
  mounted() {
    this.fetchImportantDates();
  },
  methods: {
    async fetchImportantDates() {
      try {
        const response = await ImportantDateService.getDatesByEdition(this.editionId);
        this.dates = response.data;
      } catch (error) {
        console.error('Error fetching edition dates:', error);
      }
    },
    formatDayMonth(date) {
      // Returns format like "24 / 10" (day / month)
      const day = date.getDate();
      const month = date.getMonth() + 1; // Months are 0-indexed

      return `${day} / ${month}`;
    }
  },
  watch: {
    editionId: {
      immediate: true,
      handler(newId) {
        if (newId) {
          this.fetchImportantDates();
        }
      }
    }
  }
};
</script>

<style scoped>
.important-dates {
  background-color: #245785;
  padding: 2rem 1rem;
  color: white;
  text-align: center;
  font-family: 'Segoe UI', sans-serif;
}

.title {
  font-size: 1.7rem;
  font-weight: bold;
  margin-bottom: 3rem;
}

.timeline {
  display: flex;
  flex-direction: column;
  gap: 1rem;
  align-items: center;
  position: relative;
}

.timeline-item {
  width: 100%;
  display: flex;
  justify-content: flex-start;
  position: relative;
  max-width: 700px;
 
}

.timeline-item.right {
  justify-content: flex-end;
  
}

.content {
  display: flex;
  align-items: center;
  background: linear-gradient(145deg, #e0e0e0, #ffffff);
  border-radius: 50px;
  box-shadow: 8px 8px 16px #1b4468, -8px -8px 16px #2d6aa2;
  transition: transform 0.3s ease;
  overflow: hidden;
  box-shadow: 
    0 0 20px rgba(255, 255, 255, 0.8),
    0 0 40px rgba(255, 255, 255, 0.6),
    0 0 0px rgba(255, 255, 255, 0.4);
}

.content:hover {
  transform: scale(1.02);
}

.text-box {
  padding: 1rem;
  color: #333;
  font-size: 1rem;
  min-width: 280px;
  text-align: center;
}

.timeline-item.right .text-box {
  border-radius: 0 50px 50px 0;
  order: 2;
  text-align: center;
}

.date-circle {
  width: 50px;
  height: 45px;
  background-color: #bcd5e6;
  box-shadow: 
    0 0 15px rgba(255, 255, 255, 0.7),
    0 0 30px rgba(255, 255, 255, 0.5),
    0 0 50px rgba(255, 255, 255, 0.3);
  border-radius: 50%;
  display: flex;
  align-items: center;
  justify-content: center;
  font-weight: bold;
  font-size: 0.7rem;
  color: #333;
  margin: 0.5rem;
}

/* ✅ Responsive styling: date left, text right */
@media (max-width: 768px) {

  .timeline-item,
  .timeline-item.right {
    justify-content: center !important;
  }


  .date-circle {
    order: 1;
    width: 40px;
    height: 40px;
    font-size: 0.6rem;
    margin: 0;
  }

  .text-box {
    order: 2;
    padding: 0.1rem;
    font-size: 0.9rem;
    color: #333;
  text-align: center;

    width: auto;
  }
  
  .title {
    font-size: 1.3rem; /* نقصنا حجم عنوان القسم */
    margin-bottom: 2rem; /* نقصنا المسافة تحت العنوان */
  }
}


</style>