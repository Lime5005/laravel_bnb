<template>
  <div class="row">
    <div class="col-md-8 pb-4">
      <div class="card">
        <div class="card-body">
          <div v-if="!loading">
            <h2>{{ bookable.title }}</h2>
            <hr>
            <article>{{ bookable.content }}</article>
          </div>
          <div v-else>Loading...</div>
        </div>
      </div>
      <review-list :bookable-id="this.$route.params.id"></review-list>
    </div>
    <div class="col-md-4 pb-4">
      <!-- Availability & Prices -->
      <availability :bookable-id="this.$route.params.id" v-on:availability="checkPrice($event)" class="mb-4"></availability>
      <transition name="fade">
        <price-breakdown v-if="price" :price="price"></price-breakdown>
      </transition>
      <transition name="fade"> 
        <button class="btn btn-outline-secondary btn-block mb-4" 
        v-if="price"
        v-on:click="addToShoppingcart"
        :disabled="inShoppingcartAlready"
        >Book</button>
      </transition>
      <button class="btn btn-outline-secondary btn-block mb-4" 
        v-if="inShoppingcartAlready"
        v-on:click="removeFromShoppingcart"
        >Remove from cart</button>
      <div v-if="inShoppingcartAlready" class="mt-4 text-muted warning">
        Seems you've add this hotel in the cart already.
        If you want to change date, remove it from the cart first.
      </div>
    </div>
  </div>
</template>

<script>
import Availability from "./Availability"
import ReviewList from "./ReviewList"
import PriceBreakdown from "./PriceBreakdown"
import { mapState } from "vuex"

export default {
  components: {
    Availability,
    ReviewList,
    PriceBreakdown
  },
  data(){
    return {
      bookable: null,
      loading: false,
      price: null
    }
  },
  created() {
    this.loading = true
    // console.log(this.$route.params.id);
    axios.get(`/api/bookables/${this.$route.params.id}`)
    .then(response => {
      this.bookable = response.data.data
      this.loading = false
    })
  },
  methods: {
    async checkPrice(hasAvailability) {
      if (!hasAvailability) {
        this.price = null
        return
      }

      try {
        this.price = (await axios.get(`/api/bookables/${this.bookable.id}/price?from=${this.lastSearchComputed.from}&to=${this.lastSearchComputed.to}`)).data.data
      } catch (error) {
        this.price = null
      }
    },
    addToShoppingcart() {
      this.$store.dispatch('addToShoppingcart', {
        bookable: this.bookable,
        price: this.price,
        dates: this.lastSearchComputed
      })
    },
    removeFromShoppingcart() {
      this.$store.dispatch('removeFromShoppingcart', this.bookable.id)
    }
  },
  computed:{
    ...mapState({
      lastSearchComputed: 'lastSearch',
      // inShoppingcartAlready(state) {
      //     if (null === this.bookable) {
      //       return false
      //     }
      //     return state.shoppingcart.items.reduce((result, item) => result || item.bookable.id === this.bookable.id, false) // initialize as false, then once there is a true, the result is true, the logic is, we don't accept more than one item with the same bookable.id.
      //   }
      // Above function is replaced by below, after moved the logic to getters.
      }),
      inShoppingcartAlready() {
        if (null === this.bookable) {
          return false
        }
        return this.$store.getters.inShoppingcartAlready(this.bookable.id)
      }
  }
}
</script>

<style scoped>
.warning{
  font-size: 0.7rem;
}
</style>
