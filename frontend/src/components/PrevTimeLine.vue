<template>
    <div class="program-container">
      <h2 class="title">{{ $t('prevTimeLine.title') }}</h2>

      <div class="date-tabs">
        <button
          v-for="(date, index) in dates"
          :key="index"
          :class="['tab', { active: selectedDate === date }]"
          @click="selectDate(date)"
        >
          {{ date }}
        </button>
      </div>
  
      <div class="timeline">
        <!-- Lines between events -->
        <div class="lines">
          <div
            v-for="(line, index) in timelineLines"
            :key="index"
            class="line"
            :style="{
              top: line.top + 'px',
              left: line.left + '%',
              width: line.width + '%',
              height: line.height + 'px'
            }"
          ></div>
        </div>
  
        <!-- Events -->
        <div
          v-for="(event, index) in filteredProgram"
          :key="index"
          class="event-box"
          :style="{ top: event.top + 'px', left: event.left + '%' }"
        >
          <div class="circle"></div>
          <div class="event-content">
            <div class="name">{{ eventName(event.name_fr,event.name_en) }}</div>
            <div class="time">{{ eventTime(event.time_start,event.time_end) }}</div>
          </div>
        </div>
      </div>
  
      <!-- <button class="download-btn">Download The Program</button> -->
        
    </div>
  </template>
  
  <script>
  import ProgramService from '@/services/ProgramService.js'
  import dayjs from 'dayjs'
  import 'dayjs/locale/fr' // Import French locale for dayjs
  import 'dayjs/locale/en' // Import English locale for dayjs
  export default {
    props: {
      editionId: {
        type: Number,
        required: true
      }
    },
    data() {
      return {
        selectedDate: '02/07/2025',
        dates: [],
        program: []
      }
    },
    computed: {
      filteredProgram() {
        return this.program.filter(event => dayjs(event.time_start).format("YYYY-MM-DD") === this.selectedDate)
      },
      timelineLines() {
        const events = this.filteredProgram
        const lines = []
  
        for (let i = 0; i < events.length - 1; i++) {
          const from = events[i]
          const to = events[i + 1]
  
          if (from.top === to.top) {
            lines.push({
              top: from.top + 40,
              left: Math.min(from.left, to.left) + 13,
              width: Math.abs(to.left - from.left) - 10,
              height: 2
            })
          } else if (from.left === to.left) {
            lines.push({
              top: Math.min(from.top, to.top) + 40,
              left: from.left + 13,
              width: 2,
              height: Math.abs(to.top - from.top) - 20
            })
          }
        }
  
        return lines
      }
    },
    mounted() {
    ProgramService.getProgramDates(this.editionId).then(response => {
      // If response is an array of dates, assign directly
      if (Array.isArray(response)) {
        this.dates = response;
      } else if (response && Array.isArray(response.data)) {
        // If using axios, data may be under .data
        this.dates = response.data;
      } else {
        // Fallback: try to parse if it's a stringified array
        try {
          this.dates = JSON.parse(response);
        } catch (e) {
          console.error('Unexpected days format:', response);
          this.dates = [];
        }
      }
      // Set selectedDay to the first element if available
      if (this.dates.length > 0) {
        this.selectedDate = this.dates[0];
      }
    }).catch(error => {
      console.error('Error fetching days:', error);
    });
    ProgramService.getProgramsByEdition(this.editionId).then(response => {
      // If response is an array of events, assign directly
      if (Array.isArray(response)) {
        this.program = response;
      } else if (response && Array.isArray(response.data)) {
        // If using axios, data may be under .data
        this.program = response.data;
      } else {
        // Fallback: try to parse if it's a stringified array
        try {
          this.program = JSON.parse(response);
        } catch (e) {
          console.error('Unexpected schedule format:', response);
          this.program = [];
        }
      }
    }).catch(error => {
      console.error('Error fetching schedule:', error);
    });
  },
    methods: {
      selectDate(date) {
        this.selectedDate = date
      },
      eventName(name_fr, name_en) {
        return this.$i18n.locale === 'fr' ? name_fr : name_en
      },
      eventTime(time_start, time_end){
        const startTime = dayjs(time_start).format('HH:mm')
        const endTime = dayjs(time_end).format('HH:mm')
        return `${startTime} - ${endTime}`
      }
    }
  }
  </script>
  
  <style scoped>
  .program-container {
    max-width: 1200px;
    margin: auto;
    padding: 20px;
    text-align: center;
    font-family: 'Segoe UI', sans-serif;
  }
  
  .title {
    font-size: 1.7rem;
    font-weight: bold;
    margin-bottom: 20px;
    color:#265985;
  }
  
  .date-tabs {
    display: flex;
    justify-content: center;
    gap: 10px;
    margin-bottom: 30px;
  }
  
  .tab {
    padding: 10px 20px;
    border-radius: 10px;
    border: 1px solid #ccc;
    background: #fff;
    color: #265985;
    font-weight: bold;
    cursor: pointer;
    transition: all 0.3s;
  }
  
  .tab.active {
    background-color: #265985;
    color: #fff;
  }
  
  .timeline {
    position: relative;
    min-height: 300px;
  }
  
  .event-box {
    position: absolute;
    width: 160px;
    background: white;
    border-radius: 15px;
    padding: 15px;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
    transition: transform 0.3s;
    z-index: 1;
  }
  
  .circle {
    width: 15px;
    height: 15px;
    background-color: #ccc;
    border-radius: 50%;
    margin: auto;
    margin-bottom: 10px;
  }
  
  .event-content .date {
    font-size: 12px;
    color: #555;
    margin-bottom: 5px;
  }
  
  .event-content .name {
    font-weight: bold;
    margin-bottom: 3px;
  }
  
  .event-content .day,
  .event-content .time {
    font-size: 14px;
    color: #333;
  }
  
  .download-btn {
    margin-top: 30px;
    margin-bottom: 20px;
    padding: 10px 30px;
    font-size: 15px;
    background-color: #265985;
    color: white;
    border: none;
    border-radius: 10px;
    cursor: pointer;
    transition: background 0.3s;
  }
  
  .download-btn:hover {
    background-color: #2d3e6f;
  }
  
  .lines {
    position: absolute;
    width: 100%;
    height: 100%;
    top: 0;
    left: 0;
    pointer-events: none;
  }
  
  .line {
    position: absolute;
    background-color: #999;
    z-index: 0;
    border-radius: 2px;
  }
  
  @media (max-width: 768px) {
    .timeline {
      min-height: auto;
    }
    .event-box {
      position: relative;
      margin: 20px auto;
      left: auto !important;
      top: auto !important;
    }
    .lines {
      display: none;
    }
  }
  </style>
  