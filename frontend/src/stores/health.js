import { defineStore } from 'pinia'
import axios from 'axios'
import { inject } from 'vue'

export const useHealthAPIStore = defineStore('api', () => {
    const API_BASE_URL = inject('apiBaseURL')

    const getHealtyAPI = async () => {
        const response = await axios.get(`${API_BASE_URL}/health`)
        console.log(response)
    }


    return{
        getHealtyAPI
    }
})