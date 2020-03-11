<template>
    <div class='card'>
        <div class="card-header">
            <strong>New Bank Account</strong>
        </div>

        <div class='col-8 offset-2'>
            <form-account :bankAccount='bankAccount' :errors='errors' @save='save($event.data)'/>
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
                bankAccount: {
                    id: null,
                    name: '',
                    initialBalance: 0
                },
                errors: {}
            }
        },
        methods: {
            save(data) {
                this.$http.post('bank-accounts', data)
                    .then(() => {
                        this.$router.push({ name: 'accounts.index'})
                    })
                    .catch(error => {
                        this.errors = error.response.data.errors
                    })
            }
        }
    }
</script>

<style scoped>

</style>