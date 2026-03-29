
<script setup lang="ts">
import { ref } from 'vue'
import { Button } from '@/components/ui/button'
import { Card, CardContent, CardFooter, CardHeader, CardTitle } from '@/components/ui/card'
import { Input } from '@/components/ui/input'
import { Label } from '@/components/ui/label'
import { useAuthStore } from '@/stores/auth'
import { toast } from "vue-sonner";
import {useRouter} from "vue-router";

const authStore = useAuthStore()
const router = useRouter()
const formData = ref({
  email: '',
  password: '',
})

const onSubmit = async () => {
  toast.promise(() => authStore.login(formData.value), {
    loading: 'Loading...',
    success: () => {
      if (authStore.isAdmin) {
        router.push({ name: 'dashboardAdmin' })
      } else {
        router.push({ name: 'dashboard' })
      }
      return `Login efetuado com sucesso`
    },
    error: (data: any) => {
      return data.message
    },
  });
}

</script>

<template>
  <div class="flex items-center justify-center min-h-screen bg-slate-50">
    <Card class="w-full max-w-md">
      <CardHeader class="space-y-1">
        <CardTitle class="text-2xl font-bold text-center">Login</CardTitle>

      </CardHeader>
      <form @submit.prevent ="onSubmit">
        <CardContent class="grid gap-4">
          <div class="grid gap-2">
            <Label for="email">Email</Label>
            <Input
                id="email"
                type="email"
                placeholder="m@example.com"
                v-model="formData.email"
                required
            />
          </div>
          <div class="grid gap-2">
            <Label for="password">Password</Label>
            <Input
                id="password"
                type="password"
                v-model="formData.password"
                required
            />
          </div>
        </CardContent>
        <CardFooter class="flex flex-col">
          <Button class="w-full" type="submit">
            Login
          </Button>
        </CardFooter>
      </form>
    </Card>
  </div>
</template>