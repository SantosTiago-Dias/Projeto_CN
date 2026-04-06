import { useAPIStore } from './api'
import {defineStore} from "pinia";
import {computed, ref} from "vue";
export const useWeatherStore = defineStore('weather', () => {
    const apiStore = useAPIStore()

    const getWeather = async () => {
        const response = await apiStore.getWeather()
        console.log(response)
        return response.data


    }

    return{
        getWeather
    }
})