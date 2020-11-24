const shallowCopy = function(target, ...origin) {
    return Object.assign(target, ...origin);
}

const extend = shallowCopy;

const propsCopy = function(target, origin, ...props) {
    props.forEach(v => target[v] = origin[v]);
    return target;
}

const deepCopy = function(target, origin) {
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

export { shallowCopy as extend, shallowCopy, deepCopy, propsCopy }

export default { extend, shallowCopy, deepCopy, propsCopy }
