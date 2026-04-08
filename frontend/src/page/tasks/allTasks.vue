<script setup>
import {computed, onMounted, ref} from "vue";
import { Card, CardContent, CardHeader, CardTitle } from "@/components/ui/card/index.js";
import { Plus, Pencil, X, AlertTriangle, MapPin, Eye } from "lucide-vue-next";
import { Table, TableBody, TableCell, TableHead, TableHeader, TableRow } from "@/components/ui/table/index.ts";
import { Button } from "@/components/ui/button/index.ts";
import {toast} from "vue-sonner";
import {useTasksStore} from "@/stores/tasks.js";
import {useRouter} from "vue-router";


const tasksStore = useTasksStore()
const tasks = computed(() => tasksStore.tasks)
const isLoading = ref(false)
const router = useRouter()

const getAllTasks = async () => {
  isLoading.value = true
  try {
    await tasksStore.getAllTasks()
  } catch (error) {
    toast.error("Erro ao carregar tarefas")
    router.back()
  } finally {
    isLoading.value = false
  }
}

const showDeleteModal = ref(false)
const taskToDelete = ref(null)
const isDeleting = ref(false)

const showDetailsModal = ref(false)
const selectedTask = ref(null)

const openDetails = (task) => {
  selectedTask.value = task
  showDetailsModal.value = true
}

const confirmDelete = (task) => {
  taskToDelete.value = task
  showDeleteModal.value = true
}

const handleDelete = async () => {
  isDeleting.value = true
  try {
    await tasksStore.deleteTask(taskToDelete.value.id)
    await getAllTasks()
    showDeleteModal.value = false
    toast.success('Tarefa eliminada com sucesso')
  } catch (error) {
    toast.error('Ocorreu um erro a tentar eliminar a tarefa')
  } finally {
    isDeleting.value = false
  }
}

onMounted(() => {
  getAllTasks()
})
</script>

<template>
  <div class="flex min-h-screen bg-slate-50/50">
    <main class="flex-1 flex flex-col">
      <div class="p-8 space-y-8">

        <div class="flex items-center justify-between">
          <div>
            <h2 class="text-2xl font-bold tracking-tight">Tarefas</h2>
            <p class="text-muted-foreground font-medium">Gerencie as tarefas registadas no sistema</p>
          </div>
          <Button as-child>
            <RouterLink to="/task/create" class="flex items-center gap-2">
              <Plus class="h-4 w-4" /> <span>Adicionar nova Tarefa</span>
            </RouterLink>
          </Button>
        </div>

        <Card class="border-none shadow-sm ring-1 ring-slate-200">
          <CardHeader>
            <CardTitle>Lista de Tarefas</CardTitle>
          </CardHeader>
          <CardContent>
            <div v-if="isLoading" class="text-center py-10 text-muted-foreground">A carregar...</div>
            <Table v-else>
              <TableHeader>
                <TableRow>
                  <TableHead>Título</TableHead>
                  <TableHead>Descrição</TableHead>
                  <TableHead>Status</TableHead>
                  <TableHead>Prioridade</TableHead>
                  <TableHead>Externa</TableHead>
                  <TableHead>Data Limite</TableHead>
                  <TableHead class="text-right">Ações</TableHead>
                </TableRow>
              </TableHeader>
              <TableBody>
                <TableRow v-for="task in tasks" :key="task.id">
                  <TableCell class="font-medium">{{ task.title }}</TableCell>

                  <TableCell class="max-w-[200px] truncate text-muted-foreground">
                    {{ task.description }}
                  </TableCell>

                  <TableCell>
                    <span v-if="task.status === 'PENDING'" class="px-2.5 py-1 rounded-full text-xs font-bold bg-amber-100 text-amber-700">
                      Pendente
                    </span>
                    <span v-else-if="task.status === 'IN_PROGRESS'" class="px-2.5 py-1 rounded-full text-xs font-bold bg-blue-100 text-blue-700">
                      Em Progresso
                    </span>
                    <span v-else-if="task.status === 'COMPLETED'" class="px-2.5 py-1 rounded-full text-xs font-bold bg-emerald-100 text-emerald-700">
                      Concluído
                    </span>
                    <span v-else-if="task.status === 'CANCELLED'" class="px-2.5 py-1 rounded-full text-xs font-bold bg-rose-100 text-rose-700">
                      Cancelado
                    </span>
                  </TableCell>

                  <TableCell>
                    <span v-if="task.priority === 'HIGH'" class="inline-flex items-center gap-1.5 font-bold text-xs text-red-600 uppercase tracking-wide">
                      <span class="w-2 h-2 rounded-full bg-red-600 animate-pulse"></span>
                      Alta
                    </span>
                    <span v-else-if="task.priority === 'MEDIUM'" class="inline-flex items-center gap-1.5 font-bold text-xs text-orange-500 uppercase tracking-wide">
                      <span class="w-2 h-2 rounded-full bg-orange-500"></span>
                      Média
                    </span>
                    <span v-else-if="task.priority === 'LOW'" class="inline-flex items-center gap-1.5 font-bold text-xs text-slate-500 uppercase tracking-wide">
                      <span class="w-2 h-2 rounded-full bg-slate-400"></span>
                      Baixa
                    </span>
                  </TableCell>

                  <TableCell>
                    <span v-if="task.outside" class="px-2.5 py-1 rounded-full text-xs font-bold bg-orange-100 text-orange-700">
                      Sim
                    </span>
                    <span v-else class="px-2.5 py-1 rounded-full text-xs font-bold bg-slate-100 text-slate-500">
                      Não
                    </span>
                  </TableCell>

                  <TableCell>{{ new Date(task.due_date).toLocaleDateString() }}</TableCell>

                  <TableCell class="text-right">
                    <div class="flex items-center justify-end gap-3">
                      <button
                          @click="openDetails(task)"
                          class="text-slate-500 hover:opacity-70 transition-opacity bg-transparent border-0 p-0"
                          title="Ver Detalhes"
                      >
                        <Eye :size="20" />
                      </button>

                      <RouterLink v-if="task.status !== 'COMPLETED' && task.status !== 'CANCELLED'"
                          :to="{ name: 'task/edit', params: { id: task.id } }"
                          class="text-primary hover:opacity-70 transition-opacity"
                          title="Editar Tarefa"
                      >
                        <Pencil :size="20" />
                      </RouterLink>

                      <button
                          @click="confirmDelete(task)"
                          class="text-destructive hover:opacity-70 transition-opacity bg-transparent border-0 p-0"
                          title="Eliminar Tarefa"
                      >
                        <X :size="20" />
                      </button>
                    </div>
                  </TableCell>
                </TableRow>
              </TableBody>
            </Table>
          </CardContent>
        </Card>
      </div>
    </main>

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
          </div>
        </div>

        <div class="px-6 pb-6">
          <Button variant="outline" class="w-full" @click="showDetailsModal = false">Fechar</Button>
        </div>
      </div>
    </div>

    <!-- Delete Modal -->
    <div v-if="showDeleteModal" class="fixed inset-0 bg-black/50 flex items-center justify-center z-50">
      <div class="bg-white p-6 rounded-lg shadow-xl max-w-sm w-full mx-4 text-center">
        <AlertTriangle class="h-12 w-12 text-red-500 mx-auto mb-4" />
        <h3 class="text-lg font-bold">Confirmar Eliminação</h3>
        <p class="text-muted-foreground mt-2">Deseja mesmo eliminar esta tarefa <strong>{{ taskToDelete?.title }}</strong>?</p>
        <div class="flex gap-3 mt-6">
          <Button variant="outline" class="flex-1" @click="showDeleteModal = false" :disabled="isDeleting">Cancelar</Button>
          <Button variant="destructive" class="flex-1" @click="handleDelete" :disabled="isDeleting">
            {{ isDeleting ? 'A eliminar...' : 'Eliminar' }}
          </Button>
        </div>
      </div>
    </div>
  </div>
</template>

<style scoped>
</style>