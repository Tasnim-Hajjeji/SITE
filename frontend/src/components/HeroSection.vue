<template>
    <section class="hero">
        <div class="content">
            <div class="text-content">
                <h1>{{ $t('hero.welcome') }} <span class="blue">{{ editionData.name }}</span></h1>
                <h2>{{ $t('hero.subtitle') }}</h2>
                <p>{{ currentDescription }}</p>
                <template v-if="countdown">
                    <p class="countdown_event">{{ targetDateTitle }}</p>
                    <div class="countdown">
                        <div><span class="time">{{ countdown.days }}</span><small>{{ $t('hero.countdown.days')
                        }}</small></div>
                        <div style="margin-left: -0.3rem;"><span class="time">{{ countdown.hours }}</span><small>{{
                            $t('hero.countdown.hours') }}</small></div>
                        <div style="margin-left: -0.4rem;"><span class="time">{{ countdown.minutes }}</span><small>{{
                            $t('hero.countdown.minutes') }}</small></div>
                        <div style="margin-left: -0.7rem;"><span class="time sec">{{ countdown.seconds }}</span><small
                                class="sec">{{ $t('hero.countdown.seconds') }}</small></div>
                    </div>
                </template>
                <template v-else>
                    <div class="countdown">
                        <div>{{ $t('hero.countdown.timeUp') }}</div>
                    </div>
                </template>

                <div class="buttons">
                    <a href="#date" class="btn-blue">{{ $t('hero.buttons.importantDates') }}</a>
                    <button class="btn-white">{{ $t('hero.buttons.articleSubmission') }}</button>
                    <button class="btn-whit" @click="downloadCallForPaper">{{ $t('hero.buttons.callForPaper') }}</button>
                    <router-link to="/profile-selection" class="btn-green">{{ $t('hero.buttons.registration')
                    }}</router-link>
                </div>
            </div>
            <div class="image-container">
                <img src="../assets/coeur.png" alt="Heart Image" class="hero-image" />
            </div>
        </div>
    </section>
</template>

<script>
import ImportantDatesService from '@/services/ImportantDatesService';
export default {
    props: {
        editionData: {
            type: Object,
            required: true
        }
    },
    data() {
        return {
            targeDateTitleFR: null,
            targeDateTitleEN: null,
            targetDate: new Date(),
            timeRemaining: 0,
            intervalId: null,
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
        },
        currentDescription() {
            if (!this.editionData) return '';
            return this.$i18n.locale === 'fr'
                ? this.editionData.description_fr
                : this.editionData.description_en;
        },
        targetDateTitle() {
            if (!this.targeDateTitleEN || !this.targeDateTitleFR) return null;
            return this.$i18n.locale === 'fr'
                ? this.targeDateTitleFR
                : this.targeDateTitleEN;
        },
    },
    mounted() {
        ImportantDatesService.getLatestCountdownDate(this.editionData.id)
            .then(response => {
                this.targeDateTitleFR = response.data.title_fr;
                this.targeDateTitleEN = response.data.title_en;
                this.targetDate = new Date(response.data.date);
                this.updateCountdown();
                this.intervalId = setInterval(this.updateCountdown, 1000);
            })
            .catch(error => {
                console.error('Error fetching countdown date:', error);
                this.targeDateTitleFR = "Date de conférence";
                this.targeDateTitleEN = "Conference Date";
                this.targetDate = new Date(this.editionData.start_date);
                this.updateCountdown();
                this.intervalId = setInterval(this.updateCountdown, 1000);
            });
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
        },
        async downloadCallForPaper() {
            try {
                const pdfUrl = `http://localhost:8000/storage/${this.editionData.call_for_paper}`; 
                // For external URL, uncomment below and replace with your URL
                // const pdfUrl = 'https://www.w3.org/WAI/ER/tests/xhtml/testfiles/resources/pdf/dummy.pdf';
                const response = await fetch(pdfUrl, {
                    method: 'GET',
                    headers: {
                        'Accept': 'application/pdf',
                    },
                });
                if (!response.ok) throw new Error(`Erreur HTTP: ${response.status}`);
                const blob = await response.blob();
                if (blob.type !== 'application/pdf') throw new Error('Le fichier reçu n\'est pas un PDF');
                const url = window.URL.createObjectURL(blob);
                const link = document.createElement('a');
                link.href = url;
                link.download = 'CallForPaper.pdf';
                document.body.appendChild(link);
                link.click();
                document.body.removeChild(link);
                window.URL.revokeObjectURL(url);
            } catch (error) {
                console.error('Erreur lors du téléchargement du modèle d\'article :', error.message);
                alert('Impossible de télécharger le modèle d\'article : ' + error.message + '. Veuillez vérifier votre connexion ou réessayer plus tard.');
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

.btn-blue {
    background-color: #005a90;
    margin: 0.5rem;
    padding: 0.6rem 1.2rem;
    border: none;
    border-radius: 8px;
    cursor: pointer;
    font-weight: 600;
    transition: all 0.3s ease;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    text-decoration: none;
}

a {
    color: white;
    text-decoration: none;
    margin: 0.5rem;
    padding: 0.4rem 1rem;
    font-size: 0.9rem;
    border: none;
    border-radius: 8px;
    cursor: pointer;
    font-weight: 600;
    transition: all 0.3s ease;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    text-decoration: none;
}

.buttons {
    margin-top: 1rem;
    display: flex;
    flex-wrap: wrap;
    justify-content: center;
    gap: 0.5rem;
}

.buttons button {
    margin: 0.5rem;
    padding: 0.6rem 1.2rem;
    border: none;
    border-radius: 8px;
    cursor: pointer;
    font-weight: 600;
    transition: all 0.3s ease;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    text-decoration: none;
}

.buttons .btn-white {
    background-color: #163654;
    color: white;
}

.buttons .btn-whit {
    background-color: #476784;
    color: white;
}

.buttons .btn-green {
    background-color: #3c7547;
    color: white;
    margin: 0.5rem;
    padding: 0.6rem 1.2rem;
    border: none;
    border-radius: 8px;
    cursor: pointer;
    font-weight: 600;
    transition: all 0.3s ease;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    text-decoration: none;
}

.buttons :hover {
    background-color: rgb(237, 230, 230);
    color: black;
    transform: translateY(-2px);
}

.content {
    display: flex;
    justify-content: space-between;
    align-items: center;
    width: 100%;
    max-width: 1200px;
    position: relative;
    z-index: 2;
    transform: translateX(5%);
}

.text-content {
    text-align: center;
    max-width: 70%;
}

.hero h1 {
    font-size: 3rem;
    font-weight: 700;
}

.hero .blue {
    color: #005a90;
}

.hero h2 {
    font-size: 2rem;
    font-weight: 700;
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
}

.countdown div span:not(.sec)::after {
    content: ' :';
}

.countdown small {
    font-size: 0.75rem;
    font-weight: normal;
}

.countdown small:not(.sec) {
    margin-right: 1rem;
}

.image-container {
    max-width: 30%;
    margin-top: -5rem;
    margin-right: 3rem;
}

.countdown_event {
    font-size: 1.2rem !important;
    margin-bottom: -1.2rem;
    margin-top: 1rem;
    text-align: center;
    font-weight: bold;
}

.hero-image {
    width: 100%;
    height: auto;
    display: block;
    animation: heartbeat 2s infinite;
}

@keyframes heartbeat {
    0% { transform: scale(1); }
    20% { transform: scale(1.1); }
    40% { transform: scale(1); }
    60% { transform: scale(1.1); }
    80% { transform: scale(1); }
    100% { transform: scale(1); }
}

.decorative-dots {
    position: absolute;
    top: 10%;
    left: -15%;
    width: 30%;
    height: 60%;
    background: url('data:image/svg+xml;utf8,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><circle cx="10" cy="10" r="2" fill="%23005a90"/><circle cx="30" cy="40" r="2" fill="%23005a90"/><circle cx="50" cy="70" r="2" fill="%23005a90"/><circle cx="70" cy="20" r="2" fill="%23005a90"/><circle cx="90" cy="50" r="2" fill="%23005a90"/></svg>') repeat;
    z-index: 1;
    opacity: 0.5;
}

/* ✅ SECTION MOBILE */
@media (max-width: 768px) {
    .hero {
        justify-content: center;
        padding: 1rem;
    }

    .content {
        flex-direction: column;
        text-align: center;
        transform: translateX(0);
    }

    .text-content {
        max-width: 100%;
    }

    .image-container {
        display: none;
    }

    .decorative-dots {
        display: none;
    }

    .hero h1 {
        font-size: 1.5rem;
    }

    .hero h2 {
        font-size: 1rem;
    }

    .hero p {
        font-size: 0.75rem;
        line-height: 1.4;
        padding: 0 0.5rem;
    }

    .countdown {
        font-size: 1.3rem;
        gap: 0.5rem;
        margin-top: 1rem;
    }

    .countdown small {
        font-size: 0.6rem;
    }

    .countdown_event {
        font-size: 0.9rem !important;
        margin-top: 0.5rem;
        margin-bottom: -0.5rem;
    }

    .buttons {
        flex-direction: column;
        gap: 0.3rem;
    }

    .buttons button,
    .buttons .btn-blue,
    .buttons .btn-green {
        padding: 0.4rem 0.8rem;
        font-size: 0.75rem;
    }

    a {
        font-size: 0.75rem;
        padding: 0.4rem 0.8rem;
    }
}
</style>
