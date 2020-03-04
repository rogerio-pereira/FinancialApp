import Vue from 'vue'
import VueRouter from 'vue-router'

import Home from '../views/Home.vue'
import Accounts from '../views/Accounts.vue'

Vue.use(VueRouter)

const routes = [
  {
    path: '/',
    name: 'Home',
    component: Home
  },
  {
    path: '/Accounts',
    name: 'Accounts',
    component: Accounts
  },
]

const router = new VueRouter({
  mode: 'history',
  base: process.env.BASE_URL,
  routes
})

export default router
