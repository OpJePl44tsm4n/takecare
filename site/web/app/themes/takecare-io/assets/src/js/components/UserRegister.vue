<template>
	<div id="user-register">

		<form @submit.prevent="handleSubmit">
			<div class="input-wrap">
                <input
                    ref="first"
                    type="text"
                    id="user-email" 
                    name="user-email"
                    autocomplete="email"
                    v-model="user.email"  
                    placeholder="Email address" 
                    :class="{ 'has-error': submitting && invalidEmail }"
                    @focus="clearStatus"
                />
            </div>
            <div class="input-wrap">    
                <input 
                    :type="[showPassword ? 'text' : 'password']"
                    id="user-password" 
                    name="user-password"
                    v-model="user.password" 
                    autocomplete="new-password"  
                    placeholder="Password" 
                    :class="{ 'has-error': submitting && invalidPassword }"
                    @focus="clearStatus"
                    @keypress="clearStatus"
                />
                <button 
                    type="button" 
                    @click="togglePassword" 
                    :class="['password-toggle', showPassword ? 'show' : '']"
                    title="show password">
                        <i class="fas fa-eye"></i>
                        <i class="fas fa-eye-slash"></i>
                    </button>
            </div>
            <div class="input-wrap">
                <input 
                    :type="[showPassword ? 'text' : 'password']"
                    id="user-password-copy" 
                    name="user-password-copy"
                    v-model="user.passwordCopy"   
                    autocomplete="new-password"
                    placeholder="Repeat password" 
                    :class="{ 'has-error': submitting && invalidPassword }"
                    @focus="clearStatus"
                    @keypress="clearStatus"
                />
            </div>
            <div class="input-wrap">
                <input 
                    type="checkbox" 
                    name="agree-terms"
                    id="agree-terms"
                    :class="{ 'has-error': submitting && termsNotAgreed }"
                    v-model="user.agreeTerms"
                    @focus="clearStatus"   
                /><label for="agree-terms">I agree with the terms and conditions</label>
            </div>

            <p v-if="error && submitting" class="error-message">
                ❗Please fill out all required fields
            </p>

            <p v-if="error && submitting && invalidPassword" class="error-message">
                ❗Passwords don't match
            </p>

            <p v-if="error && submitting && termsNotAgreed" class="error-message">
                ❗Please accept the terms
            </p>

            <p v-if="Object.keys(apiResponse).length > 0 && apiResponse.status " 
                :class="[apiResponse.status === 'error' ? 'error-message' : 'succes-message']">
              {{ apiResponse.message }}
            </p>
           

            <button class="btn btn-primary" type="submit">Sign up</button>

		</form>
	</div>
</template>

<script>
	export default {
		name: 'user-register',
		props: {
            apiResponse: new Object,
        },
		data() {
			return {
				submitting: false,
                error: false,
                success: false,
                showPassword: false,
                user: {
                    email: '',
                    password: '',
                    passwordCopy: '',
                    agreeTerms: '',
                },
			}
		},
		methods: {
			handleSubmit() {
				this.submitting = true
				this.clearStatus()

                if(this.invalidPassword || this.invalidEmail || this.termsNotAgreed){
                    this.error = true 
                    return
                }

				this.$emit('register:user', this.user)
				this.$refs.first.focus()

				this.user = {
					email: '',
                    password: '',
                    passwordCopy: '',
                    agreeTerms: '',
				}

				this.success = true
				this.error = false
				this.submitting = false
			},
			clearStatus() {
				this.success = false
				this.error = false
			},
			togglePassword(event) {
                event.prevent
                this.showPassword = !this.showPassword
            }
		},
		computed: {
			invalidPassword() {
				return this.user.password !== this.user.passwordCopy
			}, 
			invalidEmail() {
				return this.user.email === ''
			},
			termsNotAgreed() {
				return this.user.agreeTerms !== true
			}

		}
	};
</script>

<style scoped>
	#user-signup {
		max-width: 400px;
		margin: auto; 
		text-align: center; 
	}

    #user-signup label {
        display: inline-block;
        padding: 0 .5em;
    }

    button[type="submit"] {
        margin-top: 1em;
        width: 100%; 
    }

    .input-wrap {
        position: relative;
    } 

    .input-wrap input + label {
        padding-left: 1em;
    }

    .password-toggle {
        position: absolute;
        top: 0;
        right: 0;
        height: 100%;
        background: none;
        border-top-left-radius: unset;
        border-bottom-left-radius: unset;
        border-color: rgba(0,0,0,0);
        opacity: .4;
        outline: none;
    }

    .password-toggle .fa-eye-slash {
        position: absolute;
        right: 1em;
        top: 50%;
        transform: translateY(-50%);
        opacity: 0;
        transition: all 0.3s ease-in-out;  
    }

    .password-toggle .fa-eye {
        position: absolute;
        right: 1em;
        top: 50%;
        transform: translateY(-50%);
        opacity: 1;  
        transition: all 0.6s ease-in-out;
    }

    .password-toggle.show .fa-eye-slash {
        opacity: 1;  
    }

    .password-toggle.show .fa-eye {
        opacity: 0;  
    }

    [class*='-message'] {
        font-weight: 500;
    }

    .error-message {
        color: #d33c40;
    }

    .success-message {
        color: #32a95d;
    }
</style>