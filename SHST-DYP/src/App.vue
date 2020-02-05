<template>
  <div>

    <router-view></router-view>

  </div>
</template>

<script>
  console.log(process.env.NODE_ENV);
  export default {
    data() {
      return {

      }
    }
  }
</script>

<style>
  @import './utils/asse.min.css';
</style>
