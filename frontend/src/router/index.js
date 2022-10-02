import { createRouter, createWebHistory } from 'vue-router'
import HomeView from '../views/HomeView.vue'
import { useLoginStore } from '../stores'

const router = createRouter({
  history: createWebHistory(import.meta.env.BASE_URL),
  routes: [
    {
      path: '/',
      name: 'home',
      component: HomeView
    },
    {
      path: '/about',
      name: 'about',
      component: () => import('../views/AboutView.vue')
    },
    {
      path: '/login',
      name: 'login',
      component: () => import('../views/LoginView.vue')
    },
    {
      path: '/register',
      name: 'register',
      component: () => import('../views/RegisterView.vue')
    },
    {
      path: '/profile',
      name: 'profile',
      component: () => import('../views/ProfileView.vue')
    }
  ]
})

router.beforeEach(async (to, from) => {
  const store = useLoginStore()
  if (
    // make sure the user is authenticated
    !store.isLoggedIn &&
    // ❗️ Avoid an infinite redirect
    !['login','register'].includes(to.name)
  ) {
    // redirect the user to the login page
    return { name: 'login' }
  }
})

export default router
