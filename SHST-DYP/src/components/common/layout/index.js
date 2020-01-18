import layout from './layout';

/* istanbul ignore next */
layout.install = function(Vue) {
  Vue.component(layout.name, layout);
};

export default layout;
