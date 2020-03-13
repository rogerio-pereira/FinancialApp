<template>
    <div class='row'>
        <input type='hidden' id='id' v-model='transaction.id'>
        
        <div class="form-group col-md-6">
            <label for="description">Description</label>
            <input type='text' id='description' class='form-control' v-model='transaction.description'>
            <div class='text-danger' v-if='errors.description'>
                <small>
                    <p v-for='(error, index) in errors.description' :key='index'>{{error}}</p>
                </small>
            </div>
        </div>
        
        <div class="form-group col-md-6">
            <label for="amount">Amount</label>
            <input type='number' min='0' id='amount' class='form-control' v-model='transaction.amount'>
            <div class='text-danger' v-if='errors.amount'>
                <small>
                    <p v-for='(error, index) in errors.amount' :key='index'>{{error}}</p>
                </small>
            </div>
        </div>

        <div class="form-group col-md-6">
            <label for="category_id">Category</label>
            <select id='category_id' v-model='transaction.category_id' class='form-control'>
                <option v-for="category in categories" :key='category.id' :value='category.id'>
                    {{category.name}}
                </option>
            </select>
            <div class='text-danger' v-if='errors.category_id'>
                <small>
                    <p v-for='(error, index) in errors.category_id' :key='index'>{{error}}</p>
                </small>
            </div>
        </div>
        
        <div class="form-group col-md-6">
            <label for="account_id">Account</label>
            <select id='account_id' v-model='transaction.account_id' class='form-control'>
                <option v-for="account in bankAccounts" :key='account.id' :value='account.id'>
                    {{account.name}}
                </option>
            </select>
            <div class='text-danger' v-if='errors.account_id'>
                <small>
                    <p v-for='(error, index) in errors.account_id' :key='index'>{{error}}</p>
                </small>
            </div>
        </div>

        <div class='row' v-if='transaction.first_transaction'>
            <div class='col-md-12 text-center mt-5 mb-4'>
                This is a repeated transaction, which one(s) do you want to update?
            </div>
            <div class='form-group col-md-4 text-center'>
                <input type='radio' name='repeatCount' id='repeatCountThis' v-model='transaction.repeatCount' value='this'>
                &nbsp;&nbsp;
                <label for="repeatCountThis">Just this</label>
            </div>
            <div class='form-group col-md-4 text-center'>
                <input type='radio' name='repeatCount' id='repeatCountNext' v-model='transaction.repeatCount' value='next'>
                &nbsp;&nbsp;
                <label for="repeatCountNext">This and next ones</label>
            </div>
            <div class='form-group col-md-4 text-center'>
                <input type='radio' name='repeatCount' id='repeatCountAll' v-model='transaction.repeatCount' value='all'>
                &nbsp;&nbsp;
                <label for="repeatCountAll">All Transactions</label>
            </div>
        </div>

        <div class='form-group text-center col-md-12'>
            <button type="submit" class="btn btn-success" @click.stop.prevent='save'>Save</button>
        </div>
    </div>
</template>

<script>
    export default {
        props: {
            transaction: {type: Object, required: true},
            errors: {type: Object, required: true}
        },
        data() {
            return {
                // transaction: {
                //     description: null,
                //     amount: null,
                //     type: this.type,
                //     is_transaction: null,
                //     due_at: null,
                //     category_id: null,
                //     account_id: null,
                //     first_transaction: null,
                //     payed: null,
                // },
                bankAccounts: {},
                categories: {},
            }
        },
        created() {
            this.$http.get('bank-accounts/combobox')
                .then((data) => {
                    this.bankAccounts = data.data
                })
                .catch((error) => {
                    console.log('Failed to fetch bank accounts\n'+error);
                })
            this.$http.get('categories/combobox')
                .then((data) => {
                    this.categories = data.data
                })
                .catch((error) => {
                    console.log('Failed to fetch bank accounts\n'+error);
                })
        },
        methods: {
            save(){
                this.$emit('save', {data: this.transaction})
            }
        }
    }
</script>

<style scoped>

</style>