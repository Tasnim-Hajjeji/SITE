import { createI18n } from 'vue-i18n';

const messages = {
    en: {
        navbar: {
            home: 'Home',
            program: 'Program',
            speakers: 'Speakers',
            committees: 'Committees',
            scientificCommittee: 'Scientific Committee',
            honorCommittee: 'Honor Committee',
            organizingCommittee: 'Organizing Committee',
            previousEditions: 'Previous Editions',
            contact: 'Contact'
        },
        hero: {
            welcome: 'Welcome to',
            subtitle: 'Step into the future of sustainable innovation!',
            description: 'The third edition of the international conference on Smart Industry, Technology and Environment "SITE2025" aims to bring together academicians, researchers, professionals and experts from various fields to share their latest findings, innovations and best practices. It consists of keynotes, oral sessions and poster presentations.',
            buttons: {
                importantDates: 'Important dates',
                articleSubmission: 'Article submission',
                articleTemplate: 'Article template',
                registration: 'Registration'
            },
            countdown: {
                days: 'Days',
                hours: 'Hours',
                minutes: 'Minutes',
                seconds: 'Seconds',
                timeUp: 'Time is up!'
            }
        },
        blueSection: {
            title: 'About SITE 2025',
            items: {
                date: 'October 24–26, 2025',
                location: 'Hammamet, Tunisia',
                participants: 'Academics, researchers, professionals, experts',
                purpose: 'Share findings, innovations and best practices',
                activities: 'Keynotes, oral sessions, poster presentations',
                goal: 'Promote sustainable development & interdisciplinary'
            }
        },
        topics: {
            title: 'Topics',
            items: [
                {
                    title: 'Economy, management and Entrepreneurship',
                    description: 'Marketing digital, Finance, Management, Block chain, E-commerce …'
                },
                {
                    title: 'Engineering innovation',
                    description: 'Smart factory, Robotics, Cybersecurity, AI, Materials, Energy management, Cloud computing, Big Data, Additive manufacturing, Machine Learning, Embedded systems …'
                },
                {
                    title: 'Processes, Environment and Sustainable Development',
                    description: 'Agro-industry 4.0, Chemical process, Renewable energy, Smart Waste Management, Biotreatment, Sensors in Bio-industry, Pharmaceutic process …'
                },
                {
                    title: 'New Technologies',
                    description: 'AR/VR, IoT, Quantum computing, Edge computing, Web3…'
                },
                {
                    title: 'Social Impact',
                    description: 'Tech for good, Education, HealthTech, CivicTech…'
                }
            ]
        },
        footer: {
            contact: 'Contact us',
        },
        schedule:{
            title: 'Conference',
            sched:'Schedule',
            description:"Explore the full schedule of our conference, including keynotes, workshops, sessions, and networking moments."
        },
        voices:{
            title:'Voices of ',
            conf:'Conference',
            subtitle:'Meet the experts leading our sessions and workshops. Their insights will spark ideas and meaningful conversations.'
        },
        previousEditions: {
            title:'Conference Highlights from',
            highlight:'Previous Years',
            carousel_subtitle_1:'From inspiring talks to vibrant exchanges, each edition brought something unique.',
            carousel_subtitle_2:' Relive the highlights through photos and memories.',
            button:'See More'

        },
        partners: {
            title:'Our Partners',
        },
        sponsors:{
            title: 'Our Sponsors',
        }
    },
    fr: {
        navbar: {
            home: 'Accueil',
            program: 'Programme',
            speakers: 'Intervenants',
            committees: 'Comités',
            scientificCommittee: 'Comité Scientifique',
            honorCommittee: 'Comité d\'Honneur',
            organizingCommittee: 'Comité d\'Organisation',
            previousEditions: 'Éditions Précédentes',
            contact: 'Contact'
        },
        hero: {
            welcome: 'Bienvenue à',
            subtitle: 'Entrez dans le futur de l\'innovation durable !',
            description: 'La troisième édition de la conférence internationale sur l\'industrie intelligente, la technologie et l\'environnement "SITE2025" vise à réunir des universitaires, des chercheurs, des professionnels et des experts de divers domaines pour partager leurs dernières découvertes, innovations et meilleures pratiques. Elle comprend des conférences plénières, des sessions orales et des présentations d\'affiches.',
            buttons: {
                importantDates: 'Dates importantes',
                articleSubmission: 'Soumission d\'article',
                articleTemplate: 'Modèle d\'article',
                registration: 'Inscription'
            },
            countdown: {
                days: 'Jours',
                hours: 'Heures',
                minutes: 'Minutes',
                seconds: 'Secondes',
                timeUp: 'Le temps est écoulé !'
            }
        },
        blueSection: {
            title: 'À propos de SITE 2025',
            items: {
                date: '24–26 octobre 2025',
                location: 'Hammamet, Tunisie',
                participants: 'Universitaire, chercheurs, professionnels, experts',
                purpose: 'Partager les découvertes, innovations et meilleures pratiques',
                activities: 'Conférences plénières, sessions orales, présentations d\'affiches',
                goal: 'Promouvoir le développement durable et interdisciplinaire'
            }
        },
        topics: {
            title: 'Thèmes',
            items: [
                {
                    title: 'Économie, gestion et entreprenariat',
                    description: 'Marketing digital, Finance, Management, Block chain, E-commerce …'
                },
                {
                    title: 'Innovation en ingénierie',
                    description: 'Usine intelligente, Robotique, Cybersécurité, IA, Matériaux, Gestion de l\'énergie, Cloud computing, Big Data, Fabrication additive, Machine Learning, Systèmes embarqués …'
                },
                {
                    title: 'Procédés, Environnement et Développement Durable',
                    description: 'Agro-industrie 4.0, Procédés chimiques, Énergies renouvelables, Gestion intelligente des déchets, Biotraitement, Capteurs en Bio-industrie, Procédés pharmaceutiques …'
                },
                {
                    title: 'Nouvelles Technologies',
                    description: 'AR/VR, IoT, Informatique quantique, Edge computing, Web3…'
                },
                {
                    title: 'Impact Social',
                    description: 'Tech for good, Éducation, HealthTech, CivicTech…'
                }
            ]
        },
        footer: {
            contact: 'Contactez-nous',
        },
        schedule: {
            title: 'Conférence',
            sched: 'Programme',
            description: "Explorez le programme complet de notre conférence, y compris les conférences, ateliers, sessions et moments de réseautage."
        },
        voices: {
            title: 'Voix de ',
            conf: 'Conférence',
            subtitle: 'Rencontrez les experts qui dirigent nos sessions et ateliers. Leurs idées susciteront des réflexions et des conversations significatives.'
        },
        previousEditions: {
            title: 'Moments forts de la conférence d',
            highlight: 'Années Précédentes',
            carousel_subtitle_1: 'Des discours inspirants aux échanges vibrants, chaque édition a apporté quelque chose d\'unique.',
            carousel_subtitle_2: 'Revivez les moments forts à travers des photos et des souvenirs.',
            button: 'Voir plus'
        },
        partners: {
            title: 'Nos Partenaires',
        },
        sponsors: {
            title: 'Nos Sponsors',
        }

    }
};

const getCookie = (name) => {
    const value = `; ${document.cookie}`;
    const parts = value.split(`; ${name}=`);
    if (parts.length === 2) return parts.pop().split(';').shift();
    return null;
};
const savedLanguage = getCookie('userLanguage');
const browserLanguage = navigator.language.split('-')[0];
const defaultLanguage = savedLanguage || (['en', 'fr'].includes(browserLanguage) ? browserLanguage : 'en');

const i18n = createI18n({
    legacy: false,
    locale: defaultLanguage,
    fallbackLocale: 'en',
    messages
});

export default i18n;