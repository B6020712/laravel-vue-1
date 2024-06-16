<template>
    <main class="bg-pink-100 h-svh grid grid-cols-12 grid-rows-12">
        <header class="bg-slate-50 col-span-12 text-3xl uppercase text-center content-center"><span class="hover:cursor-pointer">sa-ra-pad-pra-yhort</span></header>

        <section class="bg-neutral-100 row-span-11 col-span-2">
            <nav class="">
                <ul>
                    <li class="h-9 mt-2 px-4 content-center bg-amber-50"><RouterLink :to="{ name: 'home' }">Home</RouterLink></li>
                    <li class="h-9 mt-2 px-4 content-center bg-amber-50"><RouterLink :to="{ name: 'note' }">Note</RouterLink></li>
                </ul>
            </nav>
        </section>
        <section class="bg-purple-100 row-span-11 col-span-8 p-10">
            <main class="bg-green-50 h-full rounded-lg p-6">
                <RouterView />
            </main>
        </section>
        <section class="row-span-11 col-span-2">
            <ul>
                <li class="h-9 mt-2 content-center"><button type="button" @click="handleLogout" class="bg-orange-300 w-full h-full px-4 text-right">Logout</button></li>
            </ul>
        </section>
    </main>
</template>

<script lang="ts">
import { RouterLink, RouterView } from 'vue-router'
import { useUserStore } from '../stores/user'

export default {
    methods: {
        async handleLogout() {
            const userStore = useUserStore()
            await userStore.logout()
        }
    }
}
</script>