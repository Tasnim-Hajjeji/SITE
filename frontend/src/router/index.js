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
    component: HomeView,
    meta:{layout :'default'}
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
    meta: { layout: 'default' }
  },
  {
    path: '/schedule',
    name: 'Schedule',
    component: Schedule,
    meta: { layout: 'default' }
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
