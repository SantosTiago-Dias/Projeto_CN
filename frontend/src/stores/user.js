import {defineStore} from "pinia";
import {useAPIStore} from "@/stores/api.js";

export const useUserStore = defineStore('user',()=>{
    const apiStore = useAPIStore()

    const getAllUsers = async () =>{
        let response = await apiStore.getAllUsers()
        return response.data
    }

    const showUser = async (id) =>{
        let response = await apiStore.showUser(id)
        return response.data
    }

    const storeUser = async (data) =>{
        let response = await apiStore.storeUser(data)
        return response
    }

    const editUser = async (id,data) =>{
        let response = await apiStore.editUser(id,data)
        return response
    }

    const deleteUser= async (id) =>{
        let response = await apiStore.deleteUser(id)
        return response
    }

    return {
        getAllUsers,
        showUser,
        storeUser,
        editUser,
        deleteUser
    }
})