<template>
  <div class="flex items-center justify-center min-h-screen bg-sky-200">
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
  mounted() {
    console.log('AuthLayout mounted')
  },
}
</script>
