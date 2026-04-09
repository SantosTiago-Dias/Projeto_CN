<script setup>
import {ref, onMounted, computed, inject} from 'vue'
import {
  CheckCircle2, Clock, AlertTriangle,
  ListTodo, Calendar as CalendarIcon, MapPin, X
} from 'lucide-vue-next'
import { Card, CardContent } from "@/components/ui/card"
import { useAuthStore } from "@/stores/auth.js"
import { useTasksStore } from "@/stores/tasks.js"
import { Button } from "@/components/ui/button/index.ts"
import { toast } from "vue-sonner"
import WeatherWidget from "@/components/ui/weather/Weather.vue"
import CompleteTaskModal from "@/components/ui/completeTaskModel/CompleteTaskModal.vue" // Import do novo componente

const authStore = useAuthStore()
const tasksStore = useTasksStore()
const s3_BASE_URL = inject('s3BaseURL')

const tasks = computed(() => tasksStore.tasks)

const showDetailsModal = ref(false)
const selectedTask = ref(null)

const showCancelModal = ref(false)
const taskToCancel = ref(null)
const cancelReason = ref('')

// Estados para o Modal de Conclusão com Imagem
const showCompleteModal = ref(false)
const taskToComplete = ref(null)

const weatherAlertReason = ref('')

const onWeatherAlert = (reason) => {
  weatherAlertReason.value = reason
}

const openDetails = (task) => {
  selectedTask.value = task
  showDetailsModal.value = true
}

// Model para preview da imagem
const openCompleteModal = (task) => {
  taskToComplete.value = task
  showCompleteModal.value = true
}

const confirmCancel = (task) => {
  taskToCancel.value = task
  cancelReason.value = task.outside && weatherAlertReason.value
      ? weatherAlertReason.value
      : ''
  showCancelModal.value = true
}

// Funçao para cancelar a tarefa
const handleCancel = async () => {
  try {
    await tasksStore.changeStatusTask(taskToCancel.value.id, {
      status: 'CANCELLED',
      reason_cancelled: cancelReason.value
    })
    await getmyTasks()
    showCancelModal.value = false
    toast.success('Tarefa cancelada com sucesso')
  } catch (error) {
    toast.error('Ocorreu um erro a cancelar a tarefa')
  }
}

// Funçao para completar a tarefa
const handleCompleteTask = async ({ taskId,file }) => {
  try {
    const formData = new FormData()
    formData.append('status', 'COMPLETED')
    formData.append('proof_image', file)

    await tasksStore.changeStatusTask(taskId, formData,{
      headers: {
        'Content-Type': 'multipart/form-data'
      }
    })

    await getmyTasks()
    showCompleteModal.value = false
    toast.success('Tarefa concluída com prova enviada!')
  } catch (error) {

    toast.error('Erro ao finalizar tarefa com imagem')
  }
}

onMounted(async () => {
  await getmyTasks()
})

const getmyTasks = async () => {
  await tasksStore.showUserTasks()
}

const stats = computed(() => {
  const list = tasks.value || []
  return {
    total: list.length,
    completed: list.filter(t => t.status === 'COMPLETED').length,
    pending: list.filter(t => t.status === 'PENDING' || t.status === 'IN_PROGRESS').length,
    urgent: list.filter(t => t.priority === 'HIGH' && t.status !== 'COMPLETED').length
  }
})

const today = new Date().toLocaleDateString('pt-PT', {
  weekday: 'long', year: 'numeric', month: 'long', day: 'numeric'
})

const changeStatus = async (id, status) => {
  try {
    await tasksStore.changeStatusTask(id, { status })
    await getmyTasks()

    if (status === 'IN_PROGRESS') toast.success('Tarefa começada com sucesso')
  } catch (error) {
    toast.error('Ocorreu um erro a atualizar o estado da tarefa')
  }
}
</script>

<template>
  <div class="p-6 space-y-8 max-w-7xl mx-auto">
    <div>
      <WeatherWidget @weather-alert="onWeatherAlert" />
    </div>

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
        <div
            v-for="task in tasks"
            :key="task.id"
            @click="openDetails(task)"
            class="flex items-center justify-between p-5 rounded-2xl border border-slate-100 hover:bg-slate-50 transition-colors group cursor-pointer"
        >
          <div class="flex items-center gap-4">
            <div :class="[
              'w-2 h-10 rounded-full',
              task.priority === 'HIGH' ? 'bg-rose-500' :
              task.priority === 'MEDIUM' ? 'bg-amber-500' :
              task.priority === 'LOW' ? 'bg-blue-500' : 'bg-slate-300'
            ]"></div>
            <div>
              <h4 class="font-semibold text-slate-900 group-hover:text-blue-600 transition-colors">{{ task.title }}</h4>
              <span v-if="task.outside" class="inline-flex items-center gap-1 mt-1 px-2 py-0.5 rounded-full text-[10px] font-black uppercase tracking-widest bg-orange-100 text-orange-600">
                <MapPin :size="10" /> Externa
              </span>
            </div>
          </div>

          <div class="flex items-center gap-3" @click.stop>
            <Button
                v-if="task.status === 'PENDING'"
                @click="changeStatus(task.id, 'IN_PROGRESS')"
                class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2.5 h-auto text-sm font-bold shadow-md shadow-blue-500/10 active:scale-95 transition-all rounded-xl"
            >
              Começar Tarefa
            </Button>

            <div v-else-if="task.status === 'IN_PROGRESS'" class="flex items-center gap-2">
              <Button
                  @click="openCompleteModal(task)"
                  class="bg-emerald-500 hover:bg-emerald-600 text-white px-5 py-2.5 h-auto text-sm font-bold flex items-center gap-2 transition-all active:scale-95 rounded-xl shadow-md shadow-emerald-500/10"
              >
                <CheckCircle2 :size="18" /> Concluir
              </Button>
              <Button
                  variant="ghost"
                  @click="confirmCancel(task)"
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

  <!-- Details Modal -->
  <div v-if="showDetailsModal && selectedTask" class="fixed inset-0 bg-black/50 flex items-center justify-center z-50" @click.self="showDetailsModal = false">
    <div class="bg-white rounded-2xl shadow-2xl max-w-md w-full mx-4 overflow-hidden">

      <div class="flex items-center justify-between p-6 border-b border-slate-100">
        <h3 class="text-lg font-bold text-slate-900">Detalhes da Tarefa</h3>
        <button @click="showDetailsModal = false" class="p-2 hover:bg-slate-100 rounded-full transition-colors">
          <X :size="18" class="text-slate-500" />
        </button>
      </div>

      <div class="p-6 space-y-4">
        <div>
          <p class="text-xs font-bold uppercase tracking-widest text-slate-400 mb-1">Título</p>
          <p class="font-semibold text-slate-900">{{ selectedTask.title }}</p>
        </div>

        <div>
          <p class="text-xs font-bold uppercase tracking-widest text-slate-400 mb-1">Descrição</p>
          <p class="text-slate-600 text-sm">{{ selectedTask.description }}</p>
        </div>

        <div class="grid grid-cols-2 gap-4">
          <div>
            <p class="text-xs font-bold uppercase tracking-widest text-slate-400 mb-1">Estado</p>
            <span :class="[
              'px-2.5 py-1 rounded-full text-xs font-bold',
              selectedTask.status === 'PENDING' ? 'bg-amber-100 text-amber-700' :
              selectedTask.status === 'IN_PROGRESS' ? 'bg-blue-100 text-blue-700' :
              selectedTask.status === 'COMPLETED' ? 'bg-emerald-100 text-emerald-700' :
              'bg-rose-100 text-rose-700'
            ]">
              {{ selectedTask.status === 'PENDING' ? 'Pendente' :
                selectedTask.status === 'IN_PROGRESS' ? 'Em Progresso' :
                    selectedTask.status === 'COMPLETED' ? 'Concluída' : 'Cancelada' }}
            </span>
          </div>

          <div>
            <p class="text-xs font-bold uppercase tracking-widest text-slate-400 mb-1">Prioridade</p>
            <span :class="[
              'inline-flex items-center gap-1.5 font-bold text-xs uppercase tracking-wide',
              selectedTask.priority === 'HIGH' ? 'text-red-600' :
              selectedTask.priority === 'MEDIUM' ? 'text-orange-500' : 'text-slate-500'
            ]">
              <span :class="[
                'w-2 h-2 rounded-full',
                selectedTask.priority === 'HIGH' ? 'bg-red-600 animate-pulse' :
                selectedTask.priority === 'MEDIUM' ? 'bg-orange-500' : 'bg-slate-400'
              ]"></span>
              {{ selectedTask.priority === 'HIGH' ? 'Alta' : selectedTask.priority === 'MEDIUM' ? 'Média' : 'Baixa' }}
            </span>
          </div>

          <div>
            <p class="text-xs font-bold uppercase tracking-widest text-slate-400 mb-1">Prazo</p>
            <p class="text-sm text-slate-700 font-medium">{{ new Date(selectedTask.due_date).toLocaleDateString('pt-PT') }}</p>
          </div>

          <div>
            <p class="text-xs font-bold uppercase tracking-widest text-slate-400 mb-1">Tarefa Externa</p>
            <span v-if="selectedTask.outside" class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-bold bg-orange-100 text-orange-600">
              <MapPin :size="12" /> Sim
            </span>
            <span v-else class="px-2.5 py-1 rounded-full text-xs font-bold bg-slate-100 text-slate-500">
              Não
            </span>
          </div>

          <div v-if="selectedTask.reason_cancelled" class="col-span-2">
            <p class="text-xs font-bold uppercase tracking-widest text-slate-400 mb-1">Motivo de Cancelamento</p>
            <p class="text-sm text-rose-600 font-medium">{{ selectedTask.reason_cancelled }}</p>
          </div>

          <div v-if="selectedTask.status === 'COMPLETED' && selectedTask.prove_complete" class="pt-4 border-t border-slate-100">
            <p class="text-xs font-bold uppercase tracking-widest text-emerald-600 mb-2 flex items-center gap-2">
              <CheckCircle2 :size="14" /> Prova de Conclusão
            </p>
            <div class="relative group cursor-pointer overflow-hidden rounded-xl border border-slate-200">
              <img
                  :src="`${s3_BASE_URL}/${selectedTask.prove_complete}`"
                  alt="Prova de trabalho"
                  class="w-full h-48 object-cover transition-transform duration-300 group-hover:scale-105"
              />
            </div>
          </div>

        </div>
      </div>

      <div class="px-6 pb-6">
        <Button variant="outline" class="w-full" @click="showDetailsModal = false">Fechar</Button>
      </div>
    </div>
  </div>

  <!-- Cancel Confirmation Modal -->
  <div v-if="showCancelModal && taskToCancel" class="fixed inset-0 bg-black/50 flex items-center justify-center z-50" @click.self="showCancelModal = false">
    <div class="bg-white rounded-2xl shadow-2xl max-w-sm w-full mx-4 overflow-hidden">

      <div class="flex items-center justify-between p-6 border-b border-slate-100">
        <h3 class="text-lg font-bold text-slate-900">Cancelar Tarefa</h3>
        <button @click="showCancelModal = false" class="p-2 hover:bg-slate-100 rounded-full transition-colors">
          <X :size="18" class="text-slate-500" />
        </button>
      </div>

      <div class="p-6 space-y-4">
        <div class="flex items-center gap-4 p-4 bg-rose-50 rounded-xl">
          <AlertTriangle class="text-rose-500 shrink-0" :size="24" />
          <p class="text-sm text-slate-700">
            Tem a certeza que deseja cancelar a tarefa <strong>{{ taskToCancel.title }}</strong>? Esta ação não pode ser revertida.
          </p>
        </div>

        <div v-if="taskToCancel.outside && weatherAlertReason" class="flex items-center gap-2 px-3 py-2 bg-amber-50 border border-amber-200 rounded-lg">
          <AlertTriangle class="text-amber-500 shrink-0" :size="14" />
          <p class="text-xs text-amber-700 font-semibold">Motivo preenchido automaticamente por alerta meteorológico</p>
        </div>

        <div class="space-y-1">
          <label class="text-xs font-bold uppercase tracking-widest text-slate-400">Motivo do Cancelamento</label>
          <textarea
              v-model="cancelReason"
              required
              rows="3"
              placeholder="Descreva o motivo do cancelamento..."
              class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-lg text-sm outline-none focus:ring-2 focus:ring-rose-500/20 focus:border-rose-400 resize-none"
          ></textarea>
        </div>
      </div>

      <div class="flex gap-3 px-6 pb-6">
        <Button variant="outline" class="flex-1" @click="showCancelModal = false">Voltar</Button>
        <Button class="flex-1 bg-rose-500 hover:bg-rose-600 text-white" @click="handleCancel">
          Confirmar Cancelamento
        </Button>
      </div>
    </div>
  </div>

  <CompleteTaskModal
      :is-open="showCompleteModal"
      :task="taskToComplete"
      @close="showCompleteModal = false"
      @confirm="handleCompleteTask"
  />
</template>