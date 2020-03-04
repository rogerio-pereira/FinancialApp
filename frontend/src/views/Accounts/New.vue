<template>
    <div class='card'>
        <div class="card-header">
            <strong>New Bank Account</strong>
        </div>

        <div class='col-8 offset-2'>
            <form-account />
        </div>
    </div>
</template>

<script>
    import FormAccount from './Form'

    export default {
        components: {
            FormAccount
        },
        data() {
            return {

            }
        },
        methods: {

        }
    }
</script>

<style scoped>

</style>