<script setup>
import { useAuthStore } from '@/stores/auth'
import {useRoute, useRouter} from 'vue-router'
import {computed, ref} from 'vue'
import AppSidebar from '@/components/ui/appssidebar/Appsidebar.vue'
import {Toaster} from "vue-sonner";
import {Bell, Search} from "lucide-vue-next";
import {Button} from "@/components/ui/button/index.ts";
import {Input} from "@/components/ui/input/index.ts";

const route = useRoute()

const isAuthPage = computed(() => {

  return route.path === '/'
})
</script>

<template>
  <Toaster richColors position="bottom-right" />
  <div v-if="isAuthPage">
    <RouterView />
  </div>

  <div class="flex min-h-screen w-full bg-slate-50/50" v-else>

    <AppSidebar />

    <main class="flex-1 flex flex-col min-w-0">

      <header class="h-16 border-b bg-white flex items-center justify-between px-8 sticky top-0 z-10">
        <div class="flex items-center gap-4 w-full max-w-md">
          <div class="relative w-full">
            <Search class="absolute left-3 top-2.5 h-4 w-4 text-muted-foreground" />
            <Input placeholder="Search everything..." class="pl-10 bg-slate-50 border-none focus-visible:ring-1" />
          </div>
        </div>

        <div class="flex items-center gap-4">
          <Button variant="outline" size="icon" class="relative">
            <Bell class="h-4 w-4" />
            <span class="absolute top-2 right-2 w-2 h-2 bg-red-500 rounded-full border-2 border-white"></span>
          </Button>
          <div class="h-8 w-8 rounded-full bg-primary flex items-center justify-center text-white font-bold text-xs">
            TS
          </div>
        </div>
      </header>

      <div class="flex-1 overflow-y-auto p-8">
        <RouterView :key="$route.fullPath" />
      </div>

    </main>
  </div>
</template>

<style>
body {
  margin: 0;
  overflow-x: hidden;
}
</style>
