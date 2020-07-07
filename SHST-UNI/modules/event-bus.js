var PubSub = function() {
    this.handlers = {};
}

PubSub.prototype = {

    on: function(key, handler) { // 订阅
        if (!(key in this.handlers)) this.handlers[key] = [];
        this.handlers[key].push(handler);
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
        this.handlers[key].forEach(handler => handler.apply(this, args));
        return true;
    },

}

export { PubSub }
export default { PubSub }
