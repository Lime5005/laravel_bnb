<template>
  <div>
    <h6 class="text-uppercase text-secondary font-weight-bolder">
      Check Availability
      <transition name="fade">
      <span v-if="noAvailability" class="text-danger">(Not Available)</span>
      <span v-if="hasAvailability" class="text-success">(Available)</span>     
      </transition>
      </h6>
    <div class="form-row">
      <div class="form-group col-md-6">
        <label for="from">From</label>
        <input v-model="from" type="text" name="from" class="form-control form-control-sm" placeholder="Start date" @keyup.enter="check" :class="[{'is-invalid': errorFor('from')}]">
        <!-- <div class="invalid-feedback" 
        v-for="(error, index) in this.errorFor('from')" :key="'from' + index">{{ error }}</div> -->
        <validation-errors :errors="errorFor('from')"></validation-errors>
      </div>
      <div class="form-group col-md-6">
        <label for="to">To</label>
        <input v-model="to" type="text" name="to" class="form-control form-control-sm" placeholder="End date" @keyup.enter="check" :class="[{'is-invalid': errorFor('to')}]">
        <!-- <div class="invalid-feedback" 
        v-for="(error, index) in this.errorFor('to')" :key="'to' + index">{{ error }}</div> -->
        <validation-errors :errors="errorFor('to')"></validation-errors>
      </div>
      <button class="btn btn-secondary btn-block" @click="check" :disabled="loading" >
        <span v-if="!loading">Check!</span>
        <span v-if="loading"><i class="fas fa-stroopwafel fa-spin"></i>Checking...</span>
        </button>
    </div>
  </div>
</template>

<script>
import {is422} from "./../shared/utils/response";
import validationErrors from "./../shared/Mixins/validationErrors"

export default {
  mixins: [validationErrors],
  props: {
    bookableId: [String, Number]
  },
  data() {
    return {
      from: this.$store.state.lastSearch.from,
      to: this.$store.state.lastSearch.to,
      loading: false,
      status: null,
      // errors: null // Replaced by mixins
    }
  },
  methods: {
    async check() {
      this.loading = true
      this.errors = null

      // console.log(this.$store)
      this.$store.dispatch('setLastSearch', {
        from: this.from,
        to: this.to
      })

      try {
        this.status = (await axios.get(`/api/bookables/${this.bookableId}/availability?from=${this.from}&to=${this.to}`)).status
        // Emit event to check availability
        this.$emit('availability', this.hasAvailability)
      } catch (error) {
          if (is422(error)) {
          this.errors = error.response.data.errors
        }
        this.status = error.response.status
        this.$emit('availability', this.hasAvailability)
      }

      this.loading = false
    }
    // Below is replaced by the above
    //   axios.get(`/api/bookables/${this.bookableId}/availability?from=${this.from}&to=${this.to}`).then(response => {
    //     this.status = response.status // If it's 200, then OK.
    //   }).catch (error => {
    //     if (is422(error)) {
    //       this.errors = error.response.data.errors
    //     }
    //     this.status = error.response.status // 404
    //   }).then(() => {this.loading = false})
    // },
    // Replaced by mixins:
    // errorFor(field) {
    //   return this.hasErrors && this.errors[field] ? this.errors[field] : null
    // }
  },
  computed: {
    hasErrors() {
      return 422 === this.status && this.errors !== null
    },
    hasAvailability() {
      return 200 === this.status
    },
    noAvailability() {
      return 404 === this.status
    }
  }
}
</script>

<style scoped>
label{
  font-size: 0.7rem;
  text-transform: uppercase;
  font-weight: bolder;
  color:grey;
}

.is-invalid{
  border-color: #b22222;
  background-image: none;
}

.invalid-feedback {
  color: #b22222;
}
  
</style>