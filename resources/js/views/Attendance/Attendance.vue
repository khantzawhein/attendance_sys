<template>
    <div>
        <div class="content">
          <div class="container-fluid">
              <div class="loading text-center align-items-center justify-content-center d-flex vh-100" v-if="!loaded">
                    <loader-component></loader-component>
                </div>
            <div v-if="loaded" class="row justify-content-center">
                <div class="col-6">
                    <form @submit.prevent="handleSubmit" action="#">
                        <div class="form-group">
                            <label>Attendance Code</label>
                            <input v-model="formData.code" type="text" class="form-control" required>
                        </div>
                        <button type="submit" class="btn btn-primary">Submit</button>
                    </form>
                </div>
            </div>
          </div>
        </div>
    </div>
</template>

<script>
    export default {
        name: "Attendance",
        data() {
            return {
                formData: {
                    code: "",
                },
                loaded: true,
            }
        },
        methods: {
            handleSubmit() {
                this.loaded = false
                axios.post('/api/attendance', this.formData)
                .then(() => {
                    this.loaded = true
                    toastr.success('Attendance has been recorded.', 'Success')
                })
                .catch(error => {
                    this.loaded = true
                    console.log(error.response)
                    toastr.error(error.response.data.errors.code.join('<br>'), 'Error');
                })
            }
        }
    }
</script>

<style scoped>

</style>
