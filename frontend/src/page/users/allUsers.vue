<script setup>
import { useUserStore } from "@/stores/user.js";
import { onMounted, ref } from "vue";
import { Card, CardContent, CardHeader, CardTitle } from "@/components/ui/card/index.js";
import { Plus, UserPenIcon, UserRoundXIcon, AlertTriangle } from "lucide-vue-next";
import { Table, TableBody, TableCell, TableHead, TableHeader, TableRow } from "@/components/ui/table/index.ts";
import { Button } from "@/components/ui/button/index.ts";
import {toast} from "vue-sonner";

const userStore = useUserStore()
const Users = ref([])
const isLoading = ref(false)

const getAllUser = async () => {
  try {
    let res = await userStore.getAllUsers()
    Users.value = res.data
  } catch (error) {
    console.error("Erro ao carregar utilizadores")
  }
}

const showDeleteModal = ref(false)
const userToDelete = ref(null)
const isDeleting = ref(false)

const confirmDelete = (user) => {
  userToDelete.value = user
  showDeleteModal.value = true
}

const handleDelete = async () => {
  isDeleting.value = true
  try {
    let res = await userStore.deleteUser(userToDelete.value.id)
    await getAllUser()
    showDeleteModal.value = false
    toast.success('Utilizador eliminado com sucesso')
  } catch (error) {
    toast.error('Ocorreu um erro a tentar eliminar o utilizador')
  } finally {
    isDeleting.value = false
  }
}

onMounted(() => {
  getAllUser()
})
</script>

<template>
  <div class="flex min-h-screen bg-slate-50/50">
    <main class="flex-1 flex flex-col">
      <div class="p-8 space-y-8">

        <div class="flex items-center justify-between">
          <div>
            <h2 class="text-2xl font-bold tracking-tight">Utilizadores</h2>
            <p class="text-muted-foreground font-medium">Gerencie os utilizadores registados no sistema</p>
          </div>
          <Button as-child>
            <RouterLink to="/user/create" class="flex items-center gap-2">
              <Plus class="h-4 w-4" /> <span>Adicionar novo utilizador</span>
            </RouterLink>
          </Button>
        </div>

        <Card class="border-none shadow-sm ring-1 ring-slate-200">
          <CardHeader>
            <CardTitle>Lista de Utilizadores</CardTitle>
          </CardHeader>
          <CardContent>
            <Table>
              <TableHeader>
                <TableRow>
                  <TableHead>Nome</TableHead>
                  <TableHead>Email</TableHead>
                  <TableHead>Função</TableHead>
                  <TableHead>Status</TableHead>
                  <TableHead class="text-right">Ações</TableHead>
                </TableRow>
              </TableHeader>
              <TableBody>
                <TableRow v-for="user in Users" :key="user.id">
                  <TableCell class="font-medium">{{ user.name }}</TableCell>
                  <TableCell>{{ user.email }}</TableCell>
                  <TableCell>
                    <span class="capitalize">{{ user.role }}</span>
                  </TableCell>
                  <TableCell>
                    <span :class="!user.deleted_at ? 'text-green-600' : 'text-red-600'">
                      {{ !user.deleted_at ? 'Ativo' : 'Inativo' }}
                    </span>
                  </TableCell>
                  <TableCell class="text-right">
                    <div class="flex items-center justify-end gap-3">
                      <RouterLink
                          :to="{ name: 'user/edit', params: { id: user.id } }"
                          class="text-primary hover:opacity-70 transition-opacity"
                          title="Editar"
                      >
                        <UserPenIcon :size="20" />
                      </RouterLink>

                      <button
                          @click="confirmDelete(user)"
                          class="text-destructive hover:opacity-70 transition-opacity bg-transparent border-0 p-0"
                          title="Eliminar"
                      >
                        <UserRoundXIcon :size="20" />
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
        <p class="text-muted-foreground mt-2">Deseja mesmo eliminar o utilizador <strong>{{ userToDelete?.name }}</strong>?</p>
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