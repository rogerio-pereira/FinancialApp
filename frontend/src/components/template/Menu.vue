<template>
    <div class="row navbarContainer">
        <div class="container">
            <nav class="navbar navbar-expand-lg">
                <router-link to='/' class='navbar-brand'>Financial App</router-link>
                <button class="navbar-toggler btn btn-light" data-toggle="collapse" data-target="#navbar" aria-controls="navbar" aria-expanded="false">
                    <i class="fas fa-bars"></i>
                </button>


                <transition enter-active-class="animated fadeIn" leave-active-class="animated fadeOut" mode='out-in'>
                    <div class="collapse navbar-collapse" id="navbar" v-if='this.$store.state.PassportApiToken.token'>
                        <ul class="navbar-nav ml-auto mt-2 mt-lg-0">
                            <li class="nav-item active">
                                <router-link to='Accounts' class='nav-link'>Accounts</router-link>
                            </li>

                            <li class="nav-item">
                                <router-link to='Categories' class='nav-link'>Categories</router-link>
                            </li>

                            <li class="nav-item">
                                <router-link to='Transactions' class='nav-link'>Transactions</router-link>
                            </li>

                            <li class="nav-item">
                                <a href='/' @click.prevent.stop='logout()' class='nav-link'>Logout</a>
                            </li>
                        </ul>
                    </div>
                </transition>
            </nav>
        </div>
    </div>     
</template>

<script>
    export default {
        data() {
            return {

            }
        },
        methods: {
            logout() {
                this.$store.commit('setToken', null)
                this.$http.defaults.headers.common['Authorization'] = null
                this.$router.push({ name: 'login'})
            }
        }
    }
</script>

<style>
    .navbarContainer
    {
        background: #093028; /* fallback for old browsers */
        background: -webkit-linear-gradient(to right, #093028, #237a57); /* Chrome 10-25, Safari 5.1-6 */
        background: linear-gradient(to right, #093028, #237a57); /* W3C, IE 10+/ Edge, Firefox 16+, Chrome 26+, Opera 12+, Safari 7+ */
    }

    .navbar a
    {
        color:white;

        font-size: 1.2em;
    }

    .navbar a:hover{
        color:white;
        text-decoration: underline;
    }

    button.navbar-toggler {
        background-color: white;
    }
</style>