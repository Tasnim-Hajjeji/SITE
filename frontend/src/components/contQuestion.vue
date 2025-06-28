<template>
    <div class="container">
      <!-- Titre H1 ajouté -->
      <h1>Questions du contact</h1>
  
      <!-- Barre d'actions avec 2 boutons alignés -->
      <div class="actions" style="display: flex; justify-content: space-between; align-items: center;">
        <!-- Bouton Ajouter -->
        <button class="btn add-btn" @click="showModal = true">
          <i class="fas fa-plus"></i> Ajouter une question
        </button>
  
        <!-- Bouton Edition -->
        <button class="btn edit" @click="toggleEditionDropdown">
          Edition ▼
        </button>
  
        <!-- Dropdown Edition (optionnel, tu peux enlever si tu veux) -->
        <ul v-if="editionDropdownOpen" class="dropdown-menu">
          <li v-for="item in editionItems" :key="item.id" @click="selectEdition(item)">
            {{ item.name }}
          </li>
        </ul>
      </div>
  
      <!-- Liste des questions -->
      <div class="cards-wrapper">
        <div class="card" v-for="(question, index) in questions" :key="index">
          <div class="card-header">
            <div class="title-date">
              <h3 class="title">{{ question.nom }}</h3>
            </div>
            <div class="info">
              <p><strong>Email :</strong> {{ question.email }}</p>
              <p><strong>Sujet :</strong> {{ question.sujet }}</p>
              <p><strong>Message :</strong> {{ question.message }}</p>
            </div>
          </div>
          <div class="tools">
           
            <button class="icon-btn" @click="supprimerQuestion(index)">
              <i class="fas fa-trash"></i>
            </button>
          </div>
        </div>
      </div>
  
      <!-- Modal Ajout/Modification -->
      <transition name="fade">
        <div v-if="showModal" class="modal-overlay">
          <div class="modal-content">
            <h3 class="text-xl font-bold text-blue-700 mb-4 text-center">Ajouter / Modifier une question</h3>
            <form @submit.prevent="enregistrerQuestion">
              <label>Nom</label>
              <input type="text" v-model="form.nom" required />
  
              <label>Email</label>
              <input type="email" v-model="form.email" required />
  
              <label>Sujet</label>
              <input type="text" v-model="form.sujet" required />
  
              <label>Message</label>
              <textarea v-model="form.message" required></textarea>
  
              <div class="modal-actions">
                <button type="button" class="cancel-btn" @click="reinitialiserFormulaire">Annuler</button>
                <button type="submit" class="add-btn">Enregistrer</button>
              </div>
            </form>
          </div>
        </div>
      </transition>
    </div>
  </template>
  
  <script>
  export default {
    data() {
      return {
        showModal: false,
        editIndex: null,
        form: {
          nom: '',
          email: '',
          sujet: '',
          message: ''
        },
        questions: [],
  
        // Pour dropdown Edition
        editionDropdownOpen: false,
        editionItems: [
          { id: 1, name: "Edition 1" },
          { id: 2, name: "Edition 2" },
          { id: 3, name: "Edition 3" }
        ]
      };
    },
    methods: {
      enregistrerQuestion() {
        if (this.editIndex !== null) {
          this.questions.splice(this.editIndex, 1, { ...this.form });
        } else {
          this.questions.push({ ...this.form });
        }
        this.reinitialiserFormulaire();
      },
     
      supprimerQuestion(index) {
        this.questions.splice(index, 1);
      },
      reinitialiserFormulaire() {
        this.showModal = false;
        this.editIndex = null;
        this.form = {
          nom: '',
          email: '',
          sujet: '',
          message: ''
        };
      },
  
      // Dropdown Edition
      toggleEditionDropdown() {
        this.editionDropdownOpen = !this.editionDropdownOpen;
      },
      selectEdition(item) {
        alert(`Édition sélectionnée : ${item.name}`);
        this.editionDropdownOpen = false;
      }
    }
  };
  </script>
  
  <style scoped>
  @import url("https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css");
  
  .container {
    padding: 24px;
    font-family: "Segoe UI", sans-serif;
  }
  
  h1 {
    font-size: 24px;
    font-weight: bold;
    color: #2c3e50;
    margin-bottom: 20px;
  }
  
  .actions {
    display: flex;
    justify-content: flex-end;
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
  
  .edit {
    border: 1px solid #265985;
    color: #265985;
    background: white;
    padding: 0.5rem 1rem;
    border-radius: 8px;
    font-weight: 600;
    font-size: 0.9rem;
    cursor: pointer;
    transition: background 0.3s ease, transform 0.3s ease, box-shadow 0.3s ease;
  }
  
  .edit:hover {
    background: #e6f0fc;
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(38, 89, 133, 0.3);
  }
  
  .cards-wrapper {
    display: flex;
    flex-wrap: wrap;
    gap: 20px;
    justify-content: flex-start;
  }
  
  .card {
    background: white;
    border-radius: 12px;
    box-shadow: 0 6px 20px rgba(0, 0, 0, 0.08);
    padding: 20px;
    width: 300px;
    margin-bottom: 20px;
    transition: box-shadow 0.3s ease, transform 0.3s ease;
    display: flex;
    flex-direction: column;
    border: #1e476b 2px solid;
    justify-content: space-between;
    cursor: pointer;
    position: relative;
  }
  
  .card:hover {
    box-shadow: 0 12px 40px rgba(0, 0, 0, 0.15);
    transform: translateY(-8px) scale(1.03);
    border-color: #3a3a83;
  }
  
  .card-header .title-date {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-top: 32px;
  }
  
  .title {
    font-weight: 600;
    color: #3a3a83;
  }
  
  .info p {
    font-size: 14px;
    margin: 4px 0;
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
  }
  
  .modal-actions {
    display: flex;
    justify-content: flex-end;
    gap: 10px;
    margin-top: 10px;
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
  
  /* Dropdown menu styles */
  .dropdown-menu {
    position: absolute;
    top: 60px; /* un peu en dessous du bouton Edition */
    right: 0;
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
  </style>
  