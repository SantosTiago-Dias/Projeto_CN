import { createRouter, createWebHistory } from 'vue-router'
import LoginPage from "@/page/login/LoginPage.vue";
import DashboardPage from "@/page/dashboard/DashboardPage.vue";

const router = createRouter({
  history: createWebHistory(import.meta.env.BASE_URL),
  routes: [
    {
      path: '/',
      name: 'home',
      component: LoginPage,
    },
    {
      path:'/dashboard',
      name:'dashboard',
      component:DashboardPage
    }
  ],
})

export default router
