import { useAPIStore } from './api'
import {defineStore} from "pinia";
import {ref} from "vue";
export const useAuthStore = defineStore('auth', () => {
    const apiStore = useAPIStore()
    const currentUser = ref(undefined)
    const login = async (credentials) => {
        try
        {
            let data= await apiStore.postLogin(credentials)
            let token = data.token

            apiStore.setBearerToken(token)
        }
        catch (error)
        {
            console.log(error)
            throw error
        }



    }

    const logout = async () => {
        await apiStore.postLogout()

    }

    return{
        login,
        logout
    }
})