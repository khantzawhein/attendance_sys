<template>
    <div>
        <div class="card card-default">
            <div v-if="!classLoaded" class="overlay"><i class="fas fa-2x fa-sync-alt fa-spin"></i></div>
            <div class="card-header">
                <div class="row">
                    <div class="col-md-12 mt-2">
                        <h3 class="card-title">This section's classes</h3>
                    </div>
                    <div class="col-md-9 mt-3">
                        <button class="btn btn-sm bg-gradient-success" data-toggle="collapse" data-target="#timetable-input" aria-expanded="false" aria-controls="timetable-input"><i class="fas fa-plus mr-1"></i>Add class</button>
                    </div>
                </div>
            </div>

            <div class="card-body">
                <div class="row">
                    <div class="col-md-12">
                        <timetable-input></timetable-input>
                    </div>
                    <div class="col-md-12">
                        <p v-if="timetables.length==0">There's nothing to show</p>
                        <div class="accordion" id="days_slide" v-for="(data, index) in timetables">
                            <div class="card">
                                <div class="card-header" id="headingOne">
                                    <h2 class="mb-0">
                                        <button class="btn btn-link btn-block text-left collapsed" type="button" data-toggle="collapse" :data-target="`#day${index}`" aria-expanded="true" :aria-controls="`#day${index}`">
                                            {{data[0].day}}
                                        </button>
                                    </h2>
                                </div>

                                <div :id="`day${index}`" class="collapse" aria-labelledby="headingOne" data-parent="#days_slide">
                                    <div class="card-body">
                                        <table class="table table-hover table-nowrap" >
                                            <thead>
                                                <tr>
                                                    <th>ID</th>
                                                    <th>Day</th>
                                                    <th>Start Time</th>
                                                    <th>End Time</th>
                                                    <th>Course</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr v-for="(timetable, index) in data">
                                                    <td>{{index+1}}</td>
                                                    <td>{{timetable.day}}</td>
                                                    <td>{{timetable.start_time}}</td>
                                                    <td>{{timetable.end_time}}</td>
                                                    <td>{{timetable.course_name}}</td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        name: "TimetableComponent",
        data() {
            return {
                timetables: {},
                loadStatus: {
                    timetableLoaded: true,
                },
            }
        },
        computed: {
            classLoaded() {
                return this.loadStatus.timetableLoaded
            }
        },
        created() {
            this.getTimetableData()
        },
        mounted() {
            Bus.$on('class-input-started', () => {
                this.loadStatus.timetableLoaded = false;
            })
            Bus.$on('class-input-finished', () => {
                this.getTimetableData()
                this.loadStatus.timetableLoaded = true;
            })
        },
        methods: {
            getTimetableData() {
                this.loadStatus.timetableLoaded = false
                axios.get('/api/sections/' + this.$route.params.id + '/classes')
                .then(response => {
                    this.timetables = response.data;
                    this.loadStatus.timetableLoaded = true
                })
                .catch(error => {
                    toastr.error(error.message, 'Opps! Something went wrong.')
                })
            },
        }
    }
</script>

<style scoped>

</style>
