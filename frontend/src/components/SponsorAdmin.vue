```vue
<template>
  <div class="container mx-auto p-6">
    <h1 class="text-2xl font-bold text-gray-800 mb-6">Sponsors</h1>

    <div class="actions flex items-center gap-4 flex-wrap mb-8">
      <button class="btn add flex items-center" @click="toggleAddModal">
        <span class="mr-2">+</span> Add Sponsor
      </button>

      <div class="relative">
        <button class="btn edit" @click="toggleFilter">Filter by ▼</button>
        <ul v-if="showFilter" class="absolute top-full right-0 mt-2 bg-white border border-gray-300 rounded-lg shadow-lg z-10 min-w-[160px]">
          <li class="px-4 py-2 hover:bg-blue-50 cursor-pointer text-sm" @click="setFilter('all')">All</li>
          <li class="px-4 py-2 hover:bg-blue-50 cursor-pointer text-sm" @click="setFilter('confirmed')">Confirmed</li>
          <li class="px-4 py-2 hover:bg-blue-50 cursor-pointer text-sm" @click="setFilter('pending')">Pending</li>
        </ul>
      </div>
    </div>

    <div class="grid">
      <div v-for="(sponsor, index) in filteredSponsors" :key="index" class="card">
        <img :src="sponsor.logo" alt="Sponsor logo" class="logo" />
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
          <button class="reject text-sm" @click="updateStatus(index, false)">Reject</button>
          <button class="confirm text-sm" @click="updateStatus(index, true)">Confirm</button>
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
              <button
                type="button"
                class="btn cancel"
                @click="toggleAddModal"
              >
                Cancel
              </button>
              <button
                type="submit"
                class="btn add"
              >
                Add
              </button>
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
              <button
                type="button"
                class="btn cancel"
                @click="toggleUpdateModal"
              >
                Cancel
              </button>
              <button
                type="submit"
                class="btn add"
              >
                Update
              </button>
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
            <button
              type="button"
              class="btn cancel"
              @click="toggleDeleteModal"
            >
              Cancel
            </button>
            <button
              type="button"
              class="btn delete"
              @click="deleteSponsor"
            >
              Yes, Delete
            </button>
          </div>
        </div>
      </div>
    </transition>
  </div>
</template>

<script>
export default {
  data() {
    return {
      sponsors: [],
      filter: 'all',
      showFilter: false,
      showAddModal: false,
      showUpdateModal: false,
      showDeleteModal: false,
      editIndex: null,
      newSponsor: {
        name: '',
        address: '',
        phone: '',
        email: '',
        description: '',
        logo: '',
        confirmed: null, // Initial state is pending
      },
      editSponsor: {
        name: '',
        address: '',
        phone: '',
        email: '',
        description: '',
        logo: '',
        confirmed: null,
      },
    };
  },
  computed: {
    filteredSponsors() {
      if (this.filter === 'all') return this.sponsors;
      if (this.filter === 'confirmed') return this.sponsors.filter(s => s.confirmed === true);
      if (this.filter === 'pending') return this.sponsors.filter(s => s.confirmed === null);
      return [];
    },
  },
  methods: {
    toggleFilter() {
      this.showFilter = !this.showFilter;
    },
    setFilter(type) {
      this.filter = type;
      this.showFilter = false;
    },
    updateStatus(index, status) {
      this.sponsors[index].confirmed = status;
    },
    onFileChange(event) {
      const file = event.target.files[0];
      if (file) this.newSponsor.logo = URL.createObjectURL(file);
    },
    onUpdateFileChange(event) {
      const file = event.target.files[0];
      if (file) this.editSponsor.logo = URL.createObjectURL(file);
    },
    addSponsor() {
      this.sponsors.push({ ...this.newSponsor });
      this.resetNewSponsor();
      this.toggleAddModal();
    },
    toggleAddModal() {
      this.showAddModal = !this.showAddModal;
      if (!this.showAddModal) this.resetNewSponsor();
    },
    openUpdateModal(index) {
      this.editSponsor = { ...this.sponsors[index] };
      this.editIndex = index;
      this.showUpdateModal = true;
    },
    toggleUpdateModal() {
      this.showUpdateModal = !this.showUpdateModal;
      if (!this.showUpdateModal) this.resetEditSponsor();
    },
    updateSponsor() {
      if (this.editIndex !== null) {
        this.sponsors.splice(this.editIndex, 1, { ...this.editSponsor });
      }
      this.toggleUpdateModal();
    },
    openDeleteModal(index) {
      this.editSponsor = { ...this.sponsors[index] };
      this.editIndex = index;
      this.showDeleteModal = true;
    },
    toggleDeleteModal() {
      this.showDeleteModal = !this.showDeleteModal;
      if (!this.showDeleteModal) this.resetEditSponsor();
    },
    deleteSponsor() {
      if (this.editIndex !== null) {
        this.sponsors.splice(this.editIndex, 1);
      }
      this.toggleDeleteModal();
    },
    resetNewSponsor() {
      this.newSponsor = {
        name: '',
        address: '',
        phone: '',
        email: '',
        description: '',
        logo: '',
        confirmed: null,
      };
    },
    resetEditSponsor() {
      this.editSponsor = {
        name: '',
        address: '',
        phone: '',
        email: '',
        description: '',
        logo: '',
        confirmed: null,
      };
      this.editIndex = null;
    },
  },
};
</script>

<style scoped>
@import url("https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css");

/* Button styles from PartnairAdmin */
.btn {
  padding: 0.5rem 1rem;
  border-radius: 9999px;
  font-weight: 500;
  font-size: 14px;
  cursor: pointer;
  transition: background-color 0.3s ease, transform 0.2s ease;
}

.add {
  background-color: #265985;
  color: white;
  border: none;
}

.add:hover {
  background-color: #1e476b;
  transform: scale(1.05);
}

.edit {
  border: 1px solid #265985;
  color: #265985;
  background: white;
}

.cancel {
  background: #999;
  color: white;
  border: none;
  padding: 0.3rem 1rem;
  border-radius: 5px;
}

.delete {
  background: #eb5a5a;
  color: white;
  border: none;
  padding: 0.3rem 1rem;
  border-radius: 5px;
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

/* Fade transition styles */
.fade-enter-active, .fade-leave-active {
  transition: opacity 0.3s ease;
}
.fade-enter-from, .fade-leave-to {
  opacity: 0;
}

/* Ensure Poppins font is applied */
.font-poppins {
  font-family: 'Poppins', sans-serif;
}

/* Card styles from PartnairAdmin */
.container {
  padding: 24px;
  font-family: "Segoe UI", sans-serif;
}

.title {
  font-size: 24px;
  font-weight: bold;
  color: #2c3e50;
  margin-bottom: 20px;
}

.actions {
  display: flex;
  justify-content: space-between;
  margin-bottom: 20px;
  flex-wrap: wrap;
  gap: 10px;
}

.dropdown {
  position: relative;
}

.dropdown-menu {
  position: absolute;
  top: 100%;
  right: 0;
  margin-top: 5px;
  background: white;
  border: 1px solid #ccc;
  border-radius: 8px;
  box-shadow: 0 6px 16px rgba(0, 0, 0, 0.1);
  z-index: 100;
  list-style: none;
  padding: 0;
  min-width: 160px;
  overflow: hidden;
}

.dropdown-menu li {
  padding: 10px 15px;
  cursor: pointer;
  transition: background 0.2s;
  font-size: 14px;
}

.dropdown-menu li:hover {
  background-color: #f0f4ff;
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