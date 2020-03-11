<template>
    <div class='row'>
        <input type='hidden' id='id' v-model='bankAccount.id'>
        
        <div class="form-group col-md-6">
            <label for="name">Name</label>
            <input type="text" class="form-control" id="name" v-model='bankAccount.name'>
            <div class='text-danger' v-if='errors.name'>
                <small>
                    <p v-for='(error, index) in errors.name' :key='index'>{{error}}</p>
                </small>
            </div>
        </div>
        <div class="form-group col-md-6">
            <label for="initialBalance">Initial Balance</label>
            <!-- <input type="number" class="form-control" id="initialBalance" v-model='bankAccount.initialBalance'> -->
            <input type="text" class="form-control" id="initialBalance" v-model='bankAccount.initialBalance'>
            <div class='text-danger' v-if='errors.initialBalance'>
                <small>
                    <p v-for='(error, index) in errors.initialBalance' :key='index'>{{error}}</p>
                </small>
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
            bankAccount: {type: Object, required: true},
            errors: {type: Object, required:false}
        },
        /*data() {
            return {
                bankAccount: {
                    id: null,
                    name: '',
                    initialBalance: 0
                }
            }
        },*/
        methods: {
            save(){
                this.$emit('save', {data: this.bankAccount})
            }
        }
    }
</script>

<style scoped>

</style>