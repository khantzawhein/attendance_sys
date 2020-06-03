<template>
    <div>
        <div class="container-fluid">
            <nav class="navbar navbar-expand-lg navbar-light bg-light">
                <a class="navbar-brand" href="#">Attendance System</a>
                <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarNav">
                    <ul class="navbar-nav">
                        <li class="nav-item active">
                            <router-link to="/app" class="nav-link" >Home<span class="sr-only">Home</span></router-link>
                        </li>
                        <li class="nav-item">
                            <router-link to="/app/teachers" class="nav-link" >Manage Teachers<span class="sr-only">Manage Teachers</span></router-link>
                        </li>
                        <li class="nav-item">
                            <router-link to="/app/students" class="nav-link" >Manage Students<span class="sr-only">Manage Students</span></router-link>
                        </li>
                    </ul>
                </div>
            </nav>
            <div class="content">
                <router-view></router-view>
            </div>
        </div>
    </div>
</template>
<script>
    export default {
        data() {
            return {

            }
        },
        methods: {

        }
    }
</script>
