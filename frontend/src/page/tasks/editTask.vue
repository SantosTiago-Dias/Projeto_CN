<script setup>
import { ref, onMounted } from 'vue'
import {Save, X, ClipboardList, Calendar,AlertCircle, Activity, User, ArrowLeft,MapPin} from 'lucide-vue-next'
import {Button} from "@/components/ui/button/index.ts";
import {toast} from "vue-sonner";
import {useRouter} from "vue-router";
import {useTasksStore} from "@/stores/tasks.js";
import {useUserStore} from "@/stores/user.js";

const props = defineProps(['id'])
const router = useRouter()

  const task = ref({
  title: '',
  description: '',
  priority: '',
  status: '',
  outside:null,
  due_date: '',
  worker_id: null
})

const users = ref({
  id:'',
  name:''
})

const tasksStore=useTasksStore()
const userStore = useUserStore()

const handleUpdate = async () => {
  try
  {
    let res = await tasksStore.editTask(props.id,task.value)

    if (res.status === 200) {
      toast.success('Tarefa editada com sucesso')
      router.push('/tasks')
    } else {
      toast.error('Ocorreu um erro ao tentar atualizar a Tarefa')
    }
  }
  catch (error) {
    toast.error(error.message)
  }

}

const goBack = () =>{
  router.back()
}

onMounted(async () => {
  const data = await tasksStore.showTask(props.id)
  //bolean data
  task.value = {
    ...data,
    outside: Boolean(data.outside)
  }
  const response = await userStore.getAllWorkers()
  users.value = response.data
})



</script>

<template>
  <div class="max-w-2xl mx-auto p-6">
    <div class="flex items-center justify-between mb-8">
      <div class="flex items-center gap-4">
        <button @click="$router.back()" class="p-2 hover:bg-slate-100 rounded-full transition-colors">
          <ArrowLeft class="w-5 h-5 text-slate-600" />
        </button>
        <div>
          <h1 class="text-2xl font-bold text-slate-900">Editar Tarefa</h1>
          <p class="text-sm text-slate-500 font-medium flex items-center gap-1">
            <User class="w-3 h-3" /> ID da Tarefa: #{{ task.id }}
          </p>
        </div>
      </div>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
      <form @submit.prevent="handleUpdate" class="p-8 space-y-6">

        <div class="space-y-2">
          <label class="flex items-center gap-2 text-sm font-semibold text-slate-700">
            <ClipboardList class="w-4 h-4 text-blue-500" /> Título da Tarefa
          </label>
          <input
              v-model="task.title"
              type="text"
              class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-lg focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-all outline-none"
              placeholder="Ex: Nome da tarefa..."
          >
        </div>

        <div class="space-y-2">
          <label class="flex items-center gap-2 text-sm font-semibold text-slate-700">
            Descrição Detalhada
          </label>
          <textarea
              v-model="task.description"
              rows="4"
              class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-lg focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-all outline-none resize-none"
          ></textarea>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

          <div class="space-y-2">
            <label class="flex items-center gap-2 text-sm font-semibold text-slate-700">
              <AlertCircle class="w-4 h-4 text-amber-500" /> Prioridade
            </label>
            <select v-model="task.priority" class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-lg focus:ring-2 focus:ring-blue-500/20 outline-none appearance-none cursor-pointer">
              <option value="LOW">Baixa (Low)</option>
              <option value="MEDIUM">Média (Medium)</option>
              <option value="HIGH">Alta (High)</option>
            </select>
          </div>

          <div class="space-y-2">
            <label class="flex items-center gap-2 text-sm font-semibold text-slate-700">
              <Activity class="w-4 h-4 " /> Estado Atual
            </label>
            <select v-model="task.status" class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-lg focus:ring-2 focus:ring-blue-500/20 outline-none cursor-pointer">
              <option value="PENDING">Pendente</option>
              <option value="IN_PROGRESS">Em Curso</option>
              <option value="COMPLETED">Concluída</option>
              <option value="CANCELLED">Cancelada</option>
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
                class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-lg focus:ring-2 focus:ring-blue-500/20 outline-none"
            >
          </div>

          <div class="space-y-2 md:col-span-2">
            <label class="flex items-center gap-2 text-sm font-semibold text-slate-700">
              <User class="w-4 h-4 text-rose-500" /> Trabalhador Responsável
            </label>

            <select
                v-model="task.worker_id"
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
              @click="$router.back()"
          >
            <X class="w-4 h-4" /> Cancelar
          </Button>
          <Button
              type="submit"
              class="bg-blue-600 hover:bg-blue-700 text-white px-8 flex items-center gap-2 shadow-md shadow-blue-500/20 transition-all active:scale-95"
          >
            <Save class="w-4 h-4" /> Guardar Alterações
          </Button>
        </div>
      </form>
    </div>
  </div>
</template>

<style scoped>
.form-control:focus, .form-select:focus {
  border-color: #0d6efd;
  box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, 0.1);
  background-color: #fff !important;
}
.input-group-text {
  border-right: none;
  color: #6c757d;
}
.form-control, .form-select {
  border-left: none;
}
</style>