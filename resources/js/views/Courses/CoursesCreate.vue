<template>
    <div>
        <div class="loading text-center align-items-center justify-content-center d-flex vh-100" v-if="!loaded">
            <loader-component></loader-component>
        </div>
        <div class="error mt-4" v-if="error" >
            <div class="alert alert-danger" role="alert">
                <strong>{{error}}</strong>
                <ul v-for="(error, index) in errorDetails">
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
        <div class="row">
            <div class="col-12">
                <form action="#" @submit.prevent="handleSubmit">
                    <div class="form-group">
                        <label for="module_no">Module No</label>
                        <input v-model="formData.module_no" type="text" class="form-control" id="module_no" required>
                    </div>
                    <div class="form-group">
                        <label for="module_no">Module Name</label>
                        <input v-model="formData.module_name" type="text" class="form-control" id="module_name" required>
                    </div>
                    <div class="form-group">
                        <label for="teacher_name" class="d-block">Teacher Name</label>
                        <select-component :id="'teacher_name'" class="w-100" :options="teachers" v-model="formData.teacher_id">
                            <option value="0" selected class="default">Select Teacher:</option>
                        </select-component>
                    </div>
                    <div class="form-group">
                        <label for="term" class="d-block">Term</label>
                        <select-component :id="'term'" class="w-100" :options="terms" v-model="formData.term_id">
                            <option value="0" selected class="default">Select Term:</option>
                        </select-component>
                    </div>
                    <button type="submit" class="btn btn-primary">Create</button>
                </form>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        name: "CoursesCreate",

        data() {
            return {
                teachers: {},
                terms: {},
                selected: null,

                formData: {
                    module_no: "",
                    module_name: "",
                    teacher_id: "",
                    term_id: ""
                },
                error: null,
                errorDetails: null,
                loaded: false
            }
        },
        created() {
            this.getTeacherData();
            this.getTermData();
        },
        methods: {
            getTeacherData() {
                this.loaded = false;
                axios.get('/api/teachers')
                .then(response => {
                    let data = []
                    response.data.map(teacher => {
                        data.push({id: teacher.id, text: teacher.name})
                    })
                    this.teachers = data;
                    console.log(data[0].id)
                    this.loaded = true;
                })
                .catch(error => {
                    this.loaded = true;
                    this.error = error.response.data.message || error.message;
                })
            },
            getTermData() {
                axios.get('/api/terms')
                .then(response => {
                    let data = []
                    response.data.data.map(term => {
                        data.push({id: term.id, text: term.name})
                    })
                    this.terms = data;
                    // this.formData.term_id = data[0].id
                })
                .catch(error => {
                    this.error = error.response.data.message || error.message;
                })
            },
            handleSubmit() {
                this.loaded = false
                axios.post('/api/courses', this.formData)
                .then(response => {
                    this.loaded = true
                    Bus.$emit('flash-success', "Successfully created.")
                    this.$router.push({name: "courses"})
                })
                .catch(error => {
                    this.loaded = true
                    this.error = error.response.data.message || error.message;
                    this.errorDetails = error.response.data.errors;
                })
            }
        }
    }
</script>

<style scoped>

</style>
