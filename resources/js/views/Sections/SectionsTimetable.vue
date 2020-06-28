<template>
<div>
        <header-component>
            <template v-slot:title>This Class' Timetable</template>
            <template v-slot:breadcrumb>
                <li class="breadcrumb-item"><router-link :to="{name: 'home'}">Home</router-link></li>
                <li class="breadcrumb-item"><router-link :to="{name: 'my_classes'}">My Classes</router-link></li>
                <li class="breadcrumb-item">Timetable</li>
            </template>
        </header-component>
        <!-- Main content -->
        <div class="content">
          <div class="container-fluid">
            <div class="row">
                <div class="col-lg-12">
                    <timetable-component :auth="auth"></timetable-component>
                </div>
            </div>
          </div>
        </div>
    </div>
</template>

<script>
    export default {
        name: "SectionsTimetable",
        props: ['auth']
    }
</script>

<style scoped>

</style>
