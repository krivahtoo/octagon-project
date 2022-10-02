<script setup>
import { RouterLink, RouterView } from 'vue-router'
import NavBar from './components/NavBar.vue'
import { useLoginStore } from '@/stores'

const login = useLoginStore()
</script>

<template>
  <NavBar v-if="login.isLoggedIn"/>

  <RouterView />
</template>
