<template>
  <div class="card-wrapper">
    <div class="speaker-card">
      <img class="avatar" :src="SpeakerPFP(speaker.image_url)" alt="Speaker photo" />
      <div class="card-content">
        <h3 class="name">{{ speaker.full_name }}</h3>
        <p class="location">{{ speaker.institut }}</p>
        <hr />
        <p class="intervention">
          <strong>Interventions :</strong><br />
        <ul>
            <li v-for="(program, index) in speaker.programs" :key="index">
            {{ programName(program) }}
          </li>
        </ul>
        </p>
      </div>
      <div class="flag"><CountryFlag :iso="speaker.code_pays" mode="squared" /></div>
      <!--<img class="flag" src="@/assets/image.png" alt="Flag" /> -->
    </div>
  </div>

</template>

<script>
import CountryFlag from '@dzangolab/vue-country-flag-icon'
import '@dzangolab/vue-country-flag-icon/dist/CountryFlag.css'
import cookieUtils from '@/utils/cookieUtils';
export default {
  name: 'SpeakerCard',
  props: {
    speaker: {
      type: Object,
      required: true,
    },
  },
  components: {
    CountryFlag,
  },
  computed: {
    language() {
      // Use $i18n.locale if available, fallback to cookie, then 'en'
      return this.$i18n?.locale || cookieUtils.getCookie('userLanguage') || 'en';
    }
  },
  methods: {
    SpeakerPFP(image_url) {
      return `http://localhost:8000/storage/${image_url}`;
    },
    programName(program) {
      return (this.language === 'fr') ? program.name_fr : program.name_en;
    },
  }
}
</script>

<style scoped>
/* Nouveau wrapper pour centrer */
.card-wrapper {
  display: flex;
  justify-content: center;
  align-items: center;
  padding: 10px;
}

.speaker-card {
  display: flex;
  align-items: center;
  background-color: #fff;
  border-radius: 15px;
  padding: 16px;
  margin: 10px 0;
  margin-right: 20px;
  box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
  position: relative;
  max-width: 800px;
  width: 100%;
  flex-wrap: wrap;
}

.avatar {
  width: 100px;
  height: 100px;
  border-radius: 20px;
  object-fit: cover;
  box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
}

.card-content {
  flex: 1;
  margin-left: 16px;
  min-width: 230px;
  color: #111;
}

.name {
  font-size: 1.3rem;
  font-weight: 600;
  color: #0f172a;
}

.location {
  font-size: 0.95rem;
  color: #6b7280;
  margin-bottom: 6px;
}

hr {
  height: 2px;
  border: none;
  background-color: #e2e8f0;
  margin: 8px 0;
}

.intervention {
  font-size: 0.87rem;
  color: #333;
  line-height: 1.4;
}

.flag {
  position: absolute;
  top: 16px;
  right: 4px;
  width: 28px;
  height: auto;
  scale: 1.2;
}

@media (max-width: 768px) {
  .speaker-card {
    flex-direction: column;
    text-align: center;
    padding: 20px;

    margin-left: 20px;
  }

  .card-content {
    margin-left: 0;
    margin-top: 16px;
  }

  .flag {
    top: 12px;
    right: 12px;
  }
}
</style>