```vue
<template>
  <div class="container mx-auto p-6">
    <h1 class="text-2xl font-bold text-gray-800 mb-6">Partners 2024</h1>

    <div class="actions flex items-center gap-4 flex-wrap mb-8">
      <button class="btn add flex items-center" @click="showAddModal = true">
        <span class="mr-2">+</span> Add Partners
      </button>

      <div class="relative">
        <button class="btn edit" @click="toggleDropdown">Edition ▼</button>
        <ul v-if="dropdownOpen" class="absolute top-full right-0 mt-2 bg-white border border-gray-300 rounded-lg shadow-lg z-10 min-w-[160px]">
          <li v-for="edition in editions" :key="edition.id" class="px-4 py-2 hover:bg-blue-50 cursor-pointer text-sm" @click="onEditOption(edition.name)">
            {{ edition.name }}
          </li>
        </ul>
      </div>
    </div>

    <div class="grid">
      <div v-for="partner in filteredPartners" :key="partner.id" class="card">
        <img :src="partner.logo" alt="Partner logo" class="logo" />
        <h2 class="name">{{ partner.name }}</h2>
        <p class="desc">{{ partner.description }}</p>
        <div class="info"><i class="fas fa-phone icon"></i><span>{{ partner.phone }}</span></div>
        <div class="info"><i class="fas fa-envelope icon"></i><span>{{ partner.email }}</span></div>
        <div class="tools">
          <button class="icon-btn" @click="openUpdateModal(partner)">
            <i class="fas fa-edit"></i>
          </button>
          <button class="icon-btn" @click="openDeleteModal(partner)">
            <i class="fas fa-trash"></i>
          </button>
        </div>
      </div>
    </div>

    <!-- Add Modal -->
    <transition name="fade">
      <div v-if="showAddModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 transition-opacity duration-300">
        <div class="bg-white p-6 rounded-lg w-full max-w-md shadow-md font-poppins max-h-[90vh] overflow-y-auto">
          <h3 class="text-xl font-bold text-gray-800 mb-4 text-center sticky top-0 bg-white z-10">Add Partner</h3>
          <form @submit.prevent="addPartner" class="space-y-4">
            <div>
              <label for="name" class="block text-sm font-medium text-gray-700">Name</label>
              <input
                name="name"
                type="text"
                v-model="newPartner.name"
                placeholder="Enter name"
                class="w-full px-3 py-2 border border-blue-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200"
                required
              />
            </div>
            <div>
              <label for="image_url" class="block text-sm font-medium text-gray-700">Logo</label>
              <input
                name="image_url"
                type="file"
                @change="handleImageUpload($event, 'new')"
                accept="image/*"
                class="w-full px-3 py-2 border border-blue-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200"
              />
            </div>
            <div>
              <label for="edition_id" class="block text-sm font-medium text-gray-700">Edition</label>
              <select
                name="edition_id"
                v-model.number="newPartner.edition_id"
                class="w-full px-3 py-2 border border-blue-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200"
                required
              >
                <option disabled value="">Select Edition</option>
                <option v-for="edition in editions" :key="edition.id" :value="edition.id">{{ edition.name }}</option>
              </select>
            </div>
            <div>
              <label for="description" class="block text-sm font-medium text-gray-700">Description</label>
              <input
                name="description"
                type="text"
                v-model="newPartner.description"
                placeholder="Enter description"
                class="w-full px-3 py-2 border border-blue-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200"
                required
              />
            </div>
            <div>
              <label for="phone" class="block text-sm font-medium text-gray-700">Phone Number</label>
              <input
                name="phone"
                type="text"
                v-model="newPartner.phone"
                placeholder="Enter phone number"
                class="w-full px-3 py-2 border border-blue-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200"
                required
              />
            </div>
            <div>
              <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
              <input
                name="email"
                type="email"
                v-model="newPartner.email"
                placeholder="Enter email"
                class="w-full px-3 py-2 border border-blue-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200"
                required
              />
            </div>
            <div class="flex justify-end space-x-3 mt-6">
              <button
                type="button"
                class="btn cancel"
                @click="showAddModal = false"
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
          <h3 class="text-xl font-bold text-gray-800 mb-4 text-center sticky top-0 bg-white z-10">Update Partner</h3>
          <form @submit.prevent="updatePartner" class="space-y-4">
            <div>
              <label for="update_name" class="block text-sm font-medium text-gray-700">Name</label>
              <input
                name="update_name"
                type="text"
                v-model="selectedPartner.name"
                placeholder="Enter name"
                class="w-full px-3 py-2 border border-blue-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200"
                required
              />
            </div>
            <div>
              <label for="update_image_url" class="block text-sm font-medium text-gray-700">Logo</label>
              <input
                name="update_image_url"
                type="file"
                @change="handleImageUpload($event, 'update')"
                accept="image/*"
                class="w-full px-3 py-2 border border-blue-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200"
              />
            </div>
            <div>
              <label for="update_edition_id" class="block text-sm font-medium text-gray-700">Edition</label>
              <select
                name="update_edition_id"
                v-model.number="selectedPartner.edition_id"
                class="w-full px-3 py-2 border border-blue-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200"
                required
              >
                <option disabled value="">Select Edition</option>
                <option v-for="edition in editions" :key="edition.id" :value="edition.id">{{ edition.name }}</option>
              </select>
            </div>
            <div>
              <label for="update_description" class="block text-sm font-medium text-gray-700">Description</label>
              <input
                name="update_description"
                type="text"
                v-model="selectedPartner.description"
                placeholder="Enter description"
                class="w-full px-3 py-2 border border-blue-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200"
                required
              />
            </div>
            <div>
              <label for="update_phone" class="block text-sm font-medium text-gray-700">Phone Number</label>
              <input
                name="update_phone"
                type="text"
                v-model="selectedPartner.phone"
                placeholder="Enter phone number"
                class="w-full px-3 py-2 border border-blue-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200"
                required
              />
            </div>
            <div>
              <label for="update_email" class="block text-sm font-medium text-gray-700">Email</label>
              <input
                name="update_email"
                type="email"
                v-model="selectedPartner.email"
                placeholder="Enter email"
                class="w-full px-3 py-2 border border-blue-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200"
                required
              />
            </div>
            <div class="flex justify-end space-x-3 mt-6">
              <button
                type="button"
                class="btn cancel"
                @click="showUpdateModal = false"
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
          <h3 class="text-xl font-bold text-gray-800 mb-4 text-center sticky top-0 bg-white z-10">Delete Partner</h3>
          <p class="text-gray-600 mb-6 text-center">Are you sure you want to delete <strong>{{ selectedPartner.name }}</strong>?</p>
          <div class="flex justify-end space-x-3">
            <button
              type="button"
              class="btn cancel"
              @click="showDeleteModal = false"
            >
              Cancel
            </button>
            <button
              type="button"
              class="btn delete"
              @click="deletePartner"
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
  name: "PartnairAdmin",
  data() {
    return {
      dropdownOpen: false,
      showAddModal: false,
      showUpdateModal: false,
      showDeleteModal: false,
      selectedEdition: null,
      editions: [
        { id: 2024, name: 'Edition 2024' },
        { id: 2023, name: 'Edition 2023' },
        { id: 2022, name: 'Edition 2022' }
      ],
      newPartner: {
        name: '',
        image_url: '',
        edition_id: '',
        description: '',
        phone: '',
        email: ''
      },
      selectedPartner: {
        id: null,
        name: '',
        image_url: '',
        edition_id: '',
        description: '',
        phone: '',
        email: ''
      },
      partners: [
        {
          id: 1,
          logo: require('@/assets/partenaire1.png'),
          name: "ISET",
          description: "Technology Development",
          phone: "+12 345 6789 0",
          email: "iset@mail.com",
          image_url: '',
          edition_id: 2024
        },
        {
          id: 2,
          logo: require('@/assets/partenaire2.png'),
          name: "Pole",
          description: "Innovation Hub",
          phone: "+98 765 4321 0",
          email: "pole@mail.com",
          image_url: '',
          edition_id: 2023
        }
      ]
    };
  },
  computed: {
    filteredPartners() {
      if (!this.selectedEdition) return this.partners;
      return this.partners.filter(p => p.edition_id === this.selectedEdition);
    },
    nextId() {
      return this.partners.length ? Math.max(...this.partners.map(p => p.id)) + 1 : 1;
    }
  },
  methods: {
    toggleDropdown() {
      this.dropdownOpen = !this.dropdownOpen;
    },
    onEditOption(optionName) {
      const edition = this.editions.find(e => e.name === optionName);
      if (edition) this.selectedEdition = edition.id;
      this.dropdownOpen = false;
    },
    handleImageUpload(event, type) {
      const file = event.target.files[0];
      if (!file) return;

      const reader = new FileReader();
      reader.onload = () => {
        // eslint-disable-next-line no-cond-assign
        if (type === 'new') this.newPartner.image_url = reader.result;
        // eslint-disable-next-line no-cond-assign
        if (type === 'update') this.selectedPartner.image_url = reader.result;
      };
      reader.readAsDataURL(file);
    },
    addPartner() {
      const newEntry = {
        ...this.newPartner,
        id: this.nextId,
        logo: this.newPartner.image_url
      };
      this.partners.push(newEntry);
      this.resetNewPartner();
      this.showAddModal = false;
    },
    resetNewPartner() {
      this.newPartner = {
        name: '',
        image_url: '',
        edition_id: '',
        description: '',
        phone: '',
        email: ''
      };
    },
    openUpdateModal(partner) {
      this.selectedPartner = { ...partner };
      this.showUpdateModal = true;
    },
    updatePartner() {
      const index = this.partners.findIndex(p => p.id === this.selectedPartner.id);
      if (index !== -1) {
        this.partners.splice(index, 1, {
          ...this.selectedPartner,
          logo: this.selectedPartner.image_url
        });
      }
      this.showUpdateModal = false;
    },
    openDeleteModal(partner) {
      this.selectedPartner = { ...partner };
      this.showDeleteModal = true;
    },
    deletePartner() {
      this.partners = this.partners.filter(p => p.id !== this.selectedPartner.id);
      this.showDeleteModal = false;
    }
  }
};
</script>

<style scoped>
@import url("https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css");

/* Button styles from original Partners component */
.btn {
  padding: 10px 20px;
  border-radius: 9999px;
  font-weight: 500;
  cursor: pointer;
  font-size: 14px;
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
  padding: 8px 16px;
  border-radius: 8px;
}

.delete {
  background: #c0392b;
  color: white;
  border: none;
  padding: 8px 16px;
  border-radius: 8px;
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

/* Original styles for non-modal elements */
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