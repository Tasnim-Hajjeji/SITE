<template>
    <section class="schedule-section">
      <div class="schedule-header">
        <h2>{{ $t('schedule.title') }} <span class="highlight">{{ $t('schedule.sched') }}</span></h2>
        <p>
          {{ $t('schedule.description') }}
        </p>
      </div>
  
      <div class="tabs">
        <div
          v-for="(day, index) in days"
          :key="index"
          :class="['tab', { active: selectedDay === day.date } ]"
          @click="selectedDay = day.date"
        >
          {{ day.date }}
        </div>
      </div>
  
      <div class="timeline">
        <div
          v-for="(event, index) in filteredSchedule"
          :key="index"
          class="timeline-item"
          :class="getColor(index)"
        >
          <div class="circle">
            <span>{{ event.time }}</span>
            <!-- Ligne de liaison qui part du cercle sauf le dernier -->
            <div v-if="index !== filteredSchedule.length - 1" class="connector-line" :class="getColor(index)"></div>
          </div>
          <p class="event-title">{{ event.title }}</p>
          <p class="event-sub">{{ event.subtitle }}</p>
        </div>
      </div>
    </section>
  </template>
  
  <script>
  export default {
    data() {
      return {
        selectedDay: '24 October',
        days: [
          { date: '24 October' },
          { date: '25 October' },
          { date: '26 October' },
        ],
        schedule: [
          // Day 1
          { date: '24 October', time: '8:00 - 10:30', title: 'Frontend Basics', subtitle: '+ Backend Introduction' },
          { date: '24 October', time: '10:45 - 12:30', title: 'HTML/CSS Workshop', subtitle: '+ Node.js Setup' },
          { date: '24 October', time: '13:30 - 15:00', title: 'JS DOM Manipulation', subtitle: '+ Express Overview' },
          { date: '24 October', time: '15:15 - 17:00', title: 'Vue Basics', subtitle: '+ API Integration' },
          { date: '24 October', time: '17:15 - 18:30', title: 'Team Coding', subtitle: '+ Backend Routes' },
  
          // Day 2
          { date: '25 October', time: '8:00 - 10:30', title: 'Vue Routing', subtitle: '+ MongoDB Setup' },
          { date: '25 October', time: '10:45 - 12:30', title: 'Component Reuse', subtitle: '+ Authentication' },
          { date: '25 October', time: '13:30 - 15:00', title: 'State Management', subtitle: '+ Database Models' },
          { date: '25 October', time: '15:15 - 17:00', title: 'Advanced UI', subtitle: '+ Server Logic' },
          { date: '25 October', time: '17:15 - 18:30', title: 'Deployment Prep', subtitle: '+ Testing' },
  
          // Day 3
          { date: '26 October', time: '8:00 - 10:30', title: 'Polishing UI', subtitle: '+ Optimization' },
          { date: '26 October', time: '10:45 - 12:30', title: 'Final Integration', subtitle: '+ Bug Fixing' },
          { date: '26 October', time: '13:30 - 15:00', title: 'Demo Rehearsal', subtitle: '+ Final Tests' },
          { date: '26 October', time: '15:15 - 17:00', title: 'Project Presentation', subtitle: '+ Feedback' },
          { date: '26 October', time: '17:15 - 18:30', title: 'Closing Ceremony', subtitle: '+ Networking' },
        ]
      };
    },
    computed: {
      filteredSchedule() {
        return this.schedule.filter(event => event.date === this.selectedDay);
      }
    },
    methods: {
      getColor(index) {
        const colors = ['purple', 'red', 'orange'];
        return colors[index % colors.length];
      }
    }
  };
  </script>
  
  <style scoped>
  .schedule-section {
    padding: 2rem;
    font-family: 'Segoe UI', sans-serif;
  }
  p{
    font-size: 1rem;
  }
  h2{
    font-size: 1.7rem;
    margin-bottom: 1rem;
  }
  .schedule-header {
    text-align: center;
  }
  
  .schedule-header .highlight {
    color: #1d74c4;
  }
  
  .tabs {
    display: flex;
    justify-content: center;
    gap: 2rem;
    margin: 2rem 0;
    background: #f1f1f1;
    border-radius: 2rem;
    padding: 0.5rem;
  }
  
  .tab {
    padding: 0.5rem 1.5rem;
    cursor: pointer;
    border-radius: 1rem;
    background: #f1f1f1;
    transition: 0.3s;
  }
  
  .tab.active {
    background: white;
    border-bottom: 4px solid #1d74c4;
  }
  
  .timeline {
    display: flex;
    flex-wrap: wrap;
    justify-content: center;
    gap: 1rem;
    padding-top: 1rem;
  }
  
  .timeline-item {
    width: 150px;
    text-align: center;
    position: relative;
  }
  
  .circle {
    width: 90px;
    height: 90px;
    margin: auto;
    border-radius: 50%;
    background-color: #f0f0f0;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: bold;
    margin-bottom: 0.5rem;
    border: 4px dashed;
    position: relative; /* pour positionner la ligne relative à ce cercle */
  }
  
  /* Bordures colorées des cercles */
  .purple .circle {
    border-color: #6c5ce7;
  }
  .red .circle {
    border-color: #d63031;
  }
  .orange .circle {
    border-color: #e17055;
  }
  
  /* Ligne de liaison entre cercles */
  .connector-line {
    position: absolute;
    top: 50%;
    right: -110px; /* espace entre cercles + marge */
    width: 100px;
    height: 4px;
    background-color: #ccc;
    transform: translateY(-50%);
    z-index: 0;
    border-radius: 2px;
  }
  
  /* Couleurs ligne selon event */
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
  
  /* Responsive : ligne devient verticale entre les cercles */
  @media (max-width: 768px) {
    .timeline {
    flex-direction: column;
    align-items: center;
  }
  .timeline-item {
    width: 100%;
  }
  .connector-line {
    display: none; /* <-- ligne cachée sur petit écran */
  }
   
  }
  </style>
  