<template>
    <div>
        <form action="#" @submit.prevent="handleLogin">
            <div class="form-group">
                <label for="email">Email address</label>
                <input v-model="formData.email" type="email" class="form-control" id="email" aria-describedby="emailHelp">
                <small id="emailHelp" class="form-text text-muted">We'll never share your email with anyone else.</small>
            </div>
            <div class="form-group">
                <label for="exampleInputPassword1">Password</label>
                <input v-model="formData.password" type="password" class="form-control" id="exampleInputPassword1">
            </div>
            <div class="form-group form-check">
                <input type="checkbox" class="form-check-input" id="exampleCheck1">
                <label class="form-check-label" for="exampleCheck1">Check me out</label>
            </div>
            <button :disabled="!submitable" type="submit" class="btn btn-primary">Submit</button>
        </form>
    </div>
</template>
<script>
    export default {
        data() {
            return {
                data: null,
                formData: {
                    email: '',
                    password: ''
                },
                errors: null,
                user: null
            }
        },
        methods: {
            handleLogin() {
                axios.get('/sanctum/csrf-cookie').then(response => {
                    axios.post('/login', this.formData).then(response => {
                        axios.get('api/user').then(response => {
                            console.log(response.data)
                            this.user = response.data;
                            this.$emit('authenticated', this.user)
                        });
                        // this.redirect()
                    })
                    .catch(error => {
                        this.error = error.message;
                        console.log(error);
                    })
                });
            },
            
        },

        computed: {
            submitable() {
                if (this.formData.email.length == 0 || this.formData.password.length == 0) {
                    return false;
                }
                return true;
            }
        }
    }
</script>
