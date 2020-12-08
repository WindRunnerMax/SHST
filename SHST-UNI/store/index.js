import Vue from "vue";
import Vuex from "vuex";
import {data} from "@/modules/global-data";

Vue.use(Vuex);

const store = new Vuex.Store({
    state: {...data},
    getters: {},
    mutations: {
        setInitData: function(state, payload) {
            state.point = payload.tips;
            state.curWeek = payload.curWeek;
            state.curTerm = payload.curTerm;
            state.curTermStart = payload.termStart;
        },
        setUserStatus: function(state, payload) {
            state.openid = payload.openid;
            state.userFlag = payload.status === 1 ? 1 : 0;
        }, 
    },
    actions: {}
})

export default store;