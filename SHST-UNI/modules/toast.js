/**
 * 弹窗提示
 */
function toast(msg, time = 2000, icon = "none") {
    uni.showToast({
        title: msg,
        icon: icon,
        duration: time
    })
    return new Promise((resolve, reject) => setTimeout(() => resolve(msg), time));
}

export { toast }
export default { toast }
