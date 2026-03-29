<script setup>
import { ref, onMounted, computed } from 'vue'
import {
  CheckCircle2, Clock, AlertTriangle,
  ListTodo, Calendar as CalendarIcon, TrendingUp
} from 'lucide-vue-next'
import { Card, CardContent, CardHeader, CardTitle } from "@/components/ui/card"
import {useAuthStore} from "@/stores/auth.js";
import {useTasksStore} from "@/stores/tasks.js";
import {Button} from "@/components/ui/button/index.ts";
import {toast} from "vue-sonner";

const authStore = useAuthStore()
const tasksStore = useTasksStore()
const tasks = ref([])

onMounted(async () => {
  getmyTasks()
})

const getmyTasks = async () => {
  let res = await tasksStore.showUserTasks()
  tasks.value = res.data
}

const stats = computed(() => {

  return {
    total: tasks.value.length,
    completed: tasks.value.filter(t => t.status === 'COMPLETED').length,
    pending: tasks.value.filter(t => t.status === 'PENDING' || t.status === 'IN_PROGRESS').length,
    urgent: tasks.value.filter(t => t.priority === 'HIGH' && t.status !== 'COMPLETED').length
  }
})

const today = new Date().toLocaleDateString('pt-PT', {
  weekday: 'long', year: 'numeric', month: 'long', day: 'numeric'
})

const changeStatus = async (id,status) =>{
  console.log(id,status)

  try {
    let res = await tasksStore.changeStatusTask(id,status)
    await getmyTasks()

    if (status === 'IN PROGRESS')
    {
      toast.success('Tarefa começada com sucesso')
    }
    else if (status === 'COMPLETED')
    {
      toast.success('Tarefa concluida com sucesso')
    }
    else
    {
      toast.success('Tarefa cancelada com sucesso')
    }

  } catch (error) {
    toast.error('Ocorreu um erro a atualizar o estado da tarefa')
  }
}
</script>

<template>
  <div class="p-6 space-y-8 max-w-7xl mx-auto">

    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
      <div>
        <h1 class="text-3xl font-bold text-slate-900 tracking-tight">Olá, {{ authStore.currentUser.name }}</h1>
        <p class="text-slate-500 flex items-center gap-2 mt-1">
          <CalendarIcon :size="16" /> {{ today }}
        </p>
      </div>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
      <Card class="border-none shadow-sm ring-1 ring-slate-200">
        <CardContent class="p-6 flex items-center gap-4">
          <div class="p-3 bg-blue-100 text-blue-600 rounded-xl"><ListTodo /></div>
          <div>
            <p class="text-sm text-slate-500 font-medium">Tarefas</p>
            <h3 class="text-2xl font-bold">{{ stats.total }}</h3>
          </div>
        </CardContent>
      </Card>

      <Card class="border-none shadow-sm ring-1 ring-slate-200">
        <CardContent class="p-6 flex items-center gap-4">
          <div class="p-3 bg-emerald-100 text-emerald-600 rounded-xl"><CheckCircle2 /></div>
          <div>
            <p class="text-sm text-slate-500 font-medium">Concluídas</p>
            <h3 class="text-2xl font-bold">{{ stats.completed }}</h3>
          </div>
        </CardContent>
      </Card>

      <Card class="border-none shadow-sm ring-1 ring-slate-200">
        <CardContent class="p-6 flex items-center gap-4">
          <div class="p-3 bg-amber-100 text-amber-600 rounded-xl"><Clock /></div>
          <div>
            <p class="text-sm text-slate-500 font-medium">Em Aberto</p>
            <h3 class="text-2xl font-bold">{{ stats.pending }}</h3>
          </div>
        </CardContent>
      </Card>

      <Card class="border-none shadow-sm ring-1 ring-slate-200">
        <CardContent class="p-6 flex items-center gap-4">
          <div class="p-3 bg-rose-100 text-rose-600 rounded-xl"><AlertTriangle /></div>
          <div>
            <p class="text-sm text-slate-500 font-medium">Urgentes</p>
            <h3 class="text-2xl font-bold text-rose-600">{{ stats.urgent }}</h3>
          </div>
        </CardContent>
      </Card>
    </div>

    <div class="bg-white p-10 rounded-3xl border border-slate-100 shadow-sm">
      <h2 class="text-2xl font-bold text-slate-950 tracking-tight mb-8">Tarefas Agendadas</h2>
      <div class="space-y-5">
        <div v-for="task in tasks" :key="task.id" class="flex items-center justify-between p-5 rounded-2xl border border-slate-100 hover:bg-slate-50 transition-colors group">

          <div class="flex items-center gap-4">
            <div :class="[
              'w-2 h-10 rounded-full',
              task.priority === 'HIGH' ? 'bg-rose-500' :
              task.priority === 'MEDIUM' ? 'bg-amber-500' :
              task.priority === 'LOW' ? 'bg-blue-500' : 'bg-slate-300'
            ]"></div>
            <div>
              <h4 class="font-semibold text-slate-900 group-hover:text-blue-600 transition-colors">{{ task.title }}</h4>
              <p class="text-[11px] text-slate-400 uppercase font-black tracking-widest" v-if="task.priority === 'HIGH'">Alta prioridade</p>
              <p class="text-[11px] text-slate-400 uppercase font-black tracking-widest" v-if="task.priority === 'MEDIUM'">Prioridade média</p>
              <p class="text-[11px] text-slate-400 uppercase font-black tracking-widest" v-if="task.priority === 'LOW'"> Prioridade baixa</p>
            </div>
          </div>

          <div class="flex items-center gap-3">

            <Button
                v-if="task.status === 'PENDING'"
                @click="changeStatus(task.id, 'IN_PROGRESS')"
                class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2.5 h-auto text-sm font-bold shadow-md shadow-blue-500/10 active:scale-95 transition-all rounded-xl"
            >
              Começar Tarefa
            </Button>

            <div v-else-if="task.status === 'IN_PROGRESS'" class="flex items-center gap-2">
              <Button
                  @click="changeStatus(task.id, 'COMPLETED')"
                  class="bg-emerald-500 hover:bg-emerald-600 text-white px-5 py-2.5 h-auto text-sm font-bold flex items-center gap-2 transition-all active:scale-95 rounded-xl shadow-md shadow-emerald-500/10"
              >
                <CheckCircle2 :size="18" /> Concluir
              </Button>

              <Button
                  variant="ghost"
                  @click="changeStatus(task.id, 'CANCELLED')"
                  class="text-rose-500 hover:bg-rose-50 px-4 py-2.5 h-auto text-sm font-semibold rounded-xl"
              >
                Cancelar
              </Button>
            </div>

            <div v-else class="flex items-center px-4 py-2">
    <span :class="[
      'px-3 py-1 rounded-full text-[11px] font-black uppercase tracking-wider',
      task.status === 'COMPLETED' ? 'bg-emerald-100 text-emerald-700' : 'bg-rose-100 text-rose-700'
    ]">
      {{ task.status === 'COMPLETED' ? 'Finalizada' : 'Cancelada' }}
    </span>
            </div>

          </div>
        </div>
      </div>
    </div>
  </div>

</template>