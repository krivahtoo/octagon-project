import { ref } from 'vue'
import { defineStore } from 'pinia'

export const useLoginStore = defineStore('login', () => {
  const isLoggedIn = ref(false)
  const token = ref('')
  const apiServer = ref('http://localhost:8000')
  function logout() {
    isLoggedIn.value = false
    token.value = ''
  }
  return { isLoggedIn, token, apiServer, logout }
})

export const useUserStore = defineStore('user', () => {
  const phone = ref('0712345678')
  const firstName = ref('John')
  const lastName = ref('Doe')
  function setUser(obj) {
    phone.value = obj.phone
    firstName.value = obj.first_name
    lastName.value = obj.last_name
  }
  return { phone, firstName, lastName, setUser }
})

