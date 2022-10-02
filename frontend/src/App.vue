<script setup>
import { RouterLink, RouterView } from 'vue-router'
import NavBar from './components/NavBar.vue'
import { useLoginStore, useUserStore } from '@/stores'
import { onMounted, onBeforeMount } from 'vue'

const store = useLoginStore()
const user = useUserStore()

onBeforeMount(async () => {
  const token = localStorage.getItem('token')
  const expiry = localStorage.getItem('token_expiry')
  if (token) {
    if (Math.floor(Date.now()/1000)-parseInt(expiry) > 0) {
      localStorage.clear()
      return
    }
    store.token = token
  }
  if (store.token !== '') {
    const response = await fetch(`${store.apiServer}/api/user`, {
      method: 'GET',
      headers: {
        'Authorization': `Bearer ${token}`
      }
    })
    if (response.ok) {
      const res = await response.json()
      store.isLoggedIn = true
      console.log(res)
      user.setUser(res)
    }
  }
})

</script>

<template>
  <NavBar v-if="store.isLoggedIn"/>

  <RouterView />
</template>
