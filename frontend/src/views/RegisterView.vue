<script setup>
import { reactive, onMounted } from 'vue'
import { RouterLink, useRouter } from 'vue-router'
import { useLoginStore, useUserStore } from '../stores'

const store = useLoginStore()
const router = useRouter()

const local_store = reactive({
  data: {
    phone: '',
    first_name: '',
    last_name: '',
    password: '',
  },
  confirm_pass: '',
  message: ''
})

async function register() {
  if (local_store.data.password !== local_store.confirm_pass || local_store.data.password == '') {
    local_store.message = 'Password confirmation do not match'
    local_store.data.password = ''
    local_store.confirm_pass = ''
    return
  }
  const response = await fetch(`${store.apiServer}/api/register`, {
    method: 'POST',
    headers: {
      'Content-Type': 'application/json'
    },
    body: JSON.stringify(local_store.data)
  })
  const res = await response.json()
  console.log(res)
  if (response.ok) {
    router.replace('/login')
  } else {
    local_store.message = 'Oops something went wrong. Please try again later'
    local_store.data.password = ''
    local_store.confirm_pass = ''
  }
}

</script>

<template>
  <section class="h-screen">
    <div class="px-6 h-full text-gray-800">
      <div class="flex xl:justify-center lg:justify-between justify-center items-center flex-wrap h-full g-6" >
        <div class="grow-0 shrink-1 md:shrink-0 basis-auto xl:w-6/12 lg:w-6/12 md:w-9/12 mb-12 md:mb-0" >
          <img
            src="https://mdbcdn.b-cdn.net/img/Photos/new-templates/bootstrap-login-form/draw2.webp"
            class="w-full"
            alt="Sample image"
          />
        </div>
        <div class="xl:ml-20 xl:w-5/12 lg:w-5/12 md:w-8/12 mb-12 md:mb-0">
          <form @submit.prevent="register">
            <div class="bg-red-100 rounded-lg py-5 px-6 mb-3 text-base text-red-700 inline-flex items-center w-full" role="alert" v-if="local_store.message !== ''">
              <svg aria-hidden="true" focusable="false" data-prefix="fas" data-icon="times-circle" class="w-4 h-4 mr-2 fill-current" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512">
                <path fill="currentColor" d="M256 8C119 8 8 119 8 256s111 248 248 248 248-111 248-248S393 8 256 8zm121.6 313.1c4.7 4.7 4.7 12.3 0 17L338 377.6c-4.7 4.7-12.3 4.7-17 0L256 312l-65.1 65.6c-4.7 4.7-12.3 4.7-17 0L134.4 338c-4.7-4.7-4.7-12.3 0-17l65.6-65-65.6-65.1c-4.7-4.7-4.7-12.3 0-17l39.6-39.6c4.7-4.7 12.3-4.7 17 0l65 65.7 65.1-65.6c4.7-4.7 12.3-4.7 17 0l39.6 39.6c4.7 4.7 4.7 12.3 0 17L312 256l65.6 65.1z"></path>
              </svg>
              {{ local_store.message }}
            </div>
            <!-- Phone input -->
            <div class="mb-6">
              <input
                type="text"
                v-model="local_store.data.phone"
                class="form-control block w-full px-4 py-2 text-xl font-normal text-gray-700 bg-white bg-clip-padding border border-solid border-gray-300 rounded transition ease-in-out m-0 focus:text-gray-700 focus:bg-white focus:border-blue-600 focus:outline-none"
                id="exampleFormControlInput2"
                placeholder="Phone number"
              />
            </div>
            <!-- First name input -->
            <div class="mb-6">
              <input
                type="text"
                v-model="local_store.data.first_name"
                class="form-control block w-full px-4 py-2 text-xl font-normal text-gray-700 bg-white bg-clip-padding border border-solid border-gray-300 rounded transition ease-in-out m-0 focus:text-gray-700 focus:bg-white focus:border-blue-600 focus:outline-none"
                placeholder="First Name"
              />
            </div>
            <!-- Last name input -->
            <div class="mb-6">
              <input
                type="text"
                v-model="local_store.data.last_name"
                class="form-control block w-full px-4 py-2 text-xl font-normal text-gray-700 bg-white bg-clip-padding border border-solid border-gray-300 rounded transition ease-in-out m-0 focus:text-gray-700 focus:bg-white focus:border-blue-600 focus:outline-none"
                placeholder="Last Name"
              />
            </div>

            <!-- Password input -->
            <div class="mb-6">
              <input
                type="password"
                v-model="local_store.data.password"
                class="form-control block w-full px-4 py-2 text-xl font-normal text-gray-700 bg-white bg-clip-padding border border-solid border-gray-300 rounded transition ease-in-out m-0 focus:text-gray-700 focus:bg-white focus:border-blue-600 focus:outline-none"
                id="exampleFormControlInput2"
                placeholder="Password"
              />
            </div>

            <!-- Password confirm input -->
            <div class="mb-6">
              <input
                type="password"
                v-model="local_store.confirm_pass"
                class="form-control block w-full px-4 py-2 text-xl font-normal text-gray-700 bg-white bg-clip-padding border border-solid border-gray-300 rounded transition ease-in-out m-0 focus:text-gray-700 focus:bg-white focus:border-blue-600 focus:outline-none"
                placeholder="Confirm Password"
              />
            </div>

            <div class="text-center lg:text-left">
              <button
                type="submit"
                class="inline-block px-7 py-3 bg-blue-600 text-white font-medium text-sm leading-snug uppercase rounded shadow-md hover:bg-blue-700 hover:shadow-lg focus:bg-blue-700 focus:shadow-lg focus:outline-none focus:ring-0 active:bg-blue-800 active:shadow-lg transition duration-150 ease-in-out"
              >
                Register
              </button>
              <p class="text-sm font-semibold mt-2 pt-1 mb-0">
                Already have an account?
                <RouterLink
                  to="/login"
                  class="text-red-600 hover:text-red-700 focus:text-red-700 transition duration-200 ease-in-out"
                  >Login</RouterLink>
              </p>
            </div>
          </form>
        </div>
      </div>
    </div>
  </section>
</template>
