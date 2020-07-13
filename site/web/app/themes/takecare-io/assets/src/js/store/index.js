import Vue from 'vue'
import Vuex from 'vuex'
import axios from 'axios'

Vue.use(Vuex)

export default new Vuex.Store({
    state: {
        status: '',
        token: localStorage.getItem('token') || '',
        user : {},
    },
    mutations: {
        auth_request(state){
            state.status = 'loading'
        },
        auth_success(state, token, user){
            state.status = 'success'
            state.token = token
            state.user = user
        },
        auth_error(state){
            state.status = 'error'
        },
        logout(state){
            state.status = ''
            state.token = ''
            state.user = ''
        },
    },
    actions: {
        login({commit}, user){
            return new Promise((resolve, reject) => {
              commit('auth_request')
              axios({url: 'http://127.0.0.1:8000/api/login', data: user, method: 'POST' })
              .then(resp => {
                const token = resp.data.token
                const user = {
                    name: resp.data.user.name,
                    email: resp.data.user.email,
                    settings: resp.data.user.settings,
                    avatar: resp.data.user.avatar,
                    created_at: resp.data.user.created_at
                }
                localStorage.setItem('token', 'Bearer' + token)
                localStorage.setItem('user', JSON.stringify(user))
                axios.defaults.headers.common['Authorization'] = token
                commit('auth_success', token, user)
                resolve(resp)
              })
              .catch(err => {
                commit('auth_error')
                localStorage.removeItem('token')
                localStorage.removeItem('user')
                reject(err)
              })
            })
        },
        register({commit}, user){
            return new Promise((resolve, reject) => {
                commit('auth_request')
                axios({url: 'http://127.0.0.1:8000/api/register', data: user, method: 'POST' })
            .then(resp => {
                const token = resp.data.token
                const user = {
                    name: resp.data.user.name,
                    email: resp.data.user.email,
                    settings: resp.data.user.settings,
                    avatar: resp.data.user.avatar,
                    created_at: resp.data.user.created_at
                }
                localStorage.setItem('token', token)
                localStorage.setItem('user', JSON.stringify(user))
                axios.defaults.headers.common['Authorization'] = 'Bearer' + token
                commit('auth_success', token, user)
                resolve(resp)
            })
            .catch(err => {
                commit('auth_error', err)
                localStorage.removeItem('token')
                localStorage.removeItem('user')
                reject(err)
            })
          })
        },
        logout({commit}){
        return new Promise((resolve, reject) => {
           commit('logout')
            localStorage.removeItem('token')
            localStorage.removeItem('user')
            delete axios.defaults.headers.common['Authorization']
            resolve()
          })
        }  
    },
    getters : {
        isLoggedIn: state => !!state.token,
        authStatus: state => state.status,
        currentUser: state => JSON.parse(localStorage.getItem('user')),
    }
})
