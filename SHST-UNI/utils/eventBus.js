var eventBus = null;

var PubSub = function(){
  this.handlers = {}  
}

PubSub.prototype={
  // 订阅
  on : function(key, handler) {
    if (!(key in this.handlers)) {
      this.handlers[key] = [];
    }
    this.handlers[key].push(handler);
  },

  // 卸载
  off : function(key, handler) {
    const index = this.handlers[key].findIndex(item => {
      item === handler;
    })
    // 不存在直接返回
    if (index < 0) {
      return false;
    }

    if (this.handlers[key].length === 1) {
      // 只有一个直接删除key 节省内存
      delete this.handlers[key]
    } else {
      this.handlers[key].splice(index, 1)
    }
    return true;
  },

  // 触发
  commit : function(key) {
    // 取出参数并转化为数组
    if (!this.handlers[key]) return false;
    const args = Array.prototype.slice.call(arguments, 1)
    this.handlers[key].forEach(handler => {
      // 防止this指向乱掉
      try{ handler.apply(this, args); } catch(e){console.warn(e);}
    });
    return true;
  }
}

export const getEventBus = new PubSub();