import { useAPIStore } from './api'
import {defineStore} from "pinia";
import {computed, ref} from "vue";
export const useAuthStore = defineStore('auth', () => {
    const apiStore = useAPIStore()
    const currentUser = ref(undefined)
    const login = async (credentials) => {
        let data = await apiStore.postLogin(credentials)

        let token = data.token
        apiStore.setBearerToken(token)
        await getUser()
        return currentUser.value

    }

    const logout = async () => {
        await apiStore.postLogout()

    }

    const loggedUser = async ()=>{
        currentUser.value = await apiStore.me()
    }

    const isAuthenticated = async () => {
        let token = localStorage.getItem('token')

        if (!token) return null

        try {
            apiStore.setBearerToken(token)
            const data = await apiStore.me()

            currentUser.value = data.data
            return true
        } catch (error) {
            localStorage.removeItem('token')
            return false
        }
    }

    const isAdmin = computed(() => {
        console.log(currentUser.value.role === 'admin')
        return currentUser.value?.role === 'admin'
    })

    const getUser = async () => {
        try {
            const data = await apiStore.me()
            currentUser.value = data.data
        } catch (error) {
            throw error
        }
    }

    return{
        login,
        logout,
        loggedUser,
        isAuthenticated,
        isAdmin,
        getUser,
        currentUser
    }
})