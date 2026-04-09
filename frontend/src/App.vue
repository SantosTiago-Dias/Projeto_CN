<script setup>
import { useAuthStore } from '@/stores/auth'
import {useRoute, useRouter} from 'vue-router'
import {computed, onMounted, ref, watch} from 'vue'
import AppSidebar from '@/components/ui/appssidebar/Appsidebar.vue'
import {Toaster} from "vue-sonner";
import {Button} from "@/components/ui/button/index.ts";
import NotificationBell from "@/components/ui/notification/Notification.vue";
import {useNotificaitonStore} from "@/stores/notification.js";
import {getEcho} from "@/websocket/echo.js";
import {useTasksStore} from "@/stores/tasks.js";

const route = useRoute()
const authStore = useAuthStore()
const notiStore = useNotificaitonStore()
const taskStore = useTasksStore()
const notifications = ref([])


const isAuthPage = computed(() => {

  return route.path === '/'
})

const removeNotification = (id) => {
  notifications.value = notifications.value.filter(n => n.id !== id)
}

onMounted(() => {
  watch(
      () => authStore.currentUser?.id,
      async (userId) => {
        let auth = await authStore.isAuthenticated()
        if (userId && auth) {
          notifications.value = await notiStore.notifications();

          const echo = getEcho();
          echo.private(`user.${userId}`)
              .listen('NotificationSent', async (e) => {
                notifications.value.push(e);

                if (!authStore.isAdmin)
                {
                  await taskStore.showUserTasks();
                }
                else
                {
                  await taskStore.getAllTasks();
                }

              });
        }
      },
      { immediate: true }
  );
});
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
        </div>

        <div class="flex items-center gap-4">
          <Button variant="outline" size="icon" class="relative">
            <NotificationBell :notifications="notifications" @remove-notification="removeNotification" />
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
