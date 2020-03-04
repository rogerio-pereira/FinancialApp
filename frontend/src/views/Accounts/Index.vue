<template>
    <div class='row'>
        <div class='col-md-12'>
            <h1 class='text-center'>Bank Accounts</h1>

            <div class='text-center my-4'>
                <a href='' class='btn btn-lg btn-success'><i class="fas fa-plus-circle"></i> &nbsp; New</a>
            </div>


            <table class="table text-center table-sm table-hover table-responsive-sm table-striped table-bordered">
                <thead>
                    <tr>
                        <th width='120px'></th>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Balance</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for='(bankAccount, index) in bankAccounts' :key='index'>
                        <td>
                            <a href='' class='btn btn-info text-white mr-2'><i class="fas fa-edit"></i></a>
                            <a class='btn btn-danger text-white' @click='deleteBankAccount(bankAccount.id, index)'><i class="fas fa-trash-alt"></i></a>
                        </td>
                        <td>{{bankAccount.id}}</td>
                        <td>{{bankAccount.name}}</td>
                        <td>{{bankAccount.initialBalance}}</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</template>

<script>
    export default {
        data() {
            return {
                bankAccounts: []
            }
        },
        created() {
            this.$http.get('bank-accounts').then(res => {this.bankAccounts = res.data})
        },
        methods: {
            deleteBankAccount(id, index) {
                this.$http.delete('bank-accounts/'+id)
                    .then(() => {
                        this.bankAccounts.splice(index, 1);
                    })
                    .catch((error) => {
                        console.log(error)
                    });
            }
        }
    }
</script>

<style scoped>

</style>