import { createI18n } from 'vue-i18n';
import cookieUtils from '@/utils/cookieUtils.js';

const messages = {
    en: {
        navbar: {
            home: 'Home',
            program: 'Program',
            speakers: 'Speakers',
            sponsors: 'sponsorship proposal',
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
                callForPaper: 'Call for Paper',
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
        importantDates: {
            title: 'Important Dates',
        },
        footer: {
            contact: 'Contact us',
            backToTop: 'Back to top',
            description: 'SITE 2025: International Conference on Software and Information Technology Excellence.',
            subtitle: 'Join us in shaping the future of technology.',
            copyright: 'Copyright © 2025 SITE. All rights reserved.'
        },
        schedule: {
            title: 'Conference',
            sched: 'Schedule',
            description: 'Explore the full schedule of our conference, including keynotes, workshops, sessions, and networking moments.'
        },
        voices: {
            title: 'Voices of ',
            conf: 'Conference',
            subtitle: 'Meet the experts leading our sessions and workshops. Their insights will spark ideas and meaningful conversations.'
        },
        previousEditions: {
            title: 'Conference Highlights from',
            highlight: 'Previous Years',
            carousel_subtitle_1: 'From inspiring talks to vibrant exchanges, each edition brought something unique.',
            carousel_subtitle_2: ' Relive the highlights through photos and memories.',
            button: 'See More'
        },
        prevTimeLine: {
            title: 'The Program'
        },
        partners: {
            title: 'Our Partners',
        },
        sponsors: {
            title: 'Our Sponsors',
        },
        committees: {
            title_scientific: 'Scientific Committee',
            title_organizing: 'Organizing Committee',
            discover_honor: 'Discover Our',
            title_honor: 'Honor Committee',
            description_honor1: 'The Honorary Committee brings together influential figures who share our vision and support our mission.',
            description_honor2: 'Their presence reflects the quality and credibility of this conference.'
        },
        contact: {
            title: 'Contact Us',
            description: 'We\'d love to hear from you! Please fill out the form below to get in touch with the SITE 2025 team.',
            name: 'Name',
            namePlaceholder: 'Enter your full name',
            email: 'Email',
            emailPlaceholder: 'Enter your email address',
            subject: 'Subject',
            subjectPlaceholder: 'Enter the subject of your message',
            message: 'Message',
            messagePlaceholder: 'Enter your message here',
            submit: 'Send Message',
            successMessage: 'Thank you for your message! We will get back to you soon.'
        }
    },
    fr: {
        navbar: {
            home: 'Accueil',
            program: 'Programme',
            speakers: 'Intervenants',
            sponsors: 'proposition du sponsoring',
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
                callForPaper: 'Appel à Papiers',
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
        importantDates: {
            title: 'Dates Importantes',
           
        },
        footer: {
            contact: 'Contactez-nous',
            backToTop: 'Retour en haut',
            description: 'SITE 2025 : Conférence internationale sur l\'excellence en logiciels et technologies de l\'information.',
            subtitle: 'Rejoignez-nous pour façonner l\'avenir de la technologie.',
            copyright: 'Droits d\'auteur © 2025 SITE. Tous droits réservés.'
        },
        schedule: {
            title: 'Conférence',
            sched: 'Programme',
            description: 'Explorez le programme complet de notre conférence, y compris les conférences, ateliers, sessions et moments de réseautage.'
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
        prevTimeLine: {
            title: 'Le Programme'
        },
        partners: {
            title: 'Nos Partenaires',
        },
        sponsors: {
            title: 'Nos Sponsors',
        },
        committees: {
            title_scientific: 'Comité Scientifique',
            title_organizing: 'Comité d\'Organisation',
            discover_honor: 'Découvrez Notre',
            title_honor: 'Comité d\'Honneur',
            description_honor1: 'Le Comité d\'honneur réunit des personnalités influentes qui partagent notre vision et soutiennent notre mission.',
            description_honor2: 'Leur présence reflète la qualité et la crédibilité de cette conférence.'
        },
        contact: {
            title: 'Nous Contacter',
            description: 'Nous serions ravis d\'avoir de vos nouvelles ! Veuillez remplir le formulaire ci-dessous pour contacter l\'équipe SITE 2025.',
            name: 'Nom',
            namePlaceholder: 'Entrez votre nom complet',
            email: 'Email',
            emailPlaceholder: 'Entrez votre adresse email',
            subject: 'Sujet',
            subjectPlaceholder: 'Entrez le sujet de votre message',
            message: 'Message',
            messagePlaceholder: 'Entrez votre message ici',
            submit: 'Envoyer le Message',
            successMessage: 'Merci pour votre message ! Nous vous répondrons bientôt.'
        }
    }
};

const savedLanguage = cookieUtils.getCookie('userLanguage');
const browserLanguage = navigator.language.split('-')[0];
const defaultLanguage = savedLanguage || (['en', 'fr'].includes(browserLanguage) ? browserLanguage : 'en');

const i18n = createI18n({
    legacy: false,
    locale: defaultLanguage,
    fallbackLocale: 'en',
    messages
});

export default i18n;