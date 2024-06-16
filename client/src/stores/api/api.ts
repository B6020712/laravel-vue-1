import axios from 'axios';
import { defineStore } from 'pinia';

export const useApiStore = defineStore('api', {
    state() {
        return {
            base_url: import.meta.env.VITE_BACK_END_URL,
            port: import.meta.env.VITE_BACK_END_PORT,
            access_token: '',
            // type_token: '',
        }
    },
    getters: {
        apis(state) {
            axios.defaults.baseURL = `http://${state.base_url}:${state.port}/`
            axios.defaults.withCredentials = true
            axios.defaults.withXSRFToken = true
            axios.defaults.headers['Authorization'] = getCookie("Authorization") ? `${getCookie("Authorization")}` : ''
            return axios
        }
    },
})

function getCookie (cname: string) {
    let name = cname + "=";
    let ca = document.cookie.split(';');
    for(let i = 0; i < ca.length; i++) {
        let c = ca[i];
        while (c.charAt(0) == ' ') {
        c = c.substring(1);
    }
    if (c.indexOf(name) == 0) {
        return c.substring(name.length, c.length);
    }
    }
    return false;
}