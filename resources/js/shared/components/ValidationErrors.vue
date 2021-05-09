<template>
  <div>
    <div class="invalid-feedback" 
      v-for="(error, index) in errors" 
      :key="key(index)">
      {{ error }}
    </div>
  </div>
</template>

<script>
export default {
  props: ['errors'],
  methods: {
    key(index) {
      return `validation_error_${index}_${Math.random()}`
    }
  }
}
</script>

<style scoped>
/* Find the class (form-control.is-invalid) and it's sibling (div), and in the div, look for (invalid-feedback) */
.form-control.is-invalid ~ div > .invalid-feedback {
  display: block;
}
</style>