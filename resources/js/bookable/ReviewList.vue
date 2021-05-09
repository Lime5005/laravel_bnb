<template>
  <div style="padding:20px">
    <h6 class="text-uppercase text-secondary font-weight-bolder pt-4">
      Review List
    </h6>
    <!-- <star-rating :rating="3.4"></star-rating>
    <star-rating :rating="2.5"></star-rating>
    <star-rating :rating="2.0"></star-rating> -->
    <div v-if="loading">Loading...</div>
    <div v-else>
      <div class="border-bottom d-none d-md-block" v-for="(review, index) in reviews"  :key="index">
        <div class="row pt-4">
          <div class="col-md-6">User name</div>
          <div class="col-md-6 d-flex justify-content-end">
            <star-rating 
            :value="review.rating" 
            class="fa-lg"></star-rating>
          </div>
        </div>
        <div class="row">
          <div class="col-md-12">{{ review.created_at | fromNow }}</div>
        </div>
        <div class="row pt-4 pb-4">
          <div class="col-md-12">{{ review.content }}</div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
// import moment from "moment" // All the libraries in node_modules don't need to specify the folder

export default {
  props: {
    bookableId: [String, Number]
  },
  data() {
    return {
      loading: false,
      reviews: null
    }
  },
  created() {
    this.loading = true
    axios.get(`/api/bookables/${this.bookableId}/reviews`)
    .then(response => this.reviews = response.data.data)
    .then(() => this.loading = false)
  },
  // filters: {
  //   fromNow(value) {
  //     return moment(value).fromNow();
  //   }
  // }

}
</script>