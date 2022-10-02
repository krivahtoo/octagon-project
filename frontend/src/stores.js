import { ref } from 'vue'
import { defineStore } from 'pinia'

export const useLoginStore = defineStore('login', () => {
  const isLoggedIn = ref(false)
  return { isLoggedIn }
})
