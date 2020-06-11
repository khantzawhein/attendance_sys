<template>
    <div class="modal fade" id="changePassword" tabindex="-1" role="dialog" aria-labelledby="changePassword" aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content">
            <div class="overlay d-flex justify-content-center align-items-center" v-if="!loaded">
                <i class="fas fa-2x fa-sync fa-spin"></i>
            </div>
          <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">
                <slot name="title">Change Password</slot>
            </h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
              <slot name="body"></slot>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            <slot name="button"></slot>
          </div>
        </div>
      </div>
    </div>
</template>

<script>
    export default {
        name: "ChangePasswordComponent.vue",
        props: ['loaded']
    }
</script>

<style scoped>

</style>
