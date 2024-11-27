<template>
    <div>
        <h1>{{ user.name }}</h1>
    </div>
</template>

<script>
export default {
    name: 'User',
    props: {
        user: Object,
    },
};
</script>
