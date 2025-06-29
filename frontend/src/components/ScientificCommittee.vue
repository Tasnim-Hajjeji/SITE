<template>
  <section class="committee-section">
    <h2 class="committee-title">{{ $t('committees.title_scientific') }}</h2>

    <!-- Chair seul en haut -->
    <div class="chair-wrapper">
      <div class="card">
        <div class="card-image" :style="{ backgroundImage: `url('http://localhost:8000/storage/${chair.image_url}')` }">
        </div>
        <div class="card-content">
          <h3 class="card-title">{{ chair.full_name }}</h3>
          <p class="card-role">Chair</p>
          <p class="card-name">{{ chair.from_etablissement }}</p>
        </div>
      </div>
    </div>

    <!-- Autres membres (vice chair + members) -->
    <div class="committee-grid">
      <div v-for="(member, index) in otherMembers" :key="index" class="card">
        <div class="card-image"
          :style="{ backgroundImage: `url('http://localhost:8000/storage/${member.image_url}')` }"></div>
        <div class="card-content">
          <h3 class="card-title">{{ member.full_name }}</h3>
          <p class="card-role">{{ member.role }}</p>
          <p class="card-name">{{ member.from_etablissement }}</p>
        </div>
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

// Extraction du chair et tri des autres membres
const chair = ref({});
const otherMembers = ref([]);

onMounted(() => {
  var editionId = cookieUtils.getCookie('editionId');
  CommitteeMemberService.getMembersByEditionAndCommittee(editionId, 'scientific')
    .then(response => {
      members.value = response.data;
      chair.value = members.value.find(member => member.role === 'chair') || {};
      otherMembers.value = members.value.filter(member => member.role !== 'chair');
      otherMembers.value.sort((a, b) => {
        if (a.role === 'vice chair' && b.role !== 'vice chair') return -1;
        if (b.role === 'vice chair' && a.role !== 'vice chair') return 1;
        return 0; // Keep original order for other members
      });
    })
    .catch(error => {
      console.error('Error fetching committee members:', error);
    });
});
</script>

<style scoped>
.committee-section {
  padding: 3rem 1rem;
  max-width: 1200px;
  margin: auto;
  font-family: 'Segoe UI', sans-serif;
}

.committee-title {
  text-align: center;
  font-size: 1.7rem;
  font-weight: 700;
  color: #1e3a8a;
  margin-bottom: 2rem;
}

.chair-wrapper {
  display: flex;
  justify-content: center;
  margin-bottom: 3rem;
}

.committee-grid {
  display: grid;
  grid-template-columns: repeat(4, minmax(250px, 1fr));
  gap: 1.5rem;
}


.card {
  background: white;
  border-radius: 12px;
  box-shadow: 0px 4px 16px rgba(0, 0, 0, 0.1);
  overflow: hidden;
  width: 260px;
  display: flex;
  flex-direction: column;
  justify-content: space-between;
  transition: transform 0.3s ease;
}



.card:hover {
  transform: translateY(-5px);
}

.card-image {
  height: 160px;
  background-size: cover;
  background-position: center;
  border-top-left-radius: 12px;
  border-top-right-radius: 12px;
}

.card-content {
  padding: 1rem;
  display: flex;
  flex-direction: column;
  gap: 0.5rem;
}

.card-title {
  color: #1e3a8a;
  font-size: 1rem;
  font-weight: 600;
}

.card-role {
  color: #4b5563;
  font-size: 0.875rem;
  line-height: 1.4;
  font-weight: 600;
  text-transform: capitalize;
}

.card-name {
  font-size: 0.875rem;
  color: #6b7280;
  border-top: 1px solid #e5e7eb;
  padding-top: 0.5rem;
}
</style>