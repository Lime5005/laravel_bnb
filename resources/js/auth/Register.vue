<template>
  <div class="w-50 m-auto">
    <div class="card card-body">
      <form>
        <div class="form-group">
          <label for="name">Name</label>
          <input type="name" name="name" placeholder="Enter your name" class="form-control" v-model="user.name" :class="[{'is-invalid': errorFor('name')}]">
          <validation-errors :errors="errorFor('name')"></validation-errors>
        </div>
        <div class="form-group">
          <label for="email">Email</label>
          <input type="email" name="email" placeholder="Enter your email" class="form-control" v-model="user.email" :class="[{'is-invalid': errorFor('email')}]">
          <validation-errors :errors="errorFor('email')"></validation-errors>
        </div>

        <div class="form-group">
          <label for="password">Password</label>
          <input type="password" name="password" placeholder="Enter your password" class="form-control" v-model="user.password" :class="[{'is-invalid': errorFor('password')}]">
          <validation-errors :errors="errorFor('password')"></validation-errors>
        </div>
        <div class="form-group">
          <label for="password">Confirm Password</label>
          <input type="password" name="password_confirmation" placeholder="Confirm your password" class="form-control" v-model="user.password_confirmation" :class="[{'is-invalid': errorFor('password_confirmation')}]">
          <validation-errors :errors="errorFor('password_confirmation')"></validation-errors> <!-- 'password_confirmation' is define in Laravel Register user file, we should use the same name -->
        </div>
        
        <button type="submit" class="btn btn-primary btn-lg btn-block" @click.prevent="register" :disabled="loading">Register</button>

        <hr>

        <div>
          Already have an account?
          <router-link :to="{name: 'login'}" class="font-weight-bold">Login</router-link>
        </div>
      </form>
    </div>
  </div>
</template>

<script>
import validationErrors from '../shared/Mixins/validationErrors'
import { logIn } from '../shared/utils/auth'

export default {
  mixins: [validationErrors],
  data() {
    return {
      user: {
        name: null,
        email: null,
        password: null,
        password_confirmation: null
      },
      loading: false
    }
  },
  methods: {
    async register() {
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
