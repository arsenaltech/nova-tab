import {Tab, Tabs} from 'vue-tabs-component'
Nova.booting((Vue, router) => {
  Vue.component('index-nova-tabs', require('./components/IndexField'));
  Vue.component('detail-nova-tabs', require('./components/DetailField'));
  Vue.component('form-nova-tabs', require('./components/FormField'));
  Vue.component('tabs', Tabs);
  Vue.component('tab', Tab);



})
