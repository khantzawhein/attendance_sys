<template>
    <div>
        <div class="loading text-center align-items-center justify-content-center d-flex vh-100" v-if="!loaded">
            <loader-component></loader-component>
        </div>
        <div class="error mt-4" v-if="error" >
            <div class="alert alert-danger" role="alert">
                {{error}}
            </div>
        </div>
        <div class="row">
            <div class="col-sm-12">
                <p v-if="!teachers.length">There's nothing to show</p>
                <table class="table table-striped" v-show="loaded&&!error&&teachers.length">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Role</th>
                            <th>Department</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                        <tbody>
                            <tr v-for="teacher in teachers" :key="teacher.id">
                                <td>{{teacher.id}}</td>
                                <td>{{teacher.name}}</td>
                                <td>{{teacher.email}}</td>
                                <td>{{teacher.role}}</td>
                                <td>{{teacher.department}}</td>
                                <td>
                                    <button class="btn btn-secondary">Manage</button>
                                </td>
                            </tr>
                        </tbody>
                </table>
                <button class="btn btn-success" @click="$router.push('/app/teachers/create')">Create Teacher Account</button>
            </div>
        </div>
    </div>
</template>
<script>
    export default {
        data() {
            return {
                teachers: {},
                error: null,
                loaded: false
            }
        },
        created() {
            this.getTeacherData();
        },
        methods: {
            getTeacherData() {
                axios.get('/api/teachers')
                .then(response => {
                    this.teachers = response.data;
                    this.loaded = true;
                })
                .catch(error => {
                    this.loaded = true;
                    this.error = error.response.data.message || error.message;
                })
            }
        }
    }

</script>
