```vue
<template>
  <section class="prizes-section">
    <div class="header-row">
      <h1 class="title">Registration Prizes</h1>
      <div class="action-buttons">
        <button class="add-btn" @click="showModal = true" :disabled="prizesAdded">
          <i class="fas fa-plus"></i> Add
        </button>
      </div>
    </div>

    <div v-if="prizes.length === 0" class="empty-message">
      No prizes added yet.
    </div>

    <table v-else class="prizes-table">
      <thead>
        <tr>
          <th>Tunisian (DT)</th>
          <th>Foreign (€)</th>
          <th>Accommodation (DT)</th>
          <th>Adult Companion (DT)</th>
          <th>Child Companion (DT)</th>
          <th>Single Supplement (DT)</th>
          <th>Extra Night (DT)</th>
          <th>Actions</th>
        </tr>
      </thead>
      <tbody>
        <tr v-for="(prize, index) in prizes" :key="index">
          <td class="numeric">{{ prize.tunisian }}</td>
          <td class="numeric">{{ prize.foreign }}</td>
          <td class="numeric">{{ prize.accommodation }}</td>
          <td class="numeric">{{ prize.adult }}</td>
          <td class="numeric">{{ prize.child }}</td>
          <td class="numeric">{{ prize.supplement }}</td>
          <td class="numeric">{{ prize.extraNight }}</td>
          <td class="actions-cell">
            <button class="small-btn update-btn" @click="openUpdateModal(prize, index)">
              <i class="fas fa-pen"></i>
            </button>
            <button class="small-btn delete-btn" @click="confirmDelete(index)">
              <i class="fas fa-trash"></i>
            </button>
          </td>
        </tr>
      </tbody>
    </table>

    <!-- Add/Update Modal -->
    <transition name="fade">
      <div v-if="showModal" class="modal-overlay">
        <div class="modal-content">
          <h3 class="text-xl font-bold text-blue-700 mb-4 text-center">{{ isUpdating ? 'Update Prize' : 'Add Prize' }}</h3>
          <form @submit.prevent="isUpdating ? updatePrize() : addPrize()" class="space-y-0">
            <div>
              <label for="tunisian" class="block mb-1 text-xs text-gray-500 font-medium">Tunisian (DT):</label>
              <input v-model.number="form.tunisian" id="tunisian" placeholder="Tunisian (DT)" type="number" class="w-[95%] p-2 border border-gray-300 rounded-lg" />
            </div>
            <div>
              <label for="foreign" class="block mb-1 text-xs text-gray-500 font-medium">Foreign (EUR):</label>
              <input v-model.number="form.foreign" id="foreign" placeholder="Foreign (EUR)" type="number" class="w-[95%] p-2 border border-gray-300 rounded-lg" />
            </div>
            <div>
              <label for="accommodation" class="block mb-1 text-xs text-gray-500 font-medium">Accommodation (DT):</label>
              <input v-model.number="form.accommodation" id="accommodation" placeholder="Accommodation (DT)" type="number" class="w-[95%] p-2 border border-gray-300 rounded-lg" />
            </div>
            <div>
              <label for="adult" class="block mb-1 text-xs text-gray-500 font-medium">Adult Companion (DT):</label>
              <input v-model.number="form.adult" id="adult" placeholder="Adult Companion (DT)" type="number" class="w-[95%] p-2 border border-gray-300 rounded-lg" />
            </div>
            <div>
              <label for="child" class="block mb-1 text-xs text-gray-500 font-medium">Child Companion (DT):</label>
              <input v-model.number="form.child" id="child" placeholder="Child Companion (DT)" type="number" class="w-[95%] p-2 border border-gray-300 rounded-lg" />
            </div>
            <div>
              <label for="supplement" class="block mb-1 text-xs text-gray-500 font-medium">Single Supplement (DT):</label>
              <input v-model.number="form.supplement" id="supplement" placeholder="Single Supplement (DT)" type="number" class="w-[95%] p-2 border border-gray-300 rounded-lg" />
            </div>
            <div>
              <label for="extraNight" class="block mb-1 text-xs text-gray-500 font-medium">Extra Night (DT):</label>
              <input v-model.number="form.extraNight" id="extraNight" placeholder="Extra Night (DT)" type="number" class="w-[95%] p-2 border border-gray-300 rounded-lg" />
            </div>
            <div class="modal-actions flex justify-end gap-2 mt-6">
              <button type="button" class="cancel-btn" @click="cancelModal">Cancel</button>
              <button type="submit" class="add-btn">{{ isUpdating ? 'Update' : 'Add' }}</button>
            </div>
          </form>
        </div>
      </div>
    </transition>

    <!-- Delete Confirmation Modal -->
    <transition name="fade">
      <div v-if="showDeleteModal" class="modal-overlay">
        <div class="modal-content">
          <h3 class="text-xl font-bold text-blue-700 mb-4 text-center">Confirm Deletion</h3>
          <p class="text-gray-600 mb-4">Are you sure you want to delete this prize? This action is irreversible.</p>
          <div class="modal-actions flex justify-end gap-2 mt-6">
            <button type="button" class="cancel-btn" @click="showDeleteModal = false; selectedPrizeIndex = null;">
              Cancel
            </button>
            <button type="button" class="delete-btn" @click="deletePrize(selectedPrizeIndex)">
              Delete
            </button>
          </div>
        </div>
      </div>
    </transition>
  </section>
</template>

<script setup>
import { ref } from 'vue'

const prizes = ref([])
const showModal = ref(false)
const showDeleteModal = ref(false)
const prizesAdded = ref(false)
const isUpdating = ref(false)
const updatingIndex = ref(null)
const selectedPrizeIndex = ref(null)

const form = ref({
  tunisian: '',
  foreign: '',
  accommodation: '',
  adult: '',
  child: '',
  supplement: '',
  extraNight: ''
})

function addPrize() {
  if (prizesAdded.value) return
  prizes.value.push({ ...form.value })
  prizesAdded.value = true
  resetForm()
}

function confirmDelete(index) {
  selectedPrizeIndex.value = index
  showDeleteModal.value = true
}

function deletePrize(index) {
  if (index !== null) {
    prizes.value.splice(index, 1)
    prizesAdded.value = false
    showDeleteModal.value = false
    selectedPrizeIndex.value = null
  }
}

function openUpdateModal(prize, index) {
  form.value = { ...prize }
  isUpdating.value = true
  updatingIndex.value = index
  showModal.value = true
}

function updatePrize() {
  prizes.value[updatingIndex.value] = { ...form.value }
  resetForm()
}

function cancelModal() {
  resetForm()
}

function resetForm() {
  showModal.value = false
  form.value = {
    tunisian: '',
    foreign: '',
    accommodation: '',
    adult: '',
    child: '',
    supplement: '',
    extraNight: ''
  }
  isUpdating.value = false
  updatingIndex.value = null
}
</script>

<style scoped>
@import url("https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css");

.prizes-section {
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

.header-row {
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin-bottom: 1rem;
  flex-wrap: wrap;
  gap: 1rem;
}

.title {
  font-size: 1.5rem;
  font-weight: 700;
  color: #1b2d56;
  margin: 0;
  position: relative;
}

.title::after {
  content: "";
  width: 100px;
  height: 4px;
  background: #265985;
  display: block;
  margin-top: 8px;
  border-radius: 2px;
}

.action-buttons {
  display: flex;
  gap: 1rem;
}

.action-buttons .add-btn {
  border: 1px solid #265985;
  color: #265985;
  background-color: white;
  border-radius: 25px;
  padding: 8px 16px;
  font-weight: 500;
  cursor: pointer;
  transition: all 0.3s ease;
}

.action-buttons .add-btn:disabled {
  opacity: 0.6;
  cursor: not-allowed;
}

.action-buttons .add-btn:hover:not(:disabled) {
  background-color: #265985;
  color: white;
}

.prizes-table {
  width: 100%;
  border-collapse: collapse;
  margin-top: 1rem;
  box-shadow: 0 3px 6px rgba(0, 0, 0, 0.1);
}

.prizes-table th {
  font-weight: 600;
  color: #1b2d56;
  background: #f1f5f9;
  padding: 0.75rem;
  border: 1px solid #e5e7eb;
  text-align: center;
  font-size: 0.95rem;
}

.prizes-table td {
  padding: 0.75rem;
  border: 1px solid #e5e7eb;
  font-size: 0.95rem;
  color: #333;
}

.prizes-table tr:hover {
  background: #f9fafb;
}

.prizes-table .numeric {
  text-align: right;
}

.prizes-table .actions-cell {
  display: flex;
  justify-content: center;
  gap: 0.5rem;
}

.small-btn {
  padding: 6px 10px;
  font-size: 0.9rem;
  border-radius: 6px;
  border: 1px solid;
  cursor: pointer;
  transition: all 0.3s ease;
  display: flex;
  align-items: center;
  justify-content: center;
  width: 32px;
  height: 32px;
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

.empty-message {
  text-align: center;
  padding: 2rem;
  font-style: italic;
  color: #666;
}

/* Modal Styles */
.modal-overlay {
  position: fixed;
  inset: 0;
  background-color: rgba(0, 0, 0, 0.45);
  display: flex;
  justify-content: center;
  align-items: center;
  z-index: 999;
}

.modal-content {
  background: white;
  padding: 30px;
  border-radius: 10px;
  max-width: 500px;
  width: 100%;
  box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
  animation: fadeInZoom 0.3s ease-out;
  max-height: 80vh;
  overflow-y: auto;
  -webkit-overflow-scrolling: touch;
  font-family: 'Poppins', sans-serif;
}

@keyframes fadeInZoom {
  0% {
    opacity: 0;
    transform: scale(0.85);
  }
  100% {
    opacity: 1;
    transform: scale(1);
  }
}

.modal-content h3 {
  font-size: 1.3rem;
  font-weight: bold;
  color: #265985;
  margin-bottom: 1rem;
  text-align: center;
}

.modal-content input {
  width: 95%;
  padding: 10px 14px;
  border: 1px solid #d1d5db;
  border-radius: 6px;
  font-size: 14px;
}

.modal-content input:focus {
  border-color: #265985;
  outline: none;
}

.modal-content label {
  display: block;
  margin-bottom: 0.5rem;
  font-weight: bold;
  color: #1f2937;
}

.modal-actions {
  display: flex;
  justify-content: flex-end;
  gap: 10px;
  margin-top: 10px;
}

.add-btn {
  background: linear-gradient(to right, #265985, #1e4b6b);
  color: white;
  border: none;
  padding: 0.5rem 1rem;
  border-radius: 8px;
  font-weight: 600;
  font-size: 0.9rem;
  cursor: pointer;
  transition: background 0.3s ease, transform 0.3s ease, box-shadow 0.3s ease;
}

.add-btn:hover {
  background: linear-gradient(to right, #1e4b6b, #163a52);
  transform: translateY(-2px);
  box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
}

.cancel-btn {
  background: linear-gradient(to right, #d1d5db, #b0b7c3);
  color: #1f2937;
  border: none;
  padding: 0.5rem 1rem;
  border-radius: 8px;
  font-weight: 600;
  font-size: 0.9rem;
  cursor: pointer;
  transition: background 0.3s ease, transform 0.3s ease, box-shadow 0.3s ease;
}

.cancel-btn:hover {
  background: linear-gradient(to right, #b0b7c3, #9ca3af);
  transform: translateY(-2px);
  box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
}

.delete-btn {
  background: linear-gradient(to right, #e53935, #c62828);
  color: white;
  border: none;
  padding: 0.5rem 1rem;
  border-radius: 8px;
  font-weight: 600;
  font-size: 0.9rem;
  cursor: pointer;
  transition: background 0.3s ease, transform 0.3s ease, box-shadow 0.3s ease;
}

.delete-btn:hover {
  background: linear-gradient(to right, #c62828, #b71c1c);
  transform: translateY(-2px);
  box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
}

.fade-enter-active,
.fade-leave-active {
  transition: opacity 0.3s ease;
}

.fade-enter-from,
.fade-leave-to {
  opacity: 0;
}

/* Responsive Styles */
@media (max-width: 768px) {
  .header-row {
    flex-direction: column;
    align-items: flex-start;
  }

  .action-buttons {
    width: 100%;
  }

  .action-buttons button {
    width: 100%;
  }

  .prizes-table {
    display: block;
    overflow-x: auto;
    white-space: nowrap;
  }

  .modal-content {
    padding: 20px;
  }

  .modal-actions {
    flex-direction: column;
    align-items: stretch;
  }

  .add-btn,
  .cancel-btn,
  .delete-btn {
    width: 100%;
    text-align: center;
  }
}
</style>