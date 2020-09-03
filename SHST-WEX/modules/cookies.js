/**
 * GetCookie
 */
function getCookies(res) {
    var cookies = "";
    if (res && res.header) {
        for(let item in res.header){
            if(item.toLowerCase() === "set-cookie"){
                var cookie = res.header[item].match(/PHPSESSID=.*?;/);
                if(cookie) cookies += cookie;
            }
        }
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