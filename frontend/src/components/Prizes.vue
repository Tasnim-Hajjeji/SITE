<template>
  <section class="prizes-section">
    <div class="header-row">
      <h1 class="title">Tarifs d’inscription</h1>
      <div class="action-buttons">
        <button class="add-btn" @click="showModal = true" v-if="!hasPrices">
          <i class="fas fa-plus"></i> Ajouter des prix
        </button>
      </div>
    </div>

    <div v-if="isLoading" class="loading-message">Chargement des tarifs...</div>
    <div v-else-if="!hasPrices" class="empty-message">
      Pas encore de prix ajoutés.
    </div>

    <table v-else class="prizes-table">
      <thead>
        <tr>
          <th>Tunisien (DT)</th>
          <th>Étranger (€)</th>
          <th>Hébergement</th>
          <th>Article</th> <!-- ou "Communication", si tu parles d’un papier scientifique -->
          <th>Accompagnateur adulte</th>
          <th>Accompagnateur Enfant</th>
          <th>Supplément single</th>
          <th>Nuit supplémentaire</th>
          <th>Actions</th>

        </tr>
      </thead>
      <tbody>
        <tr v-for="(prize) in prizes" :key="prize.id">
          <td class="numeric">{{ prize.prix_tun }}</td>
          <td class="numeric">{{ prize.prix_international }}</td>
          <td class="numeric">{{ prize.prix_tun_hebergement }} DT / {{ prize.prix_eur_hebergement }} &euro;</td>
          <td class="numeric">{{ prize.prix_article_tun }} DT / {{ prize.prix_article_eur }} &euro;</td>

          <td class="numeric">{{ prize.prix_acc_adulte_tun }} DT / {{ prize.prix_acc_adulte_eur }} &euro;</td>
          <td class="numeric">{{ prize.prix_acc_enfant_tun }} DT / {{ prize.prix_acc_enfant_eur }} &euro;</td>
          <td class="numeric">{{ prize.prix_single_supp_tun }} DT / {{ prize.prix_single_supp_eur }} &euro;</td>
          <td class="numeric">{{ prize.prix_nuit_supp_tun }} DT / {{ prize.prix_nuit_supp_eur }} &euro;</td>
          <td class="actions-cell">
            <button class="small-btn update-btn" @click="openUpdateModal(prize)">
              <i class="fas fa-pen"></i>
            </button>
            <button class="small-btn delete-btn" @click="confirmDelete(prize.id)">
              <i class="fas fa-trash"></i>
            </button>
          </td>
        </tr>
      </tbody>
    </table>

    <!-- Add / Update Modal -->
    <transition name="fade">
      <div v-if="showModal" class="modal-overlay">
        <div class="modal-content">
          <h3 class="text-xl font-bold text-blue-700 mb-4 text-center">{{ isUpdating ? 'Mise à jour des prix' : 'Ajout des prix' }}
          </h3>
          <form @submit.prevent="isUpdating ? updatePrize() : addPrize()" class="space-y-0">
            <div v-for="(label, key) in fieldLabels" :key="key">
              <label :for="key" class="block mb-1 text-xs text-gray-500 font-medium mt-2">{{ label }}</label>
              <input v-model.number="form[key]" :id="key" :placeholder="label" type="number" min="0"
                class="w-[95%] p-2 border border-gray-300 rounded-lg" />
            </div>
            <div class="modal-actions flex justify-end gap-2">
              <button type="button" class="cancel-btn update_price_btns" @click="cancelModal">Annuler</button>
              <button type="submit" class="add-btn update_price_btns" :disabled="isSubmitting">
                {{ isSubmitting ? (isUpdating ? 'Mise a jour...' : 'Ajout...') : (isUpdating ? 'Mettre a jour' : 'Ajouter') }}
              </button>
            </div>
          </form>
        </div>
      </div>
    </transition>

    <!-- Delete Modal -->
    <transition name="fade">
      <div v-if="showDeleteModal" class="modal-overlay">
        <div class="modal-content">
          <h3 class="text-xl font-bold text-blue-700 mb-4 text-center">Confirmation du suppression</h3>
          <p class="text-gray-600 mb-4">Êtes-vous sûr de vouloir supprimer ces prix ? Cette action est irréversible.</p>
          <div class="modal-actions flex justify-end gap-2 mt-6">
            <button type="button" class="cancel-btn" @click="showDeleteModal = false; selectedPrizeId = null;">
              Annuler
            </button>
            <button type="button" class="delete-btn" @click="deletePrize()" :disabled="isDeleting">
              {{ isDeleting ? 'Suppression...' : 'Supprimer' }}
            </button>
          </div>
        </div>
      </div>
    </transition>
  </section>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue';
import priceService from '@/services/FormPrices';

const prizes = ref([]);
const showModal = ref(false);
const showDeleteModal = ref(false);
const isLoading = ref(false);
const isSubmitting = ref(false);
const isDeleting = ref(false);
const isUpdating = ref(false);
const selectedPrizeId = ref(null);
const selectedEditionId = ref(null);

const form = ref({
  prix_tun: '',
  prix_international: '',
  prix_tun_hebergement: '',
  prix_eur_hebergement: '',
  prix_article_tun: '',
  prix_article_eur: '',
  prix_acc_adulte_tun: '',
  prix_acc_adulte_eur: '',
  prix_acc_enfant_tun: '',
  prix_acc_enfant_eur: '',
  prix_single_supp_tun: '',
  prix_single_supp_eur: '',
  prix_nuit_supp_tun: '',
  prix_nuit_supp_eur: '',
  edition_id: null
});

const fieldLabels = {
  prix_tun: 'Tunisien (DT)',
  prix_international: 'Étranger (EUR)',
  prix_tun_hebergement: 'Hébergement (DT)',
  prix_eur_hebergement: 'Hébergement (€)',
  prix_article_tun: 'Article (DT)',
  prix_article_eur: 'Article (€)',
  prix_acc_adulte_tun: 'Accompagnateur Adulte (DT)',
  prix_acc_adulte_eur: 'Accompagnateur Adulte (€)',
  prix_acc_enfant_tun: 'Accompagnateur Enfant (DT)',
  prix_acc_enfant_eur: 'Accompagnateur Enfant (€)',
  prix_single_supp_tun: 'Supplément Single (DT)',
  prix_single_supp_eur: 'Single Supplement (€)',
  prix_nuit_supp_tun: 'Nuit Supplémentaire (DT)',
  prix_nuit_supp_eur: 'Nuit Supplémentaire (€)'
};

const hasPrices = computed(() => {
  return prizes.value.length > 0;
});

onMounted(() => {
  selectedEditionId.value = localStorage.getItem('selectedEditionId');
  if (selectedEditionId.value) {
    fetchPrizes(selectedEditionId.value);
  }
});

async function fetchPrizes(editionId) {
  try {
    isLoading.value = true;
    const response = await priceService.getPrizesByEdition(editionId);
    prizes.value = response;
  } catch (error) {
    console.error('Error fetching prizes:', error);
    alert('Failed to load prizes. Please try again.');
  } finally {
    isLoading.value = false;
  }
}

async function addPrize() {
  try {
    isSubmitting.value = true;
    form.value.edition_id = selectedEditionId.value;

    const response = await priceService.addPrize(form.value);
    prizes.value.push(response);
    resetForm();
  } catch (error) {
    console.error('Error adding prize:', error);
    alert('Failed to add prize. Please try again.');
  } finally {
    isSubmitting.value = false;
  }
}

function confirmDelete(id) {
  selectedPrizeId.value = id;
  showDeleteModal.value = true;
}

async function deletePrize() {
  if (!selectedPrizeId.value) return;

  try {
    isDeleting.value = true;
    await priceService.deletePrize(selectedPrizeId.value);
    prizes.value = prizes.value.filter(prize => prize.id !== selectedPrizeId.value);
    showDeleteModal.value = false;
    selectedPrizeId.value = null;
  } catch (error) {
    console.error('Error deleting prize:', error);
    alert('Failed to delete prize. Please try again.');
  } finally {
    isDeleting.value = false;
  }
}

function openUpdateModal(prize) {
  form.value = { ...prize };
  isUpdating.value = true;
  selectedPrizeId.value = prize.id;
  showModal.value = true;
}

async function updatePrize() {
  try {
    isSubmitting.value = true;
    const response = await priceService.updatePrize(selectedPrizeId.value, form.value);

    const index = prizes.value.findIndex(p => p.id === selectedPrizeId.value);
    if (index !== -1) {
      prizes.value[index] = response;
    }

    resetForm();
  } catch (error) {
    console.error('Error updating prize:', error);
    alert('Failed to update prize. Please try again.');
  } finally {
    isSubmitting.value = false;
  }
}

function cancelModal() {
  resetForm();
}

function resetForm() {
  showModal.value = false;
  form.value = {
    prix_tun: '',
    prix_international: '',
    prix_tun_sans_hebergement: '',
    prix_acc_adulte: '',
    prix_acc_enfant: '',
    prix_single_supp: '',
    prix_nuit_supp: '',
    edition_id: null
  };
  isUpdating.value = false;
  selectedPrizeId.value = null;
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

.update_price_btns{
  margin-top: 15px;
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
  color: white;
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
  text-transform: capitalize;
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
  text-align: center;
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
  display: flex;
  align-items: center;
  justify-content: center;
  width: 32px;
  height: 32px;
}

.update-btn {
  border-color: #265985;
  color: #265985;
}

.update-btn:hover {
  background-color: #265985;
  color: white;
}

.delete-btn {
  border-color: #e53935;
  color: #e53935;
}

.delete-btn:hover {
  background-color: #e53935;
  color: white;
}

.empty-message {
  text-align: center;
  padding: 2rem;
  font-style: italic;
  color: #666;
}

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

.modal-actions {
  display: flex;
  justify-content: flex-end;
  gap: 10px;
  margin-top: 10px;
}

.add-btn,
.cancel-btn,
.delete-btn {
  padding: 0.5rem 1rem;
  border-radius: 8px;
  font-weight: 600;
  font-size: 0.9rem;
  cursor: pointer;
  transition: background 0.3s ease, transform 0.3s ease, box-shadow 0.3s ease;
}

.add-btn {
  background: linear-gradient(to right, #265985, #1e4b6b);
  color: white;
  border: none;
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

/* Responsive */
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
