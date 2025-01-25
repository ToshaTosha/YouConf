<template>
  <div class="flex items-center justify-center min-h-screen bg-gray-500">
    <div class="bg-white p-8 rounded-lg shadow-md w-full max-w-md">
      <slot></slot>
    </div>
  </div>
</template>

<script>
export default {
  name: 'AuthLayout',
}
</script>
