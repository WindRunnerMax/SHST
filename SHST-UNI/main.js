import Vue from "vue";
import App from "./App";
// import store from "./store";

Vue.config.productionTip = false;

App.mpType = "app";

import layout from "@/components/layout/layout.vue";
Vue.component("layout",layout);

// Vue.prototype.$store = store;

const app = new Vue({
    ...App,
    // store
})
app.$mount();


