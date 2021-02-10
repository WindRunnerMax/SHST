import stroage from "./storage.js";

/**
 * GetCookie
 */
function getCookies(res) {
    var cookies = "";
    if (res && res.header) {
        for(let item in res.header){
            if(item.toLowerCase() === "set-cookie"){
                let cookie = res.header[item].match(/.*?=.*?;/);
                cookies += cookie; // [] + "" = ""
            }
        }
        console.log("SetCookie:", cookies);
        stroage.setPromise("cookies", cookies);
    } else {
        console.log("Get Cookie From Cache");
        cookies = stroage.get("cookies") || "";
    }
    return cookies;
}

export { getCookies }
export default { getCookies }