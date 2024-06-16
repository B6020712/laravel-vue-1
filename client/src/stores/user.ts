import { defineStore } from "pinia"
import { useApiStore } from './api/api';
import router from "@/router";

export const useUserStore = defineStore('user', {
    state: () => {
        return {
            user: {} as UserInfo || null,
            isLogin: false,
        }
    },
    actions: {
        async login (userCredential: UserCredential) {
            const { username, password } = userCredential
            const api = useApiStore()
            const csrfToken = await api.apis.get('sanctum/csrf-cookie/')

            if (csrfToken) {
                await api.apis.post('api/login', { email: username, password: password })
                .then((res) => {
                    if (res.status === 200) {
                        // document.cookie = `authentication=${res.data.token_type} ${res.data.access_token}; path=/; Secure; HttpOnly;`
                        document.cookie = `Authorization=${res.data.token_type} ${res.data.access_token}; path=/; Secure;`
                        this.isLogin = true
                        router.push({ name: 'home' })
                    }
                }).catch(() => {
                    this.user = {} as UserInfo
                    this.isLogin = false
                })
            }
        },
        async logout() {
            const api = useApiStore()
            await api.apis.post('api/logout')
            .then(() => {
                this.user = {} as UserInfo
                this.isLogin = false
            }).catch(() => {
                this.user = {} as UserInfo
                this.isLogin = false
            })
            document.cookie = "Authorization=; path=/; Secure;"
            router.push({ name: 'login' })
        },
        async getUsers() {
            const api = useApiStore()
            await api.apis.get('api/user')
            .then((res) => {
                if (res.status === 200) {
                    let { name: fullname, email } = res.data.user
                    this.user = { name: fullname , email: email }
                    this.isLogin = true
                }
            }).catch(() => {
                this.user = {} as UserInfo
                this.isLogin = false
            })
        }
    }
})

interface UserInfo {
    name: string
    email: string
}

interface UserCredential {
    username: string
    password: string
}