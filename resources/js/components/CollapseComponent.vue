<template>
    <div class="collapse mb-2" id="collapse_component">
        <div class="row justify-content-center">
            <slot name="body"></slot>
            <div class="col-md-10">
            <div class="row">
                <div class="m-auto">
                    <button type="button" @click.prevent="handleSubmit" class="btn btn-sm bg-gradient-primary" >Save</button>
                    <button type="button" class="btn btn-sm bg-gradient-gray" @click='collapse'>Close</button>
                </div>
            </div>
        </div>
        </div>
    </div>
</template>

<script>
    export default {
        name: "CollapseComponent",
        methods: {
            collapse() {
                $("#collapse_component").collapse('hide');
            },
            handleSubmit() {
                this.$emit('collapse_submitted');
            }
        }
    }
</script>

<style scoped>

</style>
