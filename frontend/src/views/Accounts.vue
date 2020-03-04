<template>
    <div>
        Teste
        <table class="table table-hovered table-responsive table-striped table-bordered">
            <thead>
                <tr>
                    <th></th>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Initial Balance</th>
                </tr>
            </thead>
            <tbody>
                <tr v-for='bankAccount in bankAccounts' :key='bankAccount.id'>
                    <td></td>
                    <td>{{bankAccount.id}}</td>
                    <td>{{bankAccount.name}}</td>
                    <td>{{bankAccount.initialBalance}}</td>
                </tr>
            </tbody>
        </table>
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
            this.$http.get('bank-accounts').then(res => {this.bankAccounts = res.data; console.log(this.bankAccounts)})
        }
    }
</script>

<style scoped>

</style>