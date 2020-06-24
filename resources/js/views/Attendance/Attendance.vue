<template>
    <div class="attendance_container vh-100">
        <div class="content">
          <div class="container-fluid">
              <div class="row justify-content-center align-items-center vh-100">
                  <div v-if="!loaded" class="spinner-attendance ">
                    <div class="spinner"></div>
                  </div>
                  <div class="col-12 mb-2">
                      <h2 class="attendance_font">Enter your attendance code</h2>
                      <form @submit.prevent="handleSubmit" action="#">
                           <input v-model="formData.code" class="attendance_input" placeholder="Enter PIN" type="text" required>
                          <button type="submit" class="attendance_button mt-3">Enter</button>
                      </form>
                      <h3 class="text_or">OR</h3>
                      <button @click="openQRScanner" class="attendance_button mt-3" data-toggle="modal" data-target="#QRReader">Read QR</button>

                      <!-- Modal -->
                    <div class="modal fade" id="QRReader" data-backdrop="static" data-keyboard="false" tabindex="-1" role="dialog" aria-labelledby="QRReaderLabel" aria-hidden="true">
                      <div class="modal-dialog">
                        <div class="modal-content">
                          <div class="modal-header">
                            <h5 class="modal-title" id="QRReaderLabel">Reading QR...</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                              <span aria-hidden="true">&times;</span>
                            </button>
                          </div>
                          <div class="modal-body">
                            <div id="reader" width="400px"></div>
                          </div>
                          <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
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
    let html5QrCode;
    export default {
        name: "Attendance",
        data() {
            return {
                formData: {
                    code: "",
                },
                loaded: true,
                cameras: [],
                cameraID: null
            }
        },
        mounted() {
            html5QrCode = new Html5Qrcode( "reader");
        },
        methods: {
            handleSubmit() {
                this.loaded = false
                axios.post('/api/attendance', this.formData)
                .then(() => {
                    this.loaded = true
                    this.formData.code = ""
                    toastr.success('Attendance has been recorded.', 'Success')
                })
                .catch(error => {
                    console.log(error.response)
                    this.formData.code = ""
                    this.loaded = true
                    if (error.response.status == 429) {
                        toastr.error("Too many attempts, please try again later.", 'Error');
                    }
                    else {
                        toastr.error(error.response.data.errors.code, 'Error');
                    }
                })
            },
            openQRScanner() {
                $('#QRReader').on('hide.bs.modal', (e) => {
                    this.stopCamera()
                })
                Html5Qrcode.getCameras().then(devices => {
                  /**
                   * devices would be an array of objects of type:
                   * { id: "id", label: "label" }
                   */
                  if (devices && devices.length) {
                    this.cameras = devices
                      if(!this.cameras[1]) {
                          this.cameraID = this.cameras[0].id
                      }
                      else {
                          this.cameraID = this.cameras[1].id
                      }
                      this.startCamera()

                  }
                }).catch(err => {
                    console.log(error)
                });

            },
            startCamera() {
                    html5QrCode.start(
                      this.cameraID,
                      {
                        fps: 10,    // Optional frame per seconds for qr code scanning
                        qrbox: 250  // Optional if you want bounded box UI
                      },
                      qrCodeMessage => {
                        this.formData.code = qrCodeMessage
                          this.handleSubmit()
                        this.stopCamera()
                      },
                      errorMessage => {
                          //
                      })
                    .catch(err => {
                      toastr.error("Can't access camera" ,'Error')
                    });
            },
            stopCamera() {
                html5QrCode.stop().then(ignore => {
                    $('#QRReader').modal('hide');
                }).catch(err => {
                    console.log(err)
                })
            }
        }
    }
</script>

<style scoped>
    @import url('https://fonts.googleapis.com/css?family=Montserrat:100,400&display=swap');
    @import url('https://fonts.googleapis.com/css2?family=Raleway:wght@800&display=swap');

@keyframes kahoot{25%{background:#41c4de;}50%{background:#8cc73f;}75%{background:#f3d333;}100%{background:#f78e56;}}
    .attendance_container {
        background: #42c4de;
        animation: kahoot 14s infinite;
        animation-direction: alternate;
        font-family: 'Montserrat', sans-serif;
    }
    .attendance_font {
        font-family: 'Raleway', sans-serif;
        font-weight: 800;
        text-align: center;
        font-size: 2.4rem;
    }
    .attendance_input {
        width: 15rem;
        height: 3rem;
        border:2px #CCD4D0 solid;
        margin-left: auto;
        margin-right: auto;
        display:block;
        transition:0.3s;
        padding: 4px 6px;
        font-size:1.25rem;
        text-align:center;
        font-family: 'Montserrat', sans-serif;
        font-weight:700;
        color:black;
    }
    .text_or {
        font-family: 'Raleway', sans-serif;
        font-weight: 800;
        text-align: center;
        margin-top: 1rem;
    }
    .attendance_button{
      width: 15rem;
      height:50px;
      border: 0px;
      margin-left: auto;
      margin-right: auto;
      display:block;
      transition:0.3s;
      background-color: #333333;
      color:white;
      font-family: 'Montserrat', sans-serif;
      font-size: 20px;
      font-weight: 700;
      border-radius: 3px;
      text-align:center;

    }
    .spinner-attendance {
        z-index: 1080;
    }
    .spinner-attendance:before {
        content: '';
        display: block;
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(0, 0, 0, .4);
        }
    .spinner-attendance .spinner {
        position: fixed;
        top: 50%;
       height:75px;
       width:75px;
       margin:0px auto;
       -webkit-animation: rotation .8s infinite linear;
       -moz-animation: rotation .8s infinite linear;
       -o-animation: rotation .8s infinite linear;
       animation: rotation .8s infinite linear;
       border-left:10px solid rgba(0,174,239,.15);
       border-right:10px solid rgba(0,174,239,.15);
       border-bottom:10px solid rgba(0,174,239,.15);
       border-top:10px solid rgba(0,174,239,.8);
       border-radius:100%;
    }

    @-webkit-keyframes rotation {
       from {-webkit-transform: rotate(0deg);}
       to {-webkit-transform: rotate(359deg);}
    }
    @-moz-keyframes rotation {
       from {-moz-transform: rotate(0deg);}
       to {-moz-transform: rotate(359deg);}
    }
    @-o-keyframes rotation {
       from {-o-transform: rotate(0deg);}
       to {-o-transform: rotate(359deg);}
    }
    @keyframes rotation {
       from {transform: rotate(0deg);}
       to {transform: rotate(359deg);}
    }

    .attendance_input:focus{
      border:2px black solid;
    }
    input[type="text"]
    {
        font-size:1.25rem;
        text-align:center;
        font-family: 'Montserrat', sans-serif;
        font-weight:700;
        color:black;
    }
    input[type="text"].attendance_input::-webkit-input-placeholder {
      font-family: 'Montserrat', sans-serif;
      color:#B5B5B5;
    }

    input[type="text"].attendance_input:-ms-input-placeholder {
      font-family: 'Montserrat', sans-serif;
      color:#B5B5B5;
    }

    input[type="text"].attendance_input:-moz-placeholder {
      font-family: 'Montserrat', sans-serif;
      color:#B5B5B5;
    }

    input[type="text"].attendance_input::-moz-placeholder {
      font-family: 'Montserrat', sans-serif;
      color:#B5B5B5;
    }
    /* Extra small devices (phones, 600px and down) */
@media only screen and (max-width: 600px) {
    .spinner-attendance .spinner {
        left: 40%;

    }
    .attendance_font {
        margin: -40% 0 4% 0;
    }
}

/* Small devices (portrait tablets and large phones, 600px and up) */
@media only screen and (min-width: 600px) {
    .spinner-attendance .spinner {
        left: 43%;
    }
    .attendance_font {
        margin: -40% 0 4% 0;
    }
}

/* Medium devices (landscape tablets, 768px and up) */
@media only screen and (min-width: 768px) {
    .spinner-attendance .spinner {
        left: 46%;
    }
    .attendance_font {
        margin: -20% 0 4% 0;
    }
}


/* Large devices (laptops/desktops, 992px and up) */
@media only screen and (min-width: 992px) {
    .spinner-attendance .spinner {
        left: 57%;
    }
    .attendance_font {
        margin: -10% 0 4% 0;
    }
}

/* Extra large devices (large laptops and desktops, 1200px and up) */
@media only screen and (min-width: 1200px) {
.spinner-attendance .spinner {
        left: 56%;

    }
    .attendance_font {
        margin: -7% 0 4% 0;
    }
}

</style>
