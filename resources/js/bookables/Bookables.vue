<template>
  <div>
    <div v-if="loading">
      Loading
    </div>
    <div v-else>
      <div class="row mb-4" v-for="row in rows" :key="'row' + row">
        <div class="col d-flex align-items-stratch" v-for="(bookable, column) in bookablesInRow(row)" :key="'row' + row + column">
          <bookable-list-item v-bind="bookable"
          ></bookable-list-item>
        </div>
        <!-- {{placeholderInRow(row)}} -->
        <div class="col" v-for="p in placeholderInRow(row)" :key="'placeholder' + p"></div>
      </div>
    </div>

  </div>
</template>

<script>
import BookableListItem from "./BookableListItem";
export default {
  components: {
    BookableListItem,
  },
  data(){
    return {
      bookables: null,
      loading: false,
      columns: 3
    }
  },
  methods: {
    bookablesInRow(row) {
      return this.bookables.slice((row-1) * this.columns, row * this.columns)
    },
    placeholderInRow(row){
      return this.columns - this.bookablesInRow(row).length
    }
  },
  computed:{
    rows(){
      return this.bookables == null ? 0 : Math.ceil(this.bookables.length / this.columns)
    }
  },
  created(){
    this.loading = true
    // This below is for testing what is Promise, async, etc.
    // const p = new Promise((resolve, reject)=>{
    //   console.log(resolve);
    //   console.log(reject);
    //   setTimeout(()=>resolve(' Hi there'), 3000);
    // })
    // .then(result=>" Hi first" + result)
    // .then(result=>console.log(`Success ${result}`))
    // .catch(result=>console.log(`Failure ${result}`));
    // console.log(p);

    const request = axios.get('api/bookables')
    // console.log(request);
    .then(response => {
      this.bookables = response.data.data
      // this.bookables.push({title: "t", content: "c"})
      this.loading = false
    })
  }
}
</script>