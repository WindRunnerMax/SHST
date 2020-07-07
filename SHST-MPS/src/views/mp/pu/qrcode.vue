<template>
  <div>

    <iframe style='width:100%;height:1000px;border:none;' :src='url'></iframe>

  </div>
</template>
<script>
  export default {
    data() {
      return {
        url: ""
      }
    },
    created:function(){
      console.log(this.$route);
      this.url = `http://39.106.101.255:5000/pu/by_uid/${this.$route.params.u}`;
    },
    methods: {

    }

  }
</script>

<style>

</style>
