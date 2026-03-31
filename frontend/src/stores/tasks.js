import {defineStore} from "pinia";
import {useAPIStore} from "@/stores/api.js";
import {ref} from "vue";

export const useTasksStore = defineStore('tasks',()=>{
    const apiStore = useAPIStore()
    let tasks = ref([])

    const getAllTasks = async () =>{
        let response = await apiStore.getAllTasks()
        return response.data
    }

    const showTask = async (id) =>{
        let response = await apiStore.showTask(id)
        return response.data
    }

    const showUserTasks = async (id) =>{
        let response = await apiStore.showUserTasks(id)
        tasks.value= response.data.data
    }

    const storeTask = async (data) =>{
        let response = await apiStore.storeTask(data)
        return response
    }

    const editTask = async (id,data) =>{
        console.log(data)
        let response = await apiStore.editTask(id,data)
        return response
    }

    const changeStatusTask = async (id,status) =>{
        let response = await apiStore.changeStatusTask(id,status)
        return response
    }

    const deleteTask= async (id) =>{
        let response = await apiStore.deleteTask(id)
        return response
    }

    return {
        getAllTasks,
        showTask,
        showUserTasks,
        storeTask,
        editTask,
        changeStatusTask,
        deleteTask,
        tasks
    }
})