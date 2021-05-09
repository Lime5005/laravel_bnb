<template>
  <div>
    <h6 class="text-uppercase text-secondary font-weight-bolder">Price Breakdown</h6>
    <div v-for="(days, price) in price.breakdown" :key="price" class="py-2 border-top border-bottom d-flex justify-content-between">
      <span>{{days}} * ${{price}} </span>
      <span>${{ days * price}}</span>
    </div>
    <div class="font-weight-bolder py-2 d-flex justify-content-between">
      <span>Total:</span>
      <span>${{price.total}}</span>
    </div>
  </div>
</template>

<script>
export default {
  props: {
    price: Object
  }
}
</script>