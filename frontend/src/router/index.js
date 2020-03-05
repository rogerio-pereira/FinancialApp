import Vue from 'vue'
import VueRouter from 'vue-router'

import Home from '../views/Home.vue'
import Login from '../views/Login.vue'

import AccountsIndex from '../views/Accounts/Index.vue'
import AccountList from '../views/Accounts/List.vue'
import AccountNew from '../views/Accounts/New.vue'
import AccountEdit from '../views/Accounts/Edit.vue'

Vue.use(VueRouter)

const routes = [
  {
    path: '/',
    name: 'home',
    component: Home
  },
  {
    path: '/login',
    name: 'login',
    component: Login
  },
  {
    path: '/accounts',
    name: 'accounts',
    component: AccountsIndex,
    children: [
      {path: '', component: AccountList, props: true, name: 'accounts.index'},
      {path: 'new', component: AccountNew, props: true, name: 'accounts.new'},
      {path: 'edit/:id', component: AccountEdit, props: true, name: 'accounts.edit'},
    ]
  },
]

const router = new VueRouter({
  mode: 'history',
  base: process.env.BASE_URL,
  routes
})

export default router