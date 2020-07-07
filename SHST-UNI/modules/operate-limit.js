/**
 * 防抖
 * 定时器实现
 */
function debounceGenerater(){
    var timer = null;
    return (wait, funct, ...args) => {
        clearTimeout(timer);
        timer = setTimeout(() => funct(...args), wait);
    }
}

/**
 * 节流
 * 时间戳实现
 */
function throttleGenerater(){
    var previous = 0;
    return (wait, funct, ...args) => {
        var now = +new Date();
        if(now - previous > wait){
            funct(...args);
            previous = now;
        }
    }
}

/*
// 节流
// 定时器实现
function throttleGenerater(){
    var timer = null;
    return (wait, funct, ...args) => {
        if(!timer){
            funct(...args);
            timer = setTimeout(() => timer = null, wait);
        }  
    }
}
 */

export { debounceGenerater, throttleGenerater }
export default { debounceGenerater, throttleGenerater }