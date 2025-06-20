<template>
  <div class="container">
    <h1 class="title">Partners 2024</h1>

    <div class="actions">
      <button class="btn add" @click="showAddModal = true">
        <span class="plus">+</span> Add Partners
      </button>

      <div class="dropdown" @click="toggleDropdown">
        <button class="btn edit">Edition ▼</button>
        <ul v-if="dropdownOpen" class="dropdown-menu">
          <li v-for="edition in editions" :key="edition.id" @click="onEditOption(edition.name)">
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
          <button class="icon-btn" @click="openUpdateModal(partner)"><i class="fas fa-edit"></i></button>
          <button class="icon-btn" @click="openDeleteModal(partner)"><i class="fas fa-trash"></i></button>
        </div>
      </div>
    </div>

    <!-- Modal Ajout -->
    <div v-if="showAddModal" class="modal-overlay">
      <div class="modal">
        <h2>Add Partner</h2>
        <input name="name" type="text" v-model="newPartner.name" placeholder="Name" />
        <input name="image_url" type="file" @change="handleImageUpload($event, 'new')" />
        <select name="edition_id" v-model.number="newPartner.edition_id">
          <option disabled value="">Select Edition</option>
          <option v-for="edition in editions" :key="edition.id" :value="edition.id">{{ edition.name }}</option>
        </select>
        <input name="description" type="text" v-model="newPartner.description" placeholder="Description" />
        <input name="phone" type="text" v-model="newPartner.phone" placeholder="Phone Number" />
        <input name="email" type="email" v-model="newPartner.email" placeholder="Email" />
        <div class="modal-actions">
          <button @click="addPartner">Add</button>
          <button class="cancel" @click="showAddModal = false">Cancel</button>
        </div>
      </div>
    </div>

    <!-- Modal Update -->
    <div v-if="showUpdateModal" class="modal-overlay">
      <div class="modal">
        <h2>Update Partner</h2>
        <input name="name" type="text" v-model="selectedPartner.name" placeholder="Name" />
        <input name="image_url" type="file" @change="handleImageUpload($event, 'update')" />
        <select name="edition_id" v-model.number="selectedPartner.edition_id">
          <option disabled value="">Select Edition</option>
          <option v-for="edition in editions" :key="edition.id" :value="edition.id">{{ edition.name }}</option>
        </select>
        <input name="description" type="text" v-model="selectedPartner.description" placeholder="Description" />
        <input name="phone" type="text" v-model="selectedPartner.phone" placeholder="Phone Number" />
        <input name="email" type="email" v-model="selectedPartner.email" placeholder="Email" />
        <div class="modal-actions">
          <button @click="updatePartner">Update</button>
          <button class="cancel" @click="showUpdateModal = false">Cancel</button>
        </div>
      </div>
    </div>

    <!-- Modal Delete -->
    <div v-if="showDeleteModal" class="modal-overlay">
      <div class="modal">
        <h2>Delete Partner</h2>
        <p>Are you sure you want to delete <strong>{{ selectedPartner.name }}</strong>?</p>
        <div class="modal-actions">
          <button class="delete" @click="deletePartner">Yes, Delete</button>
          <button class="cancel" @click="showDeleteModal = false">Cancel</button>
        </div>
      </div>
    </div>
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
        if (type === 'new') this.newPartner.image_url = reader.result;
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

.modal-overlay {
  position: fixed;
  top: 0; left: 0;
  width: 100vw; height: 100vh;
  background: rgba(0, 0, 0, 0.6);
  display: flex;
  justify-content: center;
  align-items: center;
  z-index: 1000;
}

.modal {
  background: white;
  padding: 24px;
  border-radius: 12px;
  width: 90%;
  max-width: 400px;
  box-shadow: 0 8px 20px rgba(0, 0, 0, 0.2);
}

.modal input {
  width: 100%;
  padding: 10px;
  margin-bottom: 12px;
  border: 1px solid #ccc;
  border-radius: 8px;
}

.modal-actions {
  display: flex;
  justify-content: flex-end;
  gap: 10px;
}

.modal-actions button {
  padding: 8px 16px;
  border: none;
  border-radius: 8px;
  cursor: pointer;
}

.modal-actions .cancel {
  background: #999;
  color: white;
}

.modal-actions .delete {
  background: #c0392b;
  color: white;
}

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
