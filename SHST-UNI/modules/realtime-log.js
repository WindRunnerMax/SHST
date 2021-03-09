// 实时日志兼容性封装

const log = uni.getRealtimeLogManager ? uni.getRealtimeLogManager() : null;

export default {
    info: function(...args) {
        if(!log) return void 0;
        log.info(...args)
    },
    warn: function(...args) {
        if(!log) return void 0;
        log.warn(...args)
    },
    error: function(...args) {
        if(!log) return void 0;
        log.error(...args);
    },
    setFilterMsg: function(msg) {
        if(!log || !log.setFilterMsg) return void 0;
        if(typeof msg !== "string") return void 0;
        log.setFilterMsg(msg);
    },
    addFilterMsg: function(msg) {
        if(!log || !log.addFilterMsg) return void 0;
        if(typeof msg !== "string") return void 0;
        log.addFilterMsg(msg);
    }
}
