<template>
  <div class="programme-container">
    <h1 class="title">Programme</h1>

    <!-- Loading state -->
    <div v-if="isLoading" class="loading">
      Loading program data...
    </div>

    <!-- Error message -->
    <div v-if="error" class="error-message">
      {{ error }}
    </div>

    <!-- Content when loaded -->
    <div v-if="!isLoading && !error">
      <!-- Action Button -->
      <div class="action-buttons">
        <button class="add-btn" @click="showModal = true">
          <i class="fas fa-plus"></i> Add
        </button>
      </div>

      <!-- Date Selector -->
      <div class="date-selector">
        <button v-for="date in dates" :key="date" :class="['date-btn', { active: selectedDate === date }]"
          @click="selectDate(date)">
          {{ date }}
        </button>
      </div>

      <!-- Timeline -->
      <div class="timeline">
        <div v-if="filteredPrograms.length === 0" class="no-events">
          No programs scheduled for this date
        </div>

        <div class="event" v-for="program in filteredPrograms" :key="program.id">
          <div class="circle"></div>
          <div class="content">
            <div class="event-header">
              <div class="time">{{ formatTime(program.time_start) }} — {{ formatTime(program.time_end) }}</div>
              <div class="buttons">
                <button class="small-btn update-btn" @click="openUpdateModal(program)">
                  <i class="fas fa-pen"></i>
                </button>
                <button class="small-btn delete-btn" @click="deleteProgram(program.id)">
                  <i class="fas fa-trash"></i>
                </button>
              </div>
            </div>
            <div class="label">{{ program.name_en }}</div>
            <div class="description">{{ program.description_en }}</div>
          </div>
        </div>
      </div>

      <!-- Add Program Modal -->
      <div v-if="showModal"
        class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 transition-opacity duration-300">
        <div class="bg-white p-6 rounded-lg w-full max-w-md shadow-md font-poppins max-h-[90vh] overflow-y-auto">
          <h3 class="text-xl font-bold text-gray-800 mb-4 text-center sticky top-0 bg-white z-10">Add Program</h3>
          <div class="space-y-4">
            <div>
              <label class="block text-sm font-medium text-gray-700">Name (French)</label>
              <input v-model="newProgram.name_fr" type="text" placeholder="Enter name in French"
                class="w-full px-3 py-2 border border-blue-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200"
                required />
            </div>
            <div>
              <label class="block text-sm font-medium text-gray-700">Name (English)</label>
              <input v-model="newProgram.name_en" type="text" placeholder="Enter name in English"
                class="w-full px-3 py-2 border border-blue-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200"
                required />
            </div>
            <div>
              <label class="block text-sm font-medium text-gray-700">Description (French)</label>
              <textarea v-model="newProgram.description_fr" placeholder="Enter description in French"
                class="w-full px-3 py-2 border border-blue-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200"
                rows="3" required></textarea>
            </div>
            <div>
              <label class="block text-sm font-medium text-gray-700">Description (English)</label>
              <textarea v-model="newProgram.description_en" placeholder="Enter description in English"
                class="w-full px-3 py-2 border border-blue-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200"
                rows="3" required></textarea>
            </div>
            <div>
              <label class="block text-sm font-medium text-gray-700">Start Time</label>
              <input v-model="newProgram.time_start" type="datetime-local"
                class="w-full px-3 py-2 border border-blue-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200"
                required />
            </div>
            <div>
              <label class="block text-sm font-medium text-gray-700">End Time</label>
              <input v-model="newProgram.time_end" type="datetime-local"
                class="w-full px-3 py-2 border border-blue-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200"
                required />
            </div>
            <div v-if="error" class="p-2 bg-red-100 text-red-700 rounded-md flex items-center">
              <span class="mr-1">!</span> {{ error }}
            </div>
          </div>
          <div class="mt-6 flex justify-end space-x-3">
            <button @click="showModal = false; resetNewProgramForm();" type="button"
              class="px-4 py-2 bg-gray-200 text-gray-700 rounded-md hover:bg-gray-300 transition-colors duration-200">
              Cancel
            </button>
            <button @click="addProgram" type="button"
              class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition-colors duration-200">
              Add Program
            </button>
          </div>
        </div>
      </div>

      <!-- Update Program Modal -->
      <div v-if="showUpdateModal"
        class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 transition-opacity duration-300">
        <div class="bg-white p-6 rounded-lg w-full max-w-md shadow-md font-poppins max-h-[90vh] overflow-y-auto">
          <h3 class="text-xl font-bold text-gray-800 mb-4 text-center sticky top-0 bg-white z-10">Update Program</h3>
          <div class="space-y-4">
            <div>
              <label class="block text-sm font-medium text-gray-700">Name (French)</label>
              <input v-model="selectedProgram.name_fr" type="text" placeholder="Enter name in French"
                class="w-full px-3 py-2 border border-blue-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200"
                required />
            </div>
            <div>
              <label class="block text-sm font-medium text-gray-700">Name (English)</label>
              <input v-model="selectedProgram.name_en" type="text" placeholder="Enter name in English"
                class="w-full px-3 py-2 border border-blue-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200"
                required />
            </div>
            <div>
              <label class="block text-sm font-medium text-gray-700">Description (French)</label>
              <textarea v-model="selectedProgram.description_fr" placeholder="Enter description in French"
                class="w-full px-3 py-2 border border-blue-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200"
                rows="3" required></textarea>
            </div>
            <div>
              <label class="block text-sm font-medium text-gray-700">Description (English)</label>
              <textarea v-model="selectedProgram.description_en" placeholder="Enter description in English"
                class="w-full px-3 py-2 border border-blue-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200"
                rows="3" required></textarea>
            </div>
            <div>
              <label class="block text-sm font-medium text-gray-700">Start Time</label>
              <input v-model="selectedProgram.time_start" type="datetime-local"
                class="w-full px-3 py-2 border border-blue-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200"
                required />
            </div>
            <div>
              <label class="block text-sm font-medium text-gray-700">End Time</label>
              <input v-model="selectedProgram.time_end" type="datetime-local"
                class="w-full px-3 py-2 border border-blue-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200"
                required />
            </div>
            <div v-if="error" class="p-2 bg-red-100 text-red-700 rounded-md flex items-center">
              <span class="mr-1">!</span> {{ error }}
            </div>
          </div>
          <div class="mt-6 flex justify-end space-x-3">
            <button @click="showUpdateModal = false" type="button"
              class="px-4 py-2 bg-gray-200 text-gray-700 rounded-md hover:bg-gray-300 transition-colors duration-200">
              Cancel
            </button>
            <button @click="updateProgram" type="button"
              class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition-colors duration-200">
              Update Program
            </button>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import { ref, computed, onMounted } from 'vue';
import { useRoute } from 'vue-router';
import ProgramService from '@/services/ProgramService';
import EditionService from '@/services/EditionService';
import dayjs from 'dayjs';

export default {
  setup() {
    const route = useRoute();
    const editionId = ref(route.params.editionId || localStorage.getItem('selectedEditionId'));
    const edition = ref(null);
    const dates = ref([]);
    const selectedDate = ref('');
    const programs = ref([]);
    const showModal = ref(false);
    const showUpdateModal = ref(false);
    const error = ref(null);
    const isLoading = ref(true);

    // Form data
    const newProgram = ref({
      name_fr: '',
      name_en: '',
      description_fr: '',
      description_en: '',
      time_start: '',
      time_end: '',
      edition_id: editionId.value
    });

    const selectedProgram = ref({
      id: null,
      name_fr: '',
      name_en: '',
      description_fr: '',
      description_en: '',
      time_start: '',
      time_end: '',
      edition_id: editionId.value
    });

    // Fetch edition details and programs
    const fetchData = async () => {
      try {
        isLoading.value = true;

        // Get edition details
        const editionResponse = await EditionService.getEdition(editionId.value);
        edition.value = editionResponse.data;

        // Get programs for this edition
        const programsResponse = await ProgramService.getProgramsByEdition(editionId.value);
        programs.value = programsResponse.data;

        // Extract unique dates
        extractDates();

        // Select first date by default
        if (dates.value.length > 0) {
          selectedDate.value = dates.value[0];
        }
      } catch (err) {
        error.value = 'Failed to load data: ' + err.message;
        console.error(err);
      } finally {
        isLoading.value = false;
      }
    };

    // Extract unique dates from programs
    const extractDates = () => {
      const uniqueDates = new Set();
      programs.value.forEach(program => {
        const date = dayjs(program.time_start).format('DD/MM/YYYY');
        uniqueDates.add(date);
      });

      // Convert Set to Array and sort dates
      dates.value = Array.from(uniqueDates).sort((a, b) => {
        // Convert dates to Date objects for comparison
        const dateA = dayjs(a, 'DD/MM/YYYY').toDate();
        const dateB = dayjs(b, 'DD/MM/YYYY').toDate();
        return dateA - dateB;
      });
    };
    // Filter programs by selected date
    const filteredPrograms = computed(() => {
      if (!selectedDate.value) return [];
      return programs.value.filter(program => {
        const programDate = dayjs(program.time_start).format('DD/MM/YYYY');
        return programDate === selectedDate.value;
      }).sort((a, b) => new Date(a.time_start) - new Date(b.time_start));
    });

    // Format time for display
    const formatTime = (dateTime) => {
      return dayjs(dateTime).format('HH:mm');
    };

    // Select date
    const selectDate = (date) => {
      selectedDate.value = date;
    };

    // Add new program
    const addProgram = async () => {
      try {
        // Validate required fields
        if (!newProgram.value.name_fr || !newProgram.value.name_en) {
          throw new Error('Both French and English names are required');
        }

        // Validate times
        const startDate = dayjs(newProgram.value.time_start);
        const endDate = dayjs(newProgram.value.time_end);

        if (!startDate.isValid() || !endDate.isValid()) {
          throw new Error('Please enter valid start and end times');
        }

        if (endDate.isBefore(startDate)) {
          throw new Error('End time must be after start time');
        }

        // Format times with leading zeros
        newProgram.value.time_start = startDate.format('YYYY-MM-DD HH:mm');
        newProgram.value.time_end = endDate.format('YYYY-MM-DD HH:mm');

        const response = await ProgramService.createProgram(newProgram.value);
        programs.value.push(response.data);
        extractDates();
        resetNewProgramForm();
        showModal.value = false;
      } catch (err) {
        error.value = 'Failed to add program: ' +
          (err.response?.data?.error || err.message || 'Unknown error');
        console.error('Error details:', err.response?.data || err);
      }
    };

    // Open update modal
    const openUpdateModal = (program) => {
      selectedProgram.value = {
        ...program,
        time_start: dayjs(program.time_start).format('YYYY-MM-DDTHH:mm'),
        time_end: dayjs(program.time_end).format('YYYY-MM-DDTHH:mm')
      };
      showUpdateModal.value = true;
    };

    // Update program
    const updateProgram = async () => {
      try {
        // Convert datetime-local input to proper format
        const startDate = dayjs(selectedProgram.value.time_start);
        const endDate = dayjs(selectedProgram.value.time_end);

        if (!startDate.isValid() || !endDate.isValid()) {
          throw new Error('Please enter valid dates');
        }

        // Format dates for backend
        const dataToSend = {
          ...selectedProgram.value,
          time_start: startDate.format('YYYY-MM-DD HH:mm'),
          time_end: endDate.format('YYYY-MM-DD HH:mm')
        };

        const response = await ProgramService.updateProgram(
          selectedProgram.value.id,
          dataToSend
        );

        // Update local programs list
        const index = programs.value.findIndex(p => p.id === selectedProgram.value.id);
        if (index !== -1) {
          programs.value[index] = response.data;
        }

        // Close modal
        showUpdateModal.value = false;
      } catch (err) {
        error.value = 'Failed to update program: ' + (err.response?.data?.error || err.message);
        console.error(err);
      }
    };

    // Delete program
    const deleteProgram = async (id) => {
      if (confirm('Are you sure you want to delete this program?')) {
        try {
          await ProgramService.deleteProgram(id);
          programs.value = programs.value.filter(p => p.id !== id);

          // Update dates list
          extractDates();
        } catch (err) {
          error.value = 'Failed to delete program: ' + err.message;
          console.error(err);
        }
      }
    };

    // Reset new program form
    const resetNewProgramForm = () => {
      newProgram.value = {
        name_fr: '',
        name_en: '',
        description_fr: '',
        description_en: '',
        time_start: '',
        time_end: '',
        edition_id: editionId.value
      };
    };

    // Initialize
    onMounted(fetchData);

    return {
      dates,
      selectedDate,
      programs,
      filteredPrograms,
      showModal,
      showUpdateModal,
      newProgram,
      selectedProgram,
      error,
      isLoading,
      selectDate,
      addProgram,
      openUpdateModal,
      updateProgram,
      deleteProgram,
      formatTime,
      resetNewProgramForm
    };
  }
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

.loading {
  text-align: center;
  padding: 2rem;
  font-style: italic;
  color: #666;
}

.error-message {
  padding: 1rem;
  background-color: #ffebee;
  color: #c62828;
  border-radius: 4px;
  margin-bottom: 1rem;
}

.no-events {
  text-align: center;
  padding: 2rem;
  color: #666;
  font-style: italic;
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