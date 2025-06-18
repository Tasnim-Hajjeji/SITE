<template>
  <div class="programme-container">
    <h1 class="title">Programme</h1>

    <!-- Action Button -->
    <div class="action-buttons">
      <button class="add-btn">
        <i class="fas fa-plus"></i> Add
      </button>
    </div>

    <!-- Date Selector -->
    <div class="date-selector">
      <button
        v-for="date in dates"
        :key="date"
        :class="['date-btn', { active: selectedDate === date }]"
        @click="selectDate(date)"
      >
        {{ date }}
      </button>
    </div>

    <!-- Timeline -->
    <div class="timeline">
      <div class="event" v-for="(event, index) in filteredEvents" :key="index">
        <div class="circle"></div>
        <div class="content">
          <div class="event-header">
            <div class="time">{{ event.time }}</div>
            <div class="buttons">
              <button class="small-btn update-btn">
                <i class="fas fa-pen"></i>
              </button>
              <button class="small-btn delete-btn">
                <i class="fas fa-trash"></i>
              </button>
            </div>
          </div>
          <div class="label">{{ event.title }}</div>
          <div class="description">{{ event.description }}</div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  data() {
    return {
      dates: ["01/07/2025", "02/07/2025", "03/07/2025"],
      selectedDate: "02/07/2025",
      events: [
        {
          date: "01/07/2025",
          time: "08:00 — 09h30",
          title: "Workshop",
          description: "Kickoff meeting for the new project",
        },
        {
          date: "01/07/2025",
          time: "10:00 — 11h30",
          title: "Code Review",
          description: "Team code review session for Module A",
        },
        {
          date: "02/07/2025",
          time: "08:00 — 09h30",
          title: "Workshop",
          description: "Task planning and discussion",
        },
        {
          date: "02/07/2025",
          time: "10:00 — 11h00",
          title: "Meeting",
          description: "Client sync-up on deliverables",
        },
        {
          date: "03/07/2025",
          time: "09:00 — 10h00",
          title: "Training",
          description: "Internal workshop for onboarding",
        },
      ],
    };
  },
  computed: {
    filteredEvents() {
      return this.events.filter((e) => e.date === this.selectedDate);
    },
  },
  methods: {
    selectDate(date) {
      this.selectedDate = date;
    },
  },
};
</script>

<style scoped>
.programme-container {
  padding: 2rem;
  max-width: 1200px;
  margin: auto;
  font-family: 'Poppins', sans-serif;
  animation: fadeInUp 0.6s ease;
}

@keyframes fadeInUp {
  0% {
    opacity: 0;
    transform: translateY(40px);
  }
  100% {
    opacity: 1;
    transform: translateY(0);
  }
}

.title {
  font-size: 1.7rem;
  margin-bottom: 1rem;
  color: black;
  position: relative;
}

.title::after {
  content: "";
  width: 80px;
  height: 4px;
  background: #00a6a6;
  display: block;
  margin-top: 8px;
  border-radius: 2px;
}

/* Action Button */
.action-buttons {
  display: flex;
  gap: 1rem;
  margin-bottom: 2rem;
  flex-wrap: wrap;
}
.action-buttons button {
  padding: 8px 16px;
  border-radius: 25px;
  border: 1px solid;
  cursor: pointer;
  font-weight: 500;
  transition: all 0.3s ease;
}
.add-btn {
  border-color: #268557;
  color: #268557;
  background-color: #fff;
}
.add-btn:hover {
  background-color: #268557;
  color: #fff;
}

/* Date Selector */
.date-selector {
  display: flex;
  flex-wrap: wrap;
  gap: 0.5rem;
  margin: 2rem 0;
}
.date-btn {
  border: 2px solid #00a6a6;
  padding: 0.3rem 1.2rem;
  border-radius: 20px;
  background: white;
  color: #1b2d56;
  font-weight: 600;
  font-size: 14px;
  transition: all 0.3s ease;
  cursor: pointer;
}
.date-btn.active {
  background: #00a6a6;
  color: white;
  font-weight: 700;
  box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
}

/* Timeline */
.timeline {
  position: relative;
  padding-left: 20px;
  border-left: 2px solid #98c2e9;
  margin-bottom: 2rem;
}
.event {
  display: flex;
  align-items: flex-start;
  margin-bottom: 2rem;
  position: relative;
}
.circle {
  width: 14px;
  height: 14px;
  background: white;
  border: 2px solid #98c2e9;
  border-radius: 50%;
  position: absolute;
  left: -7px;
  top: 0.3rem;
}
.content {
  margin-left: 1.5rem;
  flex: 1;
}
.event-header {
  display: flex;
  justify-content: space-between;
  align-items: center;
}
.buttons {
  display: flex;
  gap: 0.5rem;
}
.small-btn {
  padding: 4px 8px;
  font-size: 0.8rem;
  border-radius: 6px;
  border: 1px solid;
  cursor: pointer;
  transition: all 0.3s ease;
  display: flex;
  align-items: center;
  justify-content: center;
}
.update-btn {
  border-color: #265985;
  color: #265985;
  background-color: #fff;
}
.update-btn:hover {
  background-color: #265985;
  color: #fff;
}
.delete-btn {
  border-color: #e53935;
  color: #e53935;
  background-color: #fff;
}
.delete-btn:hover {
  background-color: #e53935;
  color: #fff;
}

.time {
  font-size: 13px;
  font-weight: 400;
  color: #6b7280;
  margin-bottom: 0.3rem;
}
.label {
  font-weight: 700;
  font-size: 16px;
  color: #1b2d56;
  margin-bottom: 0.2rem;
}
.description {
  font-size: 14px;
  color: #1b2d56;
  line-height: 1.5;
}

/* Responsive Styles */
@media (max-width: 768px) {
  .action-buttons {
    flex-direction: column;
    align-items: stretch;
  }

  .action-buttons button {
    width: 100%;
  }

  .date-selector {
    flex-direction: column;
    align-items: stretch;
  }

  .date-btn {
    width: 100%;
  }

  .event-header {
    flex-direction: column;
    align-items: flex-start;
    gap: 0.5rem;
  }
}
</style>
