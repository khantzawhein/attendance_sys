<template>
    <div class="modal fade" :id="`editTimetable${timetable.id}`" tabindex="-1" role="dialog" :aria-labelledby="`editTimetable${timetable.id}`" aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content">
            <div class="overlay d-flex justify-content-center align-items-center" v-if="!loaded">
                <i class="fas fa-2x fa-sync fa-spin"></i>
            </div>
          <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">
                <slot name="title">Edit Timetable</slot>
            </h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
              <form action="#">
                  <div class="row">
                      <div class="col-md-12">
                          <div class="form-group">
                              <label :for="`edit_day${timetable.id}`">Day:</label>
                              <select :id="`edit_day${timetable.id}`" class="form-control" v-model="formData.day">
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
                  </div>

                  <div class="row">
                      <div class="col-md-6">
                          <div class="form-group">
                            <label :for="`edit_start_time${timetable.id}`">Start Time:</label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="far fa-clock"></i></span>
                                </div>
                                <input v-model="formData.start_time" type="text" class="form-control float-right" :id="`edit_start_time${timetable.id}`" required>
                            </div>
                        </div>
                      </div>
                      <div class="col-md-6">
                          <div class="form-group">
                              <label :for="`edit_end_time${timetable.id}`">End Time:</label>
                              <div class="input-group">
                                  <div class="input-group-prepend">
                                      <span class="input-group-text"><i class="far fa-clock"></i></span>
                                  </div>
                                  <input v-model="formData.end_time" type="text" class="form-control float-right" :id="`edit_end_time${timetable.id}`" required>
                              </div>
                          </div>
                      </div>
                  </div>
                  <div class="row">
                      <div class="col-md-12">
                          <p class="text-primary">
                              You cannot change course here. If you need to change course, delete this timetable and create a new one.
                          </p>
                      </div>
                  </div>
              </form>
          </div>
          <div class="modal-footer">
              <button @click="deleteConfirm(timetable.id)" type="button" class="btn btn-danger mr-auto">Delete</button>
              <button @click="handleSubmit" class="btn btn-primary">Save</button>
              <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
          </div>
        </div>
      </div>
    </div>
</template>

<script>
    import swal from 'sweetalert';
    export default {
        name: "TimetableEditComponent",
        props: ['timetable', 'auth'],
        data() {
            return {
                loaded: false,
                courseLoaded: false,
                formData: {...this.timetable},
                courses: [],
                 error: {
                    title: null,
                    details: {}
                },
                coursesOptions: [],
            }
        },
        created() {
            this.getCoursesData();
            let day = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'];
            this.formData.day = day.indexOf(this.formData.day)
        },
        mounted() {
            let vm = this;
            $(`#edit_start_time${this.timetable.id}`).timepicker({ 'step': 15, scrollDefault: '8:45am' }).on('changeTime', () => {
                vm.formData.start_time = $(`#edit_start_time${this.timetable.id}`).val()
            });
            $(`#edit_end_time${this.timetable.id}`).timepicker({ 'step': 15, scrollDefault: '8:45am'}).on('changeTime', () => {
                vm.formData.end_time = $(`#edit_end_time${this.timetable.id}`).val()
            });

        },
        methods: {
            handleSubmit() {
                this.loaded = false;
                axios.put(`/api/sections/${this.$route.params.id}/classes/${this.timetable.id}`, this.formData)
                .then(response => {
                    this.loaded = true
                    this.$emit('refresh-timetable')
                    $(`#editTimetable${this.timetable.id}`).modal('hide')
                    toastr.success('Timetable edited successfully', 'Success')
                    this.error.title = this.error.details = null
                })
                .catch(error => {
                    this.error.title = error.response.data.message || error.message;
                    this.error.details = error.response.data.errors;
                })
            },
            getCoursesData() {
                this.loaded = false;
                return axios.get('/api/courses')
                .then(response => {
                    this.loaded = true;
                    this.courseLoaded = true
                    this.courses = response.data.data;
                    this.courses.forEach((course) => {
                        this.coursesOptions.push({id: course.id, text: `${course.module_no}/${course.module_name}`})
                    })
                })
                .catch(error => {
                    toastr.error(error.message, 'Oops! Something went wrong.')
                })
            },
            handleDelete(id) {
                this.loaded = false
                axios.delete('/api/sections/' + this.$route.params.id + '/classes/' +id)
                    .then(response => {
                        this.loaded = true
                        this.$emit('refresh-timetable')
                        $(`#editTimetable${this.timetable.id}`).modal('hide');
                        swal("Record has been deleted.", {
                            icon: "success",
                        });
                    })
                    .catch(error => {
                        toastr.error(error.message, 'Oops! Something went wrong.')
                    })
            },
            deleteConfirm(id) {
                swal({
                    title: "Warning!",
                    text: "It will delete this timetable and its associated student's attendances. Are you sure to delete it?",
                    icon: "warning",
                    buttons: true,
                    dangerMode: true,
                })
                .then((willDelete) => {
                    if (willDelete) {
                        this.handleDelete(id)
                    }
                });
            },

        }

    }
</script>

<style scoped>

</style>
