/**
  * 延时执行
  */
 function delay(e, args) {
     setTimeout((args) => e.apply(this), 100);
 }

 /**
  * Resize
  */
 function resize(dom, that) {
     const result = dom.getComponentRect(that.$refs.box, option => {
         if (uni.getSystemInfoSync().windowHeight > option.size.height) that.signalPage = true;
         else that.signalPage = false;
         console.log(that.signalPage ? "SIGNAL PAGE" : "FULL PAGE")
     })
 }

 /**
  * NextTick
  */
 function nextTick(dom, that) {
     that.$nextTick(() => {
         resize(dom, that)
     })
 }

 /**
  * 添加字体
  */
 function addIconfont() {
     var dom = weex.requireModule("dom");
     dom.addRule('fontFace', {
         'fontFamily': 'iconfont',
         'src': "url('https://at.alicdn.com/t/font_1582902_a1btjrevzq.ttf')"
     })
 }


export {delay, resize, nextTick, addIconfont}
export default {delay, resize, nextTick, addIconfont}
