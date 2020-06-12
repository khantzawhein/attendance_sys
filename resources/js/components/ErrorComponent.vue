<template>
    <div class="error" v-if="error.title" >
        <div class="alert alert-danger" role="alert">
            <strong>{{error.title}}</strong>
            <ul v-for="(error, index) in error.details">
                <li>
                    <strong>{{index}}</strong>
                    <ul>
                        <li v-for="suberror in error">
                            {{suberror}}
                        </li>
                    </ul>
                </li>
            </ul>
        </div>
    </div>
</template>

<script>
    export default {
        name: "ErrorComponent",
        props: ['error']
    }
</script>

<style scoped>

</style>
