import { createRouter, createWebHistory } from 'vue-router'
import {useAuthStore} from "@/stores/auth.js";
import {toast} from "vue-sonner";
import LoginPage from "@/page/login/LoginPage.vue";
import DashboardPage from "@/page/dashboard/DashboardPage.vue";
import allUsers from "@/page/users/allUsers.vue";
import CreateUser from "@/page/users/createUser.vue";
import Edituser from "@/page/users/edituser.vue";
import allTasks from "@/page/tasks/allTasks.vue";
import editTask from "@/page/tasks/editTask.vue";
import createTask from "@/page/tasks/createTask.vue";
import DashboardAdminPage from "@/page/dashboard/DashboardAdminPage.vue";

const router = createRouter({
  history: createWebHistory(import.meta.env.BASE_URL),
  routes: [
    {
      path: '/',
      name: 'login',
      component: LoginPage,
    },
    {
      path:'/dashboardAdmin',
      name:'dashboardAdmin',
      component:DashboardAdminPage,
      meta:{ requiresAuth: true,  isAdmin:true}
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
      meta:{ requiresAuth: true, isAdmin:true }
    },
    {
      path:'/user/create',
      name:'user/create',
      component:CreateUser,
      meta:{ requiresAuth: true, isAdmin:true }
    },
    {
      path:'/user/edit/:id',
      name:'user/edit',
      component:Edituser,
      props:true,
      meta:{requiresAuth: true, isAdmin:true}
    },
    {
      path:'/tasks',
      name:'tasks',
      component:allTasks,
      meta:{ requiresAuth: true, isAdmin:true }
    },
    {
      path:'/task/create',
      name:'task/create',
      component:createTask  ,
      meta:{ requiresAuth: true, isAdmin:true }
    },
    {
      path:'/task/edit/:id',
      name:'task/edit',
      component:editTask,
      props:true,
      meta:{requiresAuth: true, isAdmin:true}
    }
  ],
})

router.beforeEach(async (to, from, next) => {
  const authStore = useAuthStore()

  if (to.meta.requiresAuth) {
    const auth = await authStore.isAuthenticated()

    if (auth === null || auth === false) {
      toast.info('A sessão expirou, volte a iniciar sessão')
      return next({ name: 'login' })
    }

    if (to.name === 'dashboard' && authStore.isAdmin) {
      return next({ name: 'dashboardAdmin' })
    }

    if (to.name === 'dashboardAdmin' && !authStore.isAdmin) {
      return next({ name: 'dashboard' })
    }
  }

  return next()
})
export default router
