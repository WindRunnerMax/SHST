const defineInfo = (rules) => {
    const info = {};
    for(let item in rules){
        const inner = {};
        inner.msg = "";
        inner.show = false;
        inner.rules = rules[item];
        info[item] = inner;
    }
    return info;
}

const verifyRulesUnit = (value, rulesArr, info) => {
    if(!rulesArr) return true;
    let allCheck = true;
    const checkResult = (result, info, msg) => {
        if(result){
            info.msg = "";
            info.show = false;
        }else{
            info.show = true;
            info.msg = msg;
            allCheck = false;
        }
        return result;
    }
    for(let i=0, len=rulesArr.length; i<len; ++i){
        const rule = rulesArr[i];
        const valueLength = value.length;
        if(rule.required === false && !value) {
            checkResult(true, info, rule.msg);
            break;
        }
        if(rule.min){
            if(!checkResult(rule.min <= valueLength, info, rule.msg)) break;
        }
        if(rule.max){
            if(!checkResult(valueLength <= rule.max, info, rule.msg)) break;
        }
        if(typeof(rule.pattern) === "string" && typeof(value) === "string"){
            if(!checkResult(new RegExp(rule.pattern).test(value), info, rule.msg)){
                break;
            }
        }
    }
    return allCheck;
}


const verifyRules = (form, rules, infos) => {
    let allCheck = true;
    for(let item in form){
        const value = form[item];
        const rulesArr = rules[item];
        if(rulesArr === void 0) continue;
        if(!verifyRulesUnit(value, rulesArr, infos[item])) allCheck = false;
    }
    return allCheck;
}

const verifyUnit = {
    methods: {
        verifyUnit: function(name){
            uni.$app.eventBus.commit("VerifyUnitAction", name);
            return true;
        }
    }
}

export {defineInfo, verifyRules, verifyRulesUnit, verifyUnit}
