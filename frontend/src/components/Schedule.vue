<template>
  <section class="schedule-section">
    <div class="schedule-header">
      <h2>{{ $t('schedule.title') }} <span class="highlight">{{ $t('schedule.sched') }}</span></h2>
      <p>
        {{ $t('schedule.description') }}
      </p>
    </div>

    <div class="tabs">
      <div v-for="(day, index) in days" :key="index" :class="['tab', { active: selectedDay === day }]"
        @click="selectedDay = day">
        {{ dayName(day) }}
      </div>
    </div>

    <div class="timeline">
      <div v-for="(event, index) in filteredSchedule" :key="index" class="timeline-item" :class="getColor(index)">
        <div class="circle">
          <span>{{ formatTime(event.time_start, event.time_end) }}</span>
          <!-- Ligne de liaison qui part du cercle sauf le dernier -->
          <div v-if="index !== filteredSchedule.length - 1" class="connector-line" :class="getColor(index)"></div>
        </div>
        <p class="event-title">{{ eventTitle(event.name_fr, event.name_en) }}</p>
      </div>
    </div>
  </section>
</template>

<script>
import ProgramService from '@/services/ProgramService';
import cookieUtils from '@/utils/cookieUtils';
import dayjs from 'dayjs';
import 'dayjs/locale/fr'; // Import French locale for dayjs
import 'dayjs/locale/en'; // Import English locale for dayjs
export default {
  data() {
    return {
      selectedDay: '24 October',
      days: [],
      schedule: []
    };
  },
  computed: {
    filteredSchedule() {
      return this.schedule.filter(event => dayjs(event.time_start).format("YYYY-MM-DD") === this.selectedDay);
    }
  },
  mounted() {
    let editionId = cookieUtils.getCookie('editionId');
    ProgramService.getProgramDates(editionId).then(response => {
      // If response is an array of dates, assign directly
      if (Array.isArray(response)) {
        this.days = response;
      } else if (response && Array.isArray(response.data)) {
        // If using axios, data may be under .data
        this.days = response.data;
      } else {
        // Fallback: try to parse if it's a stringified array
        try {
          this.days = JSON.parse(response);
        } catch (e) {
          console.error('Unexpected days format:', response);
          this.days = [];
        }
      }
      // Set selectedDay to the first element if available
      if (this.days.length > 0) {
        this.selectedDay = this.days[0];
      }
    }).catch(error => {
      console.error('Error fetching days:', error);
    });
    ProgramService.getProgramsByEdition(editionId).then(response => {
      // If response is an array of events, assign directly
      if (Array.isArray(response)) {
        this.schedule = response;
      } else if (response && Array.isArray(response.data)) {
        // If using axios, data may be under .data
        this.schedule = response.data;
      } else {
        // Fallback: try to parse if it's a stringified array
        try {
          this.schedule = JSON.parse(response);
        } catch (e) {
          console.error('Unexpected schedule format:', response);
          this.schedule = [];
        }
      }
    }).catch(error => {
      console.error('Error fetching schedule:', error);
    });
  },
  methods: {
    getColor(index) {
      const colors = ['purple', 'red', 'orange'];
      return colors[index % colors.length];
    },
    formatTime(start, end) {
      const startTime = dayjs(start).format('HH:mm');
      const endTime = dayjs(end).format('HH:mm');
      return `${startTime} - ${endTime}`;
    },
    eventTitle(name_fr, name_en) {
      return this.$i18n.locale === 'fr' ? name_fr : name_en;
    },
    dayName(date) {
      const locale = this.$i18n.locale === 'fr' ? 'fr' : 'en';
      return dayjs(date).locale(locale).format('DD MMMM');
    }
  }
};
</script>

<style scoped>
.schedule-section {
  padding: 2rem;
  font-family: 'Segoe UI', sans-serif;
}

p {
  font-size: 1.2rem; /* Matches hero section's larger text */
  line-height: 1.6; /* For better readability, matching hero */
  color: #444; /* Consistent with hero's paragraph color */
}

h2 {
  font-size: 2.5rem; /* Matches hero's prominent title */
  font-weight: 700; /* Bold weight to match hero's bold titles */
  margin-bottom: 1rem;
}

.schedule-header {
  text-align: center;
}

.schedule-header .highlight {
  color: #005a90; /* Matches hero's blue color */
}

.tabs {
  display: flex;
  justify-content: center;
  gap: 2rem;
  margin: 2rem 0;
  background: #f1f1f1;
  border-radius: 2rem;
  padding: 0.5rem;
  box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2); /* Added dark shadow for prominence */

}

.tab {
  padding: 0.5rem 1.5rem;
  cursor: pointer;
  border-radius: 1rem;
  background: #f1f1f1;
  transition: 0.3s;
  text-transform: capitalize;
  box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2); /* Added dark shadow for prominence */
}

.tab.active {
  background: white;
  border-bottom: 0px solid #005a90; /* Matches hero's blue */
  box-shadow: 0 4px 8px rgba(0, 0, 0, 0.3); /* Slightly darker shadow for active tab */
}

.timeline {
  display: flex;
  flex-wrap: wrap;
  justify-content: center;
  gap: 1rem;
  padding-top: 3rem; /* Increased from 1rem for more top space */
  margin-top: 5rem; /* Added to widen space above timeline */
  margin-bottom: 10rem; /* Added to widen space below timeline */
}

.timeline-item {
  width: 150px;
  text-align: center;
  position: relative;
}

.circle {
  width: 100px;
  height: 100px;
  margin: auto;
  border-radius: 50%;
  background-color: #f0f0f0;
  display: flex;
  align-items: center;
  justify-content: center;
  font-weight: bold;
  margin-bottom: 0.5rem;
  border: 4px dashed;
  position: relative;
}

.purple .circle {
  border-color: #6c5ce7;
}

.red .circle {
  border-color: #d63031;
}

.orange .circle {
  border-color: #e0ab17;
}

.connector-line {
  position: absolute;
  top: 50%;
  right: -110px;
  width: 100px;
  height: 4px;
  background-color: #ccc;
  transform: translateY(-50%);
  z-index: 0;
  border-radius: 2px;
}

.purple .connector-line {
  background-color: #6c5ce7;
}

.red .connector-line {
  background-color: #d63031;
}

.orange .connector-line {
  background-color: #e17055;
}

.event-title {
  font-size: 0.9rem;
  font-weight: bold;
}

.event-sub {
  font-size: 0.8rem;
  color: gray;
}

@media (max-width: 768px) {
  .timeline {
    flex-direction: column;
    align-items: center;
    margin-top: 2rem; /* Adjusted for smaller screens */
    margin-bottom: 2rem; /* Adjusted for smaller screens */
  }

  .timeline-item {
    width: 100%;
  }

  .connector-line {
    display: none;
  }

  p {
    font-size: 1rem; /* Matches hero's responsive paragraph */
  }

  h2 {
    font-size: 2rem; /* Matches hero's responsive title */
  }

  .tab {
    box-shadow: 0 2px 6px rgba(0, 0, 0, 0.2); /* Slightly lighter shadow for smaller screens */
  }

  .tab.active {
    box-shadow: 0 2px 6px rgba(0, 0, 0, 0.3); /* Adjusted for active tab on smaller screens */
  }
}
</style>