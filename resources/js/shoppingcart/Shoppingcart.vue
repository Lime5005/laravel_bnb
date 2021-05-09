<template>
  <div>
    <success v-if="success">Congratulations! Your booking is confirmed!</success>
    <div class="row" v-else>
      <div class="col-md-8" v-if="itemsInShoppingcart">
        <div class="row">
          <div class="col-md-6 form-group">
            <label for="firstname">First name</label>
            <input type="text" class="form-control" name="firstname" v-model="customer.firstname" :class="[{'is-invalid': errorFor('customer.firstname')}]">
            <validation-errors :errors="errorFor('customer.firstname')"></validation-errors>
          </div>
          <div class="col-md-6 form-group">
            <label for="lastname">Last name</label>
            <input type="text" class="form-control" name="lastname" v-model="customer.lastname" :class="[{'is-invalid': errorFor('customer.lastname')}]">
            <validation-errors :errors="errorFor('customer.lastname')"></validation-errors>
          </div>
        </div>
        <div class="row">
          <div class="col-md-12 form-group">
            <label for="email">Email</label>
            <input type="email" class="form-control" name="email" v-model="customer.email" :class="[{'is-invalid': errorFor('customer.email')}]">
            <validation-errors :errors="errorFor('customer.email')"></validation-errors>
          </div>
        </div>
        <div class="row">
          <div class="col-md-6 form-group">
            <label for="street">Street</label>
            <input type="text" class="form-control" name="street" v-model="customer.street" :class="[{'is-invalid': errorFor('customer.street')}]">
            <validation-errors :errors="errorFor('customer.street')"></validation-errors>
          </div>
          <div class="col-md-6 form-group">
            <label for="city">City</label>
            <input type="text" class="form-control" name="city" v-model="customer.city" :class="[{'is-invalid': errorFor('customer.city')}]">
            <validation-errors :errors="errorFor('customer.city')"></validation-errors>
          </div>
        </div>
        <div class="row">
          <div class="col-md-6 form-group">
            <label for="country">Country</label>
            <input type="text" class="form-control" name="country" v-model="customer.country" :class="[{'is-invalid': errorFor('customer.country')}]">
            <validation-errors :errors="errorFor('customer.country')"></validation-errors>
          </div>
          <div class="col-md-4 form-group">
            <label for="State">State</label>
            <input type="text" class="form-control" name="State" v-model="customer.state" :class="[{'is-invalid': errorFor('customer.state')}]">
            <validation-errors :errors="errorFor('customer.state')"></validation-errors>
          </div>
          <div class="col-md-2 form-group">
            <label for="zip">Zip code</label>
            <input type="text" class="form-control" name="zip" v-model="customer.zip" :class="[{'is-invalid': errorFor('customer.zip')}]">
            <validation-errors :errors="errorFor('customer.zip')"></validation-errors>
          </div>
        </div>
        <hr>
        <div class="row">
          <div class="col-md-12">
            <button type="submit" class="btn btn-lg btn-primary btn-block" @click.prevent="book">Book Now</button>
          </div>
        </div>
      </div>

      <div class="col-md-8" v-else>
        <div class="jumbotron jumbotron-fluid text-center">
          <h1>Empty</h1>
        </div>
      </div>

      <div class="col-md-4">
        <div class="d-flex justify-content-between">
          <h6 class="text-uppercase text-secondary font-weight-bolder">Your Cart</h6>
          <h6 class="badge badge-secondary text-uppercase">
            <span v-if="itemsInShoppingcart">{{ itemsInShoppingcart }} Items</span>
            <span v-else>Empty</span>
          </h6>
        </div>
        <transition-group name="fade">
          <div v-for="item in shoppingcart" :key="item.bookable.id">
            <div class="py-2 border-top d-flex justify-content-between">
              <span>
                <router-link :to="{ name: 'bookable', params: {id: item.bookable.id }}">{{ item.bookable.title }}</router-link>
              </span>
              <span class="font-weight-bold">${{ item.price.total }}</span>
            </div>
            <div class="py-2 text-right" v-for="(days, price) in item.price.breakdown" :key="price">
              <span>${{ price }}/night</span>
            </div>
            <div class="py-2 d-flex justify-content-between">
              <span>
                From {{ item.dates.from }}
              </span>
              <span>
                To {{ item.dates.to }}
              </span>
            </div>
            <div class="py-2 text-right">
              <button class="btn btn-sm btn-outline-secondary" @click="$store.dispatch('removeFromShoppingcart', item.bookable.id)">
                <i class="fas fa-trash-alt"></i>
              </button>
            </div>
          </div>        
        </transition-group> 
      </div>
    </div>
  </div>
</template>

<script>
import { mapState, mapGetters } from "vuex"
import validationErrors from '../shared/Mixins/validationErrors'

export default {
  mixins: [validationErrors],
  data() {
    return {
      loading: false,
      bookingAttempted: false,
      customer: {
        firstname: null,
        lastname: null,
        email: null,
        street: null,
        city: null,
        country: null,
        state: null,
        zip: null
      }
    }
  },
  computed: {
    ...mapGetters(['itemsInShoppingcart']),
    ...mapState({
      shoppingcart: state => state.shoppingcart.items
    }),
    success() {
      return !this.loading && 0 === this.itemsInShoppingcart && this.bookingAttempted
    }
  },
  methods: {
    async book() {
      this.loading = true
      this.errors = null
      this.bookingAttempted = false

      try {
        await axios.post(`/api/checkout`, {
          customer: this.customer,
          // For javascript to return an arrow function with multiples data, include data in `()` after the arrow `=>`
          bookings: this.shoppingcart.map(itemsInShoppingcart => ({
            bookable_id: itemsInShoppingcart.bookable.id,
            from: itemsInShoppingcart.dates.from,
            to: itemsInShoppingcart.dates.to
          }))
          
        })

        this.$store.dispatch('clearShoppingcart')
        this.bookingAttempted = true

      } catch(error) {
        // errors is a property imported from mixins
        this.errors = error.response && error.response.data.errors

      }
      this.loading = false
    }
  }
}
</script>

<style scoped>
h6.badge{
  font-size: 100%;
}
</style>