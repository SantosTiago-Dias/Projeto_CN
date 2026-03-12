import { createApp } from 'vue'
import { createPinia } from 'pinia'
import App from './App.vue'
import router from '@/router/router.js'

const SERVER_BASE_URL = import.meta.env.VITE_BASE_URL;
const API_BASE_URL = SERVER_BASE_URL + '/api';

const app = createApp(App)

app.provide('apiBaseURL', API_BASE_URL)

app.use(createPinia())
app.use(router)

app.mount('#app')