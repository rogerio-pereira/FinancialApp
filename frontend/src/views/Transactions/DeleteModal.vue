<template>
    <div class="modal fade" id="modalDelete" tabindex="-1" role="dialog" aria-labelledby="modalDeleteLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <strong>Recurring/Repeated Transaction</strong>

                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <div class="modal-body">
                    <p>This transaction is a recurring or repeated transaction.</p>
                    <p>Which transaction do you want to delete?</p>
                </div>
                
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary" @click="deleteTransaction('this')">Just this</button>
                    <button type="button" class="btn btn-success" @click="deleteTransaction('next')">This and next ones</button>
                    <button type="button" class="btn btn-danger" @click="deleteTransaction('all')">All Transactions</button>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        props: {
            idTransaction: {type: Number, required: false},
            indexTransaction: {type: Number, required: false}
        },
        methods: {
            deleteTransaction(transactionCount) {
                this.$emit('deleteTransaction', {
                    transactionCount: transactionCount,
                    id: this.idTransaction,
                    index: this.indexTransaction
                })
            }
        }
    }
</script>

<style scoped>

</style>