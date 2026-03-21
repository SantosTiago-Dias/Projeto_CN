import { defineStore } from 'pinia'
import axios from 'axios'
import { inject } from 'vue'

export const useAPIStore = defineStore('api', () => {
    const API_BASE_URL = inject('apiBaseURL')

    //set Barear
    const setBearerToken = (token) => {
        axios.defaults.headers.common['Authorization'] = `Bearer ${token}`
    }

    const removeBearerToken = () => {
        delete axios.defaults.headers.common['Authorization']
    }

    // AUTH
    const postLogin = async (credentials) => {
        const response = await axios.post(`${API_BASE_URL}/login`, credentials).catch(error => { throw error.response.data.message })
        return response.data
    }
    const postLogout = async () => {
        await axios.post(`${API_BASE_URL}/logout`)
    }

    return {
        postLogin,
        postLogout,
        setBearerToken,
        removeBearerToken
    }
})