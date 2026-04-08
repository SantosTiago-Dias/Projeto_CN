<script setup>
import { ref, computed } from 'vue'
import { X, Upload, ImageIcon, CheckCircle2, Loader2 } from 'lucide-vue-next'
import { Button } from "@/components/ui/button"
import { toast } from "vue-sonner"

const props = defineProps({
  task: Object,
  isOpen: Boolean
})

const emit = defineEmits(['close', 'confirm'])

const fileInput = ref(null)
const previewUrl = ref(null)
const selectedFile = ref(null)
const isUploading = ref(false)

const handleFileChange = (event) => {
  const file = event.target.files[0]
  if (file) {
    processFile(file)
  }
}

const processFile = (file) => {
  if (!file.type.startsWith('image/')) {
    toast.error('Por favor, selecione um ficheiro de imagem.')
    return
  }
  selectedFile.value = file
  previewUrl.value = URL.createObjectURL(file)
}

const removeImage = () => {
  selectedFile.value = null
  previewUrl.value = null
  if (fileInput.value) fileInput.value.value = ''
}

const handleConfirm = async () => {
  if (!selectedFile.value) return

  isUploading.value = true
  try {
    emit('confirm', { taskId: props.task.id ,file: selectedFile.value })
    removeImage()
  } catch (error) {
    console.log(error)
    toast.error('Erro ao enviar a prova de trabalho.')
  } finally {
    isUploading.value = false
  }
}
</script>

<template>
  <div v-if="isOpen" class="fixed inset-0 bg-black/60 backdrop-blur-sm flex items-center justify-center z-[60] p-4">
    <div class="bg-white rounded-3xl shadow-2xl max-w-md w-full overflow-hidden animate-in fade-in zoom-in duration-200">

      <div class="p-6 border-b border-slate-100 flex justify-between items-center">
        <h3 class="text-xl font-bold text-slate-900">Finalizar Tarefa</h3>
        <button @click="emit('close')" class="p-2 hover:bg-slate-100 rounded-full transition-colors">
          <X :size="20" class="text-slate-500" />
        </button>
      </div>

      <div class="p-6 space-y-6">
        <div class="bg-blue-50 p-4 rounded-2xl">
          <p class="text-sm text-blue-700 font-medium italic">
            "{{ task?.title }}"
          </p>
          <p class="text-xs text-blue-500 mt-1 uppercase font-bold tracking-tighter">Prova de trabalho obrigatória</p>
        </div>

        <div
            @click="fileInput.click()"
            class="relative border-2 border-dashed rounded-2xl transition-all cursor-pointer group flex flex-col items-center justify-center min-h-[200px]"
            :class="previewUrl ? 'border-emerald-500 bg-emerald-50' : 'border-slate-200 hover:border-blue-400 hover:bg-slate-50'"
        >
          <input
              type="file"
              ref="fileInput"
              class="hidden"
              accept="image/*"
              @change="handleFileChange"
          />

          <div v-if="!previewUrl" class="text-center p-6">
            <div class="w-12 h-12 bg-blue-100 text-blue-600 rounded-full flex items-center justify-center mx-auto mb-3 group-hover:scale-110 transition-transform">
              <Upload :size="24" />
            </div>
            <p class="text-sm font-bold text-slate-700">Clique para carregar foto</p>
            <p class="text-xs text-slate-400 mt-1">PNG, JPG ou JPEG (Máx. 5MB)</p>
          </div>

          <div v-else class="w-full h-full p-2 relative">
            <img :src="previewUrl" class="w-full h-48 object-cover rounded-xl shadow-inner" />
            <button
                @click.stop="removeImage"
                class="absolute top-4 right-4 bg-rose-500 text-white p-1.5 rounded-full shadow-lg hover:bg-rose-600 transition-colors"
            >
              <X :size="16" />
            </button>
          </div>
        </div>
      </div>

      <div class="p-6 bg-slate-50 flex gap-3">
        <Button variant="ghost" class="flex-1" @click="emit('close')" :disabled="isUploading">
          Cancelar
        </Button>
        <Button
            class="flex-1 bg-emerald-600 hover:bg-emerald-700 text-white gap-2"
            :disabled="!selectedFile || isUploading"
            @click="handleConfirm"
        >
          <Loader2 v-if="isUploading" class="animate-spin" :size="18" />
          <CheckCircle2 v-else :size="18" />
          {{ isUploading ? 'A enviar...' : 'Concluir' }}
        </Button>
      </div>
    </div>
  </div>
</template>