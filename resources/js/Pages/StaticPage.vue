<template>
  <div>
    <h1>{{ title }}</h1>
    <div v-html="content" />
  </div>
</template>

<script setup>
defineProps({
  title: String,
  content: String,
})
</script>
