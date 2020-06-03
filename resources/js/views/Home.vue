<template>
    <div v-if="this.user">
        Hello
    </div>
</template>
<script>

export default {
    props: {
        user: Object
    }
}
</script>
