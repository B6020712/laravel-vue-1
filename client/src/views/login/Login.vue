<template>
    <main class="self-center">
        <form @submit.prevent="handleLogin">
            <div class="flex flex-col justify-between bg-gray-500 md:w-[500px] md:h-[700px] rounded-md p-14">
                <div>
                    <div class="text-4xl text-white text-center uppercase hover:cursor-default">Authentication</div>
                    <div class="text-md text-white mt-10 mb-1.5">Username</div>
                    <input v-model="form.username" class="w-full px-4 py-2 rounded-md focus:outline-none focus:border-blue-500 focus:ring-4 focus:ring-blue-500" type="text" name="username" id="username" placeholder="username">
                    <div class="text-md text-white mt-6 mb-1.5">Password</div>
                    <input v-model="form.password" class="w-full px-4 py-2 rounded-md focus:outline-none focus:border-blue-500 focus:ring-4 focus:ring-blue-500" type="password" name="password" id="password" placeholder="password">
                </div>
                <div>
                    <button type="submit" class="text-center rounded-md text-white uppercase bg-blue-300 hover:bg-blue-500 w-full h-12"><b>Login</b></button>
                </div>
            </div>
        </form>
    </main>
</template>

<script setup lang="ts">
import { ref } from 'vue'
import { useUserStore } from '/src/stores/user.ts'

const form = ref({
    username: "coby46@example.net",
    password: "password"
})

async function handleLogin() {
    const user = useUserStore()
    user.login(form.value)
}
</script>