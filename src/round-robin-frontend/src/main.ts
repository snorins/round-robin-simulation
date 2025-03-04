import { createApp } from 'vue';
import App from './App.vue';
import { createRouter, createWebHistory } from 'vue-router';
import { routes } from './routes.ts';

const router = createRouter({
    linkActiveClass: 'text-blue-700',
    linkExactActiveClass: 'text-blue-700',
    history: createWebHistory(),
    routes,
});

createApp(App)
    .use(router)
    .mount('#app');
