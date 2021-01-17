var PubSub = function() {
    this.handlers = {};
}

PubSub.prototype = {
    constructor: PubSub,
    on: function(key, handler) { // 订阅
        if(!(key in this.handlers)) this.handlers[key] = [];
        if(!this.handlers[key].includes(handler)) {
             this.handlers[key].push(handler);
             return true;
        }
        return false;
    },

    once: function(key, handler) { // 一次性订阅
        if(!(key in this.handlers)) this.handlers[key] = [];
        if(this.handlers[key].includes(handler)) return false;
        const onceHandler = (...args) => {
            handler.apply(this, args);
            this.off(key, onceHandler);
        }
        this.handlers[key].push(onceHandler);
        return true;
    },

    off: function(key, handler) { // 卸载
        const index = this.handlers[key].findIndex(item => item === handler);
        if (index < 0) return false;
        if (this.handlers[key].length === 1) delete this.handlers[key];
        else this.handlers[key].splice(index, 1);
        return true;
    },

    commit: function(key, ...args) { // 触发
        if (!this.handlers[key]) return false;
        console.log(key, "Execute");
        this.handlers[key].forEach(handler => handler.apply(this, args));
        return true;
    },

}

export { PubSub }
export default { PubSub }
