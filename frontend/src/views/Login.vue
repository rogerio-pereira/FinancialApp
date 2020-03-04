<template>
    <div class='col-md-8 offset-md-2 '>
        <div class="card">
            <div class="card-header">
                Login
            </div>

            <div class='col-8 offset-2 my-4'>
                <div class="form-group">
                    <label for="email">Email</label>
                    <input type="email" class="form-control" id="email" v-model='email'>
                </div>
                <div class="form-group">
                    <label for="password">Password</label>
                    <input type="password" class="form-control" id="password" v-model='password'>
                </div>
                <div class='form-group text-center'>
                    <button type="submit" class="btn btn-primary" @click.stop.prevent='login'>Login</button>
                </div>

                <div class='alert alert-danger' v-if='message'>{{message}}</div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        data() {
            return {
                email: '',
                password: '',
                message: ''
            }
        },
        methods: {
            login() {
                this.$http.post('/login', {
                    email: this.email,
                    password: this.password
                })
                .then(response => {
                    this.$http.defaults.headers.common['Authorization'] = 'Bearer '+response.data.access_token
                    this.$router.push({ name: 'home'})
                }) 
                .catch(error => {
                    console.log(error);
                    this.message = 'Login Failed';
                });
            }
        }
    }
</script>

<style scoped>

</style>