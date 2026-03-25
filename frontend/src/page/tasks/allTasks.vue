<script setup>
import { onMounted, ref } from "vue";
import { Card, CardContent, CardHeader, CardTitle } from "@/components/ui/card/index.js";
import { Plus, Pencil, X, AlertTriangle } from "lucide-vue-next";
import { Table, TableBody, TableCell, TableHead, TableHeader, TableRow } from "@/components/ui/table/index.ts";
import { Button } from "@/components/ui/button/index.ts";
import {toast} from "vue-sonner";
import {useTasksStore} from "@/stores/tasks.js";
import {useRouter} from "vue-router";


const tasksStore = useTasksStore()
const tasks = ref([])
const isLoading = ref(false)
const router = useRouter()

const getAllTasks = async () => {
  try {
    let res = await tasksStore.getAllTasks()
    tasks.value = res.data
  } catch (error) {
    toast.error("Erro ao carregar tarefas")
    router.back()
  }
}

const showDeleteModal = ref(false)
const taskToDelete = ref(null)
const isDeleting = ref(false)

const confirmDelete = (user) => {
  taskToDelete.value = user
  showDeleteModal.value = true
}

const handleDelete = async () => {
  isDeleting.value = true
  try {
    let res = await tasksStore.deleteTask(taskToDelete.value.id)
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
            <Table>
              <TableHeader>
                <TableRow>
                  <TableHead>Título</TableHead>
                  <TableHead>Descrição</TableHead>
                  <TableHead>Status</TableHead>
                  <TableHead>Prioridade</TableHead>
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

                  <TableCell>{{ new Date(task.due_date).toLocaleDateString() }}</TableCell>

                  <TableCell class="text-right">
                    <div class="flex items-center justify-end gap-3">
                      <RouterLink
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

    <div v-if="showDeleteModal" class="fixed inset-0 bg-black/50 flex items-center justify-center z-50">
      <div class="bg-white p-6 rounded-lg shadow-xl max-w-sm w-full mx-4 text-center">
        <AlertTriangle class="h-12 w-12 text-red-500 mx-auto mb-4" />
        <h3 class="text-lg font-bold">Confirmar Eliminação</h3>
        <p class="text-muted-foreground mt-2">Deseja mesmo eliminar esta tarefa <strong>{{ taskToDelete?.name }}</strong>?</p>
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