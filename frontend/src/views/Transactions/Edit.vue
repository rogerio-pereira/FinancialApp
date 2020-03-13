<template>
    <div class='card'>
        <div class="card-header">
            <strong>Edit Transaction</strong>
        </div>

        <div class='col-8 offset-2'>
            <form-transaction :transaction='transaction' :errors='errors' @save='save($event.data)'/>
        </div>
    </div>
</template>

<script>
    import FormTransaction from './FormEdit'

    export default {
        components: {
            FormTransaction
        },
        data() {
            return {
                transaction: {},
                errors: {}
            }
        },
        created() {
            this.$http.get('transactions/'+this.$route.params.id)
                .then(response => {
                    this.transaction = response.data
                    console.log(this.transaction)
                })
                .catch(error => {
                    console.log('Error at fetching transaction\n'+error)
                })
        },
        methods: {
            save(data) {
                this.$http.put('transactions/'+this.transaction.id, data)
                    .then(() => {
                        this.$router.push({ name: 'transactions.index'})
                    })
                    .catch(error => {
                        console.log('Error at saving\n'+error)
                        this.errors = error.response.data.errors
                    })
            }
        }
    }
</script>

<style scoped>

</style>