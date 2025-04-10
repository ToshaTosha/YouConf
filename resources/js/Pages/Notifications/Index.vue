<template>
  {{ $page.props }}
</template>

<script>
import { Inertia } from '@inertiajs/inertia'

export default {
  props: {
    notifications: Object,
    unreadCount: Number,
  },
}
</script>
