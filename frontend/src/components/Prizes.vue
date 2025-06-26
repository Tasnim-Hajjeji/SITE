<template>
    <section class="prizes-section">
      <h2 class="title">Registration Prizes</h2>
  
      <div class="action-buttons">
        <button class="add-btn" @click="showModal = true" :disabled="prizesAdded">
          <i class="fas fa-plus"></i> Add
        </button>
      </div>
  
      <div v-if="prizes.length === 0" class="empty-message">
        No prizes added yet.
      </div>
  
      <div v-else class="prizes-list">
        <div class="prize-card" v-for="(prize, index) in prizes" :key="index">
          <div class="card-actions">
            <i class="fas fa-edit update-icon" @click="openUpdateModal(prize, index)"></i>
            <i class="fas fa-trash delete-icon" @click="deletePrize(index)"></i>
          </div>
          <ul class="prize-details">
            <li>Tunisian: {{ prize.tunisian }} DT</li>
            <li>Foreign: {{ prize.foreign }} €</li>
            <li>Accommodation: {{ prize.accommodation }} DT</li>
            <li>Adult companion: {{ prize.adult }} DT</li>
            <li>Child companion: {{ prize.child }} DT</li>
            <li>Single Supplement: {{ prize.supplement }} DT</li>
            <li>Extra Night: {{ prize.extraNight }} DT</li>
          </ul>
        </div>
      </div>
  
      <!-- Modal -->
      <div v-if="showModal" class="modal">
        <div class="modal-content">
          <h3>{{ isUpdating ? 'Update Prize' : 'Add Prize' }}</h3>
          <div class="form-grid">
            <input v-model.number="form.tunisian" placeholder="Tunisian (DT)" type="number" />
            <input v-model.number="form.foreign" placeholder="Foreign (EUR)" type="number" />
            <input v-model.number="form.accommodation" placeholder="Accommodation (DT)" type="number" />
            <input v-model.number="form.adult" placeholder="Adult Companion (DT)" type="number" />
            <input v-model.number="form.child" placeholder="Child Companion (DT)" type="number" />
            <input v-model.number="form.supplement" placeholder="Single Supplement (DT)" type="number" />
            <input v-model.number="form.extraNight" placeholder="Extra Night (DT)" type="number" />
          </div>
          <div class="modal-actions">
            <button @click="cancelModal">Cancel</button>
            <button @click="isUpdating ? updatePrize() : addPrize()">
              {{ isUpdating ? 'Update' : 'Add' }}
            </button>
          </div>
        </div>
      </div>
    </section>
  </template>
  
  <script setup>
  import { ref } from 'vue'
  
  const prizes = ref([])
  const showModal = ref(false)
  const prizesAdded = ref(false)
  const isUpdating = ref(false)
  const updatingIndex = ref(null)
  
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
  
  function deletePrize(index) {
    prizes.value.splice(index, 1)
    prizesAdded.value = false
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
  .prizes-section {
    padding: 2rem;
    font-family: 'Poppins', sans-serif;
    max-width: 800px;
    margin: auto;
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
  
  .action-buttons {
    margin-bottom: 1rem;
  }
  
  .add-btn {
    border: 1px solid #268557;
    background: #fff;
    color: #268557;
    padding: 8px 16px;
    border-radius: 25px;
    font-weight: 500;
    cursor: pointer;
    transition: all 0.3s ease;
  }
  
  .add-btn:disabled {
    opacity: 0.6;
    cursor: not-allowed;
  }
  
  .add-btn:hover:not(:disabled) {
    background: #268557;
    color: #fff;
  }
  
  .prizes-list {
    display: flex;
    flex-direction: column;
    gap: 1rem;
  }
  
  .prize-card {
    background: #fff;
    padding: 1.2rem;
    border-radius: 12px;
    box-shadow: 0 3px 6px rgba(0, 0, 0, 0.1);
    position: relative;
  }
  
  .card-actions {
    position: absolute;
    top: 12px;
    right: 12px;
    display: flex;
    gap: 10px;
  }
  
  .update-icon,
  .delete-icon {
    cursor: pointer;
    font-size: 1rem;
    padding: 6px;
    border-radius: 50%;
    background-color: #f2f2f2;
    color: #333;
    transition: all 0.3s ease;
  }
  
  .update-icon:hover {
    background-color: #265985;
    color: white;
  }
  
  .delete-icon:hover {
    background-color: #e53935;
    color: white;
  }
  
  .modal {
    position: fixed;
    inset: 0;
    background: rgba(0, 0, 0, 0.5);
    display: flex;
    align-items: center;
    justify-content: center;
    z-index: 50;
  }
  
  .modal-content {
    background: #fff;
    padding: 2rem;
    border-radius: 12px;
    max-width: 500px;
    width: 100%;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
    font-family: 'Poppins', sans-serif;
  }
  
  .form-grid {
    display: grid;
    grid-template-columns: 1fr;
    gap: 1rem;
    margin: 1rem 0;
  }
  
  .form-grid input {
    padding: 0.7rem 1rem;
    border: 1px solid #ccc;
    border-radius: 8px;
    font-size: 0.95rem;
    width: 100%;
  }
  
  .modal-actions {
    display: flex;
    justify-content: flex-end;
    gap: 0.8rem;
  }
  
  .modal-actions button {
    padding: 0.5rem 1rem;
    border-radius: 8px;
    border: none;
    cursor: pointer;
    font-weight: 500;
  }
  
  .modal-actions button:first-child {
    background: #ccc;
    color: #333;
  }
  
  .modal-actions button:last-child {
    background: #00a6a6;
    color: white;
  }
  </style>
  