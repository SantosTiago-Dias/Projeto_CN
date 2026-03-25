<script setup>
import { ref, onMounted } from 'vue'
import { UserPen, Shield, Save, X, ArrowLeft, User } from 'lucide-vue-next'
import { useUserStore } from "@/stores/user.js"
import { Button } from "@/components/ui/button/index.ts"
import { toast } from "vue-sonner"
import { useRouter } from "vue-router"

const props = defineProps(['id'])
const router = useRouter()
const userStore = useUserStore()

const user = ref({
  name: '',
  role: ''
})

const roles = ['admin', 'worker']

const handleUpdate = async () => {
  try {
    let res = await userStore.editUser(props.id, user.value)
    if (res.status === 200) {
      toast.success('Utilizador editado com sucesso')
      router.push('/users')
    } else {
      toast.error('Ocorreu um erro ao tentar atualizar o utilizador')
    }
  } catch (error) {
    toast.error(error.response?.data?.message || error.message)
  }
}

onMounted(async () => {
  const fetchedUser = await userStore.showUser(props.id)
  // Garantir que mapeamos apenas os campos necessários para o form
  user.value = {
    name: fetchedUser.name,
    role: fetchedUser.role
  }
})
</script>

<template>
  <div class="max-w-2xl mx-auto p-6">
    <div class="flex items-center justify-between mb-8">
      <div class="flex items-center gap-4">
        <button @click="router.back()" class="p-2 hover:bg-slate-100 rounded-full transition-colors text-slate-600">
          <ArrowLeft class="w-5 h-5" />
        </button>
        <div>
          <h1 class="text-2xl font-bold text-slate-900">Editar Perfil</h1>
          <p class="text-sm text-slate-500 font-medium">Atualizar permissões e dados do utilizador #{{ props.id }}</p>
        </div>
      </div>
      <div class="bg-blue-50 p-3 rounded-full">
        <UserPen class="w-6 h-6 text-blue-600" />
      </div>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
      <form @submit.prevent="handleUpdate" class="p-8 space-y-6">

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

          <div class="space-y-2 md:col-span-2">
            <label class="flex items-center gap-2 text-sm font-semibold text-slate-700">
              <User class="w-4 h-4 text-blue-500" /> Nome do Utilizador
            </label>
            <input
                v-model="user.name"
                type="text"
                required
                class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-lg focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-all outline-none"
            >
          </div>

          <div class="space-y-2 md:col-span-2">
            <label class="flex items-center gap-2 text-sm font-semibold text-slate-700">
              <Shield class="w-4 h-4 text-amber-500" /> Nível de Acesso (Role)
            </label>
            <div class="relative">
              <select
                  v-model="user.role"
                  required
                  class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-lg focus:ring-2 focus:ring-blue-500/20 outline-none cursor-pointer appearance-none capitalize"
              >
                <option v-for="role in roles" :key="role" :value="role">
                  {{ role }}
                </option>
              </select>
              <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-3 text-slate-400">
                <svg class="h-4 w-4 fill-current" viewBox="0 0 20 20"><path d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" /></svg>
              </div>
            </div>
          </div>

        </div>

        <div class="flex items-center justify-end gap-3 pt-6 border-t border-slate-100 mt-4">
          <Button
              variant="ghost"
              type="button"
              class="text-slate-500 hover:bg-slate-100 flex items-center gap-2"
              @click="router.back()"
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