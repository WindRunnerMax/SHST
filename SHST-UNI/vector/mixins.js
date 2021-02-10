const filters = {

}

const methods = {
    copy: function(str) {
        uni.setClipboardData({data: str});
    },
    nav: function(url, type = "nav"){
        const fail = e => console.log(e);
        switch(type){
            case "nav": return uni.navigateTo({ url, fail });
            case "tab": return uni.switchTab({ url, fail });
            case "relunch": return uni.reLaunch({ url, fail });
            case "back": return uni.navigateBack({ fail });
            case "webview": return uni.navigateTo({ url: "/pages/home/auxiliary/webview?url="+encodeURIComponent(url), fail});
            case "redirect": return uni.redirectTo({ url, fail });
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
