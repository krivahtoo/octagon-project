<script setup>
import { useUserStore } from '../stores'

const store = useUserStore()
</script>

<template>
  <div class="flex justify-center mt-10">
    <div class="block p-10 rounded-lg shadow-lg bg-white h-1/3 w-2/4">
      <h3 class="text-gray-900 text-xl leading-tight font-medium mb-2">Account</h3>
      <h5 class="text-gray-700 text-lg mb-4">Name: {{ store.firstName }} {{ store.lastName }}</h5>
      <h5 class="text-gray-700 text-lg mb-4">Phone: {{ store.phone }}</h5>
    </div>
  </div>
</template>
