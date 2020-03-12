<template>
    <div class='card'>
        <div class="card-header">
            <strong>New {{type}}</strong>
        </div>

        <div class='col-8 offset-2'>
            <form-transaction :transaction='transaction' :errors='errors' @save='save($event.data)'/>
        </div>
    </div>
</template>

<script>
    import FormTransaction from './Form'

    export default {
        components: {
            FormTransaction
        },
        props: [
            'type'
        ],
        data() {
            return {
                transaction: {
                    description: null,
                    amount: null,
                    type: this.type.charAt(0).toUpperCase() + this.type.slice(1),
                    due_at: null,
                    category_id: null,
                    account_id: null,
                    payed: false,

                    repeat: false,
                    repeatTimes: 2,
                    period: 'Monthly',
                },
                errors: {}
            }
        },
        methods: {
            save(data) {
                this.$http.post('transactions', data)
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
    .card-header
    {
        text-transform: capitalize;
    }
</style>