<template>
    <div>
        <b-navbar toggleable="lg" type="dark" variant="info">
            <b-navbar-brand href="#">NavBar</b-navbar-brand>

            <b-navbar-toggle target="nav-collapse"></b-navbar-toggle>

            <b-collapse id="nav-collapse" is-nav>
                <!-- Right aligned nav items -->
                <b-navbar-nav class="ml-auto">
                    <b-nav-item href='Acounts'>
                        Acounts
                    </b-nav-item>
                    <b-nav-item href='Categories'>
                        Categories
                    </b-nav-item>
                    <b-nav-item href='Expenses'>
                        Expenses
                    </b-nav-item>
                </b-navbar-nav>
            </b-collapse>
        </b-navbar>
        </div>
</template>

<script>
    export default {
        data() {
            return {

            }
        }
    }
</script>

<style scoped>

</style>