<template>
  <div class="container">
    <h1 class="title">Speakers 2024</h1>

    <!-- Actions -->
    <div class="actions">
      <button class="btn add" @click="openAddModal">
        <span class="plus">+</span> Add speaker
      </button>

      <!-- Dropdown -->
      <div class="dropdown" @click="toggleDropdown">
        <button class="btn edit">Edition ▼</button>
        <ul v-if="dropdownOpen" class="dropdown-menu">
          <li @click="selectEdition('SITE 2025')">SITE 2025</li>
          <li @click="selectEdition('SITE 2024')">SITE 2024</li>
          <li @click="selectEdition('SITE 2023')">SITE 2023</li>
          <li @click="selectEdition('SITE 2022')">SITE 2022</li>
        </ul>
      </div>
    </div>

    <!-- Grid Speakers -->
    <div class="grid">
      <div class="card" v-for="speaker in filteredSpeakers" :key="speaker.id">
        <h2 class="name">
          {{ speaker.full_name }}
          <img class="flag" src="https://flagcdn.com/tn.svg" alt="TN" />
        </h2>
        <p class="desc"><strong>Profession :</strong> {{ speaker.profession_fr }}</p>
        <p class="desc"><strong>Intervention :</strong> {{ speaker.description_fr }}</p>
        <p class="info">📌 <span class="count">1</span> Intervention</p>
        <div class="info">
          <img class="avatar" :src="speaker.image_preview" alt="avatar" />
          <span>{{ speaker.edition_id }}</span>
        </div>
        <div class="tools">
          <button class="icon-btn" @click="openEditModal(speaker)"><i class="fas fa-edit"></i></button>
          <button class="icon-btn" @click="openDeleteModal(speaker)"><i class="fas fa-trash"></i></button>
        </div>
      </div>
    </div>

    <!-- Modal Add -->
    <div v-if="showAddModal" class="modal-overlay">
      <div class="modal">
        <h3>Add Speaker</h3>
        <form @submit.prevent="addSpeaker">
          <input v-model="newSpeaker.full_name" placeholder="Full Name" required />
          <input v-model="newSpeaker.profession_fr" placeholder="Profession FR" />
          <input v-model="newSpeaker.profession_en" placeholder="Profession EN" />
          <textarea v-model="newSpeaker.description_fr" placeholder="Description FR"></textarea>
          <textarea v-model="newSpeaker.description_en" placeholder="Description EN"></textarea>
          <input v-model="newSpeaker.edition_id" placeholder="Edition" />
          <input type="file" @change="handleImageUpload($event, newSpeaker)" />
          <button type="submit">Submit</button>
          <button type="button" @click="showAddModal = false">Cancel</button>
        </form>
      </div>
    </div>

    <!-- Modal Edit -->
    <div v-if="showEditModal" class="modal-overlay">
      <div class="modal">
        <h3>Edit Speaker</h3>
        <form @submit.prevent="updateSpeaker">
          <input v-model="editSpeaker.full_name" required />
          <input v-model="editSpeaker.profession_fr" />
          <input v-model="editSpeaker.profession_en" />
          <textarea v-model="editSpeaker.description_fr"></textarea>
          <textarea v-model="editSpeaker.description_en"></textarea>
          <input v-model="editSpeaker.edition_id" />
          <input type="file" @change="handleImageUpload($event, editSpeaker)" />
          <button type="submit">Update</button>
          <button type="button" @click="showEditModal = false">Cancel</button>
        </form>
      </div>
    </div>

    <!-- Modal Delete -->
    <div v-if="showDeleteModal" class="modal-overlay">
      <div class="modal">
        <h3>Are you sure you want to delete "{{ deleteSpeakerData.full_name }}"?</h3>
        <button @click="deleteSpeaker">Yes, Delete</button>
        <button @click="showDeleteModal = false">Cancel</button>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  name: "SpeakersComponent",
  data() {
    return {
      dropdownOpen: false,
      selectedEdition: "SITE 2025",
      showAddModal: false,
      showEditModal: false,
      showDeleteModal: false,
      newSpeaker: {
        id: null,
        full_name: "",
        description_fr: "",
        description_en: "",
        profession_fr: "",
        profession_en: "",
        edition_id: "",
        image: null,
        image_preview: "",
      },
      editSpeaker: null,
      deleteSpeakerData: null,
      speakers: [],
    };
  },
  computed: {
    filteredSpeakers() {
      return this.speakers.filter((s) => s.edition_id === this.selectedEdition);
    },
  },
  methods: {
    toggleDropdown() {
      this.dropdownOpen = !this.dropdownOpen;
    },
    selectEdition(edition) {
      this.selectedEdition = edition;
      this.dropdownOpen = false;
    },
    openAddModal() {
      this.newSpeaker = {
        id: null,
        full_name: "",
        description_fr: "",
        description_en: "",
        profession_fr: "",
        profession_en: "",
        edition_id: this.selectedEdition,
        image: null,
        image_preview: "",
      };
      this.showAddModal = true;
    },
    handleImageUpload(event, targetSpeaker) {
      const file = event.target.files[0];
      if (file) {
        targetSpeaker.image = file;
        targetSpeaker.image_preview = URL.createObjectURL(file);
      }
    },
    addSpeaker() {
      const speaker = { ...this.newSpeaker, id: Date.now() };
      this.speakers.push(speaker);
      this.showAddModal = false;
    },
    openEditModal(speaker) {
      this.editSpeaker = { ...speaker };
      this.showEditModal = true;
    },
    updateSpeaker() {
      const index = this.speakers.findIndex((s) => s.id === this.editSpeaker.id);
      if (index !== -1) {
        this.speakers.splice(index, 1, { ...this.editSpeaker });
      }
      this.showEditModal = false;
    },
    openDeleteModal(speaker) {
      this.deleteSpeakerData = speaker;
      this.showDeleteModal = true;
    },
    deleteSpeaker() {
      this.speakers = this.speakers.filter((s) => s.id !== this.deleteSpeakerData.id);
      this.showDeleteModal = false;
    },
  },
};
</script>

<style scoped>
/* Le style de base est déjà donné, voici juste le style pour les modals */
.modal-overlay {
  position: fixed;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  background: rgba(0, 0, 0, 0.3);
  display: flex;
  align-items: center;
  justify-content: center;
  z-index: 1000;
}

.modal {
  background: white;
  border-radius: 12px;
  padding: 20px;
  width: 90%;
  max-width: 500px;
  box-shadow: 0 6px 20px rgba(0, 0, 0, 0.2);
}

.modal h3 {
  margin-bottom: 15px;
}

.modal form input,
.modal form textarea {
  width: 100%;
  margin-bottom: 10px;
  padding: 8px;
  font-size: 14px;
  border: 1px solid #ccc;
  border-radius: 8px;
}

.modal button {
  margin-right: 10px;
  padding: 8px 14px;
  border: none;
  border-radius: 8px;
  cursor: pointer;
}

.modal button:first-of-type {
  background-color: #265985;
  color: white;
}

.modal button:last-of-type {
  background-color: #ccc;
}

  @import url("https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css");
  
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
    grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
    gap: 20px;
  }
  
  .card {
    background: #fefcfc;
    border-radius: 16px;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
    padding: 16px;
    border: 2px solid #265985;
    position: relative;
    transition: transform 0.3s ease, box-shadow 0.3s ease;
  }
  
  .card:hover {
    transform: translateY(-8px);
    box-shadow: 0 12px 24px rgba(0, 0, 0, 0.1);
  }
  
  .name {
    font-weight: 600;
    color: #1f2937;
    display: flex;
    align-items: center;
    gap: 8px;
    margin-bottom: 10px;
  }
  
  .flag {
    width: 20px;
  }
  
  .desc {
    font-size: 14px;
    color: #4b5563;
    margin-bottom: 8px;
  }
  
  .info {
    display: flex;
    align-items: center;
    gap: 8px;
    font-size: 14px;
    color: #374151;
    margin-bottom: 8px;
  }
  
  .avatar {
    width: 32px;
    height: 32px;
    border-radius: 50%;
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
  