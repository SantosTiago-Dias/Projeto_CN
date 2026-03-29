<script setup>
import { Users, AlertOctagon, CheckCheck, BarChart3, Plus } from 'lucide-vue-next'
import { Button } from "@/components/ui/button"

// Dados de exemplo para o Admin
const adminStats = {
  totalActive: 24,
  overdue: 3,
  completedToday: 12,
  activeWorkers: 8
}

const teamOverView = [
  { id: 1, name: 'João Silva', tasks: 5, status: 'Overloaded' },
  { id: 2, name: 'Maria Sousa', tasks: 2, status: 'Available' },
  { id: 3, name: 'Ricardo Pereira', tasks: 0, status: 'Free' },
]
</script>

<template>
  <div class="p-8 space-y-8 max-w-7xl mx-auto">

    <div class="flex items-center justify-between">
      <div>
        <h1 class="text-3xl font-black text-slate-950 tracking-tighter">Painel de Controlo</h1>
        <p class="text-slate-500 font-medium">Gestão global de operações e equipa</p>
      </div>
      <Button as-child>
        <RouterLink to="/task/create" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-6 rounded-2xl">
          <Plus :size="20" /> Criar Nova Tarefa
        </RouterLink>
      </Button>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
      <div class="bg-white p-6 rounded-3xl border border-slate-100 shadow-sm">
        <div class="p-3 bg-blue-50 text-blue-600 w-fit rounded-2xl mb-4"><BarChart3 /></div>
        <p class="text-sm font-bold text-slate-400 uppercase tracking-widest">Ativas</p>
        <h3 class="text-3xl font-black">{{ adminStats.totalActive }}</h3>
      </div>

      <div class="bg-white p-6 rounded-3xl border border-rose-100 shadow-sm ring-4 ring-rose-50/50">
        <div class="p-3 bg-rose-50 text-rose-600 w-fit rounded-2xl mb-4"><AlertOctagon /></div>
        <p class="text-sm font-bold text-rose-400 uppercase tracking-widest">Em Atraso</p>
        <h3 class="text-3xl font-black text-rose-600">{{ adminStats.overdue }}</h3>
      </div>

      <div class="bg-white p-6 rounded-3xl border border-slate-100 shadow-sm">
        <div class="p-3 bg-emerald-50 text-emerald-600 w-fit rounded-2xl mb-4"><CheckCheck /></div>
        <p class="text-sm font-bold text-slate-400 uppercase tracking-widest">Feitas Hoje</p>
        <h3 class="text-3xl font-black">{{ adminStats.completedToday }}</h3>
      </div>

      <div class="bg-white p-6 rounded-3xl border border-slate-100 shadow-sm">
        <div class="p-3 bg-indigo-50 text-indigo-600 w-fit rounded-2xl mb-4"><Users /></div>
        <p class="text-sm font-bold text-slate-400 uppercase tracking-widest">Equipa</p>
        <h3 class="text-3xl font-black">{{ adminStats.activeWorkers }} <span class="text-sm font-medium text-slate-400">on-line</span></h3>
      </div>
    </div>

    <div class="bg-white rounded-3xl border border-slate-100 shadow-sm overflow-hidden">
      <div class="p-6 border-b border-slate-50 flex items-center justify-between">
        <h2 class="text-xl font-bold">Estado da Equipa</h2>
        <Button as-child>
          <RouterLink to="/users" class="flex items-center gap-2">
            <Users class="h-4 w-4" /> <span>Ver todos Utilizadores</span>
          </RouterLink>
        </Button>
      </div>
      <div class="divide-y divide-slate-50">
        <div v-for="worker in teamOverView" :key="worker.id" class="p-5 flex items-center justify-between hover:bg-slate-50 transition">
          <div class="flex items-center gap-4">
            <div class="w-10 h-10 bg-slate-100 rounded-full flex items-center justify-center font-bold text-slate-500">
              {{ worker.name.charAt(0) }}
            </div>
            <div>
              <p class="font-bold text-slate-900">{{ worker.name }}</p>
              <p class="text-xs text-slate-500">{{ worker.tasks }} tarefas atribuídas</p>
            </div>
          </div>
          <span :class="[
            'px-3 py-1 rounded-full text-[10px] font-black uppercase',
            worker.status === 'Available' ? 'bg-emerald-100 text-emerald-600' :
            worker.status === 'Overloaded' ? 'bg-rose-100 text-rose-600' : 'bg-slate-100 text-slate-500'
          ]">
            {{ worker.status }}
          </span>
        </div>
      </div>
    </div>

  </div>
</template>