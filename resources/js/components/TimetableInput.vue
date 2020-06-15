<template>
    <div class="collapse mb-2" id="timetable-input">
        <error-component :error="error"></error-component>
        <form action="#" @submit.prevent="handleSubmit">
            <div class="row justify-content-center " >
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="day">Day:</label>
                        <select id="day" class="form-control" v-model="formData.day">
                            <option value="" selected disabled>Select day:</option>
                            <option value="0">Monday</option>
                            <option value="1">Tuesday</option>
                            <option value="2">Wednesday</option>
                            <option value="3">Thursday</option>
                            <option value="4">Friday</option>
                            <option value="5">Saturday</option>
                            <option value="6">Sunday</option>
                        </select>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="start_time">Start Time:</label>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text"><i class="far fa-clock"></i></span>
                            </div>
                            <input v-model="formData.start_time" type="text" class="form-control float-right" id="start_time" required>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="end_time">End Time:</label>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text"><i class="far fa-clock"></i></span>
                            </div>
                            <input v-model="formData.end_time" type="text" class="form-control float-right" id="end_time" required>
                        </div>
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="form-group">
                        <label>Course</label>
                        <select-component style="width: 100%" :options="coursesOptions" v-model="formData.course_id">
                            <option value="" selected class="default">Select Course:</option>
                        </select-component>
                    </div>
                </div>

                <div class="col-md-12">
                    <div class="row">
                        <div class="ml-auto">
                            <button class="btn btn-sm bg-gradient-primary" >Save</button>
                            <button type="button" class="btn btn-sm bg-gradient-gray" @click='collapse'>Close</button>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</template>

<script>
    export default {
        name: "TimetableInput",
        data() {
            return {
                courses: {},
                formData: {
                    day: "",
                    start_time: "",
                    end_time: "",
                    course_id: '',
                },
                error: {
                    title: null,
                    details: {}
                },
                coursesOptions: [],
            }
        },
        created() {
            this.getCoursesData()
        },
        mounted() {
            let vm = this;
            $('#start_time').timepicker({ 'step': 15, scrollDefault: '8:45am' }).on('changeTime', function() {
                vm.formData.start_time = $("#start_time").val()
            });
            $('#end_time').timepicker({ 'step': 15, scrollDefault: '8:45am'}).on('changeTime', function() {
                vm.formData.end_time = $("#end_time").val()
            });
        },
        methods: {
            collapse() {
                $("#timetable-input").collapse('hide');
            },
            handleSubmit() {
                Bus.$emit('class-input-started');
                axios.post('/api/sections/' + this.$route.params.id + '/classes', this.formData)
                .then(response => {
                    Bus.$emit('class-input-finished');
                    toastr.success('Timetable added successfully', 'Success')
                    this.formData = {}
                    this.error.title = this.error.details = null
                })
                .catch(error => {
                    Bus.$emit('class-input-finished');
                    this.error.title = error.response.data.message || error.message;
                    this.error.details = error.response.data.errors;
                })
            },
            getCoursesData() {
                Bus.$emit('class-input-started');
                axios.get('/api/courses')
                .then(response => {
                    Bus.$emit('class-input-finished');
                    this.courses = response.data.data;
                    this.courses.forEach((course) => {
                        this.coursesOptions.push({id: course.id, text: `${course.module_no}/${course.module_name}`})
                    })
                })
                .catch(error => {
                    Bus.$emit('class-input-finished');
                    toastr.error(error.message, 'Opps! Something went wrong.')
                })
            },

        }
    }
</script>

<style scoped>

</style>
