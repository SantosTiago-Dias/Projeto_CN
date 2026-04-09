import { useAPIStore } from './api'
import {defineStore} from "pinia";

export const useNotificaitonStore = defineStore('notification', () => {
    const apiStore = useAPIStore()

    const notifications = async () =>{
        const res = await apiStore.getNotifications()
        return Object.values(res.data.data)
    }

    const markAsRead = async ($id) =>{
        const res =await apiStore.markAsRead($id)
        return res.status
    }

    return{
        notifications,
        markAsRead
    }
})