import Vue from 'vue'
import VueRouter from 'vue-router'

import Home from '../views/Home.vue'
import Login from '../views/Login.vue'

import AccountsIndex from '../views/Accounts/Index.vue'
import AccountList from '../views/Accounts/List.vue'
import AccountNew from '../views/Accounts/New.vue'
import AccountEdit from '../views/Accounts/Edit.vue'

import CategoryIndex from '../views/Categories/Index.vue'
import CategoryList from '../views/Categories/List.vue'
import CategoryNew from '../views/Categories/New.vue'
import CategoryEdit from '../views/Categories/Edit.vue'

import TransactionIndex from '../views/Transactions/Index.vue'
import TransactionList from '../views/Transactions/List.vue'
import TransactionNew from '../views/Transactions/New.vue'
import TransactionEdit from '../views/Transactions/Edit.vue'
import TransferForm from '../views/Transactions/TransferForm.vue'

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
  {
    path: '/categories',
    name: 'categories',
    component: CategoryIndex,
    children: [
      {path: '', component: CategoryList, props: true, name: 'categories.index'},
      {path: 'new', component: CategoryNew, props: true, name: 'categories.new'},
      {path: 'edit/:id', component: CategoryEdit, props: true, name: 'categories.edit'},
    ]
  },
  {
    path: '/transactions',
    name: 'transactions',
    component: TransactionIndex,
    children: [
      {path: '', component: TransactionList, props: true, name: 'transactions.index'},
      {path: 'new', component: TransactionNew, props: true, name: 'transactions.new'},
      {path: 'edit/:id', component: TransactionEdit, props: true, name: 'transactions.edit'},
      {path: 'new-transfer', component: TransferForm, props: true, name: 'transactions.new.transfer'},
    ]
  },
]

const router = new VueRouter({
  mode: 'history',
  base: process.env.BASE_URL,
  routes
})

export default router