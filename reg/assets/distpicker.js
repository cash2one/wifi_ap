!function(t){"function"==typeof define&&define.amd?define(["jquery","ChineseDistricts"],t):t(jQuery,ChineseDistricts)}(function(t,i){"use strict";if("undefined"==typeof i)throw new Error('The file "distpicker.data.js" must be included first!');var e=function(i,s){this.$element=t(i),this.defaults=t.extend({},e.defaults,t.isPlainObject(s)?s:{}),this.init()};e.prototype={constructor:e,data:i,init:function(){var i=this.$element.find("select"),e=i.length,s={};i.each(function(){t.extend(s,t(this).data())}),s.province?(this.defaults.province=s.province,this.$province=i.filter("[data-province]")):this.$province=e>0?i.eq(0):null,s.city?(this.defaults.city=s.city,this.$city=i.filter("[data-city]")):this.$city=e>1?i.eq(1):null,s.district?(this.defaults.district=s.district,this.$district=i.filter("[data-district]")):this.$district=e>=2?i.eq(2):null,this.output("province"),this.output("city"),this.output("district"),this.addListener()},addListener:function(){var t=this;this.$province&&this.$province.change(function(){t.output("city"),t.output("district")}),this.$city&&this.$city.change(function(){t.output("district")})},output:function(i){var e=1,s={},c=[],n="",d=this["$"+i],r=this;d&&(n=this.defaults[i]||"",e="province"===i?1:"city"===i?this.$province.find("option:selected").data().zipcode:"district"===i?this.$city.find("option:selected").data().zipcode:e,s=t.isNumeric(e)?this.data[e]:{},s=t.isPlainObject(s)?s:{},t.each(s,function(t,i){var e=i===n;e&&(r.selected=!0),c.push(r.template({zipcode:t,address:i,selected:e}))}),this.selected||c.unshift(r.template({zipcode:"",address:n,selected:!1})),d.html(c.join("")))},template:function(i){var e={zipcode:"",address:"",selected:!1};return t.extend(e,i),['<option value="'+(e.address&&e.zipcode?e.address:"")+'"',' data-zipcode="'+(e.zipcode?e.zipcode:"")+'"',e.selected?" selected":"",">"+(e.address?e.address:"")+"</option>"].join("")}},e.defaults={province:"\u2014\u2014 \u7701 \u2014\u2014",city:"\u2014\u2014 \u5e02 \u2014\u2014",district:"\u2014\u2014 \u533a \u2014\u2014"},e.setDefaults=function(i){t.extend(e.defaults,i)},t.fn.distpicker=function(i){return this.each(function(){t(this).data("distpicker",new e(this,i))})},t.fn.distpicker.constructor=e,t.fn.distpicker.setDefaults=e.setDefaults,t(function(){t("[distpicker]").distpicker()})});