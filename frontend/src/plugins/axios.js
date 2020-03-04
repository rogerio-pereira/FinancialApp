import Vue from 'vue'
import axios from 'axios'

Vue.use({
    install(Vue) {
        Vue.prototype.$http = axios
        Vue.prototype.$http = axios.create({
            baseURL: 'https://localhost:8000/api/',
        });
    }
})