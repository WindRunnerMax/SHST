/**
 * 正则匹配
 */
const regMatch = (regex, s) => {
    var result = [];
    var temp = null;
    var flags = `${regex.flags}${regex.global ? "" : "g"}`;
    regex = new RegExp(regex, flags);
    while (temp = regex.exec(s)) result.push(temp[1] ? temp[1] : temp[0]);
    return result;
}

export { regMatch }
export default { regMatch }
