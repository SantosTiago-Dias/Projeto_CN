<script setup>
import {ref} from "vue";
import {Button} from "@/components/ui/button/index.ts";
import {useUserStore} from "@/stores/user.js";
import {toast} from "vue-sonner";
import router from "@/router/index.js";

const data=ref({
  name:null,
  email:null,
  password:null,
  role:null
})
const userStore=useUserStore()
const roles = ref(['admin','worker'])
const handleNewUser= async ()=>{
  try
  {
    let res =await userStore.storeUser(data.value)
    if (res.status ===201)
    {
      toast.success('Utilizador criado com sucesso')
      router.push('/users')
    }
    else
    {
      toast.error('Ocorreu um erro ao tentar criar o utilizador')
    }
  }
  catch (error) {
    toast.error(error.message)
  }

}
</script>

<template>
  <div class="container py-5">
    <div class="d-flex align-items-center gap-3 mb-4">
      <h2 class="h4 mb-0 fw-bold text-dark">Novo Utilizador</h2>
    </div>

    <div class="card shadow-sm border-0 overflow-hidden">
      <div class="card-body p-4 p-md-5">
        <form @submit.prevent="handleNewUser">
          <div class="row g-4">

            <div class="col-md-6 input-group-lg">
              <label class="form-label small fw-bold text-black">Nome: </label>
              <input v-model="data.name" type="text" class="form-control bg-light" placeholder="Nome" required>
            </div>

            <div class="col-md-6 input-group-lg">
              <label class="form-label small fw-bold text-black">Email: </label>
              <input v-model="data.email" type="email" class="form-control bg-light" placeholder="Email" required />
            </div>

            <div class="col-md-6 input-group-lg">
              <label class="form-label small fw-bold text-black">Password: </label>
              <input v-model="data.password" type="password" class="form-control bg-light" placeholder="password" required/>
            </div>

            <div class="col-md-6 input-group-lg">
              <label class="form-label small fw-bold text-black mr-2">Role:</label>
              <span class="input-group-text bg-light border-end-0"></span>
              <select v-model="data.role" class="form-select bg-light border-start-0" required>
                <option v-for="role in roles" :key="role" :value="role">{{ role }}</option>
              </select>
            </div>

            <div class="col-12 mt-5 d-flex gap-3 justify-content-end">
              <Button type="submit" class="btn btn-primary px-5 fw-medium">
                Criar Utilizador
              </Button>
            </div>

          </div>
        </form>
      </div>
    </div>
  </div>
</template>

<style scoped>

</style>