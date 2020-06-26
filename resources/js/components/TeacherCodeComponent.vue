<template>
    <!-- model -->
    <div class="modal fade" :id="'code-modal'+id" data-backdrop="static" data-keyboard="false" tabindex="-1" role="dialog" aria-labelledby="code-model" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div v-if="!loaded" class="overlay d-flex justify-content-center align-items-center">
                    <i class="fas fa-2x fa-sync fa-spin"></i>
                </div>
                <div class="modal-header">
                    <h5 class="modal-title" id="staticBackdropLabel">View Access Code</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

             <ul class="nav nav-tabs" :id="`code${id}-tab`" role="tablist">
                 <li class="nav-item">
                    <a class="nav-link active" :id="`qr${id}-tab`" data-toggle="pill" :href="`#qr${id}`" role="tab" :aria-controls="`qr${id}`" aria-selected="false">QR</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" :id="`code${id}-tab`" data-toggle="pill" :href="`#code${id}`" role="tab" :aria-controls="`code${id}`" aria-selected="true">Code</a>
                </li>

             </ul>
            <div class="tab-content" :id="`code${id}-tabContent`">
                <div class="tab-pane fade text-center  active show" :id="`qr${id}`" role="tabpanel" :aria-labelledby="`qr${id}-tab`">
                    <h1 v-if="expired">Expired</h1>
                    <canvas v-if="!expired" :id="'QRCanvas'+id"></canvas>
                    <p class="text-center" >Scan this QR in the student portal</p>
                    <h4 v-if="countdown" class="text-center text-danger">Expires in {{countdown}}</h4>
                </div>
                <div class="tab-pane fade" :id="`code${id}`" role="tabpanel" :aria-labelledby="`code${id}-tab`">
                    <h1 v-if="expired" class="text-center">Expired</h1>
                    <h1 v-if="!expired" class="text-center" >{{code}}</h1>
                    <p class="text-center" >Enter this code in the student portal</p>
                    <h4 v-if="countdown" class="text-center text-danger">Expires in {{countdown}}</h4>
                </div>
            </div>
            <div class="modal-footer">
                <button @click="revokeCode(id)" class="btn btn-danger">Revoke Code</button>
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
    <!-- model end-->
</template>

<script>
    import moment from 'moment';
    import QRCode from 'qrcode'
    let loop;
    export default {
        name: "TeacherCodeComponent",
        props: ['id'],
        data(){
            return {
                loaded: false,
                code: null,
                expire_at: null,
                countdown: "",
                expired: false
            }
        },
        created() {
            Bus.$on(`modal${this.id}open`, () => {
                this.getTeacherCodeData(this.id);
            })
        },
        mounted() {
            $(`#code-modal${this.id}`).on('hide.bs.modal', function (e) {
                clearInterval(loop)
                this.countdown = ""
            })
        },
        methods:{
            getTeacherCodeData(id){
                this.expired = false;
                this.loaded = false
                axios.get(`/api/teacher-timetable/${id}/code`)
                .then(({data}) => {
                    this.code = data.code;
                    this.expire_at = data.expire_at || null
                    this.loaded = true
                    this.timeToNow(this.expire_at)
                    this.renderQR(id)
                })
                .catch((error) => {
                    toastr.error(error.message, 'Error')
                })
            },
            timeToNow(time) {
                loop = setInterval(() => {
                    let diffSec = moment(time).diff(moment(), "seconds")
                    if (diffSec <= 0) {
                        clearInterval(loop)
                        this.expired = true
                    }
                    let duration = moment.duration(diffSec,"second")
                    this.countdown = `${duration.minutes()}:${duration.seconds().toString().padStart(2, '0')}`
                },1000)
            },
            revokeCode(id) {
                this.loaded = false;
                axios.delete(`/api/teacher-timetable/${id}/code`)
                .then(() => {
                    $(`#code-modal${id}`).modal('hide')
                    clearInterval(loop)
                    this.countdown = ''
                    toastr.success('Revoked code successfully', 'Success');
                })
                .catch((error) => {
                    toastr.error(error.message, 'Error')
                })
            },
            renderQR(id) {
                let canvas = document.getElementById('QRCanvas'+id)
                let options = {
                    width: 300
                }
                QRCode.toCanvas(canvas, btoa(this.code) , options)
            }
        }


    }
</script>

<style scoped>

</style>
