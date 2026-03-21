<script setup>
import { LayoutDashboard, Users, Settings, LogOut, Trophy } from 'lucide-vue-next'
import { Button } from '@/components/ui/button'

import {toast} from "vue-sonner";
import {useRouter} from "vue-router";
import {useAuthStore} from "@/stores/auth.js";

const authStore= useAuthStore()
const router= useRouter()
const menuItems = [
  { name: 'Dashboard', icon: LayoutDashboard, active: true },
  { name: 'Players', icon: Users, active: false },
  { name: 'Tournaments', icon: Trophy, active: false },
  { name: 'Settings', icon: Settings, active: false },
]

const logout = async () => {
  console.log('aqui')
  await authStore.logout().then(()=>{
    router.push('/')
    toast.success('Logout efetuado com sucesso')
  })

}


</script>

<template>
  <aside class="w-64 bg-white border-r h-screen flex flex-col shrink-0 sticky top-0">
    <div class="p-6 border-b flex items-center h-16">
      <span class="font-bold text-lg tracking-tight">NOME</span>
    </div>

    <nav class="flex-1 p-4 space-y-1">
      <Button
          v-for="item in menuItems"
          :key="item.name"
          :variant="item.active ? 'secondary' : 'ghost'"
          class="w-full justify-start gap-3 h-11 px-3"
      >
        <component :is="item.icon" class="w-4 h-4" />
        <span class="text-sm font-medium">{{ item.name }}</span>
      </Button>
    </nav>

    <div class="p-4 border-t">
      <Button variant="ghost" class="w-full justify-start gap-3 text-slate-500 hover:text-red-600 hover:bg-red-50" @click="logout">
        <LogOut class="w-4 h-4" />
        <span class="text-sm font-medium text-red-500">Log out</span>
      </Button>
    </div>
  </aside>
</template>