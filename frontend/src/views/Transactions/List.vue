<template>
    <div>
        <h1 class='text-center'>Transactions</h1>

        <div class='text-center my-4'>
            <router-link to='/transactions/new/income' class='btn btn-lg btn-success'>
                <i class="fas fa-long-arrow-alt-up"></i> &nbsp; Income
            </router-link>

            <router-link to='/transactions/new/expense' class='btn btn-lg btn-danger ml-2'>
                <i class="fas fa-long-arrow-alt-down"></i> &nbsp; Expense
            </router-link>

            <router-link to='/transactions/new-transfer' class='btn btn-lg btn-info ml-2'>
                <i class="fas fa-exchange-alt"></i> &nbsp; Transfer
            </router-link>
        </div>


        <table class="table text-center table-sm table-hover table-responsive-sm table-striped table-bordered">
            <thead>
                <tr>
                    <th width='120px'></th>
                    <th>ID</th>
                    <th>Description</th>
                    <th>Category</th>
                    <th>Account</th>
                    <th>Amount</th>
                    <th>Due Date</th>
                    <th>Payed</th>
                </tr>
            </thead>
            <tbody>
                <tr 
                    v-for='(transaction, index) in transactions' 
                    :key='index' 
                    :class="(transaction.is_transfer == true) ? 'transfer' : transaction.type.toLowerCase()"
                >
                    <td>
                        <router-link :to="'/transactions/edit/'+transaction.id" class='btn btn-info text-white mr-2'>
                            <i class="fas fa-edit"></i>
                        </router-link >

                        <a class='btn btn-danger text-white mr-2' @click='deleteItem(transaction.id, index)'>
                            <i class="fas fa-trash-alt"></i>
                        </a>
                    </td>
                    <td>{{transaction.id}}</td>
                    <td>{{transaction.description}}</td>
                    <td>{{transaction.category.name}}</td>
                    <td>{{transaction.account.name}}</td>
                    <td>$ {{transaction.amount}}</td>
                    <td>{{transaction.due_at}}</td>
                    <td>
                        <span v-if='transaction.payed == true'>
                            <i class="fas fa-check"></i>
                        </span>

                        <button 
                            class='btn btn-success' 
                            @click.prevent.stop="pay(transaction.id, index)"
                            v-else
                        >
                            <i class="fas fa-check"></i>
                        </button>
                    </td>
                </tr>
            </tbody>
        </table>

        <delete-modal 
            :idTransaction='idTransactionDelete' 
            :indexTransaction='indexTransactionDelete'
            @deleteTransaction='deleteTransaction($event.transactionCount, $event.id, $event.index)'
        />
    </div>
</template>

<script>
    import DeleteModal from './DeleteModal'
    import * as $ from 'jquery';

    export default {
        components: {
            DeleteModal
        },
        data() {
            return {
                transactions: [],
                idTransactionDelete: null,
                indexTransactionDelete: null,
            }
        },
        created() {
            this.$http.get('transactions').then(res => {this.transactions = res.data})
        },
        methods: {
            deleteItem(id, index) {
                if(!this.transactions[index].first_transaction) {
                    this.$http.delete('transactions/'+id)
                        .then(() => {
                            this.transactions.splice(index, 1);
                        })
                        .catch((error) => {
                            console.log(error)
                        });
                }
                else {
                    this.idTransactionDelete = id
                    this.indexTransactionDelete = index

                    $('#modalDelete').modal('show')
                }
            },
            deleteTransaction(transactionCount, id, index)
            {
                $('#modalDelete').modal('hide')
                
                //Delete Transactions
                console.log('Delete: '+transactionCount+' - '+id+' - '+index);
            },
            pay(id, index) {
                //POST PARA ALTERAR STATUS
                this.$http.put('/transaction/'+id+'/pay', {payed: true})
                    .then(() => {
                        this.transactions[index].payed = true
                    })
                    .catch((error) => {
                        console.log(error)
                    });
            }
        }
    }
</script>

<style scoped>
    table tr.income {
        border-right: solid 10px #28a745;
        color: #28a745;
    }
    table tr.income:hover {
        background-color: #28a74588;
    }

    table tr.expense {
        border-right: solid 10px #dc3545;
        color: #dc3545;
    }
    table tr.expense:hover {
        background-color: #dc354588;
    }

    table tr.transfer {
        border-right: solid 10px #17a2b8;
        color: #007bff;
    }
    table tr.transfer:hover {
        background-color: #17a2b888;
    }
</style>