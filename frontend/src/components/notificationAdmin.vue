<template>
    <div class="container">
      <h2 class="title">Notifications</h2>
  
      <div class="actions">
        <button class="btn add" @click="showModal = true">
          <span class="plus">+</span> Ajouter une Notification
        </button>
        <div class="dropdown" @click="toggleDropdown">
          <button class="btn edit">Édition ▼</button>
          <ul v-if="dropdownOpen" class="dropdown-menu">
            <li v-for="edition in editions" :key="edition.id" @click="selectEdition(edition)">
              {{ edition.name }}
            </li>
          </ul>
        </div>
      </div>
  
      <div class="grid">
        <div class="card" v-for="notif in notifications" :key="notif.id">
          <h3 class="name">{{ notif.titre }}</h3>
          <p class="desc">{{ notif.message }}</p>
          <div class="tools">
            <button class="icon-btn" @click="deleteNotif(notif.id)"><i class="fas fa-trash"></i></button>
            <button class="icon-btn" @click="activateNotif(notif)"><i class="fas fa-check"></i></button>
            <button class="icon-btn" @click="deactivateNotif(notif)"><i class="fas fa-times"></i></button>
          </div>
        </div>
      </div>
  
      <div v-if="showModal" class="modal-overlay">
        <div class="modal-content">
          <h3 class="text-xl font-bold text-blue-700 mb-4 text-center">Ajouter une Notification</h3>
          <form @submit.prevent="submitNotification">
            <div>
              <label class="block mb-1 text-xs text-gray-500 font-medium">Titre</label>
              <input v-model="newNotif.titre" required class="w-[95%] p-2 border border-gray-300 rounded-lg" />
            </div>
            <div>
              <label class="block mb-1 text-xs text-gray-500 font-medium">Message</label>
              <textarea v-model="newNotif.message" required class="w-[95%] p-2 border border-gray-300 rounded-lg"></textarea>
            </div>
            <div class="modal-actions flex justify-end gap-2 mt-6">
              <button type="button" class="cancel-btn" @click="showModal = false">Annuler</button>
              <button type="submit" class="add-btn">Soumettre</button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </template>
  
  <script>
  export default {
    name: "NotificationComponent",
    data() {
      return {
        dropdownOpen: false,
        showModal: false,
        editions: [
          { id: 1, name: "Édition 1" },
          { id: 2, name: "Édition 2" },
        ],
        selectedEdition: null,
        notifications: [],
        newNotif: {
          titre: "",
          message: "",
        },
      };
    },
    methods: {
      toggleDropdown() {
        this.dropdownOpen = !this.dropdownOpen;
      },
      selectEdition(edition) {
        this.selectedEdition = edition;
        this.dropdownOpen = false;
      },
      submitNotification() {
        this.notifications.push({ ...this.newNotif, id: Date.now() });
        this.newNotif = { titre: "", message: "" };
        this.showModal = false;
      },
      deleteNotif(id) {
        this.notifications = this.notifications.filter(n => n.id !== id);
      },
      activateNotif(notif) {
        alert(`Notification activée: ${notif.titre}`);
      },
      deactivateNotif(notif) {
        alert(`Notification désactivée: ${notif.titre}`);
      },
    },
  };
  </script>
  
  <style scoped>
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
    margin-bottom: 10px;
  }
  
  .desc {
    font-size: 14px;
    color: #4b5563;
    margin-bottom: 8px;
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
  
  .modal-content {
    background: white;
    border-radius: 12px;
    padding: 30px;
    width: 90%;
    max-width: 500px;
    box-shadow: 0 6px 20px rgba(0, 0, 0, 0.2);
    max-height: 80vh;
    overflow-y: auto;
  }
  
  .modal-actions {
    display: flex;
    justify-content: flex-end;
    gap: 10px;
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
  </style>
  