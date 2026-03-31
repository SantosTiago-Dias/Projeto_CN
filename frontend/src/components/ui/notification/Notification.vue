<script setup>
import {ref} from 'vue';
import {useNotificaitonStore} from "@/stores/notification.js";



const showDropdown = ref(false);
const props = defineProps(['notifications'])
const notificationStore = useNotificaitonStore()
const emit = defineEmits(['remove-notification'])

const markAsRead = async (id) =>{
    let status = await notificationStore.markAsRead(id)
    if (status === 204)
    {
      emit('remove-notification', id)
    }
}
</script>

<template>
  <div class="notification-wrapper">
    <button @click="showDropdown = !showDropdown" class="notification-bell">
      <svg class="w-6 h-6 text-gray-700" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path>
      </svg>
    </button>

    <div v-if="showDropdown" class="notification-dropdown">
      <div class="dropdown-header">
        <h3 class="text-sm font-semibold text-gray-900">Notificações</h3>
      </div>

      <div class="dropdown-body">
        <div v-if="notifications.length === 0" class="no-notifications">
          Nenhuma notificação recente.
        </div>
        <div v-else v-for="notif in notifications" :key="notif.id"
             :class="['notification-item', { 'unread': !notif.read }]"
             @click="markAsRead(notif.id)">
          <div class="notification-content">
            <p class="notification-title">A tarefa:{{ notif.title }}</p>

            <p v-if="notif.status === 'PENDING'" class="px-2.5 py-1 rounded-full text-xs font-bold bg-amber-100 text-amber-700">
              Esta Pendente
            </p>

            <span v-else-if="notif.status === 'IN_PROGRESS'" class="px-2.5 py-1 rounded-full text-xs font-bold bg-blue-100 text-blue-700">
              Entrou num estado em progresso
            </span>

            <span v-else-if="notif.status === 'COMPLETED'" class="px-2.5 py-1 rounded-full text-xs font-bold bg-emerald-100 text-emerald-700">
              Foi Concluída
            </span>

            <span v-else-if="notif.status === 'CANCELLED'" class="px-2.5 py-1 rounded-full text-xs font-bold bg-rose-100 text-rose-700">
              Foi Cancelado
            </span>

          </div>
          <span v-if="!notif.read" class="unread-dot"></span>
        </div>
      </div>

      <div class="dropdown-footer">
        <a href="#" class="view-all">Ver todas as notificações</a>
      </div>
    </div>
  </div>
</template>

<style scoped>
/* Estilos Simples (podes adaptar para Tailwind CSS ou o teu framework) */
.notification-wrapper {
  position: relative;
  display: inline-block;
}

.notification-bell {
  background: none;
  border: none;
  cursor: pointer;
  padding: 8px;
  position: relative;
}

.notification-bell:focus {
  outline: none;
}

.notification-badge {
  position: absolute;
  top: 2px;
  right: 2px;
  background-color: #ef4444; /* Vermelho */
  color: white;
  font-size: 11px;
  font-weight: bold;
  border-radius: 9999px;
  width: 18px;
  height: 18px;
  display: flex;
  align-items: center;
  justify-content: center;
}

.notification-dropdown {
  position: absolute;
  right: 0;
  top: 100%;
  width: 320px;
  background-color: white;
  border: 1px solid #e5e7eb;
  border-radius: 8px;
  box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
  z-index: 1000;
  margin-top: 8px;
  overflow: hidden;
}

.dropdown-header {
  padding: 12px 16px;
  border-bottom: 1px solid #e5e7eb;
  display: flex;
  justify-content: space-between;
  align-items: center;
}

.dropdown-body {
  max-height: 400px;
  overflow-y: auto;
}

.no-notifications {
  padding: 24px 16px;
  text-align: center;
  color: #6b7280;
  font-size: 14px;
}

.notification-item {
  padding: 12px 16px;
  border-bottom: 1px solid #f3f4f6;
  cursor: pointer;
  display: flex;
  align-items: center;
  gap: 12px;
  transition: background-color 0.2s;
}

.notification-item:hover {
  background-color: #f9fafb;
}

.notification-item.unread {
  background-color: #eff6ff; /* Azul clarinho para não lidas */
}

.notification-content {
  flex: 1;
}

.notification-title {
  font-size: 14px;
  font-weight: 500;
  color: #111827;
  margin: 0;
}

.notification-status {
  font-size: 12px;
  color: #4b5563;
  margin: 2px 0 0 0;
}

.notification-time {
  font-size: 11px;
  color: #9ca3af;
  margin: 4px 0 0 0;
}

.unread-dot {
  width: 8px;
  height: 8px;
  background-color: #3b82f6; /* Azul */
  border-radius: 9999px;
}

.dropdown-footer {
  padding: 10px 16px;
  border-top: 1px solid #e5e7eb;
  text-align: center;
  background-color: #f9fafb;
}

.view-all {
  font-size: 13px;
  color: #2563eb;
  text-decoration: none;
  font-weight: 500;
}
</style>