<script setup>
import {  Users, LogOut, LayoutDashboardIcon,ListPlusIcon } from 'lucide-vue-next'
import { Button } from '@/components/ui/button'
import {toast} from "vue-sonner";
import {useRoute, useRouter} from "vue-router";
import {useAuthStore} from "@/stores/auth.js";
import {NavigationMenu, NavigationMenuLink} from "@/components/ui/navigation-menu/index.js";

const authStore= useAuthStore()
const router= useRouter()
const route= useRoute()

const logout = async () => {
  console.log('aqui')
  await authStore.logout().then(()=>{
    router.push('/')
    toast.success('Logout efetuado com sucesso')
  })
}

const isActive = (path) => route.path === path


</script>

<template>
  <aside class="w-64 bg-white border-r h-screen flex flex-col shrink-0 sticky top-0">
    <div class="p-6 border-b flex items-center h-16">
      <span class="font-bold text-lg tracking-tight">Projeto Mestrado-CN</span>
    </div>

    <NavigationMenu orientation="vertical" class="flex-1 items-start justify-start max-w-none p-4">
      <NavigationMenuList class="flex-col space-y-2 w-full space-x-0">

        <NavigationMenuItem class="w-full">
          <NavigationMenuLink as-child :active="isActive('/dashboard')">
            <RouterLink
                to="/dashboard"
                class="flex items-center gap-3 px-3 py-3 rounded-md transition-colors w-full group"
                :class="isActive('/dashboard') ? 'bg-slate-900 text-white' : 'text-slate-500 hover:bg-slate-100'"

            >
              <LayoutDashboardIcon class="w-4 h-4" />
              <span class="text-sm font-medium">Tarefas</span>
            </RouterLink>
          </NavigationMenuLink>
        </NavigationMenuItem>

        <NavigationMenuItem class="w-full">
          <NavigationMenuLink as-child :active="isActive('/users')" v-if="authStore.isAdmin">
            <RouterLink
                to="/users"
                class="flex items-center gap-3 px-3 py-3 rounded-md transition-colors w-full group"
                :class="isActive('/users') ? 'bg-slate-900 text-white' : 'text-slate-500 hover:bg-slate-100'"
            >
              <Users class="w-4 h-4" />
              <span class="text-sm font-medium">Utilizadores</span>
            </RouterLink>
          </NavigationMenuLink>
        </NavigationMenuItem>

        <NavigationMenuItem class="w-full">
          <NavigationMenuLink as-child :active="isActive('/tasks')" v-if="authStore.isAdmin">
            <RouterLink
                to="/tasks"
                class="flex items-center gap-3 px-3 py-3 rounded-md transition-colors w-full group"
                :class="isActive('/tasks') ? 'bg-slate-900 text-white' : 'text-slate-500 hover:bg-slate-100'"
            >
              <ListPlusIcon class="w-4 h-4" />
              <span class="text-sm font-medium">Tarefas</span>
            </RouterLink>
          </NavigationMenuLink>
        </NavigationMenuItem>

      </NavigationMenuList>
    </NavigationMenu>

    <div class="p-4 border-t">
      <Button variant="ghost" class="w-full justify-start gap-3 text-slate-500 hover:text-red-600 hover:bg-red-50" @click="logout">
        <LogOut class="w-4 h-4" />
        <span class="text-sm font-medium text-red-500">Log out</span>
      </Button>
    </div>
  </aside>
</template>