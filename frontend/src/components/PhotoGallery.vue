<template>
  <section class="photo-gallery">
    <div class="header-row">
      <h2 class="title">Galerie Photo</h2>
      <div class="action-buttons">
        <button class="delete-btn" @click="showDeleteAllModal = true">
          <i class="fas fa-trash"></i> Delete All
        </button>
        <button class="add-btn" @click="showModal = true">
          <i class="fas fa-plus"></i> Add
        </button>
      </div>
    </div>

    <div v-if="isLoading" class="loading">
      Loading images...
    </div>

    <div v-else-if="images.length === 0" class="empty-msg">
      No images available.
    </div>

    <div v-else class="carousel">
      <button class="nav-btn" @click="prevSlide" :disabled="currentIndex === 0">
        <i class="fas fa-arrow-left"></i>
      </button>

      <div class="slides-wrapper">
        <div v-for="(image, index) in visibleImages" :key="index" class="slide">
          <div class="action-icons">
            <button class="delete-btn hover-button" @click="openDeleteModal(index)">
              <i class="fas fa-trash"></i>
            </button>
          </div>
          <img :src="getImageUrl(image)" alt="Gallery photo" />
        </div>
      </div>

      <button class="nav-btn" @click="nextSlide" :disabled="currentIndex >= maxIndex">
        <i class="fas fa-arrow-right"></i>
      </button>
    </div>

    <div v-if="!isLoading && images.length > 0" class="dots">
      <span v-for="(dot, i) in totalDots" :key="i" :class="['dot', { active: i === currentIndex }]"></span>
    </div>

    <!-- Add Image Modal -->
    <div v-if="showModal" class="modal-overlay">
      <div class="modal-content">
        <h3 class="text-xl font-bold text-blue-700 mb-4 text-center">Add Image</h3>
        <form @submit.prevent="addImage" class="space-y-0">
          <div>
            <label for="image_upload" class="block mb-1 text-xs text-gray-500 font-medium">Upload Image</label>
            <input id="image_upload" type="file" accept="image/*" @change="handleImageUpload"
              class="w-[95%] p-2 border border-gray-300 rounded-lg" required />
            <div v-if="imagePreview" class="mt-2">
              <img :src="imagePreview" alt="Image Preview" class="w-20 h-20 object-cover rounded-md" />
            </div>
          </div>
          <div v-if="error" class="p-2 bg-red-100 text-red-700 rounded-md flex items-center">
            <span class="mr-1">!</span> {{ error }}
          </div>
          <div class="modal-actions flex justify-end gap-2 mt-6">
            <button type="button"
              class="cancel-btn bg-gradient-to-r from-gray-200 to-gray-300 text-gray-800 font-semibold rounded-lg px-4 py-1.5 hover:from-gray-300 hover:to-gray-400 transform hover:-translate-y-1 hover:shadow-md transition-all duration-300 ease-in-out"
              @click="showModal = false">
              Cancel
            </button>
            <button type="submit"
              class="add-btn bg-gradient-to-r from-blue-800 to-blue-600 text-white font-semibold rounded-lg px-4 py-1.5 hover:from-blue-900 hover:to-blue-700 transform hover:-translate-y-1 hover:shadow-md transition-all duration-300 ease-in-out"
              :disabled="!imagePreview || isAdding">
              <span v-if="!isAdding">Add Image</span>
              <span v-else class="flex items-center"><i class="fas fa-spinner fa-spin mr-2"></i> Adding...</span>
            </button>
          </div>
        </form>
      </div>
    </div>

    <!-- Delete Confirmation Modal -->
    <div v-if="showDeleteModal || showDeleteAllModal" class="modal-overlay">
      <div class="modal-content">
        <h3 class="text-xl font-bold text-blue-700 mb-4 text-center">
          {{ showDeleteModal ? 'Delete Image' : 'Delete All Images' }}
        </h3>
        <p class="text-center mb-4">Are you sure you want to delete {{ showDeleteModal ? 'this image?' : 'all images?' }}</p>
        <div class="modal-actions flex justify-end gap-2">
          <button type="button"
            class="cancel-btn bg-gradient-to-r from-gray-200 to-gray-300 text-gray-800 font-semibold rounded-lg px-4 py-1.5 hover:from-gray-300 hover:to-gray-400 transform hover:-translate-y-1 hover:shadow-md transition-all duration-300 ease-in-out"
            @click="cancelDelete">
            Cancel
          </button>
          <button type="button"
            class="delete-btn bg-gradient-to-r from-red-600 to-red-800 text-white font-semibold rounded-lg px-4 py-1.5 hover:from-red-700 hover:to-red-900 transform hover:-translate-y-1 hover:shadow-md transition-all duration-300 ease-in-out"
            @click="confirmDelete">
            Confirm Delete
          </button>
        </div>
      </div>
    </div>
  </section>
</template>

<script setup>
import { ref, computed, onMounted, onUnmounted } from 'vue'
import EditionService from '@/services/EditionService'
import { useRoute } from 'vue-router'

const route = useRoute()
const editionId = ref(localStorage.getItem('selectedEditionId') || route.params.editionId)
const images = ref([])
const currentIndex = ref(0)
const visibleCount = ref(3) // Show 3 images at a time
const showModal = ref(false)
const imagePreview = ref(null)
const selectedFile = ref(null)
const error = ref(null)
const isLoading = ref(true)
const isAdding = ref(false) // Loading state for adding image
const showDeleteModal = ref(false) // Modal for individual delete
const showDeleteAllModal = ref(false) // Modal for delete all
const deleteIndex = ref(null) // Store index for individual delete

// Fetch edition images on component mount
onMounted(() => {
  fetchImages()
})
async function fetchImages() {
  try {
    isLoading.value = true
    const response = await EditionService.getEdition(editionId.value)
    console.log('Fetched edition:', response.data)
    console.log('Fetched images:', response.data.images_url)
    images.value = response.data.images_url || []
    handleResize()
    window.addEventListener('resize', handleResize)
  } catch (err) {
    error.value = 'Failed to load images: ' + err.message
  } finally {
    isLoading.value = false
  }
}
const visibleImages = computed(() =>
  images.value.slice(currentIndex.value, currentIndex.value + visibleCount.value)
)

const maxIndex = computed(() =>
  Math.max(images.value.length - visibleCount.value, 0)
)

const totalDots = computed(() => images.value.length)

function getImageUrl(imagePath) {
  return `http://localhost:8000/storage/${imagePath}`;
}

function prevSlide() {
  if (currentIndex.value > 0) {
    currentIndex.value--
  }
}

function nextSlide() {
  if (currentIndex.value < maxIndex.value) {
    currentIndex.value++
  }
}

function handleResize() {
  visibleCount.value = 3; // Fixed to 3 images
}

function handleImageUpload(event) {
  const file = event.target.files[0]
  if (file && file.type.startsWith('image/')) {
    selectedFile.value = file
    const reader = new FileReader()
    reader.onload = (e) => {
      imagePreview.value = e.target.result
    }
    reader.readAsDataURL(file)
  } else {
    error.value = 'Please upload a valid image file'
    imagePreview.value = null
    selectedFile.value = null
  }
}

async function addImage() {
  try {
    if (!selectedFile.value) {
      error.value = 'Please upload an image first'
      return
    }

    isAdding.value = true // Start loading
    const formData = new FormData()
    formData.append('images[]', selectedFile.value)

    const response = await EditionService.addImages(editionId.value, formData)
    images.value = response.data.images || []

    // Reset form and refresh
    imagePreview.value = null
    selectedFile.value = null
    error.value = null
    isAdding.value = false
    showModal.value = false
    await fetchImages() // Automatic refresh
  } catch (err) {
    isAdding.value = false // Stop loading on error
    error.value = 'Error adding image: ' + (err.response?.data?.error || err.message)
  }
}

function openDeleteModal(index) {
  deleteIndex.value = index
  showDeleteModal.value = true
}

async function confirmDelete() {
  if (showDeleteModal.value && deleteIndex.value !== null) {
    await deleteImage(deleteIndex.value)
  } else if (showDeleteAllModal.value) {
    await deleteAllImages()
  }
  cancelDelete()
}

function cancelDelete() {
  showDeleteModal.value = false
  showDeleteAllModal.value = false
  deleteIndex.value = null
}

async function deleteImage(index) {
  try {
    await EditionService.removeImage(editionId.value, index)
    images.value.splice(index, 1)
    if (currentIndex.value > maxIndex.value) {
      currentIndex.value = maxIndex.value
    }
    await fetchImages() // Automatic refresh
  } catch (err) {
    error.value = 'Error deleting image: ' + err.message
  }
}

async function deleteAllImages() {
  try {
    // Delete images starting from the end to avoid index shifting
    for (let i = images.value.length - 1; i >= 0; i--) {
      await EditionService.removeImage(editionId.value, i)
    }
    // Reset local state and refresh
    images.value = []
    currentIndex.value = 0
    await fetchImages() // Automatic refresh
  } catch (err) {
    console.error(err)
    error.value = 'Error deleting images: ' + (err.response?.data?.message || err.message)
  }
}

onUnmounted(() => {
  window.removeEventListener('resize', handleResize)
})
</script>

<style scoped>
@import url("https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css");

.photo-gallery {
  padding: 2rem;
  max-width: 1500px;
  margin-right: auto;
  font-family: 'Segoe UI', sans-serif;
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

/* Buttons */
.action-buttons button {
  padding: 8px 16px;
  border-radius: 25px;
  border: 1px solid;
  cursor: pointer;
  font-weight: 500;
  transition: all 0.3s ease;
}

.delete-btn {
  border-color: #e53935;
  color: #e53935;
  background-color: white;
}

.delete-btn:hover {
  background-color: #e53935;
  color: white;
}

.add-btn {
  border-color: #265985;
  color: #265985;
  background-color: white;
}

.add-btn:hover {
  background-color: #265985;
  color: white;
}

/* Carousel and Slide Styles */
.carousel {
  display: flex;
  align-items: center;
  justify-content: center;
  gap: 1rem;
  flex-wrap: nowrap;
  transition: all 0.5s ease;
}

.nav-btn {
  border: 2px solid #1c1c3c;
  background: white;
  color: #1c1c3c;
  border-radius: 50%;
  width: 60px;
  height: 48px;
  font-size: 1.2rem;
  cursor: pointer;
  display: flex;
  align-items: center;
  justify-content: center;
  transition: all 0.3s ease;
}

.nav-btn:hover {
  background-color: #f6f6f6;
  transform: scale(1.1);
}

.nav-btn:disabled {
  opacity: 0.5;
  cursor: not-allowed;
}

.slides-wrapper {
  display: flex;
  justify-content: center;
  width: 100%;
  max-width: 900px; /* Match KeynoteSession slides-wrapper */
  position: relative;
}

.slide {
  background: white;
  border-radius: 20px;
  box-shadow: 0px 6px 20px rgba(0, 0, 0, 0.1);
  padding: 1rem;
  width: calc(33.33% - 0.8rem); /* 1/3 width with gap adjustment */
  display: flex;
  flex-direction: column;
  justify-content: center;
  align-items: center;
  animation: slideIn 0.4s ease;
  transition: transform 0.3s ease;
  position: relative;
  height: 300px; /* Consistent height for uniformity */
  margin: 0 0.4rem; /* Half of gap for spacing */
}

@keyframes slideIn {
  from {
    opacity: 0;
    transform: translateX(30px);
  }
  to {
    opacity: 1;
    transform: translateX(0);
  }
}

.action-icons {
  position: absolute;
  top: 15px;
  right: 15px;
  display: flex;
  gap: 10px;
  z-index: 10;
}

.hover-button {
  padding: 6px 12px;
  border: none;
  border-radius: 6px;
  cursor: pointer;
  transition: all 0.3s ease;
  background-color: #f2f2f2;
}

.hover-button:hover {
  background-color: #e53935;
  color: white;
}

.slide img {
  width: 100%;
  height: 100%;
  object-fit: cover;
  max-height: 300px; /* Limit image height within slide */
  border-radius: 10px;
}

.slide:hover img {
  transform: scale(1.05);
}

.dots {
  display: flex;
  justify-content: center;
  gap: 0.5rem;
  margin-top: 1rem;
}

.dot {
  width: 10px;
  height: 10px;
  background: #c4d6d6;
  border-radius: 50%;
  transition: all 0.3s ease;
}

.dot.active {
  background: #00a6a6;
  width: 12px;
  height: 12px;
  animation: bounce 0.4s ease;
}

@keyframes bounce {
  0% {
    transform: scale(1);
  }
  50% {
    transform: scale(1.4);
  }
  100% {
    transform: scale(1);
  }
}

.loading {
  text-align: center;
  padding: 2rem;
  font-style: italic;
  color: #666;
}

.empty-msg {
  text-align: center;
  color: #666;
  margin-top: 2rem;
  font-style: italic;
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
  margin-bottom: 1rem;
  color: #1b2d56;
  text-align: center;
}

.modal-content input {
  width: 95%;
  padding: 10px 14px;
  border: 1px solid #d1d5db;
  border-radius: 6px;
  margin-bottom: 12px;
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

.add-btn:disabled {
  background: #a3bffa;
  cursor: not-allowed;
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

/* Responsive */
@media (max-width: 768px) {
  .photo-gallery {
    margin-left: 0; /* Remove margin on mobile */
    padding: 1rem;
  }

  .header-row {
    flex-direction: column;
    align-items: flex-start;
  }

  .action-buttons {
    width: 100%;
  }

  .action-buttons button {
    width: 100%;
    margin-top: 0.5rem;
  }

  .nav-btn {
    width: 57px;
    height: 45px;
  }

  .slides-wrapper {
    max-width: 100%; /* Allow full width on mobile */
  }

  .slide {
    width: calc(50% - 0.4rem); /* 2 images per row on mobile */
    margin: 0 0.2rem;
  }
}
</style>