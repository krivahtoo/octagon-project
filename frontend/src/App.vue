<script setup>
import { RouterLink, RouterView } from 'vue-router'
import NavBar from './components/NavBar.vue'
import { useLoginStore, useUserStore } from '@/stores'
import { onMounted, onBeforeMount } from 'vue'

const store = useLoginStore()
const user = useUserStore()

onBeforeMount(async () => {
  const token = localStorage.getItem('token')
  if (token) {
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
