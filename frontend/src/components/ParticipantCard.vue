<template>
  <div class="container">
    <h1>Participants 2024</h1>

    <div class="actions">
      <button class="btn add">
        <span class="plus">+</span> Add Participants
      </button>

      <div class="dropdown" @click="toggleDropdown">
        <button class="btn edit">Edition ▼</button>
        <ul v-if="dropdownOpen" class="dropdown-menu">
          <li @click="onEditOption('Edition 2024')">Edition 2024</li>
          <li @click="onEditOption('Edition 2023')">Edition 2023</li>
          <li @click="onEditOption('Edition 2022')">Edition 2022</li>
        </ul>
      </div>
    </div>

    <div class="cards-wrapper">
      <div class="card" v-for="(participant, index) in participants" :key="index">
        <div class="card-header">
          <div class="title-date">
            <h3 class="title">{{ participant.name }}</h3>
            <span class="date">{{ participant.date }}</span>
          </div>
          <div class="info">
            <p><strong>Country</strong>: {{ participant.country }}</p>
            <p><strong>Profession</strong>: {{ participant.profession }}</p>
            <p><strong>Institution</strong>: {{ participant.institution }}</p>
            <p><strong>Email</strong>: {{ participant.email }}</p>
            <p><strong>Phone</strong>: {{ participant.phone }}</p>
          </div>
          <button class="toggle-btn" @click="toggleDetails(index)">
            {{ participant.showDetails ? 'Less details' : 'More details' }}
            <i :class="['arrow', participant.showDetails ? 'up' : 'down']"></i>
          </button>
        </div>

        <transition name="fade">
          <div v-if="participant.showDetails" class="details">
            <p>Participation: {{ participant.details.participation }}</p>
            <p>Accommodation: {{ participant.details.accommodation }}</p>
            <p>Children: {{ participant.details.children }}</p>
            <p>Adults: {{ participant.details.adults }}</p>
            <p>Single Supplement: {{ participant.details.singleSupplement }}</p>
            <p>Extra Nights: {{ participant.details.extraNights }}</p>
            <p>Payment: {{ participant.details.payment }}</p>
            <p>Total: {{ participant.details.total }}</p>
            <button class="download-btn">
              <i class="fas fa-download"></i> Payment Proof
            </button>
          </div>
        </transition>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  name: "ParticipantCard",
  data() {
    return {
      dropdownOpen: false,
      participants: [
        {
          name: "Maria Garcia",
          date: "12-10-2025",
          country: "Spain",
          profession: "Researcher",
          institution: "University of Barcelona",
          email: "maria.garcia@example.com",
          phone: "+34 123 456 789",
          showDetails: false,
          details: {
            participation: "with paper",
            accommodation: "no",
            children: 0,
            adults: 1,
            singleSupplement: "no",
            extraNights: 0,
            payment: "credit card",
            total: "300 EUR",
          },
        },
        {
          name: "John Smith",
          date: "11-09-2025",
          country: "USA",
          profession: "Engineer",
          institution: "MIT",
          email: "john.smith@example.com",
          phone: "+1 555 123 4567",
          showDetails: false,
          details: {
            participation: "without paper",
            accommodation: "yes",
            children: 2,
            adults: 2,
            singleSupplement: "yes",
            extraNights: 2,
            payment: "purchase order",
            total: "450 USD",
          },
        },
        {
          name: "Fatima Ben Ali",
          date: "10-09-2025",
          country: "Tunisia",
          profession: "Teacher",
          institution: "ENSI",
          email: "fatima.benali@example.tn",
          phone: "+216 98 765 432",
          showDetails: false,
          details: {
            participation: "with paper",
            accommodation: "yes",
            children: 1,
            adults: 1,
            singleSupplement: "no",
            extraNights: 1,
            payment: "bank transfer",
            total: "250 TND",
          },
        },
        {
          name: "Liam Nguyen",
          date: "13-10-2025",
          country: "Vietnam",
          profession: "Professor",
          institution: "Hanoi University",
          email: "liam.nguyen@example.vn",
          phone: "+84 123 456 789",
          showDetails: false,
          details: {
            participation: "without paper",
            accommodation: "no",
            children: 0,
            adults: 1,
            singleSupplement: "no",
            extraNights: 0,
            payment: "credit card",
            total: "280 USD",
          },
        },
      ],
    };
  },
  methods: {
    toggleDropdown() {
      this.dropdownOpen = !this.dropdownOpen;
    },
    onEditOption(option) {
      this.dropdownOpen = false;
      alert(`Option sélectionnée : ${option}`);
    },
    toggleDetails(index) {
      this.participants = this.participants.map((p, i) => {
        if (i === index) {
          return { ...p, showDetails: !p.showDetails };
        }
        return { ...p, showDetails: false };
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
  font-size: 1.3rem;
  margin-bottom: 1rem;
  color: black;
  position: relative;
}

h1 {
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
}

.title {
  font-weight: 600;
  color: #3a3a83;
}

.date {
  font-size: 14px;
  color: gray;
}

.info p {
  font-size: 14px;
  margin: 4px 0;
}

.toggle-btn {
  background: none;
  border: none;
  color: #3a3a83;
  font-weight: 500;
  cursor: pointer;
  margin-top: 10px;
  display: flex;
  align-items: center;
  justify-content: space-between;
  width: 100%;
}

.arrow {
  margin-left: 8px;
  border: solid #3a3a83;
  border-width: 0 2px 2px 0;
  display: inline-block;
  padding: 5px;
  transition: 0.3s ease;
}

.down {
  transform: rotate(45deg);
}

.up {
  transform: rotate(-135deg);
}

.details {
  font-size: 14px;
  margin-top: 10px;
  color: #333;
}

.download-btn {
  background: #eef6ff;
  color: #3a3a83;
  border: 1px solid #3a3a83;
  padding: 6px 12px;
  border-radius: 6px;
  font-size: 13px;
  margin-top: 10px;
  cursor: pointer;
}

.download-btn i {
  margin-right: 5px;
}

/* Animation */
.fade-enter-active,
.fade-leave-active {
  transition: all 0.4s ease;
}

.fade-enter-from,
.fade-leave-to {
  opacity: 0;
  transform: translateY(-5px);
}

/* Responsive */
@media (max-width: 960px) {
  .cards-wrapper {
    justify-content: center;
  }
}

@media (max-width: 480px) {
  .cards-wrapper {
    flex-direction: column;
    align-items: center;
  }
  .card {
    width: 90%;
  }
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
