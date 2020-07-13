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
                <user-login :apiResponse="apiResponse" @login:user="loginUser" />
            </div>

            <div class="form-container register-container">
                <user-register :apiResponse="apiResponse" @register:user="registerUser" />
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
                showLoginForm: true,
                apiResponse: Object
            } 
        },
        mounted() {
            // loads the users when component is mounted
        },
        methods: {
            toggleUserForm() {
                this.showLoginForm = !this.showLoginForm
            },
        
            async registerUser(user) {
                let data = {
                    email: user.email,
                    password: user.password,
                    password_confirmation: user.passwordCopy,
                    agreed_terms: user.agreeTerms,
                }

               this.$store.dispatch('register', data)
               .then(() => this.$router.push('/account'))
               .catch(err => console.log(err))
            },
            async loginUser(user) {
                let email = user.email 
                let password = user.password
                this.$store.dispatch('login', { email, password })
               .then(() => this.$router.push('/account'))
               .catch(err => console.log(err))
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
