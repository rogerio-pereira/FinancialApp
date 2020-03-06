<template>
    <div class='card'>
        <div class="card-header">
            <strong>Edit Category</strong>
        </div>

        <div class='col-8 offset-2'>
            <form-category :category='category' @save='save($event.data)'/>
        </div>
    </div>
</template>

<script>
    import FormCategory from './Form'

    export default {
        components: {
            FormCategory
        },
        data() {
            return {
                category: {}
            }
        },
        created() {
            this.$http.get('categories/'+this.$route.params.id)
                .then(response => {
                    this.category = response.data
                })
                .catch(error => {
                    console.log('Error at fetching category\n'+error)
                })
        },
        methods: {
            save(data) {
                this.$http.put('categories/'+this.category.id, data)
                    .then(() => {
                        this.$router.push({ name: 'categories.index'})
                    })
                    .catch(error => {
                        console.log('Error at saving\n'+error)
                    })
            }
        }
    }
</script>

<style scoped>

</style>