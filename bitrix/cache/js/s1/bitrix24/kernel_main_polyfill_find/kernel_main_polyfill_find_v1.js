; /* /bitrix/js/main/polyfill/find/js/find.min.js?1557756318431*/

; /* Start:"a:4:{s:4:"full";s:58:"/bitrix/js/main/polyfill/find/js/find.min.js?1557756318431";s:6:"source";s:40:"/bitrix/js/main/polyfill/find/js/find.js";s:3:"min";s:44:"/bitrix/js/main/polyfill/find/js/find.min.js";s:3:"map";s:44:"/bitrix/js/main/polyfill/find/js/find.map.js";}"*/
(function(){"use strict";if(typeof Array.prototype.find!=="function"){Object.defineProperty(Array.prototype,"find",{enumerable:false,value:function(t,r){if(this===null){throw new TypeError("Cannot read property 'find' of null")}if(typeof t!=="function"){throw new TypeError(typeof t+" is not a function")}var n=this.length;for(var e=0;e<n;e++){if(t.call(r,this[e],e,this)){return this[e]}}}})}})();
/* End */
;
//# sourceMappingURL=kernel_main_polyfill_find.map.js