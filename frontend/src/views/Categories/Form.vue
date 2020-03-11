<template>
    <div class='row'>
        <input type='hidden' id='id' v-model='category.id'>
        
        <div class="form-group col-md-12">
            <label for="name">Name</label>
            <input type="text" class="form-control" id="name" v-model='category.name'>
            <div class='text-danger' v-if='errors.name'>
                <small>
                    <p v-for='(error, index) in errors.name' :key='index'>{{error}}</p>
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
            category: {type: Object, required: true},
            errors: {type: Object, required:false}
        },
        /*data() {
            return {
                category: {
                    id: null,
                    name: '',
                    initialBalance: 0
                }
            }
        },*/
        methods: {
            save(){
                this.$emit('save', {data: this.category})
            }
        }
    }
</script>

<style scoped>

</style>