<template>
  <div class="d-flex">
    <i class="fas fa-star" 
    v-for="(star, index) in fullStar" 
    :key="'full' + index"
    v-on:click="$emit('input', star)"
    ></i>
    <i class="fas fa-star-half-alt" v-if="halfStar"></i>
    <i class="far fa-star" 
    v-for="(star, index) in emptyStar" 
    :key="'empty' + index"
    v-on:click="$emit('input', fullStar + star)" 
    ></i>
    <!-- 点击第四个，前面的自动都点击了 -->
  </div>
</template>

<script>
export default {
  props: {
    value: Number
  },
  computed: {
    halfStar() {
      // return false // value = 3.4, so return true
      const fraction = this.value - Math.floor(this.value) // 3.4 - 3 = 0.4
      if (fraction * 10 > 0 && fraction * 10 < 5 ) {
        return true
      }
      return false
    },
    fullStar() {
      return Math.round(this.value) // value = 3.4, so return 3
    },
    emptyStar() {
      return 5 - Math.ceil(this.value) // value = 3.4, so return 5 - 4 = 1
    }
  },
  // All below is for learning Math.round/floor/ceil
  // created() {
  //   const numbers = [0.9, 3.1, 4.4, 4.6, 4.9]
  //   numbers.forEach(n => {
  //     console.log(`round for ${n} is ${Math.round(n)}`); // 四舍五入
  //     console.log(`floor for ${n} is ${Math.floor(n)}`); // 尽量取最小
  //     console.log(`ceil for ${n} is ${Math.ceil(n)}`);  // 尽量取最大
  //   })
  // }
}
</script>