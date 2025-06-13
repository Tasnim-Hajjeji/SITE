import { createRouter, createWebHistory } from 'vue-router';
import HomeView from '@/views/HomeView.vue';
import PreviousEditions from '@/views/PreviousEditions.vue';
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

  ];

const router = createRouter({
  history: createWebHistory(),
  routes
});

export default router;
