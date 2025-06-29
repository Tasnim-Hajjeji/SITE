<template>
  <section class="article-section">
    <div class="header-row">
      <h1 class="title">Articles</h1>
      <div class="action-buttons">
        <button class="add-btn" @click="showModal = true">
          <i class="fas fa-plus" style="font-weight: bold;"></i> Ajouter un article 
        </button>
      </div>
    </div>

    <div v-if="isLoading" class="loading-message">Chargement des articles...</div>
    <div v-else-if="articles.length === 0" class="empty-message">Aucun article disponible.</div>
    <div v-else class="article-list">
      <div class="article-card" v-for="(article) in articles" :key="article.id">
        <div class="article-name" @click="openPDF(getPdfUrl(article.url))">
          <i class="fas fa-file-pdf"></i> {{ article.name_fr }}
        </div>
        <button class="small-btn delete-btn" @click="confirmDelete(article.id, article.name_fr)">
          <i class="fas fa-trash"></i>
        </button>
      </div>
    </div>

    <!-- Add PDF Modal -->
    <transition name="fade">
      <div v-if="showModal" class="modal-overlay">
        <div class="modal-content">
          <h3 class="text-xl font-bold text-blue-700 mb-4 text-center">Ajouter un article (PDF)</h3>
          <form @submit.prevent="addArticle" class="space-y-4">
            <div>
              <label for="name_en" class="block mb-1 text-xs text-gray-500 font-medium">Nom en anglais :</label>
              <input id="name_en" v-model="newArticle.name_en" type="text"
                class="w-[95%] p-2 border border-gray-300 rounded-lg" required />
            </div>
            <div>
              <label for="name_fr" class="block mb-1 text-xs text-gray-500 font-medium">Nom en français :</label>
              <input id="name_fr" v-model="newArticle.name_fr" type="text"
                class="w-[95%] p-2 border border-gray-300 rounded-lg" required />
            </div>
            <div>
              <label for="pdf-upload" class="block mb-1 text-xs text-gray-500 font-medium">Sélectionner un PDF :</label>
              <input id="pdf-upload" type="file" accept="application/pdf" @change="handlePDFUpload"
                class="w-[95%] p-2 border border-gray-300 rounded-lg" required />
            </div>
            <div class="modal-actions flex justify-end gap-2 mt-6">
              <button type="button" class="cancel-btn" @click="showModal = false">Annuler</button>
              <button type="submit" class="add-btn" :disabled="isSubmitting">
                {{ isSubmitting ? 'Ajout en cours...' : 'Ajouter' }}
              </button>
            </div>
          </form>
        </div>
      </div>
    </transition>

    <!-- Delete Confirmation Modal -->
    <transition name="fade">
      <div v-if="showDeleteModal" class="modal-overlay">
        <div class="modal-content">
          <h3 class="text-xl font-bold mb-4 text-center">Confirmer la suppression</h3>
          <p class="text-gray-600 mb-4">Êtes-vous sûr de vouloir supprimer l'article "{{ selectedArticleName }}" ? Cette action est irréversible.</p>
          <div class="modal-actions flex justify-end gap-2 mt-6">
            <button type="button" class="cancel-btn mt-4" @click="showDeleteModal = false; selectedArticleId = null;">
              Annuler
            </button>
            <button type="button" class="delete-btn mt-4" @click="deleteArticle" :disabled="isDeleting">
              {{ isDeleting ? 'Suppression en cours...' : 'Supprimer' }}
            </button>
          </div>
        </div>
      </div>
    </transition>
  </section>
</template>


<script setup>
import { ref, onMounted } from 'vue';
import documentService from '@/services/DocumentService';

const showModal = ref(false);
const showDeleteModal = ref(false);
const articles = ref([]);
const isLoading = ref(false);
const isSubmitting = ref(false);
const isDeleting = ref(false);
const selectedEditionId = ref(null);
const selectedArticleId = ref(null);
const selectedArticleName = ref('');
const uploadedFile = ref(null);

const newArticle = ref({
  name_en: '',
  name_fr: '',
  description_en: '',
  description_fr: '',
  edition_id: null,
  file: null
});

onMounted(() => {
  selectedEditionId.value = localStorage.getItem('selectedEditionId');
  if (selectedEditionId.value) {

    fetchDocuments(selectedEditionId);
  }
});

async function fetchDocuments(editionId) {
  try {
    isLoading.value = true;
    const response = await documentService.getDocumentsByEdition(editionId.value);
    articles.value = response;
  } catch (error) {
    console.error('Error fetching documents:', error);
    alert('Failed to load documents. Please try again.');
  } finally {
    isLoading.value = false;
  }
}

function handlePDFUpload(event) {
  const file = event.target.files[0];
  if (file && file.type === 'application/pdf') {
    uploadedFile.value = file;
  } else {
    alert('Please select a PDF file.');
  }
}

async function addArticle() {
  if (!uploadedFile.value) return;

  try {
    isSubmitting.value = true;

    const formData = new FormData();
    formData.append('name_en', newArticle.value.name_en);
    formData.append('name_fr', newArticle.value.name_fr);
    formData.append('description_en', newArticle.value.description_en || '');
    formData.append('description_fr', newArticle.value.description_fr || '');
    formData.append('file', uploadedFile.value);
    formData.append('edition_id', selectedEditionId.value);

    console.log("form data document");
    for (const [key, value] of formData.entries()) {
      console.log(`${key}: ${value}`);
    }

    const response = await documentService.addDocument(formData);
    articles.value.push(response);

    // Reset form
    newArticle.value = {
      name_en: '',
      name_fr: '',
      description_en: '',
      description_fr: '',
      edition_id: null,
      file: null
    };
    uploadedFile.value = null;
    showModal.value = false;
  } catch (error) {
    console.error('Error adding document:', error);
    alert('Failed to add document. Please try again.');
  } finally {
    isSubmitting.value = false;
  }
}
function getPdfUrl(PdfPath) {
  return `http://localhost:8000/storage/${PdfPath}`;
}

function openPDF(url) {
  window.open(url, '_blank');
}

function confirmDelete(id, name) {
  selectedArticleId.value = id;
  selectedArticleName.value = name;
  showDeleteModal.value = true;
}

async function deleteArticle() {
  if (!selectedArticleId.value) return;

  try {
    isDeleting.value = true;
    await documentService.deleteDocument(selectedArticleId.value);
    articles.value = articles.value.filter(article => article.id !== selectedArticleId.value);
    showDeleteModal.value = false;
    selectedArticleId.value = null;
    selectedArticleName.value = '';
  } catch (error) {
    console.error('Error deleting document:', error);
    alert('Failed to delete document. Please try again.');
  } finally {
    isDeleting.value = false;
  }
}
</script>

<style scoped>
@import url("https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css");

.article-section {
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
  color: #ffffff;
  background-color: #265985;
  border-radius: 25px;
  padding: 8px 16px;
  font-weight: 500;
  cursor: pointer;
  transition: all 0.3s ease;
}

.action-buttons .add-btn:hover {
  background-color: #265985;
  color: white;
}

.article-list {
  display: flex;
  flex-direction: column;
  gap: 1rem;
}

.article-card {
  background: #fff;
  border-radius: 12px;
  box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
  padding: 1rem 1.5rem;
  display: flex;
  justify-content: space-between;
  align-items: center;
}

.article-name {
  font-size: 1rem;
  cursor: pointer;
  display: flex;
  align-items: center;
  gap: 0.5rem;
  color: #1c1c3c;
}

.article-name:hover {
  text-decoration: underline;
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

.delete-btn {
  border-color: #e53935;
  color: #e53935;
  background-color: #fff;
}

.delete-btn:hover {
  background-color: #e53935;
  color: #fff;
}

.loading-message {
  text-align: center;
  padding: 2rem;
  font-style: italic;
  color: #666;
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

.modal-content h3 {
  margin-bottom: 1rem;
  text-align: center;
  color: #265985;
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