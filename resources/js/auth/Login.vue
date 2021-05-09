<template>
  <div class="w-50 m-auto">
    <div class="card card-body">
      <form>
        <div class="form-group">
          <label for="email">Email</label>
          <input type="email" name="email" placeholder="Enter your email" class="form-control" v-model="email" :class="[{'is-invalid': errorFor('email')}]">
          <validation-errors :errors="errorFor('email')"></validation-errors>
        </div>
        <div class="form-group">
          <label for="password">Password</label>
          <input type="password" name="password" placeholder="Enter your password" class="form-control" v-model="password" :class="[{'is-invalid': errorFor('password')}]">
          <validation-errors :errors="errorFor('password')"></validation-errors>
        </div>
        <button type="submit" class="btn btn-primary btn-lg btn-block" @click.prevent="login" :disabled="loading">Login</button>

        <hr>

        <div>
          No account yet?
          <router-link :to="{name: 'register'}" class="font-weight-bold">Register</router-link>
        </div>
        <div>
          Forgot password?
          <router-link :to="{name: 'home'}" class="font-weight-bold">Reset Password</router-link>
        </div>
      </form>
    </div>
  </div>
</template>

<script>
import validationErrors from '../shared/Mixins/validationErrors'
import { logIn } from './../shared/utils/auth'

export default {
  mixins: [validationErrors],
  data() {
    return {
      email: null,
      password: null,
      loading: false
    }
  },
  methods: {
    async login() {
      this.loading = true
      this.errors = null
      try {
        await axios.get('/sanctum/csrf-cookie')
        await axios.post('/login', {
            email: this.email,
            password: this.password
        })
        // await axios.get('/user')
        logIn()
        this.$store.dispatch('loadUser')
        this.$router.push({name: 'home'})
      } catch(error) {
        this.errors = error.response && error.response.data.errors
      }
      this.loading = false
    }
  }
}
</script>
