<template>
  <section class="guest-section">
    <h2 class="title">{{ $t("previousEditions.honor.title") }}</h2>
    <p class="description">
      {{ $t("previousEditions.honor.description") }}
    </p>
    <div class="guest-grid">
      <div v-for="(guest, index) in guests" :key="index" class="guest-card">
        <div class="img_container">
          <img :src="`http://localhost:8000/storage/${guest.image_url}`" alt="Guest photo" class="guest-image" />
        </div>
        <h3 class="guest-name">{{ guest.full_name }}</h3>
        <p class="guest-title">{{ guest.from_etablissement }}</p>
      </div>
    </div>
  </section>
</template>

<script>
import CommitteeMemberService from '@/services/CommitteeMemberService';
export default {
  name: "GuestGrid",
  props: {
    editionId: {
      type: Number,
      required: true
    }
  },
  data() {
    return {
      guests: []
    };
  },
  mounted(){
    CommitteeMemberService.getMembersByEditionAndCommittee(this.editionId, 'honor')
    .then(response => {
      this.guests = response.data
    }).catch(error => {console.log(error)})
  }
};
</script>

<style scoped>
.guest-section {
  text-align: center;
  padding: 2rem 1rem;
  font-family: 'Segoe UI', sans-serif;
}

.title {
  font-size: 1.7rem;
  color: #265985;
  margin-bottom: 0.5rem;
  font-weight: bold;
}

.img_container{
  display: flex;
  justify-content: center;
}

.description {
  max-width: 600px;
  margin: 0 auto 2rem auto;
  font-size: 0.95rem;
  color: #5a5a5a;
}

.guest-grid {
  display: grid;
  grid-template-columns: repeat(5, 1fr); /* 5 colonnes par défaut */
  gap: 1.5rem;
  justify-items: center;
}

@media (max-width: 1200px) {
  .guest-grid {
    grid-template-columns: repeat(4, 1fr);
  }
}

@media (max-width: 992px) {
  .guest-grid {
    grid-template-columns: repeat(3, 1fr);
  }
}

@media (max-width: 768px) {
  .guest-grid {
    grid-template-columns: repeat(2, 1fr);
  }
}

@media (max-width: 480px) {
  .guest-grid {
    grid-template-columns: 1fr;
  }
}

.guest-card {
  background: #f3f4f2;
  border-radius: 20px;
  padding: 1rem;
  box-shadow: 0 4px 12px rgba(0, 0, 0, 0.06);
  width: 180px;
  text-align: center;
  position: relative;
}

.guest-image {
  width: 80px;
  height: 80px;
  border-radius: 50%;
  object-fit: cover;
  margin-bottom: 0.5rem;
}

.guest-name {
  font-size: 0.95rem;
  font-weight: bold;
  margin: 0.3rem 0;
}

.guest-title {
  font-size: 0.85rem;
  color: #444;
  margin-bottom: 0.5rem;
}

.guest-flag {
  width: 20px;
  height: auto;
}
</style>