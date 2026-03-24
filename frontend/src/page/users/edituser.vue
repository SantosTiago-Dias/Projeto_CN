<script setup>
import { ref, onMounted } from 'vue'
import { UserPen, Mail, Shield, Save, X, Lock } from 'lucide-vue-next'
import {useUserStore} from "@/stores/user.js";
import {Button} from "@/components/ui/button/index.ts";
import {toast} from "vue-sonner";
import {useRouter} from "vue-router";

const props = defineProps(['id'])
const router = useRouter()

const user = ref({
  name: '',
  role: ''
})

const userStore=useUserStore()
const roles = ['admin', 'worker']

const handleUpdate = async () => {
  try
  {
    let res = await userStore.editUser(props.id,user.value)
    console.log(res)
    if (res.status === 200) {
      toast.success('Utilizador editado com sucesso')
      router.push('/users')
    } else {
      toast.error('Ocorreu um erro ao tentar atualizar o utilizador')
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
  user.value = await userStore.showUser(props.id)
})



</script>

<template>
  <div class="container py-5">
    <div class="card shadow-sm border-0 rounded-3 overflow-hidden">
      <div class="card-header bg-white border-bottom py-3 d-flex align-items-center justify-content-between">
        <div class="d-flex align-items-center gap-2">
          <UserPen class="text-primary" :size="20" />
          <h5 class="mb-0 fw-bold">Editar Utilizador : {{props.id}}</h5>
        </div>


      <div class="card-body p-4">
        <form @submit.prevent="handleUpdate">
          <div class="row g-4">

            <div class="col-md-6">
              <label class="form-label small fw-bold text-black">Nome: </label>
              <input v-model="user.name" type="text" class="form-control bg-light" required>
            </div>

            <div class="col-md-6">
              <div class="input-group">

              <label class="form-label small fw-bold text-black mr-2">Role</label>

                <select v-model="user.role" class="form-select bg-light">
                  <option v-for="role in roles" :key="role" :value="role">{{ role }}</option>
                </select>
              </div>
            </div>

            <div class="col-12 mt-4 d-flex gap-2 justify-content-end">
              <Button variant="destructive" class="btn btn-outline-secondary px-4 d-flex align-items-center gap-2" @click="goBack">
                <X :size="18" /> Cancelar
              </Button>
              <Button type="submit" class="btn btn-primary px-4 d-flex align-items-center gap-2 shadow-sm ml-2">
                <Save :size="18" /> Guardar Alterações
              </Button>
            </div>

          </div>
        </form>
      </div>
      </div>
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