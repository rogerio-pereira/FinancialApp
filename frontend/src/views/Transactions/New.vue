<template>
    <div class='card'>
        <div class="card-header">
            <strong>New Category</strong>
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
                category: {
                    id: null,
                    name: '',
                }
            }
        },
        methods: {
            save(data) {
                this.$http.post('categories', data)
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