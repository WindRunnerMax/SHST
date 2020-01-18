import axios from 'axios'
// axios.defaults.withCredentials = true
import {
  Message,
  Loading
} from 'element-ui'

const globalData = {
  user: 0,
  url: "http://www.touchczy.top/",
  header: {
    'content-type': 'application/x-www-form-urlencoded'
  }
}

function extend() {
  var aLength = arguments.length;
  var options = arguments[0];
  var target = {};
  var copy;
  var i = 1;
  if (typeof options === "boolean" && options === true) {
    //深拷贝 (仅递归处理对象)
    for (; i < aLength; i++) {
      if ((options = arguments[i]) != null) {
        if (typeof options !== 'object') {
          return options;
        }
        for (var name in options) {
          copy = options[name];
          if (target === copy) {
            continue;
          }
          target[name] = this.extend(true, options[name]);
        }
      }
    }
  } else {
    //浅拷贝
    target = options;
    if (aLength === i) {
      target = this;
      i--;
    } //如果是只有一个参数，拓展功能 如果两个以上参数，将后续对象加入到第一个对象
    for (; i < aLength; i++) {
      options = arguments[i];
      for (var name in options) {
        target[name] = options[name];
      }
    }
  }
  return target;
}

function startLoading(options) {
  if (!options.load) return true;
  var loadingInstance = Loading.service({
    lock: true,
    text: 'loading...'
  })
  return loadingInstance;
}

function endLoading(options, loadingInstance) {
  if (!options.load) return true;
  loadingInstance.close();
}

function toast(msg, type = 'error') {
  Message({
    message: msg,
    type: type,
    duration: 2000,
    center: true
  })
}


function ajax(requestInfo) {
  var options = {
    load: true,
    url: "",
    method: "GET",
    headers: {},
    data: {},
    param: {},
    success: () => {},
    fail: function() { this.completeLoad = () => {toast("服务器错误", 'error');}
    },
    complete: () => {},
    completeLoad: () => {}
  };
  extend(options, requestInfo);
  extend(options.headers, globalData.header);
  let loadingInstance = startLoading(options);
  return axios.request({
    url: options.url,
    data: options.data,
    params: options.param,
    method: options.method,
    headers: options.headers,
    transformRequest: [function(data) {
      let ret = ''
      for (let it in data) ret += encodeURIComponent(it) + '=' + encodeURIComponent(data[it]) + '&'
      return ret
    }]
  }).then(function(res) {
    try {
      options.success(res);
    } catch (e) {
      options.completeLoad = () => {toast("PARSE ERROR");}
      console.warn(e);
    }
  }).catch(function(res) {
    options.fail(res);
  }).then(function(res) {
    endLoading(options, loadingInstance);
    try {
      options.complete(res);
    } catch (e) {
      console.warn(e);
    }
    options.completeLoad(res);
  })
}

export default {
  ajax,
  toast,
  globalData
}
