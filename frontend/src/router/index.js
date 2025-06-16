import { createRouter, createWebHistory } from 'vue-router';
import HomeView from '@/views/HomeView.vue';
import PreviousEditions from '@/views/PreviousEditions.vue';
import PrevEdition from '@/views/PrevEdition.vue';
import SpeakerPage from '@/views/SpeakerPage.vue';
import HonorCom from '@/views/HonorCom.vue';
import ScientificCom from '@/views/ScientificCom.vue';
import Schedule from '@/views/ConferenceSchedule.vue';

const routes = [
  {
    path: '/',
    name: 'HomeView',
    component: HomeView
  },
  { 
    path: '/previous-editions',
    name: 'PreviousEditions',
    component: PreviousEditions
  },
  {
    path: '/previous-editions/edition',
    name: 'PrevEdition',
    component: PrevEdition
  },
  {
    path: '/speaker',
    name: 'SpeakerPage',
    component: SpeakerPage
  },
  {
    path: '/honor',
    name: 'HonorCommittee',
    component: HonorCom
  },
  {
    path: '/scientific',
    name: 'ScientificCom',
    component: ScientificCom
  },
  {
    path: '/schedule',
    name: 'Schedule',
    component: Schedule
  }

];

const router = createRouter({
  history: createWebHistory(),
  routes,
  scrollBehavior() {
    // Always scroll to top
    return { top: 0 }
  }
});

export default router;
