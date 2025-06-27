<template>
    <div class="program-container">
      <h2 class="title">The Program</h2>
  
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
            <div class="date">{{ event.date }}</div>
            <div class="name">{{ event.name }}</div>
            <div class="time">{{ event.time }}</div>
          </div>
        </div>
      </div>
  
      <!-- <button class="download-btn">Download The Program</button> -->
        
    </div>
  </template>
  
  <script>
  export default {
    data() {
      return {
        selectedDate: '02/07/2025',
        dates: ['01/07/2025', '02/07/2025', '03/07/2025'],
        program: {
          '01/07/2025': [
            {
              date: '8 November 2021',
              name: 'ORAKOM',
              day: 'Senin',
              time: '09:30 - 12:00',
              top: 0,
              left: 0
            },
            {
              date: '9 November 2021',
              name: 'JARKOM',
              day: 'Selasa',
              time: '07:00 - 09:30',
              top: 0,
              left: 25
            }
          ],
          '02/07/2025': [
            {
              date: '10 November 2021',
              name: 'PPB',
              day: 'Rabu',
              time: '07:15 - 09:45',
              top: 0,
              left: 50
            },
            {
              date: '10 November 2021',
              name: 'DISKRIT',
              day: 'Rabu',
              time: '09:30 - 12:00',
              top: 0,
              left: 75
            },
            {
              date: '11 November 2021',
              name: 'MATRIKS',
              day: 'Kamis',
              time: '09:30 - 12:00',
              top: 150,
              left: 75
            },
            {
              date: '13 November 2021',
              name: 'PPKN',
              day: 'Sabtu',
              time: '07:00 - selesai',
              top: 150,
              left: 50
            },
            {
              date: '15 November 2021',
              name: 'STRUKTUR DATA',
              day: 'Senin',
              time: '13:00 - 14:40',
              top: 150,
              left: 25
            },
            {
              date: '18 November 2021',
              name: 'WEB',
              day: 'Kamis',
              time: '13:00 - 14:40',
              top: 150,
              left: 0
            }
          ],
          '03/07/2025': [
            {
              date: '18 ',
              name: 'test',
              day: 'test',
              time: '13:00 - 14:40',
              top: 150,
              left: 0
            },
            {
              date: '15 November 2021',
              name: 'STRUKTUR DATA',
              day: 'Senin',
              time: '13:00 - 14:40',
              top: 150,
              left: 25
            },
          ]
        }
      }
    },
    computed: {
      filteredProgram() {
        return this.program[this.selectedDate] || []
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
    methods: {
      selectDate(date) {
        this.selectedDate = date
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
  