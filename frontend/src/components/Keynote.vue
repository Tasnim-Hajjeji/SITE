<template>
  <section class="section-keynote">
    <div class="title-section">
      <h2 class="main-title">Keynote Session</h2>
      <p class="subtitle">
        Highlights from past SITE keynotes, where leading experts shaped the future of smart industry and sustainability
      </p>
    </div>
    <div class="cards-wrapper">
      <transition-group name="fade-up" tag="div" class="cards-row" v-for="(chunk, rowIndex) in chunkedPersons" :key="rowIndex">
        <div class="card" v-for="(person, index) in chunk" :key="index">
          <img :src="`http://localhost:8000/storage/${person.image_url}`" :alt="person.speaker_name" class="card-img" />
          <h3 class="card-title">{{ person.speaker_name }}</h3>
          <p class="card-role">{{ person.speaker_role }}</p>
          <p class="card-desc" v-html="person_desc(person.description_fr,person.description_en)"></p>
        </div>
      </transition-group>
    </div>
  </section>
</template>

<script>
import KeynoteService from '@/services/KeynoteService';
export default {
  name: 'KeynoteSection',
  props: {
      editionId: {
        type: Number,
        required: true
      }
    },
  data() {
    return {
      persons: []
    };
  },
  computed: {
    chunkedPersons() {
      const result = [];
      for (let i = 0; i < this.persons.length; i += 2) {
        result.push(this.persons.slice(i, i + 2));
      }
      return result;
    }
  },
  mounted() {
    KeynoteService.getKeynotesByEdition(this.editionId)
      .then(response => {
        if (Array.isArray(response)) {
          this.persons = response;
        } else if (response && Array.isArray(response.data)) {
          this.persons = response.data;
        } else {
          try {
            this.persons = JSON.parse(response);
          } catch (e) {
            console.error('Unexpected keynote format:', response);
            this.persons = [];
          }
        }
      })
      .catch(error => {
        console.error('Error fetching keynotes:', error);
        this.persons = [];
      });
  },
  methods: {
    person_desc(desc_fr,desc_en) {
      return this.$i18n.locale === 'fr' ? desc_fr : desc_en;
    }
  }
};
</script>

<style scoped>
.section-keynote {
  background-color: #ffffff;
  padding: 4rem 1rem;
  max-width: 1300px;
  margin: 0 auto;
  font-family: 'Segoe UI', sans-serif;
}

.title-section {
  text-align: center;
  margin-bottom: 1.7rem;
}

.main-title {
  color: #0c4a6e;
  font-size: 1.7rem;
  font-weight: 700;
}

.subtitle {
  margin-top: 0.5rem;
  color: #4b5563;
  font-size: 1rem;
}

.cards-wrapper {
  display: flex;
  flex-direction: column;
  gap: 2rem;
}

.cards-row {
  display: flex;
  flex-wrap: wrap;
  gap: 2rem;
  justify-content: center;
}

.card {
  background-color: #f3f4f2;
  padding: 2rem;
  border-radius: 1rem;
  box-shadow: 0 2px 6px rgba(0, 0, 0, 0.05);
  text-align: center;
  flex: 1 1 300px;
  max-width: 500px;
  transition: transform 0.4s ease, box-shadow 0.4s ease;
}

.card:hover {
  transform: translateY(-8px);
  box-shadow: 0 12px 24px rgba(0, 0, 0, 0.1);
}

.card-img {
  width: 100px;
  height: 100px;
  object-fit: cover;
  border-radius: 9999px;
  margin-bottom: 1rem;
  display: inline-block;
}

.card-title {
  font-size: 1.125rem;
  font-weight: 700;
  color: #0c4a6e;
}

.card-role {
  font-size: 0.875rem;
  color: #6b7280;
  margin-bottom: 0.5rem;
}

.card-desc {
  font-size: 0.875rem;
  color: #374151;
  line-height: 1.5;
}

.link {
  color: #0ea5e9;
  text-decoration: underline;
}

/* Animation */
.fade-up-enter-active, .fade-up-leave-active {
  transition: all 0.6s ease;
}

.fade-up-enter-from, .fade-up-leave-to {
  opacity: 0;
  transform: translateY(20px);
}
</style>