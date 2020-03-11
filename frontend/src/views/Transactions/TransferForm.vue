<template>
    <div class='row'>
        <div class="form-group col-md-6">
            <label for="from">From</label>
            <select id='from' v-model='transfer.from' class='form-control'>
                <option v-for="account in bankAccounts" :key='account.id' :value='account.id'>
                    {{account.name}}
                </option>
            </select>
            <div class='text-danger' v-if='errors.from'>
                <small>
                    <p v-for='(error, index) in errors.from' :key='index'>{{error}}</p>
                </small>
            </div>
        </div>

        <div class="form-group col-md-6">
            <label for="to">To</label>
            <select id='to' v-model='transfer.to' class='form-control'>
                <option v-for="account in bankAccounts" :key='account.id' :value='account.id'>
                    {{account.name}}
                </option>
            </select>
            <div class='text-danger' v-if='errors.to'>
                <small>
                    <p v-for='(error, index) in errors.to' :key='index'>{{error}}</p>
                </small>
            </div>
        </div>

        <div class="form-group col-md-6">
            <label for="category_id">Category</label>
            <select id='category_id' v-model='transfer.category_id' class='form-control'>
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
            <label for="due_at">Date</label>
            <input type="date" class="form-control" id="due_at" v-model='transfer.due_at'>
            <div class='text-danger' v-if='errors.due_at'>
                <small>
                    <p v-for='(error, index) in errors.due_at' :key='index'>{{error}}</p>
                </small>
            </div>
        </div>

        <div class="form-group col-md-6">
            <label for="amount">Amount</label>
            <input type="text" class="form-control" id="amount" v-model='transfer.amount'>
            <div class='text-danger' v-if='errors.amount'>
                <small>
                    <p v-for='(error, index) in errors.amount' :key='index'>{{error}}</p>
                </small>
            </div>
        </div>

        <div class="form-group col-md-6">
            <label for="payed">Payed</label>
            <select id='payed' v-model='transfer.payed' class='form-control'>
                <option value='false'>No</option>
                <option value='true'>Yes</option>
            </select>
            <div class='text-danger' v-if='errors.payed'>
                <small>
                    <p v-for='(error, index) in errors.payed' :key='index'>{{error}}</p>
                </small>
            </div>
        </div>

        <div class='form-group text-center col-md-12'>
            <button type="submit" class="btn btn-success" @click.stop.prevent='save'>Save</button>
        </div>
    </div>
</template>

<script>
    import moment from 'moment'

    export default {
        data() {
            return {
                transfer: {
                    from: '',
                    to: '',
                    category_id: '',
                    due_at: moment(new Date()).format('YYYY-MM-DD'),
                    amount: 0,
                    payed: false
                },
                bankAccounts: '',
                categories: '',
                errors: {}
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
                this.$http.post('transactions/new/transfer', this.transfer)
                    .then((data) => {
                        console.log(data)
                        this.$router.push({ name: 'transactions.index'})
                    })
                    .catch((error) => {
                        console.log(error)
                        this.errors = error.response.data.errors
                    })
            },
        }
    }
</script>

<style scoped>

</style>