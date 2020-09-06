/**
 * startLoading
 */
function startLoading(option) {
    switch (option.load) {
        case 1:
            uni.showNavigationBarLoading();
            break;
        case 2:
            uni.showNavigationBarLoading();
            uni.setNavigationBarTitle({
                title: option.title || "加载中..."
            })
            break;
        case 3:
            uni.showLoading({
                title: option.title || "请求中",
                mask: true
            })
            break;
    }
}

/**
 * endLoading
 */
function endLoading(option) {
    switch (option.load) {
        case 1:
            uni.hideNavigationBarLoading();
            break;
        case 2:
            uni.hideNavigationBarLoading();
            uni.setNavigationBarTitle({
                title: option.title || "山科小站"
            })
            break;
        case 3:
            uni.hideLoading();
            break;
    }
}

export { startLoading, endLoading }
export default { startLoading, endLoading }
