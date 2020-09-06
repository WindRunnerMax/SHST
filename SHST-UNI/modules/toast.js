/**
 * 弹窗提示
 */
function toast(e, time = 2000, icon = 'none') {
    uni.showToast({
        title: e,
        icon: icon,
        duration: time
    })
}

export { toast }
export default { toast }