<script setup>
import {computed, onMounted, ref} from 'vue';
import {useWeatherStore} from "@/stores/weather.js";
import {toast} from "vue-sonner";

const weather = ref(null);
const loading = ref(true);
const weatherStore = useWeatherStore()

const emit = defineEmits(['weather-alert'])

const isRaining = computed(() => {
  if (!weather.value) return false;
  const condition = weather.value.weather[0].main.toLowerCase();
  return condition.includes('rain') || condition.includes('drizzle') || condition.includes('thunderstorm');
});

const fetchWeather = async () => {
  try {
    loading.value = true;
    weather.value = await weatherStore.getWeather()

    if (isRaining.value) {
      emit('weather-alert', 'Condições Adversas - Chuva detetada no local de trabalho')
    }

  } catch (error) {
    toast.error("Ocorreu um erro ao carregar o tempo")
    weather.value = null;
  } finally {
    loading.value = false;
  }
};

onMounted(() => {
  fetchWeather();
});
</script>

<template>
  <div class="p-4 bg-white rounded-lg shadow-md border-l-4" :class="isRaining ? 'border-yellow-500' : 'border-blue-500'">
    <div v-if="loading" class="text-gray-500 animate-pulse font-medium">
      A carregar condições meteorológicas...
    </div>

    <div v-else-if="weather" class="flex flex-col gap-2">
      <div class="flex items-center justify-between">
        <div>
          <h3 class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Condições Locais</h3>
          <p class="text-2xl font-bold text-gray-800">{{ Math.round(weather.main.temp) }}°C</p>
        </div>
        <img
            :src="`https://openweathermap.org/img/wn/${weather.weather[0].icon}@2x.png`"
            :alt="weather.weather[0].description"
            class="w-16 h-16"
        />
      </div>

      <p class="text-sm text-gray-600">
        Atualmente em <span class="font-medium">{{ weather.name }}</span>:
        <span class="italic text-capitalize">{{ weather.weather[0].description }}</span>
      </p>

      <div v-if="isRaining" class="mt-2 p-2 bg-yellow-50 rounded border border-yellow-200 flex items-start gap-2">
        <span class="text-lg">⚠️</span>
        <p class="text-xs text-yellow-800">
          <strong>Alerta Meteorológico:</strong> Chuva detetada. Os trabalhadores têm permissão para cancelar tarefas ao ar livre devido a "Condições Adversas".
        </p>
      </div>

      <div v-else class="mt-2 p-2 bg-green-50 rounded border border-green-200 flex items-start gap-2">
        <span class="text-lg">✅</span>
        <p class="text-xs text-green-800">
          Boas condições para realizar tarefas ao ar livre.
        </p>
      </div>
    </div>

    <div v-else class="text-red-500 text-sm font-medium">
      Dados meteorológicos indisponíveis no momento.
    </div>
  </div>
</template>
