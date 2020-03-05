<template>
    <div class='card'>
        <div class="card-header">
            <strong>New Bank Account</strong>
        </div>

        <div class='col-8 offset-2'>
            <form-account :bankAccount='bankAccount' @saveBankAccount='save($event.data)'/>
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
                }
            }
        },
        methods: {
            save(data) {
                this.$http.post('bank-accounts', data)
                    .then(() => {
                        this.$router.push({ name: 'accounts.index'})
                    })
                    .catch(error => {
                        console.log('Error at saving\n'+error)
                    })
            }
        }
    }
</script>

<style scoped>

</style>