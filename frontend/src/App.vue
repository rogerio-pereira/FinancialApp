<template>
    <div id="app">
        <menu-app />

        <div class='row content my-3 mb-5'>
            <div class='container'>
                <router-view/>
            </div>
        </div>

        <footer-app />
    </div>
</template>

<script>
    import MenuApp from './components/template/Menu'
    import FooterApp from './components/template/Footer'

    export default {
        components: {
            MenuApp,
            FooterApp
        }
    }
</script>

<style>
    * {
        font-family: 'Open Sans Condensed', sans-serif;
    }

    body {
        background: #ece9e6; /* fallback for old browsers */
        background: -webkit-linear-gradient(to top, #ece9e6, #ffffff); /* Chrome 10-25, Safari 5.1-6 */
        background: linear-gradient(to top, #ece9e6, #ffffff); /* W3C, IE 10+/ Edge, Firefox 16+, Chrome 26+, Opera 12+, Safari 7+ */

        overflow-x: hidden;
        font-size: 1.4rem !important;
    }

    h1 {
        font-size: 2.2em;
        font-weight: bolder;
    }

    div.content{
        min-height: 500px;
    }
</style>
