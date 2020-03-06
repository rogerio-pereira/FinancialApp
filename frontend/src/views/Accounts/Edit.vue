<template>
    <div class='card'>
        <div class="card-header">
            <strong>Edit Bank Account</strong>
        </div>

        <div class='col-8 offset-2'>
            <form-account :bankAccount='bankAccount' @save='save($event.data)'/>
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
                bankAccount: {}
            }
        },
        created() {
            this.$http.get('bank-accounts/'+this.$route.params.id)
                .then(response => {
                    this.bankAccount = response.data
                })
                .catch(error => {
                    console.log('Error at fetching bankAccount\n'+error)
                })
        },
        methods: {
            save(data) {
                this.$http.put('bank-accounts/'+this.bankAccount.id, data)
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