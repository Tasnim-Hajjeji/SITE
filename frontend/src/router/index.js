import { createRouter, createWebHistory } from 'vue-router';
import HomeView from '@/views/HomeView.vue';
import PreviousEditions from '@/views/PreviousEditions.vue';
import PrevEdition from '@/views/PrevEdition.vue';
import SpeakerPage from '@/views/SpeakerPage.vue';
import HonorCommittee from '@/views/HonorCom.vue';
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
    component: HonorCommittee
  } 

];

const router = createRouter({
  history: createWebHistory(),
  routes
});

export default router;
