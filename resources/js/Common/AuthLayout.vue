<template>
  <div class="flex items-center justify-center min-h-screen bg-blue-100">
    <slot></slot>
  </div>
</template>

<script lang="ts">
import { Link } from '@inertiajs/inertia-vue3'

export default {
  name: 'AuthLayout',
  components: {
    Link,
  },
  methods: {
    toggleMenu() {
      this.isMenuOpen = !this.isMenuOpen
    },
  },
}
</script>
