const filters = {

}

const methods = {
    copy: function(str) {
        uni.setClipboardData({data: str});
    },
    nav: function(url, type = "nav"){
        switch(type){
            case "nav": return uni.navigateTo({ url });
            case "tab": return uni.switchTab({ url });
            case "relunch": return uni.reLaunch({ url });
            case "back": return uni.navigateBack({});
            case "webview": return uni.navigateTo({ url: "/pages/home/auxiliary/webview?url="+encodeURIComponent(url) });
        }
    },
    viewImage: function(url, list){
        uni.previewImage({current: url, urls: list});
    }
}

const filterMount = (Vue) => {
    for(let key in filters){
        Vue.filter(key, filters[key])
    }
}

const methodMount = (Vue) => {
    Vue.mixin({ methods });
}

const run = function(Vue){
    filterMount(Vue);
    methodMount(Vue);
}

export {filters, methods};
export default {run};
