import { createRouter, createWebHistory } from 'vue-router';
import HomeView from '@/views/HomeView.vue';
import PreviousEditions from '@/views/PreviousEditions.vue';
import PrevEdition from '@/views/PrevEdition.vue';
import SpeakerPage from '@/views/SpeakerPage.vue';
import HonorCom from '@/views/HonorCom.vue';
import ScientificCom from '@/views/ScientificCom.vue';
import Schedule from '@/views/ConferenceSchedule.vue';
import Admin from '@/views/AdminEditions.vue';
import AdEdition from '@/views/AdEdition.vue';
import AdPartnair from '@/views/AdPartnair.vue';
import SpeakerAdmin from '@/views/SpeakerAdmin.vue';
import ParticipantCard from '@/views/ParticipantCardvue.vue';
import CommittiesAdmin from '@/views/AdCommittees.vue';
import ProfileSelectionvue from '@/views/ProfileSelectionvue.vue';
import TunisianFormvue from '@/views/TunisianFormvue.vue';
import StrangerFormvue from '@/views/StrangerFormvue.vue';
import Registrationvue from '@/views/Registrationvue.vue';
import RegistrationFinalvue from '@/views/RegistrationFinalvue.vue';
import RegSuccesvue from '@/views/RegSuccesvue.vue';
import PageSponsorvue from '@/views/PageSponsorvue.vue';
import SponsorAdminvue from '@/views/SponsorAdminvue.vue';
import ContactUs from '@/views/ContactUs.vue';
import contQuestionvue from '@/views/contQuestionvue.vue';
import notificationAdminvue from '@/views/notificationAdminvue.vue';


const routes = [
  {
    path: '/',
    name: 'HomeView',
    component: HomeView,
    meta: { layout: 'default' }
  },
  {
    path: '/previous-editions',
    name: 'PreviousEditions',
    component: PreviousEditions,
    meta: { layout: 'default' }
  },
  {
    path: '/previous-editions/edition',
    name: 'PrevEdition',
    component: PrevEdition,
    meta: { layout: 'default' }
  },
  {
    path: '/speaker',
    name: 'SpeakerPage',
    component: SpeakerPage,
    meta: { layout: 'default' }
  },
  {
    path: '/honor',
    name: 'HonorCommittee',
    component: HonorCom,
    meta: { layout: 'default' }
  },
  {
    path: '/scientific',
    name: 'ScientificCom',
    component: ScientificCom,
    props: { committeeType: 'scientific' },
    meta: { layout: 'default' }
  },
  {
    path: '/organizing',
    name: 'OrganizingCom',
    component: ScientificCom,
    props: { committeeType: 'organizing' },
    meta: { layout: 'default' }
  },
  {
    path: '/schedule',
    name: 'Schedule',
    component: Schedule,
    meta: { layout: 'default' }
  },
  {
    path: '/admin',
    name: 'Admin',
    component: Admin,
    meta: { layout: 'admin', requiresAuth: true }
  },
  {
    path: '/admin/edition/:editionId',
    name: 'AdEdition',
    component: AdEdition,
    props: true,
    meta: { layout: 'admin', requiresAuth: true }

  },
  {
    path: '/admin/partnair',
    name: 'AdPartnair',
    component: AdPartnair,
    meta: { layout: 'admin', requiresAuth: true }
  },
  {
    path: '/admin/speaker',
    name: 'SpeakerAdmin',
    component: SpeakerAdmin,
    meta: { layout: 'admin', requiresAuth: true }
  },
  {
    path: '/admin/participant-card',
    name: 'ParticipantCard',
    component: ParticipantCard,
    meta: { layout: 'admin', requiresAuth: true }
  },
  {
    path: '/admin/committies',
    name: 'Committees',
    component: CommittiesAdmin,
    meta: { layout: 'admin', requiresAuth: true }
  },
  {
    path: '/profile-selection',
    name: 'ProfileSelection',
    component: ProfileSelectionvue,
    meta: { layout: 'default' }
  },
  {
    path: '/tunisian-form',
    name: 'TunisianForm',
    component: TunisianFormvue,
    meta: { layout: 'default' }
  },
  {
    path: '/stranger-form',
    name: 'StrangerForm',
    component: StrangerFormvue,
    meta: { layout: 'default' }
  },
  {
    path: '/registration',
    name: 'Registration',
    component: Registrationvue,
    meta: { layout: 'default' }
  },
  {
    path: '/registration-final',
    name: 'RegistrationFinal',
    component: RegistrationFinalvue,
    meta: { layout: 'default' }
  },
  {
    path: '/reg-success',
    name: 'RegSucces',
    component: RegSuccesvue,
    meta: { layout: 'default' }
  },
  {
    path: '/admin/committies',
    name: 'Committees',
    component: CommittiesAdmin,
    meta: { layout: 'admin', requiresAuth: true }
  },
  {
    path: '/sponsor',
    name: 'PageSponsor',
    component: PageSponsorvue,
    meta: { layout: 'default' }
  },
  {
    path: '/admin/sponsor',
    name: 'SponsorAdmin',
    component: SponsorAdminvue,
    meta: { layout: 'admin', requiresAuth: true }
  },
  {
    path: '/contactUs',
    name: 'ContactUs',
    component: ContactUs,
    meta: { layout: 'default' }
  },
  {
    path: '/admin/contact-question',
    name: 'ContactQuestion',
    component: contQuestionvue, 
    meta: { layout: 'admin', requiresAuth: true }
  },
  {
    path: '/admin/notification',
    name: 'NotificationAdmin',
    component: notificationAdminvue,
    meta: { layout: 'admin', requiresAuth: true }
  }


];

const router = createRouter({
  history: createWebHistory(),
  routes,
  scrollBehavior() {
    // Always scroll to top
    return { top: 0 }
  },

});
router.beforeEach((to, from, next) => {
  // Check if the route requires authentication
  if (to.matched.some(record => record.meta.requiresAuth)) {
    // Check if user is authenticated
    const token = localStorage.getItem('admin_token');
    if (!token) {
      // Redirect to home if not authenticated
      next('/');
    } else {
      // Verify token is still valid (optional)
      // You could add an API call here to verify token
      next();
    }
  } else {
    // Public route - allow access
    next();
  }
});
export default router;