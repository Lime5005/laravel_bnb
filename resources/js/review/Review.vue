<template>
  <div>
    <!-- <div class="row" v-if="error">
      Unknown error has occured, please try again later!
    </div> -->
    <success v-if="success">
      You've left a review, thank you very much!
    </success>
    <fatal-error v-if="error"></fatal-error>
    <div class="row" v-if="!success && !error">
      <div :class="[{'col-md-4': twoColumns}, {'d-none': oneColumn}]">
        <div class="card">
          <div class="card-body">
            <div v-if="loading">Loading...</div>
            <div v-if="hasBooking">
              <p>You've stayed at <router-link :to="{name: 'bookable', params: {id: booking.bookable.bookable_id} }">{{ booking.bookable.title }}</router-link></p>
              <p>From {{ booking.from }} to {{ booking.to }}</p>
            </div>
          </div>
        </div>
      </div>
      <div :class="[{'col-md-8': twoColumns}, {'col-md-12': oneColumn}]">
        <div v-if="loading">Loading...</div>
        <div v-else>
        <div v-if="alreadyReviewed">
          <h3>You've already left a review for this booking!</h3>
        </div>
        <div v-else>
          <div class="form-group">
            <label class="text-muted">Select the star rating (1 is worst - 5 is best)</label>
            <!-- <star-rating 
            v-bind:rating="review.rating"
            class="fa-3x" 
            v-on:rating-changed="review.rating = $event"></star-rating> -->
            <star-rating class="fa-3x"
            v-model="review.rating"
            ></star-rating>
          </div>
          <div class="form-group">
            <label for="content" class="text-muted">Describe your experience with</label>
            <textarea name="content" cols="30" rows="10" 
            class="form-control"
            v-model="review.content"
            :class="[{'is-invalid': errorFor('content')}]"
            ></textarea>
            <validation-errors :errors="errorFor('content')"></validation-errors>
          </div>
          <button class="btn btn-primary btn-block" @click.prevent="submit" :disabled="sending">Submit</button>
        </div>
      </div>
      </div>
    </div>
  </div>
</template>

<script>
import { is404, is422 } from "./../shared/utils/response"
import validationErrors from "./../shared/Mixins/validationErrors"

export default {
  mixins: [validationErrors],
  data() {
    return {
      review: {
        id: null,
        rating: 5,
        content: null
      },
      existingReview: null,
      loading: false,
      booking: null,
      error: false,
      // errors: null, // Replaced by mixins
      sending: false,
      success: false
    }
  },
  methods: {
    // 3. Sending data to the server, store the review
    // This is the original code: 
    submit() {
      this.errors = null
      this.sending = true
      this.success = false
      axios.post(`/api/reviews`, this.review)
      // .then(response => console.log(response))
      .then(response => {
        this.success = 201 === response.status
      })
      .catch((err) => {
        if (is422(err)){
          const otherErrors = err.response.data.errors
          // This will filter out the maliciously entry
          if (otherErrors["content"] && _.size(otherErrors) === 1) {
            this.errors = otherErrors
            return
          }
        }
        // This is for the malicious entry
        this.error = true
      })
      .then(() => this.sending = false)
    },
    // Replaced by mixins
    // errorFor(field) {
    //   // This error is not null, and this error field exist, return this errors
    //   return null !== this.errors && this.errors[field] ? this.errors[field] : null
    // }
  },
  async created() {
    this.review.id = this.$route.params.id
    this.loading = true
    try {
      this.existingReview = (await axios.get(`/api/reviews/${this.review.id}`)).data.data
    } catch(err) {
      if (is404(err)) {
        try {
          this.booking = (await axios.get(`/api/booking-by-review/${this.review.id}`)).data.data
        } catch(err) {
          this.error = !is404(err)
        }
      } else {
        this.error = true
      }
    }   
    this.loading = false
  },

  // This is the original code: 
  // created() {
  //   this.review.id = this.$route.params.id
  //   this.loading = true
  //   axios.get(`/api/reviews/${this.review.id}`)
  //   .then(response => {this.existingReview = response.data.data}) // 1. Check if reviewed
  //   .catch(err => {
  //     if(is404(err)) {
  //       // 2. If not, fetch the booking by review_key
  //       return axios
  //       .get(`/api/booking-by-review/${this.review.id}`)
  //       .then(response => {this.booking = response.data.data})
  //       .catch(err => {
  //         this.error = !is404(err)
  //         // Same as below
  //         // is404(err) ? {} : (this.error = true)
  //         // if(!is404(err)) {
  //         //     this.error = true
  //         //   }
  //       })
  //     }
  //     this.error = true
  //   })
  //   .then(response => {
  //     this.loading = false})
  // },
  computed: {
    hasReview(){
      return this.existingReview !== null
    },
    hasBooking(){
      return this.booking !== null
    },
    alreadyReviewed(){
      return this.hasReview || !this.booking;
      // The logic is, either reviewed, or the review_key is empty, because we'll set it as empty once reviewed.
    },
    oneColumn() {
      return !this.loading && this.alreadyReviewed
    },
    twoColumns() {
      return this.loading || !this.alreadyReviewed
    }
  }
  // Below is for testing the star rating component
  // methods: {
  //   onRatingChanged(rating) {
  //     console.log(rating);
  //   }
  // }
}
</script>

<style scoped>  
/* Find the class (form-control.is-invalid) and it's sibling (div), and in the div, look for (invalid-feedback) */
/* .form-control.is-invalid ~ div > .invalid-feedback {
  display: block;
} */
</style>