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
          <img :src="person.img" :alt="person.name" class="card-img" />
          <h3 class="card-title">{{ person.name }}</h3>
          <p class="card-role">{{ person.role }}</p>
          <p class="card-desc" v-html="person.desc"></p>
        </div>
      </transition-group>
    </div>
  </section>
</template>

<script>
export default {
  name: 'KeynoteSection',
  data() {
    return {
      persons: [
        {
          name: 'Accusamus etc',
          role: 'Printing And Tv',
          img: require('@/assets/keynote1.png'),
          desc: `Jose emigrated to the Los Angeles area at a young age... where his research is focused on developing imaging and diffraction technologies for chemical and biomedical science. He has published over 50 peer-reviewed papers and holds several patents in advanced imaging techniques.`
        },
        {
          name: 'Dan Gostovic',
          role: 'President',
          img: require('@/assets/keynote2.png'),
          desc: `Dan Gostovic PhD, co-founded Microedlab.com... Dan is most passionate about quality time with family and friends across the world. Additionally, he actively mentors young entrepreneurs and contributes to STEM education initiatives globally.`
        },
        {
          name: 'Hosea Nelson',
          role: 'President Every',
          img: require('@/assets/keynote3.png'),
          desc: `"AT VERO EOS ET ACCUSAMUS ET IUSTO ODIO DIGNISSIM PLEASURE ITSELF... His work extends to innovative teaching methods, influencing educational reforms across multiple continents over the past decade."`
        },
        {
          name: 'Hosea Nelson',
          role: 'President Every',
          img: require('@/assets/keynote4.png'),
          desc: `"AT VERO EOS ET ACCUSAMUS ET IUSTO ODIO DIGNISSIM PLEASURE ITSELF... He is also recognized for his contributions to sustainable technology, leading projects that reduce carbon footprints in industrial applications."`
        }
      ]
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