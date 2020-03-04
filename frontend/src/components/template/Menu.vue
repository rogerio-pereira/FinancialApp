<template>
    <div class="row navbarContainer">
        <div class="container">
            <nav class="navbar navbar-expand-lg">
                <a class="navbar-brand" href="/">Financial App</a>
                <button class="navbar-toggler btn btn-light" data-toggle="collapse" data-target="#navbar" aria-controls="navbar" aria-expanded="false">
                    <i class="fas fa-bars"></i>
                </button>

                <div class="collapse navbar-collapse" id="navbar">
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