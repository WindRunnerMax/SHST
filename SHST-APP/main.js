import Vue from 'vue'
import App from './App'

Vue.config.productionTip = false

App.mpType = 'app'

const app = new Vue({
    ...App
})
app.$mount()

import list from "@/components/list.vue"
Vue.component('list',list)
import layout from "@/components/layout.vue"
Vue.component('layout',layout)
import imagecache from "@/components/imagecache.vue"
Vue.component('imagecache',imagecache)