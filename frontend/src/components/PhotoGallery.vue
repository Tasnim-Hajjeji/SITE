<template>
  <section class="photo-gallery">
    <h2 class="title">Galerie Photo</h2>

    <div class="action-buttons">
      <button class="delete-btn" @click="deleteAllImages">
      <button class="delete-btn" @click="deleteAllImages">
        <i class="fas fa-trash"></i> Delete All
      </button>
      <button class="add-btn" @click="showModal = true">
        <i class="fas fa-plus"></i> Add
      </button>
    </div><br><br>

    <div v-if="isLoading" class="loading-message">
      Loading images...
    </div>

    <div v-else-if="images.length === 0" class="empty-message">
      No images available.
    </div>

    <div v-else class="carousel-container">
      <button class="nav-btn" @click="prevSlide" :disabled="currentIndex === 0">
        <i class="fas fa-arrow-left"></i>
      </button>

      <div class="carousel-track">
        <div v-for="(image, index) in visibleImages" :key="index" class="image-card">
          <img :src="getImageUrl(image)" alt="Gallery photo" />
          <button class="hover-button delete-btn" @click="deleteImage(index)">
            Delete
          </button>
        </div>
      </div>

      <button class="nav-btn" @click="nextSlide" :disabled="currentIndex >= maxIndex">
        <i class="fas fa-arrow-right"></i>
      </button>
    </div>

    <div v-if="!isLoading && images.length > 0" class="carousel-dots">
      <span v-for="(dot, i) in totalDots" :key="i" :class="['dot', { active: i === currentIndex }]"></span>
    </div>

    <!-- Add Image Modal -->
    <div v-if="showModal"
      class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 transition-opacity duration-300">
      <div class="bg-white p-6 rounded-lg w-full max-w-md shadow-md font-poppins max-h-[90vh] overflow-y-auto">
        <h3 class="text-xl font-bold text-gray-800 mb-4 text-center sticky top-0 bg-white z-10">Add Image</h3>
        <div class="space-y-4">
          <div>
            <label for="image_upload" class="block text-sm font-medium text-gray-700">Upload Image</label>
            <input id="image_upload" type="file" accept="image/*" @change="handleImageUpload"
              class="w-full px-3 py-2 border border-blue-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200"
              required />
            <div v-if="imagePreview" class="mt-2">
              <img :src="imagePreview" alt="Image Preview" class="w-20 h-20 object-cover rounded-md" />
            </div>
          </div>
          <div v-if="error" class="p-2 bg-red-100 text-red-700 rounded-md flex items-center">
            <span class="mr-1">!</span> {{ error }}
          </div>
        </div>
        <div class="mt-6 flex justify-end space-x-3">
          <button @click="showModal = false" type="button"
            class="px-4 py-2 bg-gray-200 text-gray-700 rounded-md hover:bg-gray-300 transition-colors duration-200">
            Cancel
          </button>
          <button @click="addImage" type="button"
            class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition-colors duration-200"
            :disabled="!imagePreview">
            Add Image
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
const visibleCount = ref(4)
const showModal = ref(false)
const imageFile = ref(null)
const imagePreview = ref(null)
const selectedFile = ref(null)
const error = ref(null)
const isLoading = ref(true)

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

function getImageUrl(imagePath) {
  return `http://localhost:8000/storage/${imagePath}`;
}

const fetchImages = async () => {
  try {
    const response = await EditionService.getEdition(props.editionId)
    images.value = response.data.images_url || []
  } catch (err) {
    error.value = 'Failed to load images: ' + err.message
    console.error(err)
  }
}

onMounted(() => {
  handleResize()
  window.addEventListener('resize', handleResize)
  fetchImages()
})

onUnmounted(() => {
  window.removeEventListener('resize', handleResize)
})

watch(() => props.editionId, () => {
  fetchImages()
})

function prevSlide() {
  if (currentIndex.value > 0) currentIndex.value--
}

function nextSlide() {
  if (currentIndex.value < maxIndex.value) currentIndex.value++
}

function handleResize() {
  const width = window.innerWidth
  visibleCount.value = width < 640 ? 1 : width < 1024 ? 2 : width < 1280 ? 3 : 4
  if (currentIndex.value > maxIndex.value) currentIndex.value = maxIndex.value
}

function handleImageUpload(event) {
  const file = event.target.files[0]
  if (file && file.type.startsWith('image/')) {
    selectedFile.value = file
    const reader = new FileReader()
    reader.onload = (e) => (imagePreview.value = e.target.result)
    reader.readAsDataURL(file)
    error.value = null
  } else {
    error.value = 'Please upload a valid image file'
    imageFile.value = null
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

    const formData = new FormData()
    formData.append('images[]', selectedFile.value)

    const response = await EditionService.addImages(editionId.value, formData)
    images.value = response.data.images || []

    // Reset form
    imagePreview.value = null
    selectedFile.value = null
    error.value = null
    showModal.value = false
    fetchImages() // Refresh images after adding
  } catch (err) {
    error.value = 'Error adding image: ' + (err.response?.data?.error || err.message)
  }
}

async function deleteImage(index) {
  try {
    if (confirm('Are you sure you want to delete this image?')) {
      await EditionService.removeImage(editionId.value, index)
      images.value.splice(index, 1)
      if (currentIndex.value > maxIndex.value) {
        currentIndex.value = maxIndex.value
      }
    }
    fetchImages() // Refresh images after deletion
  } catch (err) {
    error.value = 'Error deleting image: ' + err.message
  }
}

async function deleteAllImages() {
  try {
    if (confirm('Are you sure you want to delete all images?')) {
      // Delete images starting from the end to avoid index shifting
      for (let i = images.value.length - 1; i >= 0; i--) {
        await EditionService.removeImage(editionId.value, i)
      }

      // Refetch updated image list from backend
      await fetchImages()

      // Reset local state
      images.value = []
      currentIndex.value = 0
    }
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
.photo-gallery {
  padding: 2rem;
  max-width: 1200px;
  margin: auto;
  font-family: 'Poppins', sans-serif;
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
  display: flex;
  gap: 1rem;
  margin-bottom: 2rem;
  flex-wrap: wrap;
}

.action-buttons button {
  margin-left: 1rem;
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
  background-color: #fff;
}

.delete-btn:hover {
  background-color: #e53935;
  color: #fff;
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

.carousel-container {
  display: flex;
  align-items: center;
  justify-content: center;
  gap: 1rem;
  flex-wrap: nowrap;
}

.nav-btn {
  border: 2px solid #1c1c3c;
  background: white;
  color: #1c1c3c;
  border-radius: 50%;
  width: 48px;
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
  opacity: 0.4;
  cursor: not-allowed;
}

.carousel-track {
  display: flex;
  gap: 1.2rem;
  flex-wrap: nowrap;
  overflow: hidden;
  width: 100%;
  max-width: 90%;
}

.image-card {
  position: relative;
  flex: 0 0 auto;
  width: 220px;
  height: auto;
  border-radius: 10px;
  box-shadow: 0px 6px 15px rgba(0, 0, 0, 0.1);
  overflow: hidden;
  transition: transform 0.3s ease;
  background: white;
}

.image-card img {
  width: 100%;
  height: 100%;
  object-fit: cover;
  display: block;
  transition: transform 0.3s ease;
}

.image-card:hover img {
  transform: scale(1.05);
}

.carousel-dots {
  display: flex;
  justify-content: center;
  margin-top: 1.2rem;
  gap: 0.5rem;
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
  transform: scale(1.2);
}

@media (max-width: 768px) {
  .nav-btn {
    width: 57px;
    height: 45px;
  }
}

.hover-button {
  position: absolute;
  bottom: 5%;
  left: 5%;
  z-index: 10;
  display: none;
  padding: 8px 16px;
  border: none;
  border-radius: 10px;
  cursor: pointer;
  transition: all 0.3s ease;
}

.image-card:hover .hover-button {
  display: block;
}

.image-card img {
  position: relative;
  z-index: 1;
  transition: opacity 0.3s ease;
}

.image-card:hover img {
  opacity: 0.8;
}
</style>