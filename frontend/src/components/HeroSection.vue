<template>
  <section class="hero">
    <div class="content">
      <div class="text-content">
        <h1>Welcome to <span class="blue">SITE 2025</span></h1>
        <h2>Step into the future of sustainable innovation !</h2>
        <p>
          The third edition of the international conference on Smart Industry, Technology and Environment<br />
          “SITE’2025” aims to bring together academicians, researchers, professionals and experts from various fields to share their latest findings, innovations and best practices. It consists of keynotes, oral sessions and poster presentations.
        </p>
        <div class="countdown">
          <template v-if="countdown">
            <div><span class="time">{{ countdown.days }}</span><small>Days</small></div>
            <div style="margin-left: -0.3rem;"><span class="time">{{ countdown.hours }}</span><small>Hours</small></div>
            <div style="margin-left: -0.4rem;"><span class="time">{{ countdown.minutes }}</span><small>Minutes</small></div>
            <div style="margin-left: -0.7rem;"><span class="time sec">{{ countdown.seconds }}</span><small class="sec">Seconds</small></div>
          </template>
          <template v-else>
            <div>Time is up!</div>
          </template>
        </div>
        <br>
        <div class="buttons">
          <button class="btn-blue">Important dates</button>
          <button class="btn-blue">Article submission</button>
          <button class="btn-green">Registration</button>
        </div>
      </div>
      <div class="image-container">
        <img src="../assets/coeur.png" alt="Heart Image" class="hero-image" />
      </div>
    </div>
  </section>
</template>

<script>
export default {
  data() {
    return {
      targetDate: new Date('2025-06-30T00:00:00'),
      timeRemaining: 0,
      intervalId: null
    };
  },
  computed: {
    countdown() {
      if (this.timeRemaining <= 0) {
        return null;
      }
      const days = Math.floor(this.timeRemaining / (1000 * 60 * 60 * 24));
      const hours = Math.floor((this.timeRemaining % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
      const minutes = Math.floor((this.timeRemaining % (1000 * 60 * 60)) / (1000 * 60));
      const seconds = Math.floor((this.timeRemaining % (1000 * 60)) / 1000);
      return {
        days: String(days).padStart(2, '0'),
        hours: String(hours).padStart(2, '0'),
        minutes: String(minutes).padStart(2, '0'),
        seconds: String(seconds).padStart(2, '0')
      };
    }
  },
  mounted() {
    this.updateCountdown();
    this.intervalId = setInterval(this.updateCountdown, 1000);
  },
  beforeUnmount() {
    clearInterval(this.intervalId);
  },
  methods: {
    updateCountdown() {
      const now = new Date();
      this.timeRemaining = this.targetDate - now;
      if (this.timeRemaining < 0) {
        this.timeRemaining = 0;
      }
    }
  }
};
</script>

<style scoped>
.hero {
  font-family: 'Segoe UI', sans-serif;
  background: white;
  padding: 2rem;
  position: relative;
  min-height: 40vh;
  display: flex;
  justify-content: flex-end;
  overflow: hidden;
}

.content {
  display: flex;
  justify-content: space-between;
  align-items: center;
  width: 100%;
  max-width: 1200px;
  position: relative;
  z-index: 2;
  transform: translateX(5%); /* Décalage léger à droite pour centrer visuellement */
}

.text-content {
  text-align: center;
  max-width: 70%;
}

.hero h1 {
  font-size: 3rem; /* Agrandi */
  font-weight: bold;
  font-weight: 700; /* Plus gras */
}

.hero .blue {
  color: #005a90;
}

.hero h2 {
  font-size: 2rem; /* Agrandi */
  font-weight: bold;
  font-weight: 700; /* Plus gras */
  margin-top: 0.5rem;
}

.hero p {
  margin-top: 1rem;
  font-size: 1rem;
  line-height: 1.6;
  color: #444;
}

.countdown {
  display: flex;
  justify-content: center;
  gap: 1rem;
  margin-top: 1.5rem;
  font-size: 2rem;
  font-weight: bolder;
}

.countdown div {
  display: flex;
  flex-direction: column;
  align-items: right;
  span:not(.sec) {
    &::after{
    content: ' :';
  }
}}

.countdown small {
  font-size: 0.75rem;
  font-weight: normal;
  &:not(.sec) {
    margin-right: 1rem;
  }
}

.buttons {
  margin-top: 1.5rem;
}

.buttons button {
  margin: 0.5rem;
  padding: 0.6rem 1.2rem;
  border: none;
  border-radius: 8px;
  cursor: pointer;
  font-weight: 600;
  transition: all 0.3s ease;
  box-shadow: 0 4px 6px rgba(0,0,0,0.1);
}

.buttons .btn-blue {
  background-color: #005a90;
  color: white;
}

.buttons .btn-green {
  background-color: #3c7547;
  color: white;
}

.buttons :hover {
  background-color: rgb(237, 230, 230);
  color: black;
  transform: translateY(-2px);
}

.image-container {
  max-width: 30%;
  margin-top: -5rem; /* Ajusté pour coller à la navbar */
  margin-right: 3rem; /* Ajusté pour coller à la navbar */

}

.hero-image {
  width: 100%;
  height: auto;
  display: block;
  animation: heartbeat 2s infinite; /* Animation de battement */
}

@keyframes heartbeat {
  0% {
    transform: scale(1);
  }
  20% {
    transform: scale(1.1); /* Léger agrandissement */
  }
  40% {
    transform: scale(1);
  }
  60% {
    transform: scale(1.1); /* Deuxième battement */
  }
  80% {
    transform: scale(1);
  }
  100% {
    transform: scale(1);
  }
}

.decorative-dots {
  position: absolute;
  top: 10%;
  left: -15%;
  width: 30%;
  height: 60%;
  background: url('data:image/svg+xml;utf8,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><circle cx="10" cy="10" r="2" fill="%23005a90"/><circle cx="30" cy="40" r="2" fill="%23005a90"/><circle cx="50" cy="70" r="2" fill="%23005a90"/><circle cx="70" cy="20" r="2" fill="%23005a90"/><circle cx="90" cy="50" r="2" fill="%23005a90"/></svg>') repeat;
  z-index: 1;
  opacity: 0.5; /* Rendu plus discret et loin de l'écriture */
}

@media (max-width: 768px) {
  .hero {
    justify-content: center;
    padding: 1rem;
  }

  .content {
    flex-direction: column;
    text-align: center;
    transform: translateX(0); /* Réinitialise le décalage sur mobile */
  }

  .text-content {
    max-width: 100%;
  }

  .image-container {
    max-width: 50%;
    margin-top: 1rem;
  }

  .decorative-dots {
    display: none; /* Masque les points sur mobile pour simplicité */
  }

  .hero h1 {
    font-size: 2rem;
  }

  .hero h2 {
    font-size: 1.1rem;
  }

  .hero p {
    font-size: 0.8rem;
  }
}
</style>