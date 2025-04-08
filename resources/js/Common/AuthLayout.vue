<template>
  <div
    class="min-h-screen flex items-center justify-center bg-gray-50 py-12 px-4 sm:px-6 lg:px-8"
  >
    <div
      class="bg-white p-8 rounded-lg shadow-md w-96 flex flex-col items-center"
    >
      <slot></slot>
    </div>
  </div>
</template>

<script>
export default {
  name: 'AuthLayout',
}
</script>
