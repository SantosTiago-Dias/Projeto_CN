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
        const response = await axios.post(`${API_BASE_URL}/login`, credentials).catch(error => { throw error.response.data })
        localStorage.setItem('token',response.data.token)
        return response.data
    }
    const postLogout = async () => {
        localStorage.removeItem('token')
        await axios.post(`${API_BASE_URL}/logout`)
    }

    const me = () => {
       return axios.get(`${API_BASE_URL}/me`)
    }

    /*USERS*/

    const getAllUsers= () => {
        return axios.get(`${API_BASE_URL}/users`)
    }

    const getAllWorkers = () =>{
        return axios.get(`${API_BASE_URL}/users/workers`)
    }

    const showUser= (id) => {
        return axios.get(`${API_BASE_URL}/users/${id}`)
    }

    const storeUser = (data) =>{
        return axios.post(`${API_BASE_URL}/users`, data).catch(error => { throw error.response.data })
    }

    const editUser = (id,data) =>{
        return axios.put(`${API_BASE_URL}/users/${id}`, data).catch(error => { throw error.response.data })
    }

    const deleteUser = (id) =>{
        return axios.delete(`${API_BASE_URL}/users/${id}`)
    }

    /*USERS*/

    const getAllTasks= () => {
        return axios.get(`${API_BASE_URL}/tasks`)
    }

    const showTask = (id) =>{
        return axios.get(`${API_BASE_URL}/tasks/${id}`)
    }
    const showUserTasks = () =>{
        return axios.get(`${API_BASE_URL}/mytasks`)
    }

    const storeTask =  (data) =>{
        return axios.post(`${API_BASE_URL}/tasks`, data).catch(error => { throw error.response.data })
    }

    const editTask = (id,data) =>{
        console.log(data)
        return axios.put(`${API_BASE_URL}/tasks/${id}`, data).catch(error => { throw error.response.data })
    }

    const changeStatusTask = (id,data) =>{
        return axios.put(`${API_BASE_URL}/tasks/${id}/changestatus`, {
            'status':data
        })
    }


    const deleteTask = (id) =>{
        return axios.delete(`${API_BASE_URL}/tasks/${id}`)
    }

    const getNotifications = () =>{
        return axios.get(`${API_BASE_URL}/notifications`)
    }

    const markAsRead = (id) => {
       return axios.post(`${API_BASE_URL}/notifications/${id}`)
    }

    return {
        postLogin,
        postLogout,
        setBearerToken,
        removeBearerToken,
        me,
        getAllUsers,
        getAllWorkers,
        showUser,
        storeUser,
        editUser,
        deleteUser,
        getAllTasks,
        showTask,
        showUserTasks,
        storeTask,
        editTask,
        changeStatusTask,
        deleteTask,
        getNotifications,
        markAsRead
    }
})