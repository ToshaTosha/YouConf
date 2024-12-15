<template>
  <div class="flex flex-col min-h-screen">
    <header
      class="bg-gray-800 text-white flex items-center justify-between p-4"
    >
      <div class="flex items-center">
        <Link href="/" class="text-lg font-bold">
          <!-- <img src="/path/to/logo.png" alt="Logo" class="h-8 mr-2" /> -->
          YouConf
        </Link>
      </div>
      <div class="flex items-center space-x-4">
        <Link
          v-if="user_data"
          :href="`/user/${user_data.id}`"
          class="hover:text-gray-400"
        >
          <svg
            class="h-8 w-8 text-gray-500"
            width="24"
            height="24"
            viewBox="0 0 24 24"
            stroke-width="2"
            stroke="currentColor"
            fill="none"
            stroke-linecap="round"
            stroke-linejoin="round"
          >
            <path stroke="none" d="M0 0h24v24H0z" />
            <circle cx="12" cy="7" r="4" />
            <path d="M6 21v-2a4 4 0 0 1 4 -4h4a4 4 0 0 1 4 4v2" />
          </svg>
        </Link>
        <a
          href="/admin"
          v-if="user_data && roles?.includes('organizer')"
          class="hover:text-gray-400"
        >
          <svg
            class="h-8 w-8 text-gray-500"
            fill="none"
            viewBox="0 0 24 24"
            stroke="currentColor"
          >
            <path
              stroke-linecap="round"
              stroke-linejoin="round"
              stroke-width="2"
              d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"
            />
          </svg>
        </a>
        <button v-if="user_data" @click="logout" class="hover:text-gray-400">
          <svg
            class="h-8 w-8 text-gray-500"
            width="24"
            height="24"
            viewBox="0 0 24 24"
            stroke-width="2"
            stroke="currentColor"
            fill="none"
            stroke-linecap="round"
            stroke-linejoin="round"
          >
            <path stroke="none" d="M0 0h24v24H0z" />
            <path d="M9 11l-4 4l4 4m-4 -4h11a4 4 0 0 0 0 -8h-1" />
          </svg>
        </button>
      </div>
    </header>

    <main class="flex-grow container mx-auto p-4">
      <Link href="/">Home</Link>
      <slot></slot>
    </main>

    <footer class="bg-gray-800 text-white text-center p-4">
      &copy; 2023 My App. All rights reserved.
    </footer>
  </div>
</template>

<script lang="ts">
import { Link } from '@inertiajs/inertia-vue3'
import { Inertia } from '@inertiajs/inertia'

export default {
  components: {
    Link,
  },
  props: {
    user_data: Object,
    roles: Array,
  },
  methods: {
    logout() {
      Inertia.post('/logout')
    },
    goToRoute() {
      Inertia.visit('/admin', { replace: true })
    },
  },
}
</script>
