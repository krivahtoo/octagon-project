<script setup>
import { RouterLink } from 'vue-router'
import { useLoginStore } from '../stores'

const login = useLoginStore()
</script>

<template>
  <nav class="relative w-full flex flex-wrap items-center justify-between py-4 bg-gray-100 text-gray-500 hover:text-gray-700 focus:text-gray-700 shadow-lg navbar navbar-expand-lg navbar-light ">
    <div class="container-fluid w-full flex flex-wrap items-center justify-between px-6">
      <div class="flex-grow items-center" >
        <!-- Left links -->
        <ul class="navbar-nav flex flex-row pl-0 list-style-none mr-auto">
          <li class="nav-item p-2" v-if="login.isLoggedIn">
            <RouterLink class="nav-link text-gray-500 hover:text-gray-700 focus:text-gray-700 p-0" to="/">Home</RouterLink>
          </li>
          <li class="nav-item p-2" v-if="login.isLoggedIn">
            <RouterLink class="nav-link text-gray-500 hover:text-gray-700 focus:text-gray-700 p-0" to="/">Profile</RouterLink>
          </li>
          <li class="nav-item p-2" v-if="!login.isLoggedIn">
            <RouterLink class="nav-link text-gray-500 hover:text-gray-700 focus:text-gray-700 p-0" to="/login">Login</RouterLink>
          </li>
          <li class="nav-item p-2" v-if="!login.isLoggedIn">
            <RouterLink class="nav-link text-gray-500 hover:text-gray-700 focus:text-gray-700 p-0" to="/register">Register</RouterLink>
          </li>
        </ul>
      </div>
    </div>
  </nav>
</template>
