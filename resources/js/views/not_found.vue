<template>
    <div>
        <header-component>
            <template v-slot:title>404</template>
            <template v-slot:breadcrumb>
                <li class="breadcrumb-item"><router-link :to="{name: 'home'}">Home</router-link></li>
                <li class="breadcrumb-item">404</li>
            </template>
        </header-component>
        <!-- Main content -->
        <div class="content">
          <div class="error-page">
            <h2 class="headline text-warning"> 404</h2>

            <div class="error-content">
              <h3><i class="fas fa-exclamation-triangle text-warning"></i> Oops! Page not found.</h3>
              <p>
                We could not find the page you were looking for.
                Meanwhile, you may <router-link :to="{name: 'home'}">return to dashboard</router-link>.
              </p>
            </div>
            <!-- /.error-content -->
          </div>
        <!-- /.content -->
        </div>
    </div>
</template>

<script>
    export default {
        name: "not_found"
    }
</script>

<style scoped>

</style>
