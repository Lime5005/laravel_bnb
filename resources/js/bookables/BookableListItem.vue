<template>
  <div class="card w-100">
    <div class="card-body">
      <router-link :to="{ name: 'bookable', params: {id: id} }">
        <h5 class="card-title">{{ title }}</h5>
      </router-link>      
      <p class="card-text">{{ content }}</p>
    </div>
  </div>
</template>

<script>
export default {
  props: {
    id: Number,
    title: String, 
    content: String,
  },
}
</script>