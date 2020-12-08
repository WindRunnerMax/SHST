import {startLoading, endLoading} from "./loading";
import {getCookies} from "./cookies";
import {extend} from "./copy";
import {toast} from "./toast";
import operateLimit from "./operate-limit";

var throttle = operateLimit.throttleGenerater();
var debounce = operateLimit.debounceGenerater();
var headers = {"content-type": "application/x-www-form-urlencoded"};

/**
 * HTTP请求
 */
function ajax(requestInfo) {
    var option = {
        load: 1,
        url: "",
        method: "GET",
        data:{},
        cookie: true,
        debounce: false,
        throttle: false,
        headers: headers,
        success: () => {},
        resolve: () => {},
        fail: function() { this.completeLoad = () => toast("External Error"); },
        reject: () => {},
        complete: () => {},
        completeLoad: () => {}
    };
    extend(option, requestInfo);
    var run = function(){
        startLoading(option);
        console.log("Request for", option.url);
        uni.request({
            url: option.url,
            data: option.data,
            method: option.method,
            header: headers,
            success: function(res) {
                if(option.cookie && !headers.cookie) headers.cookie = getCookies(res);
                if(option.cookie && !headers.cookie) {
                    option.fail(res);
                    option.reject(res);
                }
                if(res.statusCode === 200 && res.data.status){
                    if(res.data.status === -1 && res.data.msg){
                        option.completeLoad = (res) => toast(res.data.msg);
                        return void 0;
                    }
                    try {
                        option.success(res);
                        option.resolve(res);
                    } catch (e) {
                        option.completeLoad = () => toast("External Error");
                        console.log(e);
                    }
                }else{
                    option.fail(res);
                    option.reject(res);
                }

            },
            fail: function(res) {
                option.fail(res);
            },
            complete: function(res) {
                endLoading(option);
                try {
                    option.complete(res);
                } catch (e) {
                    console.log(e);
                }
                option.completeLoad(res);
            }
        });
    }
    if(option.debounce) debounce(500, () => run());
    else if(option.throttle) throttle(500, () => run());
    else run();
}

/**
 * request promise封装
 */
function request(option) {
    return new Promise((resolve,reject) => {
        option.resolve = resolve;
        option.reject = reject;
        ajax(option);
    })
}


export { ajax, request }
export default { ajax, request }
