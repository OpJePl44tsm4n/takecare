import Vue from 'vue'
import VueRouter from 'vue-router'
import store from '../store/index.js'
import Login from '../components/UserAuth.vue'
import Account from '../components/Account.vue'
import Register from '../components/Register.vue'

Vue.use(VueRouter)

const routes = [
  {
    path: '/account/login',
    name: 'login',
    component: Login
  },
  {
    path: '/register',
    name: 'register',
    component: Register
  },
  {
    path: '/account',
    name: 'Account',
    component: Account,
    meta: {
      requiresAuth: true
    }
  }
]

const router = new VueRouter({
  routes,
  mode: 'history'
})

router.beforeEach((to, from, next) => {
  if (to.matched.some(record => record.meta.requiresAuth)) {
    if (store.getters.isLoggedIn) {
      next()
      return
    }
    next('/account/login')
  } else {
    next()
  }
})

export default router
