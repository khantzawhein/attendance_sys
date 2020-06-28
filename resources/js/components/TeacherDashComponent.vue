<template>
    <div class="col-12">
        <div class="row">
            <in-progress-class-component></in-progress-class-component>
            <teacher-timetable-component></teacher-timetable-component>
        </div>
    </div>
</template>

<script>
    export default {
        name: "TeacherDashComponent",
        data() {
            return {
                my_timetables: [],
                error: null,
            }
        },
        created() {
            this.getMyTimetableData();
        },
        methods: {
            getMyTimetableData() {
                Bus.$emit('my_timetable_loading')
                axios.get('/api/teacher-timetable')
                .then(({data}) => {
                    let day = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday']
                    this.my_timetables = data;
                    this.my_timetables.forEach((timetable, index, data) => {
                        data[index].day = day[timetable.day];
                    })
                    Bus.$emit('my_timetable_loaded', this.my_timetables)
                })
                .catch(error => {
                    toastr.error(error.message, 'Error')
                })
            }
        }
    }
</script>

<style scoped>

</style>
