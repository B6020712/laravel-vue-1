<template>
    <main class="flex flex-col text-center">
        <div class="text-xl">Home</div>
    </main>
</template>

<script lang="ts">
import { useUserStore } from '../../stores/user'

export default {
    data() {
        return {
            user: {}
        }
    },
    mounted() {
        this.getUser()
    },
    methods: {
        async getUser() {
            const userStore = useUserStore()
            this.user = userStore.user
        }
    },
}
</script>