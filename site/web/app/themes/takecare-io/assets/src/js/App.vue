<template>
  <div style="margin-top:5em;" id="app" 
   :class="['account', isLoggedIn ? 'logged-in' : '']">
    <div id="nav">
      <span v-if="isLoggedIn"><a @click="logout">Logout</a></span>
    </div>
    <div class="container">
      <router-view/>
    </div>
  </div>
</template>

<script>
export default {
  computed: {
    isLoggedIn: function () { return this.$store.getters.isLoggedIn }
  },
  created: function () {
    this.$http.interceptors.response.use(undefined, function (err) {
      return new Promise(function (resolve, reject) {
        if (err.status === 401 && err.config && !err.config.__isRetryRequest) {
          this.$store.dispatch('logout')
        }
        throw err
      })
    })
  },
  methods: {
    logout: function () {
      this.$store.dispatch('logout')
        .then(() => {
          this.$router.push('/account/login')
        })
    }
  }
}
</script>