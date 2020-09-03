function shallowCopy(target, ...origin) {
    return Object.assign(target, ...origin);
}

function extend(target, ...origin) {
    return shallowCopy(target, ...origin);
}

function deepCopy(target, origin) {
    for (let item in origin) {
        if (origin[item] && typeof(origin[item]) === "object") {
            // Object Array Date RegExp 深拷贝
            if (Object.prototype.toString.call(origin[item]) === "[object Object]") {
                target[item] = deepCopy({}, origin[item]);
            } else if (origin[item] instanceof Array) {
                target[item] = deepCopy([], origin[item]);
            } else if (origin[item] instanceof Date) {
                target[item] = new Date(origin[item]);
            } else if (origin[item] instanceof RegExp) {
                target[item] = new RegExp(origin[item].source, origin[item].flags);
            } else {
                target[item] = origin[item];
            }
        } else {
            target[item] = origin[item];
        }
    }
    return target;
}

export { extend, shallowCopy, deepCopy }

export default { extend, shallowCopy, deepCopy }
