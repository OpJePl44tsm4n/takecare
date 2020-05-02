<template>
    <div id="user-auth" class="card">
        <div class="row">
            <div class="col-6">
                <button 
                    :class="['btn', 'btn-nav', showLoginForm ? 'active' : '']" 
                    id="signIn"
                    @click="toggleUserForm">Sign In</button>
            </div>
            <div class="col-6">
                <button 
                    :class="['btn', 'btn-nav', showLoginForm ? '' : 'active']" 
                    id="register"
                    @click="toggleUserForm">Register</button>
            </div>
        </div>
        
        <div :class="['form-wrap', showLoginForm ? 'login-active' : '']">
            <div class="form-container log-in-container">
                <user-login :currentUser="currentUser" @login:user="loginUser" />
            </div>

            <div class="form-container register-container">
                <user-register :currentUser="currentUser" @register:user="registerUser" />
            </div>
        </div>

    </div>
</template>

<script>
    // import UserTable from '@/components/UserTable.vue'
    import UserRegister from './UserRegister'
    import UserLogin from './UserLogin'

    export default {
        name: 'user-auth',
        components: {
            UserLogin,
            UserRegister,
        },
        data() {
            return {
                users: [],
                currentUser: Object,
                showLoginForm: true
            } 
        },
        mounted() {
            // loads the users when component is mounted
        },
        methods: {
            toggleUserForm() {
                this.showLoginForm = !this.showLoginForm
            },
            // async editUser(id, updatedUser){
            //     try {
            //         const response = await fetch(`https://jsonplaceholder.typicode.com/users/${id}`, {
            //             method: 'PUT', 
            //             body: JSON.stringify(updatedUser),
            //             headers: { 'Content-type': 'application/json; charset=UTF-8' },
            //         }) 
            //         const data = await response.json()
            //         this.users = this.users.map(user => (user.id === id ? data : user) )
            //     } catch (error) {
            //         console.error(error)
            //     }
            // },
            // async deleteUser(id) {
            //     try {
            //         await fetch(`https://jsonplaceholder.typicode.com/users/${id}`, {
            //             method: 'DELETE', 
            //         }) 
            //         this.users = this.users.filter(user => user.id !== id) 
            //     } catch (error) {
            //         console.error(error)
            //     }
            // },
            async registerUser(user) {
                try {
                    var urlencoded = new URLSearchParams();
                    urlencoded.append('email', user.email);
                    urlencoded.append('password', user.password);
                    urlencoded.append('c_password', user.passwordCopy);
                    const response = await fetch('https://api.takecare.io/api/register', {
                        method: 'POST',
                        body: urlencoded,
                        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                    })
                    const data = await response.json()
                    this.currentUser = data;
                } catch (error) {
                    console.error(error)
                }
            },
            async loginUser(user) {

                try {
                    var urlencoded = new URLSearchParams();
                    urlencoded.append('email', user.email);
                    urlencoded.append('password', user.password);
                    urlencoded.append('remember', user.remember);
                    await fetch('https://api.takecare.io/api/login', {
                        method: 'POST',
                        body: urlencoded,
                        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                    }).then(function (response) {
                        console.log(response.json());
                        if (response.status === 200 && 'token' in response.body) {
                          this.currentUser = response.body;
                          this.$session.start()
                          this.$session.set('jwt', response.body.token)
                          window.Vue.http.headers.common['Authorization'] = 'Bearer ' + response.body.token
                        }
                    }, function (err) {
                        console.log('err', err)
                    })
                } catch (error) {
                    console.error(error)
                }
            },

        }
    };
</script>

<style>

    #user-auth {
        max-width: 400px;
        overflow: hidden;
        min-height: 400px;
        padding: 1.5em;
    }

    #user-auth .form-wrap {
        position: relative;
        padding-top: 2em;
        width: 200%;
    }

    #user-auth .form-container {
        transition: all 0.6s ease-in-out;
        display: inline-block;
        float: left;
    }

    #user-auth .log-in-container {
        transform: translateX(-100%);
        width: 50%;
        opacity: 0;
    }

    #user-auth .register-container {
        width: 50%;
        opacity: 1;
        transform: translateX(-100%);
    }

    #user-auth .login-active .register-container {
        transform: translateX(100%);
        opacity: 0;
    }

    #user-auth .login-active .log-in-container {
        opacity: 1;
        transform: translateX(0%);
    }

</style>
