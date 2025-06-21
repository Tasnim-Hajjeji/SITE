<template>
    <div class="container">
      <h1 class="title">Sponsors</h1>
  
      <div class="actions">
        <button class="btn add" @click="toggleAddModal">
          <span class="plus">+</span> Add Sponsor
        </button>
  
        <div class="dropdown" @click="toggleFilter">
          <button class="btn edit">Filter by ▼</button>
          <ul v-if="showFilter" class="dropdown-menu">
            <li @click="setFilter('all')">All</li>
            <li @click="setFilter('confirmed')">Confirmed</li>
            <li @click="setFilter('pending')">Pending</li>
          </ul>
        </div>
      </div>
  
      <div class="grid">
        <div
          v-for="(sponsor, index) in filteredSponsors"
          :key="index"
          class="card"
        >
          <img :src="sponsor.logo" alt="logo" class="logo" />
          <h2 class="name">{{ sponsor.name }}</h2>
          <p class="desc">{{ sponsor.description }}</p>
          <div class="info"><i class="fas fa-phone icon"></i>{{ sponsor.phone }}</div>
          <div class="info"><i class="fas fa-envelope icon"></i>{{ sponsor.email }}</div>
  
          <div class="tools">
            <button class="icon-btn" @click="deleteSponsor(index)">
              <i class="fas fa-trash"></i>
            </button>
          </div>
  
          <div class="buttons">
            <button class="reject" @click="updateStatus(index, false)">Reject</button>
            <button class="confirm" @click="updateStatus(index, true)">Confirm</button>
          </div>
  
          <div class="status-dot" :class="{ confirmed: sponsor.confirmed }"></div>
        </div>
      </div>
  
      <!-- Modal -->
      <div v-if="showAddModal" class="modal-overlay">
        <div class="modal">
          <h3>Add Sponsor</h3>
          <input v-model="newSponsor.name" placeholder="Company name" />
          <input v-model="newSponsor.address" placeholder="Address" />
          <input v-model="newSponsor.phone" placeholder="Phone" />
          <input v-model="newSponsor.email" placeholder="Email" />
          <textarea v-model="newSponsor.description" placeholder="Description"></textarea>
          <input type="file" @change="onFileChange" />
          <div class="modal-actions">
            <button @click="addSponsor">Add</button>
            <button class="cancel" @click="toggleAddModal">Cancel</button>
          </div>
        </div>
      </div>
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
        newSponsor: {
          name: '',
          address: '',
          phone: '',
          email: '',
          description: '',
          logo: '',
          confirmed: false,
        },
      };
    },
    computed: {
      filteredSponsors() {
        if (this.filter === 'all') return this.sponsors;
        if (this.filter === 'confirmed') return this.sponsors.filter(s => s.confirmed);
        if (this.filter === 'pending') return this.sponsors.filter(s => !s.confirmed);
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
      deleteSponsor(index) {
        this.sponsors.splice(index, 1);
      },
      onFileChange(event) {
        const file = event.target.files[0];
        if (file) this.newSponsor.logo = URL.createObjectURL(file);
      },
      addSponsor() {
        this.sponsors.push({ ...this.newSponsor });
        this.resetNewSponsor();
        this.toggleAddModal();
      },
      toggleAddModal() {
        this.showAddModal = !this.showAddModal;
      },
      resetNewSponsor() {
        this.newSponsor = {
          name: '',
          address: '',
          phone: '',
          email: '',
          description: '',
          logo: '',
          confirmed: false,
        };
      },
    },
  };
  </script>
  
  <style scoped>
  @import url("https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css");
  
  .container {
    padding: 2rem;
    font-family: Arial, sans-serif;
  }
  
  .title {
    font-size: 24px;
    font-weight: bold;
    margin-bottom: 1rem;
    color: #333;
  }
  
  .actions {
    margin-top: 3rem;
    display: flex;
    align-items: center;
    gap: 1rem;
    flex-wrap: wrap;
  }
  
  .btn {
    padding: 0.5rem 1rem;
    border-radius: 9999px;
    font-weight: 500;
    font-size: 14px;
    cursor: pointer;
    transition: 0.3s ease;
  }
  
  .btn.add {
    background-color: #265985;
    color: white;
    border: none;
  }
  
  .btn.add:hover {
    background-color: #1e476b;
    transform: scale(1.05);
  }
  
  .btn.edit {
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
    gap: 1rem;
    margin-top: 2rem;
  }
  
  .card {
    border: 1px solid #ddd;
    border-radius: 10px;
    padding: 1rem;
    position: relative;
    background: #fff;
    box-shadow: 0 2px 5px rgba(0,0,0,0.1);
  }
  
  .logo {
    width: 100%;
    max-height: 80px;
    object-fit: contain;
  }
  
  .name {
    font-weight: bold;
    margin-top: 0.5rem;
  }
  
  .desc {
    font-size: 14px;
    color: #555;
    margin: 0.5rem 0;
  }
  
  .info {
    font-size: 13px;
    color: #444;
    margin-bottom: 4px;
    display: flex;
    align-items: center;
    gap: 6px;
  }
  
  .tools {
    position: absolute;
    top: 10px;
    right: 10px;
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
  
  .buttons {
    display: flex;
    justify-content: space-between;
    margin-top: 1rem;
  }
  
  .reject {
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
  
  .status-dot {
    width: 10px;
    height: 10px;
    border-radius: 50%;
    position: absolute;
    bottom: 10px;
    right: 10px;
    background: red;
  }
  
  .status-dot.confirmed {
    background: green;
  }
  
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
    padding: 2rem;
    border-radius: 10px;
    width: 300px;
  }
  
  .modal input,
  .modal textarea {
    width: 100%;
    margin-bottom: 1rem;
    padding: 0.5rem;
    border: 1px solid #ccc;
    border-radius: 5px;
  }
  
  .modal-actions {
    display: flex;
    justify-content: space-between;
  }
  
  .modal-actions button {
    padding: 0.5rem 1rem;
    border: none;
    border-radius: 5px;
    cursor: pointer;
  }
  
  .modal-actions .cancel {
    background: #999;
    color: white;
  }
  
  @media (max-width: 600px) {
    .grid {
      grid-template-columns: 1fr;
    }
  }
  </style>
  