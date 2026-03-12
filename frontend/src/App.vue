<script setup>
import {inject} from "vue";
import {useHealthAPIStore} from "@/stores/health.js";

const API_BASE_URL = inject('apiBaseURL')
const healthAPI= useHealthAPIStore()

console.log(API_BASE_URL)
healthAPI.getHealtyAPI()


</script>

<template>
  TESTE
</template>