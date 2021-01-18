import {formatDate, addDate, safeDate} from "./datetime.js";


const convertKey = (key) => String(key).replace(/-storage$/g, "") + "-storage"; // 避免跟之前没有封装的缓存冲突

const convertToOrigin = (str) => {
    try{
        const data = JSON.parse(str);
        if(data.expire && safeDate().getTime() > data.expire) return null;
        return data.origin;
    }catch(e){
        console.log(e);
        return str;
    }
    return str;
}

const convertToStr = (origin, expire) => {
    const data = {origin, expire: null};
    if(expire) data.expire = expire.getTime();
    return JSON.stringify(data);
}

const storage = {
    get: function(key){
        key = convertKey(key);
        const str = uni.getStorageSync(key);
        if(str === "") return null;
        const origin = convertToOrigin(str);
        if(origin === null) this.removePromise(key);
        return origin;
    },
    set: function(key, data, expire = null){
        key = convertKey(key);
        const str = convertToStr(data, expire);
        return uni.setStorageSync(key, str);
    },
    getSync: function(key){
        return this.get(key);
    },
    setSync: function(key, data, expire = null){
        return this.set(key, data, expire);
    },
    getPromise: function(key){
        key = convertKey(key);
        return uni.getStorage({key}).then(([err, res]) => {
            if(err || res.data === "") return null;
            const origin = convertToOrigin(res.data);
            if(origin === null) this.removePromise(key);
            return origin;
        });
    },
    getOrigin: function(key){
        key = convertKey(key);
        return uni.getStorageSync(key);
    },
    setPromise: function(key, data, expire = null){
        key = convertKey(key);
        const str = convertToStr(data, expire);
        return uni.setStorage({ key, data: str }).then(([err, res]) => !err);
    },
    remove: function(key){
        key = convertKey(key);
        return uni.removeStorageSync(key);
    },
    removeSync: function(key){
        return this.remove(key);
    },
    removePromise: function(key){
        key = convertKey(key);
        return uni.removeStorage({ key }).then(([err, res]) => !err);
    },
    clear: function(){
        return uni.clearStorageSync();
    },
    clearSync: function(){
        return this.clear();
    },
    clearPrmise: function(){
        return uni.clearStorage().then(([err, res]) => !err);
    }
}

export default storage;
