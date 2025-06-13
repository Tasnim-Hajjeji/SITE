import { createRouter, createWebHistory } from 'vue-router';
import HomeView from '@/views/HomeView.vue';

const routes = [
  {
    path: '/',
    name: 'HomeView',
    component: HomeView
  },
  // 7ot routes okhrin houni ken 3andek
];

const router = createRouter({
  history: createWebHistory(),
  routes
});

export default router;
