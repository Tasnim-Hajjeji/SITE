<template>
  <div class="programme-container">
    <h1 class="title">Programme</h1>

    <!-- Action Button -->
    <div class="action-buttons">
      <button class="add-btn" @click="showModal = true">
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
            <div class="time">{{ event.time_start }} — {{ event.time_end }}</div>
            <div class="buttons">
              <button class="small-btn update-btn" @click="openUpdateModal(event, index)">
                <i class="fas fa-pen"></i>
              </button>
              <button class="small-btn delete-btn">
                <i class="fas fa-trash"></i>
              </button>
            </div>
          </div>
          <div class="label">{{ event.name_en }}</div>
          <div class="description">{{ event.description_en }}</div>
        </div>
      </div>
    </div>

    <!-- Add Programme Modal -->
    <div v-if="showModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 transition-opacity duration-300">
      <div class="bg-white p-6 rounded-lg w-full max-w-md shadow-md font-poppins max-h-[90vh] overflow-y-auto">
        <h3 class="text-xl font-bold text-gray-800 mb-4 text-center sticky top-0 bg-white z-10">Add Programme</h3>
        <div class="space-y-4">
          <div>
            <label for="name_fr" class="block text-sm font-medium text-gray-700">Name (French)</label>
            <input
              v-model="newProgramme.name_fr"
              id="name_fr"
              type="text"
              placeholder="Enter name in French"
              class="w-full px-3 py-2 border border-blue-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200"
              required
            />
          </div>
          <div>
            <label for="name_en" class="block text-sm font-medium text-gray-700">Name (English)</label>
            <input
              v-model="newProgramme.name_en"
              id="name_en"
              type="text"
              placeholder="Enter name in English"
              class="w-full px-3 py-2 border border-blue-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200"
              required
            />
          </div>
          <div>
            <label for="description_fr" class="block text-sm font-medium text-gray-700">Description (French)</label>
            <textarea
              v-model="newProgramme.description_fr"
              id="description_fr"
              placeholder="Enter description in French"
              class="w-full px-3 py-2 border border-blue-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200"
              rows="3"
              required
            ></textarea>
          </div>
          <div>
            <label for="description_en" class="block text-sm font-medium text-gray-700">Description (English)</label>
            <textarea
              v-model="newProgramme.description_en"
              id="description_en"
              placeholder="Enter description in English"
              class="w-full px-3 py-2 border border-blue-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200"
              rows="3"
              required
            ></textarea>
          </div>
          <div>
            <label for="time_start" class="block text-sm font-medium text-gray-700">Start Time</label>
            <input
              v-model="newProgramme.time_start"
              id="time_start"
              type="datetime-local"
              class="w-full px-3 py-2 border border-blue-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200"
              required
            />
          </div>
          <div>
            <label for="time_end" class="block text-sm font-medium text-gray-700">End Time</label>
            <input
              v-model="newProgramme.time_end"
              id="time_end"
              type="datetime-local"
              class="w-full px-3 py-2 border border-blue-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200"
              required
            />
          </div>
          <div v-if="error" class="p-2 bg-red-100 text-red-700 rounded-md flex items-center">
            <span class="mr-1">!</span> {{ error }}
          </div>
        </div>
        <div class="mt-6 flex justify-end space-x-3">
          <button
            @click="showModal = false"
            type="button"
            class="px-4 py-2 bg-gray-200 text-gray-700 rounded-md hover:bg-gray-300 transition-colors duration-200"
          >
            Cancel
          </button>
          <button
            @click="addProgramme"
            type="button"
            class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition-colors duration-200"
          >
            Add Programme
          </button>
        </div>
      </div>
    </div>

    <!-- Update Programme Modal -->
    <div v-if="showUpdateModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 transition-opacity duration-300">
      <div class="bg-white p-6 rounded-lg w-full max-w-md shadow-md font-poppins max-h-[90vh] overflow-y-auto">
        <h3 class="text-xl font-bold text-gray-800 mb-4 text-center sticky top-0 bg-white z-10">Update Programme</h3>
        <div class="space-y-4">
          <div>
            <label for="update_name_fr" class="block text-sm font-medium text-gray-700">Name (French)</label>
            <input
              v-model="selectedProgramme.name_fr"
              id="update_name_fr"
              type="text"
              placeholder="Enter name in French"
              class="w-full px-3 py-2 border border-blue-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200"
              required
            />
          </div>
          <div>
            <label for="update_name_en" class="block text-sm font-medium text-gray-700">Name (English)</label>
            <input
              v-model="selectedProgramme.name_en"
              id="update_name_en"
              type="text"
              placeholder="Enter name in English"
              class="w-full px-3 py-2 border border-blue-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200"
              required
            />
          </div>
          <div>
            <label for="update_description_fr" class="block text-sm font-medium text-gray-700">Description (French)</label>
            <textarea
              v-model="selectedProgramme.description_fr"
              id="update_description_fr"
              placeholder="Enter description in French"
              class="w-full px-3 py-2 border border-blue-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200"
              rows="3"
              required
            ></textarea>
          </div>
          <div>
            <label for="update_description_en" class="block text-sm font-medium text-gray-700">Description (English)</label>
            <textarea
              v-model="selectedProgramme.description_en"
              id="update_description_en"
              placeholder="Enter description in English"
              class="w-full px-3 py-2 border border-blue-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200"
              rows="3"
              required
            ></textarea>
          </div>
          <div>
            <label for="update_time_start" class="block text-sm font-medium text-gray-700">Start Time</label>
            <input
              v-model="selectedProgramme.time_start"
              id="update_time_start"
              type="datetime-local"
              class="w-full px-3 py-2 border border-blue-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200"
              required
            />
          </div>
          <div>
            <label for="update_time_end" class="block text-sm font-medium text-gray-700">End Time</label>
            <input
              v-model="selectedProgramme.time_end"
              id="update_time_end"
              type="datetime-local"
              class="w-full px-3 py-2 border border-blue-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200"
              required
            />
          </div>
          <div v-if="error" class="p-2 bg-red-100 text-red-700 rounded-md flex items-center">
            <span class="mr-1">!</span> {{ error }}
          </div>
        </div>
        <div class="mt-6 flex justify-end space-x-3">
          <button
            @click="showUpdateModal = false"
            type="button"
            class="px-4 py-2 bg-gray-200 text-gray-700 rounded-md hover:bg-gray-300 transition-colors duration-200"
          >
            Cancel
          </button>
          <button
            @click="updateProgramme"
            type="button"
            class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition-colors duration-200"
          >
            Update Programme
          </button>
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
          time_start: "2025-07-01T08:00",
          time_end: "2025-07-01T09:30",
          name_fr: "Atelier",
          name_en: "Workshop",
          description_fr: "Réunion de lancement pour le nouveau projet",
          description_en: "Kickoff meeting for the new project",
        },
        {
          date: "01/07/2025",
          time_start: "2025-07-01T10:00",
          time_end: "2025-07-01T11:30",
          name_fr: "Revue de code",
          name_en: "Code Review",
          description_fr: "Session de revue de code pour le Module A",
          description_en: "Team code review session for Module A",
        },
        {
          date: "02/07/2025",
          time_start: "2025-07-02T08:00",
          time_end: "2025-07-02T09:30",
          name_fr: "Atelier",
          name_en: "Workshop",
          description_fr: "Planification et discussion des tâches",
          description_en: "Task planning and discussion",
        },
        {
          date: "02/07/2025",
          time_start: "2025-07-02T10:00",
          time_end: "2025-07-02T11:00",
          name_fr: "Réunion",
          name_en: "Meeting",
          description_fr: "Synchronisation avec le client sur les livrables",
          description_en: "Client sync-up on deliverables",
        },
        {
          date: "03/07/2025",
          time_start: "2025-07-03T09:00",
          time_end: "2025-07-03T10:00",
          name_fr: "Formation",
          name_en: "Training",
          description_fr: "Atelier interne pour l'onboarding",
          description_en: "Internal workshop for onboarding",
        },
      ],
      showModal: false,
      showUpdateModal: false,
      newProgramme: {
        date: "",
        time_start: "",
        time_end: "",
        name_fr: "",
        name_en: "",
        description_fr: "",
        description_en: "",
      },
      selectedProgramme: {
        date: "",
        time_start: "",
        time_end: "",
        name_fr: "",
        name_en: "",
        description_fr: "",
        description_en: "",
        index: -1,
      },
      error: null,
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
    addProgramme() {
      if (
        this.newProgramme.date &&
        this.newProgramme.time_start &&
        this.newProgramme.time_end &&
        this.newProgramme.name_fr &&
        this.newProgramme.name_en &&
        this.newProgramme.description_fr &&
        this.newProgramme.description_en
      ) {
        this.events.push({
          date: this.selectedDate,
          time_start: this.newProgramme.time_start,
          time_end: this.newProgramme.time_end,
          name_fr: this.newProgramme.name_fr,
          name_en: this.newProgramme.name_en,
          description_fr: this.newProgramme.description_fr,
          description_en: this.newProgramme.description_en,
        });
        this.newProgramme = {
          date: "",
          time_start: "",
          time_end: "",
          name_fr: "",
          name_en: "",
          description_fr: "",
          description_en: "",
        };
        this.error = null;
        this.showModal = false;
      } else {
        this.error = "Please fill all required fields";
      }
    },
    openUpdateModal(event, index) {
      this.selectedProgramme = { ...event, index };
      this.showUpdateModal = true;
    },
    updateProgramme() {
      if (
        this.selectedProgramme.date &&
        this.selectedProgramme.time_start &&
        this.selectedProgramme.time_end &&
        this.selectedProgramme.name_fr &&
        this.selectedProgramme.name_en &&
        this.selectedProgramme.description_fr &&
        this.selectedProgramme.description_en
      ) {
        this.events[this.selectedProgramme.index] = {
          ...this.selectedProgramme,
        };
        this.showUpdateModal = false;
        this.error = null;
      } else {
        this.error = "Please fill all required fields";
      }
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