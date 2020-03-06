<template>
    <div>
        <h1 class='text-center'>Categories</h1>

            <div class='text-center my-4'>
                <router-link to='/categories/new' class='btn btn-lg btn-success'>
                    <i class="fas fa-plus-circle"></i> &nbsp; New
                </router-link>
            </div>


            <table class="table text-center table-sm table-hover table-responsive-sm table-striped table-bordered">
                <thead>
                    <tr>
                        <th width='120px'></th>
                        <th>ID</th>
                        <th>Name</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for='(category, index) in categories' :key='index'>
                        <td>
                            <router-link :to="'/categories/edit/'+category.id" class='btn btn-info text-white mr-2'>
                                <i class="fas fa-edit"></i>
                            </router-link >
                            <a class='btn btn-danger text-white' @click='deleteItem(category.id, index)'>
                                <i class="fas fa-trash-alt"></i>
                            </a>
                        </td>
                        <td>{{category.id}}</td>
                        <td>{{category.name}}</td>
                        <td>{{category.initialBalance}}</td>
                    </tr>
                </tbody>
            </table>
    </div>
</template>

<script>
    export default {
        data() {
            return {
                categories: []
            }
        },
        created() {
            this.$http.get('categories').then(res => {this.categories = res.data})
        },
        methods: {
            deleteItem(id, index) {
                this.$http.delete('categories/'+id)
                    .then(() => {
                        this.categories.splice(index, 1);
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