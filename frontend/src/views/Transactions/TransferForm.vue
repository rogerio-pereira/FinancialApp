<template>
    <div class='row'>
        <div class="form-group col-md-6">
            <label for="from">From</label>
            <select id='from' v-model='from' class='form-control'>
                <option v-for="account in bankAccounts" :key='account.id' :value='account.id'>
                    {{account.name}}
                </option>
            </select>
        </div>

        <div class="form-group col-md-6">
            <label for="to">To</label>
            <select id='to' v-model='to' class='form-control'>
                <option v-for="account in bankAccounts" :key='account.id' :value='account.id'>
                    {{account.name}}
                </option>
            </select>
        </div>

        <div class="form-group col-md-6">
            <label for="date">Date</label>
            <input type="date" class="form-control" id="date" v-model='date'>
        </div>

        <div class="form-group col-md-6">
            <label for="amount">Amount</label>
            <input type="text" class="form-control" id="amount" v-model='amount'>
        </div>

        <div class='form-group text-center col-md-12'>
            <button type="submit" class="btn btn-success" @click.stop.prevent='save'>Save</button>
        </div>
    </div>
</template>

<script>
    export default {
        data() {
            return {
                from: '',
                to: '',
                date: '',
                amount: '',
                bankAccounts: ''
            }
        },
        created() {
            this.$http.get('bank-accounts/combobox')
                .then((data) => {
                    this.bankAccounts = data.data
                    console.log(data.data);
                })
                .catch((error) => {
                    console.log('Failed to fetch bank accounts\n'+error);
                })
        }
    }
</script>

<style scoped>

</style>