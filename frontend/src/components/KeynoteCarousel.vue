<template>
  <section class="keynote-section">
    <h2 class="title">Keynote Session</h2>

    <div class="action-buttons">
      <button class="add-btn" @click="showModal = true">
        <i class="fas fa-plus"></i> Add
      </button>
    </div><br><br>

    <div class="carousel">
      <button class="nav-btn" @click="prevSlide" :disabled="currentIndex === 0">
        <i class="fas fa-arrow-left"></i>
      </button>

      <div class="slides-wrapper">
        <div class="slide" v-for="(item, index) in visibleSlides" :key="index">
          <div class="action-icons">
            <i class="fas fa-edit update-icon" @click="openUpdateModal(item, index)"></i>
            <i class="fas fa-trash delete-icon"></i>
          </div>
          <div class="quote-icon">❝</div>
          <p class="text">{{ item.text }}</p>
          <div class="profile">
            <img :src="item.image" alt="Speaker photo" class="avatar" />
            <div>
              <h3>{{ item.name }}</h3>
              <p class="role">{{ item.role }}</p>
            </div>
          </div>
        </div>
      </div>

      <button class="nav-btn" @click="nextSlide" :disabled="currentIndex === items.length - 1">
        <i class="fas fa-arrow-right"></i>
      </button>
    </div>

    <div class="dots">
      <span v-for="(dot, i) in items.length" :key="i" :class="['dot', { active: i === currentIndex }]"></span>
    </div>

    <!-- Add New Keynote Modal -->
    <div v-if="showModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 transition-opacity duration-300">
      <div class="bg-white p-6 rounded-lg w-full max-w-md shadow-md font-poppins max-h-[90vh] overflow-y-auto">
        <h3 class="text-xl font-bold text-gray-800 mb-4 text-center sticky top-0 bg-white z-10">Add New Keynote</h3>
        <div class="space-y-4">
          <div>
            <label for="text" class="block text-sm font-medium text-gray-700">Quote Text</label>
            <textarea
              v-model="newKeynote.text"
              id="text"
              placeholder="Enter the keynote quote here..."
              class="w-full px-3 py-2 border border-blue-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200"
              rows="4"
              required
            ></textarea>
          </div>
          <div>
            <label for="name" class="block text-sm font-medium text-gray-700">Name</label>
            <input
              v-model="newKeynote.name"
              id="name"
              type="text"
              placeholder="Enter speaker's full name"
              class="w-full px-3 py-2 border border-blue-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200"
              required
            />
          </div>
          <div>
            <label for="role" class="block text-sm font-medium text-gray-700">Role</label>
            <input
              v-model="newKeynote.role"
              id="role"
              type="text"
              placeholder="Enter speaker's role or title"
              class="w-full px-3 py-2 border border-blue-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200"
              required
            />
          </div>
          <div>
            <label for="quote_fr" class="block text-sm font-medium text-gray-700">Quote (French)</label>
            <input
              v-model="newKeynote.quote_fr"
              id="quote_fr"
              type="text"
              placeholder="Enter French quote"
              class="w-full px-3 py-2 border border-blue-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200"
              required
            />
          </div>
          <div>
            <label for="quote_en" class="block text-sm font-medium text-gray-700">Quote (English)</label>
            <input
              v-model="newKeynote.quote_en"
              id="quote_en"
              type="text"
              placeholder="Enter English quote"
              class="w-full px-3 py-2 border border-blue-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200"
              required
            />
          </div>
          <div>
            <label for="image" class="block text-sm font-medium text-gray-700">Profile Image</label>
            <input
              id="image"
              type="file"
              @change="handleImageUpload"
              accept="image/*"
              class="w-full px-3 py-2 border border-blue-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200"
              required
            />
            <div v-if="imagePreview" class="mt-2">
              <img :src="imagePreview" alt="Image Preview" class="w-20 h-20 object-cover rounded-md" />
            </div>
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
            @click="addKeynote"
            type="button"
            class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition-colors duration-200"
          >
            Add Keynote
          </button>
        </div>
      </div>
    </div>

    <!-- Update Keynote Modal -->
    <div v-if="showUpdateModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 transition-opacity duration-300">
      <div class="bg-white p-6 rounded-lg w-full max-w-md shadow-md font-poppins max-h-[90vh] overflow-y-auto">
        <h3 class="text-xl font-bold text-gray-800 mb-4 text-center sticky top-0 bg-white z-10">Update Keynote</h3>
        <div class="space-y-4">
          <div>
            <label for="update_text" class="block text-sm font-medium text-gray-700">Quote Text</label>
            <textarea
              v-model="selectedItem.text"
              id="update_text"
              placeholder="Enter the keynote quote here..."
              class="w-full px-3 py-2 border border-blue-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200"
              rows="4"
              required
            ></textarea>
          </div>
          <div>
            <label for="update_name" class="block text-sm font-medium text-gray-700">Name</label>
            <input
              v-model="selectedItem.name"
              id="update_name"
              type="text"
              placeholder="Enter speaker's full name"
              class="w-full px-3 py-2 border border-blue-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200"
              required
            />
          </div>
          <div>
            <label for="update_role" class="block text-sm font-medium text-gray-700">Role</label>
            <input
              v-model="selectedItem.role"
              id="update_role"
              type="text"
              placeholder="Enter speaker's role or title"
              class="w-full px-3 py-2 border border-blue-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200"
              required
            />
          </div>
          <div>
            <label for="update_quote_fr" class="block text-sm font-medium text-gray-700">Quote (French)</label>
            <input
              v-model="selectedItem.quote_fr"
              id="update_quote_fr"
              type="text"
              placeholder="Enter French quote"
              class="w-full px-3 py-2 border border-blue-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200"
              required
            />
          </div>
          <div>
            <label for="update_quote_en" class="block text-sm font-medium text-gray-700">Quote (English)</label>
            <input
              v-model="selectedItem.quote_en"
              id="update_quote_en"
              type="text"
              placeholder="Enter English quote"
              class="w-full px-3 py-2 border border-blue-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200"
              required
            />
          </div>
          <div>
            <label for="update_image" class="block text-sm font-medium text-gray-700">Profile Image</label>
            <input
              id="update_image"
              type="file"
              @change="handleUpdateImageUpload"
              accept="image/*"
              class="w-full px-3 py-2 border border-blue-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200"
            />
            <div v-if="updateImagePreview" class="mt-2">
              <img :src="updateImagePreview" alt="Image Preview" class="w-20 h-20 object-cover rounded-md" />
            </div>
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
            @click="updateKeynote"
            type="button"
            class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition-colors duration-200"
          >
            Update Keynote
          </button>
        </div>
      </div>
    </div>
  </section>
</template>

<script setup>
import { ref, computed } from 'vue'

const currentIndex = ref(0)
const showModal = ref(false)
const showUpdateModal = ref(false)
const newKeynote = ref({
  text: '',
  name: '',
  role: '',
  quote_fr: '',
  quote_en: '',
  image: null,
})
const imagePreview = ref(null)
const selectedItem = ref(null)
const updateImagePreview = ref(null)
const error = ref(null)

const items = ref([
  {
    text: 'Sed eligendi facere repellendus. Ipsam ipsam incidunt minima harum tenetur. '.repeat(2),
    name: 'Samantha W.',
    role: 'Directeur ISET',
    quote_fr: 'Citation en français',
    quote_en: 'Quote in English',
    image: 'https://i.pravatar.cc/50?img=12',
  },
  {
    text: 'Sed eligendi facere repellendus. Ipsam ipsam incidunt minima harum tenetur. '.repeat(2),
    name: 'John D.',
    role: 'Directeur ISET',
    quote_fr: 'Citation en français',
    quote_en: 'Quote in English',
    image: 'https://i.pravatar.cc/50?img=32',
  },
  {
    text: 'Sed eligendi facere repellendus. Ipsam ipsam incidunt minima harum tenetur. '.repeat(2),
    name: 'Alice K.',
    role: 'Directeur ISET',
    quote_fr: 'Citation en français',
    quote_en: 'Quote in English',
    image: 'https://i.pravatar.cc/50?img=21',
  },
])

const visibleSlides = computed(() => [items.value[currentIndex.value]])

function nextSlide() {
  if (currentIndex.value < items.value.length - 1) currentIndex.value++
}

function prevSlide() {
  if (currentIndex.value > 0) currentIndex.value--
}

function handleImageUpload(event) {
  const file = event.target.files[0]
  if (file) {
    const reader = new FileReader()
    reader.onload = (e) => {
      imagePreview.value = e.target.result
    }
    reader.readAsDataURL(file)
    newKeynote.value.image = file
  }
}

function handleUpdateImageUpload(event) {
  const file = event.target.files[0]
  if (file) {
    const reader = new FileReader()
    reader.onload = (e) => {
      updateImagePreview.value = e.target.result
    }
    reader.readAsDataURL(file)
    selectedItem.value.image = file
  } else {
    updateImagePreview.value = selectedItem.value.image // Keep original if no new file
  }
}

function addKeynote() {
  if (newKeynote.value.text && newKeynote.value.name && newKeynote.value.role && newKeynote.value.quote_fr && newKeynote.value.quote_en && newKeynote.value.image) {
    items.value.push({
      text: newKeynote.value.text,
      name: newKeynote.value.name,
      role: newKeynote.value.role,
      quote_fr: newKeynote.value.quote_fr,
      quote_en: newKeynote.value.quote_en,
      image: imagePreview.value,
    })
    newKeynote.value = { text: '', name: '', role: '', quote_fr: '', quote_en: '', image: null }
    imagePreview.value = null
    error.value = null
    showModal.value = false
  } else {
    error.value = 'Please fill all required fields'
  }
}

function openUpdateModal(item, index) {
  selectedItem.value = { ...item, index }
  updateImagePreview.value = item.image
  showUpdateModal.value = true
}

function updateKeynote() {
  if (selectedItem.value.text && selectedItem.value.name && selectedItem.value.role && selectedItem.value.quote_fr && selectedItem.value.quote_en) {
    items.value[selectedItem.value.index] = {
      ...selectedItem.value,
      image: updateImagePreview.value || selectedItem.value.image, // Use new preview or original
    }
    showUpdateModal.value = false
    error.value = null
  } else {
    error.value = 'Please fill all required fields'
  }
}
</script>

<style scoped>
.keynote-section {
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

.action-buttons button {
  margin-left: 1rem;
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
  opacity: 0.5;
  cursor: not-allowed;
}

.slides-wrapper {
  display: flex;
  justify-content: center;
  width: 100%;
  max-width: 600px;
  position: relative;
}

.slide {
  background: white;
  border-radius: 20px;
  box-shadow: 0px 6px 20px rgba(0, 0, 0, 0.1);
  padding: 2rem;
  width: 100%;
  display: flex;
  flex-direction: column;
  justify-content: space-between;
  animation: slideIn 0.4s ease;
  transition: transform 0.3s ease;
  position: relative;
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

.quote-icon {
  font-size: 2rem;
  color: #f6b10c;
  margin-bottom: 1rem;
  transform: rotate(-10deg);
  animation: pulse 2s infinite;
}

@keyframes pulse {
  0% {
    transform: rotate(-10deg) scale(1);
  }
  50% {
    transform: rotate(-10deg) scale(1.05);
  }
  100% {
    transform: rotate(-10deg) scale(1);
  }
}

.text {
  font-size: 0.95rem;
  color: #333;
  flex-grow: 1;
  margin-bottom: 1rem;
  line-height: 1.6;
  transition: color 0.3s ease;
}

.profile {
  display: flex;
  align-items: center;
  gap: 1rem;
}

.avatar {
  width: 50px;
  height: 50px;
  border-radius: 50%;
  object-fit: cover;
  transition: transform 0.3s ease;
}

.avatar:hover {
  transform: scale(1.1);
}

.role {
  font-size: 0.85rem;
  color: gray;
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

@media (max-width: 768px) {
  .nav-btn {
    width: 57px;
    height: 45px;
  }
}
</style>