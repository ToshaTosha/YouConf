<template>
  <div class="flex flex-col min-h-screen">
    <header class="bg-gray-800 text-white">
      <ul>
        {{
          $page.props
        }}
        <li>
          <Link href="/about">About1</Link>
        </li>
      </ul>
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

export default {
  components: {
    Link,
  },
  props: {
    auth: Object,
  },
  methods: {
    toggleMenu() {
      this.isMenuOpen = !this.isMenuOpen
    },
  },
}
</script>
