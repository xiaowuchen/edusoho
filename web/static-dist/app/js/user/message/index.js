webpackJsonp(["app/js/user/message/index"],{0:function(e,t,n){"use strict";function a(e){return e&&e.__esModule?e:{default:e}}var r=n("09eb1f9807af90690645"),u=a(r);new u.default({element:"#message-create-form"})},"09eb1f9807af90690645":function(e,t,n){"use strict";function a(e){return e&&e.__esModule?e:{default:e}}function r(e,t){if(!(e instanceof t))throw new TypeError("Cannot call a class as a function")}Object.defineProperty(t,"__esModule",{value:!0});var u=function(){function e(e,t){for(var n=0;n<t.length;n++){var a=t[n];a.enumerable=a.enumerable||!1,a.configurable=!0,"value"in a&&(a.writable=!0),Object.defineProperty(e,a.key,a)}}return function(t,n,a){return n&&e(t.prototype,n),a&&e(t,a),t}}(),s=n("b334fd7e4c5a19234db2"),i=a(s),o=function(){function e(t){r(this,e),this.$element=$(t.element),this.validator()}return u(e,[{key:"validator",value:function(){var e=this.$element;e.validate({rules:{"message[receiver]":{required:!0,es_remote:!0,chinese_alphanumeric:!0},"message[content]":{required:!0,maxlength:500}},ajax:!0,submitSuccess:function(){(0,i.default)("success",Translator.trans("私信发送成功")),e.closest(".modal").modal("hide")},submitError:function(){(0,i.default)("danger",Translator.trans("私信发送失败，请重试！"))}})}}]),e}();t.default=o}});