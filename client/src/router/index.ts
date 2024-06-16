import { createRouter, createWebHistory } from 'vue-router'
import LoginLayout from '@/layouts/login.vue'
import DefaultLayout from '@/layouts/default.vue'
import LoginView from '@/views/login/Login.vue'

import HomeView from '@/views/home/Home.vue'
import NoteView from '@/views/note/Note.vue'

import { useUserStore } from '@/stores/user'

const routes = [
  { 
    path: '/',
    component: DefaultLayout,
    children: [
      {
        path: '',
        name: 'home',
        component: HomeView,
        meta: { requiresAuth: true },
      },
      {
        path: 'note',
        name: 'note',
        component: NoteView,
        meta: { requiresAuth: true },
      },  
    ]
  },
  {
    path: '/login',
    component: LoginLayout,
    children: [
      {
        path: '',
        name: 'login',
        component: LoginView,
        meta: { requiresAuth: false },
      }
    ]
  }
]

const router = createRouter({
  history: createWebHistory(import.meta.env.BASE_URL),
  routes
})

router.beforeEach(async (to, from) => {
  const user = useUserStore()
  
  if (to.meta.requiresAuth && user.isLogin === false) {
    await user.getUsers()

    if (! user.user.name) {
      return {
        name: 'login',
      }
    }
  }
})

export default router
