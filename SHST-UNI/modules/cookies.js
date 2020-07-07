/**
 * GetCookie
 */
function getCookies(res) {
    var cookies = "";
    if (res && res.header && res.header['Set-Cookie']) {
        // #ifdef MP-ALIPAY
        var cookies = res.header['Set-Cookie'][0].split(";")[0] + ";";
        // #endif
        // #ifndef MP-ALIPAY
        var cookies = res.header['Set-Cookie'].split(";")[0] + ";";
        // #endif
        console.log("SetCookie:" + cookies);
        uni.setStorage({
            key: "cookies",
            data: cookies
        });
    } else {
        console.log("Get Cookie From Cache");
        cookies = uni.getStorageSync("cookies") || "";
    }
    return cookies;
}

export { getCookies }
export default { getCookies }