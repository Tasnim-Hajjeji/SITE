import { createApp } from 'vue'
import App from './App.vue'
import router from './router'
import i18n from './i18n';
import '@fortawesome/fontawesome-free/css/all.css';
import './assets/style/tailwind.css';
const app = createApp(App)
app.use(router)
app.use(i18n);
app.mount('#app')
