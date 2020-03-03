<template>
    <div id="app">
        <menu-app />
        <h1>App</h1>
        <router-view/>
    </div>
</template>

<script>
    import MenuApp from './components/template/Menu'

    export default {
        components: {
            MenuApp
        }
    }
</script>

<style>
    * {
        font-family: 'Open Sans Condensed', sans-serif;
        font-size: 1.6m;
    }
</style>
