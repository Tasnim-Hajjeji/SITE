<template>
  <section class="honor-section">
    <div class="honor-header">
      <h2>
        {{ $t('committees.discover_honor') }}&nbsp;<span class="highlight">{{ $t('committees.title_honor')
          }}&nbsp;!</span>
      </h2>
      <p>
        {{ $t('committees.description_honor1') }} <br>
        {{ $t('committees.description_honor2') }}
      </p>
    </div>

    <div class="cards-container">
      <div v-for="(member, index) in members" :key="index" class="honor-card">
        <div class="image-placeholder"
          :style="member.image_url ? { backgroundImage: `url('http://localhost:8000/storage/${member.image_url}')`, backgroundSize: 'cover' } : {}"
          :class="{ filled: !member.image_url && index === 0 }"></div>
        <h3 class="card-title">{{ member.full_name }}</h3>
        <hr />
        <p class="card-footer">{{ member.from_etablissement }}</p>
      </div>
    </div>
  </section>
</template>

<script setup>
import { ref } from 'vue';
import { onMounted } from 'vue';
import CommitteeMemberService from '@/services/CommitteeMemberService';
import cookieUtils from '@/utils/cookieUtils';
const members = ref([]);

onMounted(() => {
  var editionId = cookieUtils.getCookie('editionId');
  CommitteeMemberService.getMembersByEditionAndCommittee(editionId, 'honor')
      .then(response => {
        members.value = response.data;
      })
      .catch(error => {
        console.error('Error fetching committee members:', error);
      });
  })
</script>

<style scoped>
.honor-section {
  padding: 4rem 2rem;
  max-width: 1200px;
  margin: auto;
  text-align: center;
  font-family: 'Segoe UI', sans-serif;
  background-color: #f9f9f9;
  /* ✅ fond clair */
}

.honor-header h2 {
  font-size: 2rem;
  font-weight: bold;
  margin-bottom: 0.5rem;
}

.honor-header .highlight {
  color: #1e518f;
}

.honor-header p {
  font-size: 0.95rem;
  color: #333;
  margin-bottom: 2rem;
}

.cards-container {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
  /* Réduit la taille minimale des cartes */
  gap: 3rem 2rem;
  /* Espacement entre les cartes */
  justify-items: center;
}

.honor-card {
  background: white;
  border-radius: 10px;
  box-shadow: 0 6px 16px rgba(0, 0, 0, 0.06);
  overflow: hidden;
  padding: 1rem;
  width: 100%;
  max-width: 250px;
  /* Réduit la largeur maximale */
  display: flex;
  flex-direction: column;
  align-items: flex-start;
  transition: transform 0.3s ease, box-shadow 0.3s ease;
  height: fit-content;
}

.honor-card:hover {
  transform: translateY(-5px);
  box-shadow: 0 8px 20px rgba(0, 0, 0, 0.1);
}

.image-placeholder {
  background-color: #ccc;
  width: 100%;
  height: 50%;
  /* Hauteur égale à la largeur pour un effet carré */
  border-radius: 10px;
  margin-bottom: 1rem;
  background-position: center;
  background-repeat: no-repeat;
  aspect-ratio: 1 / 1;
  /* Assure un ratio carré */
}

.image-placeholder.filled {
  background-color: #1e518f;
}

.card-title {
  font-weight: bold;
  color: #1e518f;
  font-size: 1rem;
  text-align: left;
  margin-bottom: 0.5rem;
}

.card-description {
  font-size: 0.85rem;
  text-align: left;
  margin-bottom: 1rem;
  color: #555;
}

hr {
  width: 100%;
  border: none;
  border-top: 1px solid #ccc;
  margin-bottom: 0.5rem;
}

.card-footer {
  font-size: 0.85rem;
  color: #444;
  text-align: left;
}
</style>