import Vue from 'vue'
import Router from 'vue-router'

import index from "@/components/index/index.vue"
import mp from "@/components/mp/index.vue"
import scl from "@/components/mp/classroom/scl.vue"
import lcl from "@/components/mp/classroom/lcl.vue"
import pu from "@/components/mp/pu/qrcode.vue"

Vue.use(Router)

export default new Router({
  routes: [
    {
      path:"/",
      name: "index",
      component: index,
    },
    {
      path: '/mp',
      name: "mp",
      component: mp,
      children: [
        {
          path: 'cl/:t/:k/:s',
          name: "scl",
          component: scl,
        },
        {
          path: 'lcl',
          name: "lcl",
          component: lcl,
        },
        {
          path: 'pu/:u',
          name: "qrcode",
          component: pu,
        }
      ]
    }


  ]
})
