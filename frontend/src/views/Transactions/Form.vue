<template>
    <div class='row'>
        <input type='hidden' id='id' v-model='transaction.id'>
        <input type='hidden' id='type' v-model='transaction.type'>
        
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
            <label for="due_at">Date</label>
            <input type="date" class="form-control" id="due_at" v-model='transaction.due_at'>
            <div class='text-danger' v-if='errors.due_at'>
                <small>
                    <p v-for='(error, index) in errors.due_at' :key='index'>{{error}}</p>
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

        <div class="form-group col-md-6">
            <label for="payed">Payed</label>
            <select id='payed' v-model='transaction.payed' class='form-control'>
                <option value='0'>No</option>
                <option value='1'>Yes</option>
            </select>
            <div class='text-danger' v-if='errors.payed'>
                <small>
                    <p v-for='(error, index) in errors.payed' :key='index'>{{error}}</p>
                </small>
            </div>
        </div>

        <div class="form-group col-md-12 text-center">
            <input type='checkbox' id='repeat' v-model='transaction.repeat'> &nbsp; <label for="repeat">Repeat</label>
        </div>

        <transition enter-active-class="animated fadeInDown" leave-active-class="animated fadeOutUp" mode='out-in'>
            <div class='col-md-12' v-if='transaction.repeat'>
                <div class='row'>
                    <div class="form-group col-md-6">
                        <label for="repeatTimes">Repeat Times</label>
                        <input type='number' min='2' id='repeatTimes' class='form-control' v-model='transaction.repeatTimes'>
                        <div class='text-danger' v-if='errors.repeatTimes'>
                            <small>
                                <p v-for='(error, index) in errors.repeatTimes' :key='index'>{{error}}</p>
                            </small>
                        </div>
                    </div>

                    <div class="form-group col-md-6">
                        <label for="period">Period</label>
                        <select id='period' v-model='transaction.period' class='form-control'>
                            <option value='Daily'>Daily</option>
                            <option value='Weekly'>Weekly</option>
                            <option value='Biweekly'>Biweekly</option>
                            <option value='Monthly'>Monthly</option>
                            <option value='Quarterly'>Quarterly</option>
                            <option value='Semiannually'>Semiannually</option>
                            <option value='Annually'>Annually</option>
                        </select>
                        <div class='text-danger' v-if='errors.period'>
                            <small>
                                <p v-for='(error, index) in errors.period' :key='index'>{{error}}</p>
                            </small>
                        </div>
                    </div>
                </div>
            </div>
        </transition>

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
                console.log(this.transaction)
                this.$emit('save', {data: this.transaction})
            }
        }
    }
</script>

<style scoped>

</style>