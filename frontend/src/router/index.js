import { createRouter, createWebHistory } from 'vue-router'
import LoginPage from "@/page/login/LoginPage.vue";
import DashboardPage from "@/page/dashboard/DashboardPage.vue";
import allUsers from "@/page/users/allUsers.vue";
import {useAuthStore} from "@/stores/auth.js";
import {toast} from "vue-sonner";
import CreateUser from "@/page/users/createUser.vue";
import Edituser from "@/page/users/edituser.vue";

const router = createRouter({
  history: createWebHistory(import.meta.env.BASE_URL),
  routes: [
    {
      path: '/',
      name: 'login',
      component: LoginPage,
    },
    {
      path:'/dashboard',
      name:'dashboard',
      component:DashboardPage,
      meta:{ requiresAuth: true }
    },
    {
      path:'/users',
      name:'users',
      component:allUsers,
      meta:{ requiresAuth: true }
    },
    {
      path:'/user/create',
      name:'user/create',
      component:CreateUser,
      meta:{ requiresAuth: true }
    },
    {
      path:'/user/edit/:id',
      name:'user/edit',
      component:Edituser,
      props:true,
      meta:{requiresAuth: true}
    }
  ],
})

router.beforeEach(async (to, from, next) => {
  const authStore = useAuthStore()

  if (to.meta.requiresAuth) {
    const auth = await authStore.isAuthenticated()

    if (auth === false) {
      toast.info('A sessão expirou, volte a iniciar sessão')
      return next({ name: 'login' })
    }

    if (auth === null) {
      // no token at all, silent redirect
      return next({ name: 'login' })
    }
  }

  return next()
})

export default router
