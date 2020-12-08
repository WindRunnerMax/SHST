/**
 * 小程序更新
 */
function checkUpdate() {
    if (!uni.getUpdateManager) return false;
    uni.getUpdateManager().onCheckForUpdate((res) => {
        console.log("Update:", res.hasUpdate);
        if (res.hasUpdate) { //如果有新版本
            uni.getUpdateManager().onUpdateReady(() => { // 当新版本下载完成
                uni.showModal({
                    title: "更新提示",
                    content: "新版本已经准备好，单击确定重启应用",
                    success: (res) => {
                        if (res.confirm) uni.getUpdateManager().applyUpdate(); // applyUpdate 应用新版本并重启
                    }
                })
            })
            uni.getUpdateManager().onUpdateFailed(() => { // 当新版本下载失败
                uni.showModal({
                    title: "提示",
                    content: "检查到有新版本，但下载失败，请检查网络设置",
                    showCancel: false
                })
            })
        }
    })
}

export { checkUpdate }
export default { checkUpdate }
