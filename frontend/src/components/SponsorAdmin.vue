```vue
<template>
  <div class="container mx-auto p-6">
    <h1 class="text-2xl font-bold text-gray-800 mb-6">Sponsors</h1>

    <div class="actions flex items-center gap-4 flex-wrap mb-8">
      <button class="btn add flex items-center" @click="showAddModal = true">
        <span class="mr-2">+</span> Add Sponsor
      </button>

      <div class="relative">
        <button class="btn edit" @click="showFilter = !showFilter">Filter by ▼</button>
        <ul v-if="showFilter" class="absolute top-full right-0 mt-2 bg-white border border-gray-300 rounded-lg shadow-lg z-10 min-w-[160px]">
          <li class="px-4 py-2 hover:bg-blue-50 cursor-pointer text-sm" @click="setFilter('all')">All</li>
          <li class="px-4 py-2 hover:bg-blue-50 cursor-pointer text-sm" @click="setFilter('confirmed')">Confirmed</li>
          <li class="px-4 py-2 hover:bg-blue-50 cursor-pointer text-sm" @click="setFilter('pending')">Pending</li>
        </ul>
      </div>
    </div>

    <div v-if="isLoading" class="loading-message">
      Loading sponsors...
    </div>

    <div v-else-if="sponsors.length === 0" class="empty-message">
      No sponsors available.
    </div>

    <div v-else class="grid">
      <div v-for="(sponsor, index) in filteredSponsors" :key="sponsor.id || index" class="card">
        <img :src="getImageUrl(sponsor.logo)" alt="Sponsor logo" class="logo" />
        <h2 class="name">{{ sponsor.name }}</h2>
        <p class="desc">{{ sponsor.description }}</p>
        <div class="info"><i class="fas fa-phone icon"></i><span>{{ sponsor.phone }}</span></div>
        <div class="info"><i class="fas fa-envelope icon"></i><span>{{ sponsor.email }}</span></div>
        <div class="tools">
          <button class="icon-btn" @click="openUpdateModal(index)">
            <i class="fas fa-edit"></i>
          </button>
          <button class="icon-btn" @click="openDeleteModal(index)">
            <i class="fas fa-trash"></i>
          </button>
        </div>
        <div v-if="sponsor.confirmed === null" class="flex justify-between mt-4">
          <button class="reject text-sm" @click="updateStatus(sponsor.id, false)">Reject</button>
          <button class="confirm text-sm" @click="updateStatus(sponsor.id, true)">Confirm</button>
        </div>
        <span v-else class="absolute bottom-2 left-2 px-2 py-1 text-xs font-semibold rounded-full"
              :class="{ 'bg-green-100 text-green-800': sponsor.confirmed, 'bg-red-100 text-red-800': !sponsor.confirmed }">
          {{ sponsor.confirmed ? 'Confirmed' : 'Rejected' }}
        </span>
      </div>
    </div>

    <!-- Add Modal -->
    <transition name="fade">
      <div v-if="showAddModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 transition-opacity duration-300">
        <div class="bg-white p-6 rounded-lg w-full max-w-md shadow-md font-poppins max-h-[90vh] overflow-y-auto">
          <h3 class="text-xl font-bold text-gray-800 mb-4 text-center sticky top-0 bg-white z-10">Add Sponsor</h3>
          <form @submit.prevent="addSponsor" class="space-y-4">
            <div>
              <label for="name" class="block text-sm font-medium text-gray-700">Company Name</label>
              <input
                v-model="newSponsor.name"
                id="name"
                type="text"
                placeholder="Enter company name"
                class="w-full px-3 py-2 border border-blue-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200"
                required
              />
            </div>
            <div>
              <label for="address" class="block text-sm font-medium text-gray-700">Address</label>
              <input
                v-model="newSponsor.address"
                id="address"
                type="text"
                placeholder="Enter address"
                class="w-full px-3 py-2 border border-blue-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200"
                required
              />
            </div>
            <div>
              <label for="phone" class="block text-sm font-medium text-gray-700">Phone</label>
              <input
                v-model="newSponsor.phone"
                id="phone"
                type="text"
                placeholder="Enter phone number"
                class="w-full px-3 py-2 border border-blue-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200"
                required
              />
            </div>
            <div>
              <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
              <input
                v-model="newSponsor.email"
                id="email"
                type="email"
                placeholder="Enter email"
                class="w-full px-3 py-2 border border-blue-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200"
                required
              />
            </div>
            <div>
              <label for="description" class="block text-sm font-medium text-gray-700">Description</label>
              <textarea
                v-model="newSponsor.description"
                id="description"
                placeholder="Enter description"
                class="w-full px-3 py-2 border border-blue-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200"
                rows="4"
                required
              ></textarea>
            </div>
            <div>
              <label for="logo" class="block text-sm font-medium text-gray-700">Logo</label>
              <input
                id="logo"
                type="file"
                @change="onFileChange"
                accept="image/*"
                class="w-full px-3 py-2 border border-blue-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200"
              />
            </div>
            <div class="flex justify-end space-x-3 mt-6">
              <button type="button" class="btn cancel" @click="showAddModal = false">Cancel</button>
              <button type="submit" class="btn add">Add</button>
            </div>
          </form>
        </div>
      </div>
    </transition>

    <!-- Update Modal -->
    <transition name="fade">
      <div v-if="showUpdateModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 transition-opacity duration-300">
        <div class="bg-white p-6 rounded-lg w-full max-w-md shadow-md font-poppins max-h-[90vh] overflow-y-auto">
          <h3 class="text-xl font-bold text-gray-800 mb-4 text-center sticky top-0 bg-white z-10">Update Sponsor</h3>
          <form @submit.prevent="updateSponsor" class="space-y-4">
            <div>
              <label for="update_name" class="block text-sm font-medium text-gray-700">Company Name</label>
              <input
                v-model="editSponsor.name"
                id="update_name"
                type="text"
                placeholder="Enter company name"
                class="w-full px-3 py-2 border border-blue-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200"
                required
              />
            </div>
            <div>
              <label for="update_address" class="block text-sm font-medium text-gray-700">Address</label>
              <input
                v-model="editSponsor.address"
                id="update_address"
                type="text"
                placeholder="Enter address"
                class="w-full px-3 py-2 border border-blue-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200"
                required
              />
            </div>
            <div>
              <label for="update_phone" class="block text-sm font-medium text-gray-700">Phone</label>
              <input
                v-model="editSponsor.phone"
                id="update_phone"
                type="text"
                placeholder="Enter phone number"
                class="w-full px-3 py-2 border border-blue-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200"
                required
              />
            </div>
            <div>
              <label for="update_email" class="block text-sm font-medium text-gray-700">Email</label>
              <input
                v-model="editSponsor.email"
                id="update_email"
                type="email"
                placeholder="Enter email"
                class="w-full px-3 py-2 border border-blue-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200"
                required
              />
            </div>
            <div>
              <label for="update_description" class="block text-sm font-medium text-gray-700">Description</label>
              <textarea
                v-model="editSponsor.description"
                id="update_description"
                placeholder="Enter description"
                class="w-full px-3 py-2 border border-blue-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200"
                rows="4"
                required
              ></textarea>
            </div>
            <div>
              <label for="update_status" class="block text-sm font-medium text-gray-700">Status</label>
              <select
                v-model="editSponsor.confirmed"
                id="update_status"
                class="w-full px-3 py-2 border border-blue-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200"
                required
              >
                <option :value="null">Pending</option>
                <option :value="true">Confirmed</option>
                <option :value="false">Rejected</option>
              </select>
            </div>
            <div>
              <label for="update_logo" class="block text-sm font-medium text-gray-700">Logo</label>
              <input
                id="update_logo"
                type="file"
                @change="onUpdateFileChange"
                accept="image/*"
                class="w-full px-3 py-2 border border-blue-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200"
              />
            </div>
            <div class="flex justify-end space-x-3 mt-6">
              <button type="button" class="btn cancel" @click="showUpdateModal = false">Cancel</button>
              <button type="submit" class="btn add">Update</button>
            </div>
          </form>
        </div>
      </div>
    </transition>

    <!-- Delete Modal -->
    <transition name="fade">
      <div v-if="showDeleteModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 transition-opacity duration-300">
        <div class="bg-white p-6 rounded-lg w-full max-w-md shadow-md font-poppins max-h-[90vh] overflow-y-auto">
          <h3 class="text-xl font-bold text-gray-800 mb-4 text-center sticky top-0 bg-white z-10">Delete Sponsor</h3>
          <p class="text-gray-600 mb-6 text-center">Are you sure you want to delete <strong>{{ editSponsor.name }}</strong>?</p>
          <div class="flex justify-end space-x-3">
            <button type="button" class="btn cancel" @click="showDeleteModal = false">Cancel</button>
            <button type="button" class="btn delete" @click="deleteSponsor">Yes, Delete</button>
          </div>
        </div>
      </div>
    </transition>
  </div>
</template>

<script setup>
import { ref } from 'vue';
import { useRoute } from 'vue-router';
import SponsorService from '@/services/SponsorService';

const route = useRoute();
const editionId = ref(localStorage.getItem('selectedEditionId') || route.params.editionId);
const sponsors = ref([]);
const filter = ref('all');
const showFilter = ref(false);
const showAddModal = ref(false);
const showUpdateModal = ref(false);
const showDeleteModal = ref(false);
const editIndex = ref(null);
const isLoading = ref(true);
const newSponsor = ref({
  name: '',
  address: '',
  phone: '',
  email: '',
  description: '',
  logo: null,
  edition_id: editionId.value,
  confirmed: null
});
const editSponsor = ref({
  name: '',
  address: '',
  phone: '',
  email: '',
  description: '',
  logo: '',
  confirmed: null
});
const selectedFile = ref(null);

async function fetchSponsors() {
  try {
    isLoading.value = true;
    const response = await SponsorService.getSponsorsByEdition(editionId.value);
    sponsors.value = response.data;
  } catch (error) {
    console.error('Error fetching sponsors:', error);
  } finally {
    isLoading.value = false;
  }
}

function setFilter(type) {
  filter.value = type;
  showFilter.value = false;
}

async function updateStatus(id, status) {
  try {
    await SponsorService.updateSponsor(id, { confirmed: status });
    await fetchSponsors();
  } catch (error) {
    console.error('Error updating sponsor status:', error);
  }
}

async function deleteSponsor() {
  try {
    await SponsorService.deleteSponsor(sponsors.value[editIndex.value].id);
    sponsors.value.splice(editIndex.value, 1);
    showDeleteModal.value = false;
    editIndex.value = null;
    await fetchSponsors();
  } catch (error) {
    console.error('Error deleting sponsor:', error);
  }
}

function onFileChange(event) {
  const file = event.target.files[0];
  if (file) {
    selectedFile.value = file;
    newSponsor.value.logo = URL.createObjectURL(file);
  }
}

function onUpdateFileChange(event) {
  const file = event.target.files[0];
  if (file) {
    selectedFile.value = file;
    editSponsor.value.logo = URL.createObjectURL(file);
  }
}

async function addSponsor() {
  try {
    const formData = new FormData();
    formData.append('name', newSponsor.value.name);
    formData.append('address', newSponsor.value.address);
    formData.append('phone', newSponsor.value.phone);
    formData.append('email', newSponsor.value.email);
    formData.append('description', newSponsor.value.description);
    formData.append('edition_id', newSponsor.value.edition_id);
    formData.append('confirmed', newSponsor.value.confirmed || null);
    if (selectedFile.value) {
      formData.append('logo', selectedFile.value);
    }
    await SponsorService.createSponsor(formData);
    await fetchSponsors();
    showAddModal.value = false;
    resetNewSponsor();
  } catch (error) {
    console.error('Error adding sponsor:', error);
  }
}

function updateSponsor() {
  if (editIndex.value !== null) {
    sponsors.value.splice(editIndex.value, 1, { ...editSponsor.value });
    showUpdateModal.value = false;
    resetEditSponsor();
  }
}

function openUpdateModal(index) {
  editSponsor.value = { ...sponsors.value[index] };
  editIndex.value = index;
  showUpdateModal.value = true;
}

function openDeleteModal(index) {
  editSponsor.value = { ...sponsors.value[index] };
  editIndex.value = index;
  showDeleteModal.value = true;
}

function resetNewSponsor() {
  newSponsor.value = {
    name: '',
    address: '',
    phone: '',
    email: '',
    description: '',
    logo: null,
    edition_id: editionId.value,
    confirmed: null
  };
  selectedFile.value = null;
}

function resetEditSponsor() {
  editSponsor.value = {
    name: '',
    address: '',
    phone: '',
    email: '',
    description: '',
    logo: '',
    confirmed: null
  };
  editIndex.value = null;
}

function getImageUrl(imagePath) {
  return `http://localhost:8000/storage/${imagePath}`;
}

fetchSponsors();
</script>

<style scoped>
@import url("https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css");

.btn.add {
  background-color: #265985;
  color: white;
  border: none;
  padding: 0.5rem 1rem;
  border-radius: 5px;
}

.btn.add:hover {
  background-color: #1e476b;
}

.btn.cancel {
  background: #999;
  color: white;
  border: none;
  padding: 0.5rem 1rem;
  border-radius: 5px;
}

.btn.delete {
  background: #eb5a5a;
  color: white;
  border: none;
  padding: 0.5rem 1rem;
  border-radius: 5px;
}

.btn.edit {
  border: 1px solid #265985;
  color: #265985;
  background: white;
}

.confirm {
  background: #4a90e2;
  color: white;
  border: none;
  padding: 0.3rem 1rem;
  border-radius: 5px;
}

.reject {
  background: #eb5a5a;
  color: white;
  border: none;
  padding: 0.3rem 1rem;
  border-radius: 5px;
}

.fade-enter-active, .fade-leave-active {
  transition: opacity 0.3s ease;
}
.fade-enter-from, .fade-leave-to {
  opacity: 0;
}

.font-poppins {
  font-family: 'Poppins', sans-serif;
}

.container {
  padding: 24px;
  font-family: "Segoe UI", sans-serif;
}

.actions {
  display: flex;
  justify-content: space-between;
  margin-bottom: 20px;
  flex-wrap: wrap;
  gap: 10px;
}

.grid {
  display: grid;
  grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
  gap: 20px;
}

.card {
  background: rgb(254, 252, 252);
  border-radius: 16px;
  box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
  padding: 16px;
  border: 2px solid #265985;
  text-align: center;
  position: relative;
  transition: transform 0.3s ease, box-shadow 0.3s ease;
}

.card:hover {
  transform: translateY(-8px);
  box-shadow: 0 12px 24px rgba(0, 0, 0, 0.1);
}

.logo {
  height: 60px;
  object-fit: contain;
  margin-bottom: 16px;
  transition: transform 0.3s ease;
}

.card:hover .logo {
  transform: scale(1.1);
}

.name {
  font-weight: 600;
  color: #1f2937;
}

.desc {
  font-size: 14px;
  color: #9ca3af;
  margin-bottom: 16px;
}

.info {
  display: flex;
  align-items: center;
  justify-content: center;
  gap: 8px;
  font-size: 14px;
  color: #4b5563;
  margin-bottom: 8px;
}

.icon {
  color: #8b5cf6;
}

.tools {
  position: absolute;
  top: 8px;
  right: 8px;
  display: flex;
  gap: 8px;
}

.icon-btn {
  background: transparent;
  border: none;
  color: #6b7280;
  cursor: pointer;
  font-size: 16px;
}

.icon-btn:hover {
  color: #852c26;
}

.loading-message,
.empty-message {
  text-align: center;
  padding: 2rem;
  color: #666;
  font-style: italic;
}

@media (max-width: 480px) {
  .btn {
    width: 100%;
    justify-content: center;
  }
  .actions {
    flex-direction: column;
    align-items: stretch;
  }
}
</style>
```