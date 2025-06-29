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
        <div class="details">
          <p>Created on: {{ formatDate(notif.dateCreation) }}</p>
          <p>Activated on: {{ notif.dateActivation ? formatDate(notif.dateActivation) : 'Not activated' }}</p>
        </div>
        <div class="card-footer">
          <div class="buttons">
            <button class="status-btn activate" @click="showActivateConfirm(notif)" :disabled="notif.isActive">
              Activer
            </button>
            <button class="status-btn deactivate" @click="showDeactivateConfirm(notif)" :disabled="!notif.isActive">
              Désactiver
            </button>
          </div>
          <div class="status-badge" :class="{ 'active': notif.isActive, 'inactive': !notif.isActive }">
            {{ notif.isActive ? 'Activé' : 'Désactivé' }}
          </div>
        </div>
        <button class="icon-btn delete-btn" @click="showDeleteConfirm(notif.id)"><i class="fas fa-trash"></i></button>
      </div>
    </div>

    <!-- Add Notification Modal -->
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
          <div>
            <label class="block mb-1 text-xs text-gray-500 font-medium">Date de Création</label>
            <input v-model="newNotif.dateCreation" type="datetime-local" required class="w-[95%] p-2 border border-gray-300 rounded-lg" />
          </div>
          <div>
            <label class="block mb-1 text-xs text-gray-500 font-medium">Date d'Activation</label>
            <input v-model="newNotif.dateActivation" type="datetime-local" class="w-[95%] p-2 border border-gray-300 rounded-lg" />
          </div>
          <div class="modal-actions flex justify-end gap-2 mt-6">
            <button type="button" class="cancel-btn" @click="showModal = false">Annuler</button>
            <button type="submit" class="add-btn">Soumettre</button>
          </div>
        </form>
      </div>
    </div>

    <!-- Delete Confirmation Modal -->
    <div v-if="showDeleteConfirmModal" class="modal-overlay">
      <div class="modal-content">
        <h3 class="text-xl font-bold text-blue-700 mb-4 text-center">Confirmer la Suppression</h3>
        <p class="text-center mb-4">Êtes-vous sûr de vouloir supprimer cette notification ?</p>
        <div class="modal-actions flex justify-end gap-2 mt-6">
          <button type="button" class="cancel-btn" @click="showDeleteConfirmModal = false">Annuler</button>
          <button type="button" class="add-btn" @click="confirmDelete">Supprimer</button>
        </div>
      </div>
    </div>

    <!-- Activate Confirmation Modal -->
    <div v-if="showActivateConfirmModal" class="modal-overlay">
      <div class="modal-content">
        <h3 class="text-xl font-bold text-blue-700 mb-4 text-center">Confirmer l'Activation</h3>
        <p class="text-center mb-4">Êtes-vous sûr de vouloir activer cette notification ?</p>
        <div class="modal-actions flex justify-end gap-2 mt-6">
          <button type="button" class="cancel-btn" @click="showActivateConfirmModal = false">Annuler</button>
          <button type="button" class="add-btn" @click="confirmActivate">Activer</button>
        </div>
      </div>
    </div>

    <!-- Deactivate Confirmation Modal -->
    <div v-if="showDeactivateConfirmModal" class="modal-overlay">
      <div class="modal-content">
        <h3 class="text-xl font-bold text-blue-700 mb-4 text-center">Confirmer la Désactivation</h3>
        <p class="text-center mb-4">Êtes-vous sûr de vouloir désactiver cette notification ?</p>
        <div class="modal-actions flex justify-end gap-2 mt-6">
          <button type="button" class="cancel-btn" @click="showDeactivateConfirmModal = false">Annuler</button>
          <button type="button" class="add-btn" @click="confirmDeactivate">Désactiver</button>
        </div>
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
      showDeleteConfirmModal: false,
      showActivateConfirmModal: false,
      showDeactivateConfirmModal: false,
      editions: [
        { id: 1, name: "Édition 1" },
        { id: 2, name: "Édition 2" },
      ],
      selectedEdition: null,
      notifications: [
        {
          id: 1,
          titre: "Nouvelle Mise à Jour",
          message: "Une mise à jour importante est disponible.",
          dateCreation: "2025-06-28T10:00:00",
          dateActivation: "2025-06-29T14:00:00",
          isActive: true,
        },
        {
          id: 2,
          titre: "Événement Spécial",
          message: "Rejoignez notre événement spécial demain.",
          dateCreation: "2025-06-27T15:30:00",
          dateActivation: null,
          isActive: false,
        },
        {
          id: 3,
          titre: "Alerte Technique",
          message: "Problème technique résolu avec succès.",
          dateCreation: "2025-06-26T09:15:00",
          dateActivation: "2025-06-26T12:00:00",
          isActive: true,
        },
      ],
      newNotif: {
        titre: "",
        message: "",
        dateCreation: "",
        dateActivation: "",
        isActive: false,
      },
      selectedNotifId: null,
      selectedNotif: null,
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
      this.notifications.push({
        ...this.newNotif,
        id: Date.now(),
        isActive: false,
      });
      this.newNotif = { titre: "", message: "", dateCreation: "", dateActivation: "", isActive: false };
      this.showModal = false;
    },
    showDeleteConfirm(id) {
      this.selectedNotifId = id;
      this.showDeleteConfirmModal = true;
    },
    confirmDelete() {
      this.deleteNotif(this.selectedNotifId);
      this.showDeleteConfirmModal = false;
    },
    deleteNotif(id) {
      this.notifications = this.notifications.filter(n => n.id !== id);
    },
    showActivateConfirm(notif) {
      this.selectedNotif = notif;
      this.showActivateConfirmModal = true;
    },
    confirmActivate() {
      this.activateNotif(this.selectedNotif);
      this.showActivateConfirmModal = false;
    },
    activateNotif(notif) {
      notif.isActive = true;
      notif.dateActivation = new Date().toISOString().slice(0, 16); // 05:11 PM CET, June 29, 2025
    },
    showDeactivateConfirm(notif) {
      this.selectedNotif = notif;
      this.showDeactivateConfirmModal = true;
    },
    confirmDeactivate() {
      this.deactivateNotif(this.selectedNotif);
      this.showDeactivateConfirmModal = false;
    },
    deactivateNotif(notif) {
      notif.isActive = false;
      notif.dateActivation = null;
    },
    formatDate(dateStr) {
      return new Date(dateStr).toLocaleString('fr-FR', {
        year: 'numeric',
        month: '2-digit',
        day: '2-digit',
        hour: '2-digit',
        minute: '2-digit',
      });
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

.details {
  font-size: 12px;
  color: #6b7280;
  margin-bottom: 10px;
}

.card-footer {
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin-top: auto;
  padding-top: 10px;
  border-top: 1px solid #e5e7eb;
}

.buttons {
  display: flex;
  gap: 8px;
}

.status-btn {
  padding: 4px 8px;
  border-radius: 4px;
  font-size: 12px;
  cursor: pointer;
  border: none;
  transition: background-color 0.3s ease, transform 0.2s ease;
}

.activate {
  background-color: #10b981;
  color: white;
}

.activate:hover {
  background-color: #059669;
  transform: scale(1.05);
}

.deactivate {
  background-color: #ef4444;
  color: white;
}

.deactivate:hover {
  background-color: #dc2626;
  transform: scale(1.05);
}

.status-badge {
  padding: 2px 4px;
  border-radius: 16px;
  font-size: 10px;
  font-weight: 500;
  border: 1px solid transparent;
}

.status-badge.active {
  background-color: #ffffff;
  color: #10b981;
  border-color: #10b981;
}

.status-badge.inactive {
  background-color: #ffffff;
  color: #ef4444;
  border-color: #ef4444;
}

.icon-btn {
  background: transparent;
  border: none;
  color: #6b7280;
  cursor: pointer;
  font-size: 16px;
  position: absolute;
  top: 8px;
  right: 8px;
}

.delete-btn:hover {
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