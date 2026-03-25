<script setup>
import { ref } from "vue";
import { Button } from "@/components/ui/button/index.ts";
import { useUserStore } from "@/stores/user.js";
import { toast } from "vue-sonner";
import { useRouter } from "vue-router";
import { UserPlus, User, Mail, Lock, ShieldCheck, ArrowLeft, Save, X } from "lucide-vue-next";

const router = useRouter();
const userStore = useUserStore();

const data = ref({
  name: '',
  email: '',
  password: '',
  role: ''
});

const roles = ref(['admin', 'worker']);

const handleNewUser = async () => {
  try {
    let res = await userStore.storeUser(data.value);
    if (res.status === 201 || res.status === 200) {
      toast.success('Utilizador criado com sucesso');
      router.push('/users');
    } else {
      toast.error('Ocorreu um erro ao tentar criar o utilizador');
    }
  } catch (error) {
    toast.error(error.response?.data?.message || error.message);
  }
};
</script>

<template>
  <div class="max-w-2xl mx-auto p-6">
    <div class="flex items-center justify-between mb-8">
      <div class="flex items-center gap-4">
        <button @click="router.back()" class="p-2 hover:bg-slate-100 rounded-full transition-colors text-slate-600">
          <ArrowLeft class="w-5 h-5" />
        </button>
        <div>
          <h1 class="text-2xl font-bold text-slate-900">Novo Utilizador</h1>
          <p class="text-sm text-slate-500 font-medium italic">Registe um novo membro na plataforma</p>
        </div>
      </div>
      <div class="bg-blue-50 p-3 rounded-full">
        <UserPlus class="w-6 h-6 text-blue-600" />
      </div>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
      <form @submit.prevent="handleNewUser" class="p-8 space-y-6">

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

          <div class="space-y-2">
            <label class="flex items-center gap-2 text-sm font-semibold text-slate-700">
              <User class="w-4 h-4 text-blue-500" /> Nome Completo
            </label>
            <input
                v-model="data.name"
                type="text"
                placeholder="Ex: João Silva"
                required
                class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-lg focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-all outline-none"
            >
          </div>

          <div class="space-y-2">
            <label class="flex items-center gap-2 text-sm font-semibold text-slate-700">
              <Mail class="w-4 h-4 text-emerald-500" /> Endereço de Email
            </label>
            <input
                v-model="data.email"
                type="email"
                placeholder="joao@exemplo.com"
                required
                class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-lg focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-all outline-none"
            >
          </div>

          <div class="space-y-2">
            <label class="flex items-center gap-2 text-sm font-semibold text-slate-700">
              <Lock class="w-4 h-4 text-rose-500" /> Password
            </label>
            <input
                v-model="data.password"
                type="password"
                placeholder="••••••••"
                required
                class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-lg focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-all outline-none"
            >
          </div>

          <div class="space-y-2">
            <label class="flex items-center gap-2 text-sm font-semibold text-slate-700">
              <ShieldCheck class="w-4 h-4 text-amber-500" /> Função (Role)
            </label>
            <select
                v-model="data.role"
                required
                class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-lg focus:ring-2 focus:ring-blue-500/20 outline-none cursor-pointer appearance-none"
            >
              <option value="" disabled selected>Selecionar função...</option>
              <option v-for="role in roles" :key="role" :value="role" class="capitalize">
                {{ role }}
              </option>
            </select>
          </div>

        </div>

        <div class="flex items-center justify-end gap-3 pt-6 border-t border-slate-100 mt-4">
          <Button
              variant="ghost"
              type="button"
              class="text-slate-600 hover:bg-slate-100 flex items-center gap-2"
              @click="router.back()"
          >
            <X class="w-4 h-4" /> Cancelar
          </Button>
          <Button
              type="submit"
              class="bg-blue-600 hover:bg-blue-700 text-white px-10 flex items-center gap-2 shadow-md shadow-blue-500/20 transition-all active:scale-95"
          >
            <Save class="w-4 h-4" /> Criar Utilizador
          </Button>
        </div>

      </form>
    </div>
  </div>
</template>

<style scoped>
/* Transição suave para o foco nos inputs */
input, select {
  transition: all 0.2s ease-in-out;
}
</style>