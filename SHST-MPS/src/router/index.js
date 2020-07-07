import Vue from 'vue'
import VueRouter from 'vue-router'

import index from "@/views/index/index"

import mp from "@/views/mp/index"
import scl from '@/views/mp/classroom/scl'
import lcl from '@/views/mp/classroom/lcl'
import qrcode from '@/views/mp/pu/qrcode'

import shst from "@/views/shst/index"
import login from "@/views/shst/login"
import custom from "@/views/shst/custom"


Vue.use(VueRouter)

const routes = [
    {
        path: "/",
        component: index,
    }, {
        path: '/mp',
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
                component: qrcode,
            }
        ]
    }, {
        path: "/shst",
        component: shst,
        children:[
            {
                path: "login/:t/:u/:s",
                name: "login",
                component: login
            },
            {
                path: "custom/:t/:u/:s",
                name: "custom",
                component: custom
            },
        ]
    },{
        path: '*',
        component: () => import('@/views/system/notFound')
    }
]

const router = new VueRouter({
    routes
})

export default router
