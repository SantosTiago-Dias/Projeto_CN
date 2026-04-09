<script setup>
import { ref, onMounted } from 'vue'
import { Save, X, ClipboardList, Calendar, AlertCircle, Activity, User, ArrowLeft,MapPin } from 'lucide-vue-next'
import { Button } from "@/components/ui/button/index.ts";
import { toast } from "vue-sonner";
import { useRouter } from "vue-router";
import { useTasksStore } from "@/stores/tasks.js";
import { useUserStore } from "@/stores/user.js";

const router = useRouter()
const tasksStore = useTasksStore()
const userStore = useUserStore()

const task = ref({
  title: '',
  description: '',
  priority: 'LOW',
  status: 'PENDING',
  outside: false,
  due_date: '',
  worker_id: null
})

const users = ref({
  id:'',
  name:''
})


const handleCreate = async () => {
  try {
    let res = await tasksStore.storeTask(task.value)

    if (res.status === 200) {
      toast.success('Tarefa criada com sucesso!')
      router.push('/tasks')
    } else {
      toast.error('Ocorreu um erro ao tentar criar a Tarefa')
    }
  } catch (error) {
    console.log(error)
    toast.error(error .message)
  }
}

const goBack = () => {
  router.back()
}

onMounted(async () => {
  try {
    const response = await userStore.getAllWorkers()
    users.value = response.data
  } catch (error) {
    toast.error("Erro ao carregar lista de trabalhadores")
  }
})
</script>

<template>
  <div class="max-w-2xl mx-auto p-6">
    <div class="flex items-center justify-between mb-8">
      <div class="flex items-center gap-4">
        <button @click="goBack" class="p-2 hover:bg-slate-100 rounded-full transition-colors">
          <ArrowLeft class="w-5 h-5 text-slate-600" />
        </button>
        <div>
          <h1 class="text-2xl font-bold text-slate-900">Nova Tarefa</h1>
          <p class="text-sm text-slate-500 font-medium italic">Preencha os campos para atribuir trabalho</p>
        </div>
      </div>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
      <form @submit.prevent="handleCreate" class="p-8 space-y-6">

        <div class="space-y-2">
          <label class="flex items-center gap-2 text-sm font-semibold text-slate-700">
            <ClipboardList class="w-4 h-4 text-blue-500" /> Título da Tarefa
          </label>
          <input
              v-model="task.title"
              type="text"
              required
              class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-lg focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-all outline-none"
              placeholder="Ex: Revisão de Relatório Anual..."
          >
        </div>

        <div class="space-y-2">
          <label class="flex items-center gap-2 text-sm font-semibold text-slate-700">
            Descrição Detalhada
          </label>
          <textarea
              v-model="task.description"
              rows="4"
              required
              class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-lg focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-all outline-none resize-none"
              placeholder="Descreva o que deve ser feito..."
          ></textarea>
        </div>



        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

          <div class="space-y-2">
            <label class="flex items-center gap-2 text-sm font-semibold text-slate-700">
              <AlertCircle class="w-4 h-4 text-amber-500" /> Prioridade
            </label>
            <select v-model="task.priority" required class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-lg focus:ring-2 focus:ring-blue-500/20 outline-none cursor-pointer">
              <option value="LOW">Baixa (Low)</option>
              <option value="MEDIUM">Média (Medium)</option>
              <option value="HIGH">Alta (High)</option>
            </select>
          </div>

          <div class="space-y-2">
            <label class="flex items-center gap-2 text-sm font-semibold text-slate-700">
              <Activity class="w-4 h-4 text-emerald-500" /> Estado Inicial
            </label>
            <select v-model="task.status" required class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-lg focus:ring-2 focus:ring-blue-500/20 outline-none cursor-pointer">
              <option value="PENDING">Pendente</option>
              <option value="IN_PROGRESS">Em Curso</option>
            </select>
          </div>

          <div class="space-y-2 md:col-span-2">
            <label class="flex items-center gap-2 text-sm font-semibold text-slate-700">
              <MapPin class="w-4 h-4 text-orange-500" /> Tarefa Externa
            </label>
            <div class="flex items-center gap-3 px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-lg">
              <input
                  v-model="task.outside"
                  type="checkbox"
                  id="outside"
                  class="w-4 h-4 accent-blue-600 cursor-pointer"
              >
              <label for="outside" class="text-sm text-slate-600 cursor-pointer">
                Esta tarefa é realizada fora das instalações
              </label>
            </div>
          </div>

          <div class="space-y-2 md:col-span-2">
            <label class="flex items-center gap-2 text-sm font-semibold text-slate-700">
              <Calendar class="w-4 h-4 text-rose-500" /> Prazo de Entrega (Due Date)
            </label>
            <input
                v-model="task.due_date"
                type="date"
                required
                class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-lg focus:ring-2 focus:ring-blue-500/20 outline-none"
            >
          </div>

          <div class="space-y-2 md:col-span-2">
            <label class="flex items-center gap-2 text-sm font-semibold text-slate-700">
              <User class="w-4 h-4 text-indigo-500" /> Trabalhador Responsável
            </label>
            <select
                v-model="task.worker_id"
                required
                class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-lg focus:ring-2 focus:ring-blue-500/20 outline-none cursor-pointer"
            >
              <option :value="null" disabled>Selecione um trabalhador</option>
              <option v-for="user in users" :key="user.id" :value="user.id">
                {{ user.name }}
              </option>
            </select>
          </div>
        </div>


        <div class="flex items-center justify-end gap-3 pt-6 border-t border-slate-100">
          <Button
              variant="ghost"
              type="button"
              class="text-slate-600 hover:bg-slate-100 flex items-center gap-2"
              @click="goBack"
          >
            <X class="w-4 h-4" /> Cancelar
          </Button>
          <Button
              type="submit"
              class="bg-blue-600 hover:bg-blue-700 text-white px-10 flex items-center gap-2 shadow-md shadow-blue-500/20 transition-all active:scale-95"
          >
            <Save class="w-4 h-4" /> Criar Tarefa
          </Button>
        </div>
      </form>
    </div>
  </div>
</template>