define("common:widget/video/advList/advList.js",function(require,exports,module){function MisOutputAd(){}require("common:static/ui/tmpl/tmpl.js");var $=require("common:static/vendor/jquery/jquery.js"),_=require("common:static/vendor/underscore/underscore.js"),admis=require("common:static/ui/admis/admis.js"),advPositions={};MisOutputAd.prototype.init=function(){this.getData(this.render)},MisOutputAd.prototype.collection={indexRightTop:{id:"index_right_top",html:function(obj){{var __t,__p="";Array.prototype.join}with(obj||{}){__p+="",__p+="\n";var item=data&&data[0]||[];data&&data.length>1&&(item=data[Math.floor(Math.random()*data.length)]),__p+="\n",item&&1==item.hot_day&&(__p+='\n<a href="'+(null==(__t=item.url)?"":__t)+'" target="_blank" static="stp=toplist_140_70" title="'+(null==(__t=item.title)?"":_.escape(__t))+'">\n    <img src="'+(null==(__t=item.imgh_url)?"":__t)+'" alt="'+(null==(__t=item.title)?"":_.escape(__t))+'">\n</a>\n'),__p+="\n"}return __p},filter:function(){return document.referrer&&/www\.baidu\.com/.test(document.referrer)||/frp=bdbrand/.test(location.href)?!1:!0},action:function(){}},indexRightFloat:{id:"index_right_float",html:function(obj){{var __t,__p="";Array.prototype.join}with(obj||{}){__p+="",__p+='\n<div class="index_AD_right_close">\n	<a title="鍙充晶鎮诞骞垮憡" class="close" href="javascript:void(0)"></a>\n</div>\n<div class="adContainer">\n\n';for(var data=data&&data.slice(0,3)||[],i=0,len=data.length;len>i;i++){var item=data[i];__p+='\n\n<div id="adRightFloat'+(null==(__t=i)?"":__t)+'" \n    class="index_AD_right_fixed \n    ',item.title&&3>len&&(__p+=" has-title "),__p+=" AD-close-status\n	",1==item.intro&&(__p+=" index_AD_right_fixed_big"),__p+=' ">\n	<a class="normal" target="_blank" href="'+(null==(__t=item.url)?"":__t)+'" static="bl=index_AD_right_fixed&stp=adv_right_90">\n		<img  src="'+(null==(__t=item.imgh_url||item.imgv_url)?"":__t)+'" />',1!=item.intro&&3>len&&(__p+=""+(null==(__t=item.title)?"":__t)),__p+='\n	</a>\n	<a title="'+(null==(__t=item.title)?"":__t)+'" class="close" href="javascript:void(0)"></a>\n</div>\n'}__p+="\n</div>\n"}return __p},filter:function(){return document.referrer&&/www\.baidu\.com/.test(document.referrer)||/frp=bdbrand/.test(location.href)?!1:!0},action:function(){var t=$(".index_AD_right_fixed"),e={bdv_adRightFloat0:"#adRightFloat0",bdv_adRightFloat1:"#adRightFloat1",bdv_adRightFloat2:"#adRightFloat2",bdv_adRightFloat3:"#adRightFloat3",bdv_right_ad_poster:"#adRightFloatBig"};if(_.each(e,function(t,e){$.cookie.get(e)||$(t).removeClass("AD-close-status")}),t&&t.length&&$(".index_AD_right_fixed.AD-close-status").length&&$(".index_AD_right_close").show(),!$("#adRightFloatBig").hasClass("AD-close-status")){var i=5;$("#adRightFloatBig").append($("<span class='time-num'><b>5</b>绉掑悗鑷姩鍏抽棴</span>"));var n=$(".time-num b"),a=setInterval(function(){0>=i?($("#adRightFloatBig").addClass("AD-close-status"),n.parent().remove(),$.cookie.set("bdv_right_ad_poster","1",{expires:864e4,path:"/"}),clearInterval(a)):n.html(i--)},1e3)}$(".index_AD_right_fixed .close").on("click",function(){var t=$(this).parent(),e=t.attr("id");$(".index_AD_right_close").show(),t.hasClass("AD-close-status")?(t.removeClass("AD-close-status"),$(".index-right-float-big").removeClass("AD-close-status")):(t.addClass("AD-close-status"),$(".index-right-float-big").addClass("AD-close-status")),$.cookie.set("bdv_"+e,"1",{expires:864e4,path:"/"})}),$(".index_AD_right_close").on("click",function(){_.each(e,function(t,e){$.cookie.remove(e)}),$(this).hide().parent().find(".index_AD_right_fixed").removeClass("AD-close-status")}),$(".adContainer").on("mouseover",function(){$("#adRightFloatBig").removeClass("AD-close-status")}),$(".adContainer").on("mouseleave",function(){$("#adRightFloatBig").addClass("AD-close-status")})}},indexBannerTop:{id:"index_banner_top",html:function(obj){{var __t,__p="";Array.prototype.join}with(obj||{}){__p+="",__p+="\n";var item=data&&data[0]||[];data&&data.length>1&&(item=data[Math.floor(Math.random()*data.length)]),__p+="\n",item&&1==item.hot_day&&(__p+='\n    <div class="ad-top-banner" > \n        <a href="'+(null==(__t=item.url)?"":__t)+'" target="_blank" id="linkAdv984" static="stp=adv_top_984_90">\n            <img src="'+(null==(__t=item.imgh_url)?"":__t)+'" alt="'+(null==(__t=item.title)?"":__t)+'"/>\n        </a>\n        <a href="javascript:void(0)" class="btnTopBannerClose"></a>\n    </div>\n'),__p+="\n"}return __p},filter:function(){return document.referrer&&/www\.baidu\.com/.test(document.referrer)||/frp=bdbrand/.test(location.href)?!1:$.cookie.get("bdv_top_ad_banner")?!1:!0},action:function(t){$("#"+t).on("click",".btnTopBannerClose",function(){var t=new Date,e=3600*(23-t.getHours()),i=60*(59-t.getMinutes());$(this).parent(".ad-top-banner").hide(),$.cookie.set("bdv_top_ad_banner","1",{expires:1e3*(e+i),path:"/"})})}},event2014Warmup:{id:"event_2014_warmup",html:function(obj){{var __t,__p="";Array.prototype.join}with(obj||{}){__p+="",__p+="\n";var item=data&&data[0]||[];data&&data.length>1&&(item=data[Math.floor(Math.random()*data.length)]),__p+="\n",item&&1==item.hot_day&&(__p+='\n    <div class="ad-top-banner" style=\'position:relative;margin-bottom:20px;\' > \n        <a href="'+(null==(__t=item.url)?"":__t)+'" target="_blank" id="linkAdv984" static="stp=adv1234">\n            <img src="'+(null==(__t=item.imgh_url)?"":__t)+'" alt="'+(null==(__t=item.title)?"":__t)+'"/>\n        </a>\n        <a href="javascript:void(0)" \n		   class="btnTopBannerClose" \n		   style=\'position: absolute;\n	              top: 8px; right: 8px; width: 14px; height: 13px; \n				  background: url(http://list.video.baidu.com/pc_static/promotion/icon_close.gif) 0 0 no-repeat;\'></a>\n    </div>\n'),__p+="\n"}return __p},filter:function(){return document.referrer&&/www\.baidu\.com/.test(document.referrer)||/frp=bdbrand/.test(location.href)?!1:$.cookie.get("bdv_search_top_banner")?!1:!0},action:function(t){$("#"+t).on("click",".btnTopBannerClose",function(){var t=new Date,e=3600*(23-t.getHours()),i=60*(59-t.getMinutes());$(this).parent(".ad-top-banner").hide(),$.cookie.set("bdv_search_top_banner","1",{expires:1e3*(e+i),path:"/"})})}},indexBannerBottom:{id:"index_banner_bottom",html:function(obj){{var __t,__p="";Array.prototype.join}with(obj||{}){__p+="",__p+="\n";var item=data&&data[0]||[];data&&data.length>1&&(item=data[Math.floor(Math.random()*data.length)]),__p+="\n",item&&1==item.hot_day&&(__p+='\n	<div> \n	<a href="'+(null==(__t=item.url)?"":__t)+'" target="_blank" static="stp=iph_promote_984_83">\n		<img src="'+(null==(__t=item.imgh_url)?"":__t)+'" alt="'+(null==(__t=item.title)?"":__t)+'" />\n	</a>\n	</div>\n'),__p+="\n"}return __p},filter:function(){return document.referrer&&/www\.baidu\.com/.test(document.referrer)||/frp=bdbrand/.test(location.href)?!1:!0},action:function(){}}},MisOutputAd.prototype.getData=function(t){var e=this.bdvAdCache={},i=this;admis.get(["鍙充晶鎮诞","鍙充晶鑲╄唨鍥�","椤堕儴閫氭爮","妫€绱㈢椤堕儴閫氭爮","搴曢儴閫氭爮"],function(n){e.indexRightTop=n["鍙充晶鑲╄唨鍥�"],e.indexRightFloat=n["鍙充晶鎮诞"],e.indexBannerTop=n["椤堕儴閫氭爮"],e.event2014Warmup=n["妫€绱㈢椤堕儴閫氭爮"],e.indexBannerBottom=n["搴曢儴閫氭爮"],"function"==typeof t&&t(i,e)})},MisOutputAd.prototype.render=function(t,e){_.each(e,function(e,i){var n=t.collection[i].filter&&t.collection[i].filter();if(n&&t.collection[i].id in advPositions){var a=t.collection[i].html({data:e});$("#"+t.collection[i].id).html(a),t.collection[i].action(t.collection[i].id)}})};var misAD=new MisOutputAd;exports.init=function(){$(document).ready(function(){misAD.init()})},exports.push=function(t){var e=t();advPositions[e.id]=e}});
;define("common:widget/video/bdv_trace/bdv_trace.js",function(e,t,i){function n(){r.show(function(){setTimeout(function(){a(v+"&stp=new_login"),location.reload()},200)})}var s=$,a=(s.cookie,s.log),o=s.stringFormat,r=e("common:static/ui/bdPassPop/bdPassPop.js"),c=e("common:static/ui/bdvTrace/core/core.js"),d=e("common:static/ui/bdvTrace/config/config.js"),l=e("common:static/ui/bdvTrace/ugcRpTrack/ugcRpTrack.js"),g=e("common:static/ui/eventcenter/eventcenter.js"),h=e("common:static/ui/loginCheck/loginCheck.js"),p=e("common:static/vendor/underscore/underscore.js").template,u=c.delay,b="http://nsclick.baidu.com/u.gif?pid=104",v="http://nsclick.baidu.com/v.gif?pid=104",f=function(e,t){if(!e)throw new Error("Creating BdvTrace with empty model.");this.model=e,t=t||{},this.enableCarousel="undefined"!=typeof t.enableCarousel?t.enableCarousel:!0,this.isUgcRpTrack="undefined"!=typeof t.isUgcRpTrack?t.isUgcRpTrack:!1,this.isLogin=this.model.isLogin,this.frp=t.frp,this._hasCreatedCarousel=!1,this._isVisible=!1,this._isEmpty=!1,this._isUserTrigger=!1,this._initialize()};f.prototype={_initialize:function(){this._bindEl(),this._isVisible="none"!==this.$main.style.display,this._bindEvent(),this.isLogin&&(this.$content.innerHTML="")},_bindEl:function(){this.$el=$("#bdvTrace")[0],this.isUgcRpTrack&&this.$el.setAttribute("static",this.$el.getAttribute("static")+"&track=1"),this.$login=$(".bdv-trace-login",this.$el)[0],this.$toggle=$(".bdv-trace-toggle",this.$el)[0],this.$notify=$(".bdv-trace-notify",this.$el)[0],this.$main=$(".bdv-trace-main",this.$el)[0],this.$content=$(".bdv-trace-content",this.$el)[0],this.$bdvRecord=$("#bdvRecord"),this.$bdvRecord&&(this.$bdvRecordToggle=$(".bdv-record-toggle",this.$bdvRecord)[0],this.$bdvRecordMain=$(".bdv-record-main",this.$bdvRecord)[0])},_bindEvent:function(){var e=this,t=e.isUgcRpTrack?function(){return e._isEmpty}:function(){return!e.isLogin||e._isEmpty};s(e.$toggle).on("click",function(i){i.preventDefault(),t()?e[e._isVisible?"hide":"show"]():e._isLoading||(e._isVisible?e.hide():(e._isUserTrigger=!0,e.model.list()))}),e.$bdvRecordToggle&&s(e.$bdvRecordToggle).on("click",function(t){t.preventDefault(),e.hide()}),e.$login&&s(e.$login).on("click",function(t){t.preventDefault(),n(),e.hide()}),e._bindInnerEvent(),e._bindOuterEvent()},_bindInnerEvent:function(){var e=this;e.model.on("render",function(t){s.extend(e,t.pageMeta),(e._isUserTrigger||e._isVisible)&&e._render(t.pageData)}),e.model.on("notify",function(){e.show()})},_bindOuterEvent:function(){var e=this;g.attach("bdvTrace.list",function(t){e.isLogin?(e._isUserTrigger=!0,e.model.list(t.params)):e.show()}),g.attach("bdvTrace.add",function(t){e.isLogin?(e._isUserTrigger=!0,e.model.add(t.params,t.callback)):n()}),g.attach("bdvTrace.del",function(t){!e.isLogin&&e._isEmpty?n():(e._isUserTrigger=!0,e.model.del(t.params,t.callback))})},show:function(e){if(!this._isVisible||e===!0){this.$toggle.setAttribute("static","stp=toggle&toggle=0"),$(this.$el).addClass("bdv-trace-show");var t=this.enableCarousel&&!this._isEmpty;this.isLogin||(t=t&&this.isUgcRpTrack),t||(this.$main.style.display="block"),this._isVisible=!0,this.isLogin||u(function(){a(b)},0)}},hide:function(){this._isVisible&&(this.$toggle.setAttribute("static","stp=toggle&toggle=1"),g.trigger("bdvTraceCarousel.hide"),$(this.$el).removeClass("bdv-trace-show"),this.$main.style.display="none",this._isVisible=!1)},_hideRecord:function(){this.$bdvRecordMain&&"none"!==this.$bdvRecordMain.style.display&&(this.$bdvRecordMain.style.display="none")},_render:function(e){var t=this,i=[];if(1!=t.pageNo||e&&0!==e.length){if(e&&e.length)if(i.push("data=1"),t.enableCarousel)t._hasCreatedCarousel||(g.trigger("bdvTraceCarousel.create",{once:!0,isUgcRpTrack:t.isUgcRpTrack,model:t.model}),t._hasCreatedCarousel=!0),g.trigger("bdvTraceCarousel.page",{frp:t.frp,pageData:e,pageMeta:{pageNo:t.pageNo,pages:t.pages,size:t.size},recGroups:t.model.recGroups}),t.show(),i.push("fr=carousel");else{t.$bd?t.$bd.innerHTML="":t._initPage();var n={frp:t.frp,pageData:e,func:{getPlayUrl:d.getPlayUrl}},o="";try{o=p(s("#bdvTraceItemTpl").html(),n),t.$bd.innerHTML=o,t._rendered()}catch(r){}}}else t._isEmpty=!0,i.push("data=0"),t._renderNone();u(function(){a(b+"&"+i.join("&"))},0)},_initPage:function(){$(this.$content).removeClass("bdv-trace-none"),this.$content.innerHTML=$("#bdvTraceBdTpl")[0].innerHTML,this.$bd=$(".bdv-trace-bd",this.$content)[0],this.$ft=$(".bdv-trace-ft",this.$content)[0],this.$pager=$(".bdv-trace-pager",this.$content),this.$pagerNo=$(".bdv-trace-pager-no",this.$content)[0];var e=this;e.$pager&&s.each(e.$pager,function(t,i){s(i).on("click",function(t){t.preventDefault();var n=e.pageNo;"prev"==i.getAttribute("data-page")?n-=1:n+=1,n>0&&n<=e.pages&&(e._isUserTrigger=!0,e.model.list({pn:n}))})});var t=function(t){var i=t.parentNode.parentNode,n=t.getAttribute("data-ep"),s=t.getAttribute("data-new"),a=i.getAttribute("data-id"),o=i.getAttribute("data-type");1==s&&e.model.fire("clearNotify"),e.hide(),n&&a&&o&&u(function(){e.model.update({type:o,works_id:a,last_view:n})},0)},i=function(t){var i=t.getAttribute("data-id"),n=t.getAttribute("data-type");i&&n&&u(function(){e.model.del({type:n,works_id:i})},0)};s(this.$bd).on("click",function(e){var n=e.target;"A"===n.tagName?n.getAttribute("data-ep")?t(n):$(n).hasClass("bdv-trace-item-del")&&(e.preventDefault(),i(n)):"SPAN"===n.tagName&&(n=n.parentNode,"A"===n.tagName&&""!=n.getAttribute("data-ep")&&t(n))})},_rendered:function(){this.pages>1?(this.$pagerNo.innerHTML=this.pageNo+"/"+this.pages,this.$ft.style.display="block"):this.$ft.style.display="none",this.show()},_renderNone:function(){$(this.$content).addClass("bdv-trace-none"),this.$content.innerHTML=$("#bdvTraceNoneTpl")[0].innerHTML,this.$bd=null,this.show(!0)}},i.exports=function(e,t){var i=null,n=o("&tn=#{0}&tpl=#{0}&bl=bdvTrace",[e]);b+=n,v+=n;var s={tip:!0,autoCheck:!1,crossDomain:!0};rules=d.rules;for(var a,r=0;a=rules[r];r+=1)if(a.test()){s=a.config;break}var g=function(){h(function(e){var n="object"==typeof e&&e.value,a=n&&e.vipinfo&&e.vipinfo.isvalid,r=1==t,d={crossDomain:s.crossDomain,isLogin:n,pageSize:r?6:2},g=c,h=!n&&l.isHit(5);h&&(g=l.Klass,d.crossDomain=!0),b+=o("&login=#{0}&track=#{1}",[n?1:0,h?1:0]);var p=new g(d);i=i||new f(p,{enableCarousel:r,frp:a?"":"bdbrand",isUgcRpTrack:h}),s.autoCheck&&p.check()})};u(g,400)}});
;define("common:widget/video/cutdown/cutdown.js",function(t,n,e){function i(t){this.endClock=t.endClock||new Date(Date.parse("2015/5/13 00:00:00")),this.nowClock=t.nowClock||(new Date).getTime(),this.timeGap=Math.round((this.endClock-this.nowClock)/1e3),this.onChangeTime=t.onChangeTime,this.finish=t.finish||null,this.start()}t("common:static/vendor/jquery/jquery.js");i.prototype={constructor:i,parseDate:function(t){var n=t.split(/[- :\/]/);return new Date(n[0],n[1]-1,n[2],n[3],n[4],n[5])},countOnce:function(){var t=this,n=t.formatTime(t.timeGap);t.changeTime(n),t.lastCount=n},start:function(){var t=this;t.timeout=setTimeout(function(){t.start()},1e3),t.timeGap>0?(t.timeGap--,t.countOnce(t.timeGap)):("function"==typeof t.finish&&t.finish(t),clearTimeout(t.timeout))},formatTime:function(t){var n=Math.floor(t/86400),e=Math.floor((t-60*n*60*24)/3600),i=Math.floor((t-60*n*60*24-60*e*60)/60),o=t-60*n*60*24-60*e*60-60*i;return[n,e,i,o]},changeTime:function(t){this.curCount=t,this.onChangeTime&&this.onChangeTime(this,t)}},e.exports=i});
;define("common:widget/video/header/header.js",function(i,n,o){var s=i("common:static/ui/scrolling/scrolling.js"),e=i("common:static/ui/client/client.js");n=o.exports=function(i){$(function(){function n(){c.addClass("hdmini"),t.stop().hide().fadeIn(),r=!0}function o(){t.stop().show(),c.removeClass("hdmini"),r=!1}if("ie6"!==e.browser.ie){var d=$(window),c=$("#header"),t=c.find(".hd-inner"),a=180,r=!1;i&&i.navMini?(c.addClass("hdmini"),t.show(),c.show(),r=!0):$(function(){s(window,function(){var i=d.scrollTop();i>a?!r&&n():r&&o()}),d.scrollTop()>a&&!r&&n()})}})}});
;define("common:widget/video/nav/nav.js",function(require,exports,module){var tpl=function(obj){{var __t,__p="";Array.prototype.join}with(obj||{}){__p+="";for(var i=0,len=menu.length;len>i;i++){var item=menu[i];__p+="\n	<li",pageTn&&item.s_intro==pageTn&&(__p+=' class="current"'),__p+='>\n		<a href="'+(null==(__t=item.url)?"":__t)+'"',1==item.update&&(__p+=' target="_blank"'),__p+=">"+(null==(__t=item.title)?"":__t),item.hot_day>0&&(__p+='\n		<img src="'+(null==(__t=item.imgh_url)?"":__t)+'" style="bottom:'+(null==(__t=item.duration||30)?"":__t)+"px;left:"+(null==(__t=item.rating||3)?"":__t)+'px">\n		'),__p+="</a>\n	</li>\n"}__p+=""}return __p},onlineAPI="http://v.baidu.com/staticapi/api_mis_nav_utf8.json";exports=module.exports=function(t){window.cbMISNav=function(n){for(var _,e={common_nav_left:"#nav .menu-main",common_nav_right:"#nav .menu-sub"},i=0,a=n.length;a>i;i++)_=n[i],e[_.name]&&_.data&&_.data.videos&&$(e[_.name]).append(tpl({pageTn:t,menu:_.data.videos}))},$.getScript(onlineAPI+"?v="+Math.ceil(new Date/72e5))}});
;define("common:widget/video/navGuider/navGuider.js",function(){function e(){var e=n("#btn_more"),o=n("#box_more"),t=null;e[0]&&o[0]&&(e.on("mouseenter",function(){clearTimeout(t),o.show(),n("#btn_more").addClass("btn_more_hover")}),e.on("mouseleave",function(){t=setTimeout(function(){o.hide(),n("#btn_more").removeClass("btn_more_hover")},200)}),o.on("mouseenter",function(){clearTimeout(t)}),o.on("mouseleave",function(){t=setTimeout(function(){o.hide(),n("#btn_more").removeClass("btn_more_hover")},200)}))}var n=$;e(),$(function(){window.cbMISNav=function(e){var n=e&&e[0]&&e[0].data&&e[0].data.videos;if(n&&n.length)for(var o,t=0,i=n.length;i>t;t++)o=n[t],"live"===o.s_intro&&$("#navMainMenu .btn_more").before('<li><a href="'+o.url+'"><span>'+o.title+"</span></a></li>");window.cbMISNav=null},$.getScript("http://v.baidu.com/staticapi/api_mis_nav_utf8.json?v="+Math.ceil(new Date/72e5))})});
;define("common:widget/video/navmini/navmini.js",function(n,i,e){i=e.exports=function(){var n=!1;$("#navmini").mouseenter(function(){n||($("#navmini .bd ul").append($("#nav li").clone()),n=!0),$(this).addClass("show")}).mouseleave(function(){$(this).removeClass("show")})}});
;define("common:widget/video/push/push.js",function(require,exports,module){var push={init:function(t){var n=this.formatData(t);this.render(n)},formatData:function(t){var n={config:{},data:[]};return $.each(t,function(t,i){"config"===i.title?n.config={caption:i.sub_title||"鐑棬鏂伴椈",more_txt:i.intro||"鏇村",more_url:i.url||"http://v.baidu.com",cookieid:i.update||0,duration:i.id||0}:n.data.push(i)}),n},render:function(data){var self=this;self.pushBox=$('<div id="video_push_box" style="display:none;"></div>');var html=(require("common:static/vendor/underscore/underscore.js"),function(obj){{var __t,__p="";Array.prototype.join}with(obj||{}){__p+="";var page=encodeURIComponent(window.location.href);__p+='\n<div class="video-push-inner" static="bl=pushBox&o='+(null==(__t=page)?"":__t)+'">\n	<div class="video-push-title">\n		<span class="caption">'+(null==(__t=config.caption)?"":__t)+'</span>\n		<span class="push-close-btn"><a href="javascript:void(0);" static="stp=close"></a></span>\n	</div>\n	<div class="video-push-body">\n		<div class="video-push-wrap">\n			<a href="'+(null==(__t=data.url)?"":__t)+'" class="video-view" target="_blank" static="stp=img&to=play">\n				<img src="'+(null==(__t=data.imgh_url)?"":__t)+'" title="'+(null==(__t=data.title)?"":__t)+'">\n				<span class="s-play"></span>\n				<span class="info-bg"></span>\n				<div class="desc">'+(null==(__t=data.update)?"":__t)+'</div>\n			</a>\n			<h3><a href="'+(null==(__t=data.url)?"":__t)+'" target="_blank" title="'+(null==(__t=data.title)?"":__t)+'" static="stp=title&to=play">'+(null==(__t=data.title)?"":__t)+"</a></h3>\n			<p>"+(null==(__t=data.sub_title)?"":__t)+'</p>\n		</div>\n		<div class="video-push-footer"><a href="'+(null==(__t=config.more_url)?"":__t)+'" static="stp=more" target="_blank" title="'+(null==(__t=config.more_txt)?"":__t)+'">'+(null==(__t=config.more_txt)?"":__t)+">></a></div>\n	</div>\n</div>\n"}return __p}({config:data.config,data:data.data[0]}));self.pushBox.html(html),require("common:static/ui/push/push.js")({el:self.pushBox,cookie:"BDPCPS",cookieid:data.config.cookieid,duration:data.config.duration,time:120,delay:2e3,data:data,afterRender:function(){var t=this;$(".push-close-btn").on("click",function(){t.close()}),t.el.hover(function(){$.log("http://nsclick.baidu.com/v.gif?pid=104&u="+encodeURIComponent(window.location.href)+"&bl=pushBox&ext=hover")},function(){}),$.log("http://nsclick.baidu.com/p.gif?pid=104&u="+encodeURIComponent(window.location.href)+"&bl=pushBox&ext=init")}})}};module.exports=function(t){push.init(t)}});
;define("common:widget/video/searchKeyword/searchKeyword.js",function(require,exports,module){function _request(){loading||(loading=!0,window.videoSearchKeywordMIS=function(e){if(loaded=!0,dataCache=e&&e[0]&&e[0].data&&e[0].data.videos,dataCache.length&&queue.length){var t=tpl({videos:dataCache});queue.each(function(e,a){a.innerHTML=t})}},$.getScript("http://v.baidu.com/staticapi/api_search_keyword.json?v="+Math.ceil(new Date/72e5)))}var dataCache,tpl=function(obj){{var __t,__p="";Array.prototype.join}with(obj||{}){__p+="<ul>\n";for(var i=0,len=videos.length;len>i;i++){var item=videos[i];__p+='\n	<li>\n		<a href="'+(null==(__t=item.url)?"":__t)+'" ',item.update&&(__p+='class="hot"'),__p+=' target="_blank">'+(null==(__t=item.title)?"":__t)+"</a>\n		",item.date&&(__p+='<img src="http://vs4.bdstatic.com/short/icon_hot_tag.gif" alt="" />'),__p+="\n	</li>\n"}__p+="\n</ul>\n"}return __p},queue=$(),loaded=!1,loading=!1;exports=module.exports=function(e){var t=$("#"+e);t&&(loaded?dataCache&&dataCache.length&&t.html(tpl({videos:dataCache})):(queue=queue.add(t),_request()))}});
;define("common:widget/video/searchbox/searchbox.js",function(e,i,t){function n(e){$("#"+e.id+"Btn").click(e.openQuickSearch?function(i){i.preventDefault(),i.stopPropagation(),$.log($("#header").hasClass("hdmini")?"http://nsclick.baidu.com/v.gif?pid=104&searchpage="+c+"&s=zdjs&eventtype=click&newheader=1&wd="+encodeURIComponent($("#"+e.id+"Input").val()):"http://nsclick.baidu.com/v.gif?pid=104&searchpage="+c+"&s=zdjs&eventtype=click&wd="+encodeURIComponent($("#"+e.id+"Input").val())),setTimeout(function(){$("#"+e.id).submit()},200)}:function(){$.log($("#header").hasClass("hdmini")?"http://nsclick.baidu.com/v.gif?pid=104&searchpage="+c+"&s=zdjs&eventtype=click&newheader=1&wd="+encodeURIComponent($("#"+e.id+"Input").val()):"http://nsclick.baidu.com/v.gif?pid=104&searchpage="+c+"&s=zdjs&eventtype=click&wd="+encodeURIComponent($("#"+e.id+"Input").val()))})}function a(e){this.pageTn=e.pageTn,this.$input=$("#"+e.id+"Input"),e.ismis?this._bind():a.query?this._fillQuery():(a.instances.push(this),a.request())}var c="";a.prototype={_fillQuery:function(){this.$input.attr("data-prekey",a.query).addClass("place-holder").val(a.query),this._bind()},_bind:function(){var e=this.$input,i=e.attr("data-prekey");i&&e.focus(function(){e.val()===i&&e.val("").removeClass("place-holder")}).blur(function(){e.val()||e.addClass("place-holder").val(i)})}},a.instances=[],a.request=function(){this.loading||(this.loading=!0,window.videoPreKeywordMIS=function(e){e&&e[0]&&e[0].data&&e[0].data.videos&&$.each(e[0].data.videos,function(e,i){return $.trim(i.s_intro)===c?(a.query=i.title,!1):void 0}),a.query&&$.each(a.instances,function(e,i){i._fillQuery()})},$.getScript("http://v.baidu.com/staticapi/api_prekeyword.json?v="+Math.ceil(new Date/72e5)))},i=t.exports=function(e){c=e.pageTn;new a(e);n(e)}});
;define("common:widget/video/tabsearch/tabsearch.js",function(t,a,e){a=e.exports=function(){var t={news:"http://news.baidu.com/ns?cl=2&rn=20&tn=news&word={{query}}&ie=utf-8",ps:"http://www.baidu.com/s?cl=3&wd={{query}}&ie=utf-8",tieba:"http://tieba.baidu.com/f?kw={{query}}&t=1&ie=utf-8",zhidao:"http://zhidao.baidu.com/q?ct=17&pn=0&tn=ikaslist&rn=10&word={{query}}&ie=utf-8",music:"http://music.baidu.com/search?fr=video&key={{query}}&ie=utf-8",image:"http://image.baidu.com/search/index?tn=baiduimage&ct=201326592&cl=2&lm=-1&pv=&word={{query}}&z=0&ie=utf-8",map:"http://map.baidu.com/m?word={{query}}&fr=map004&ie=utf-8",baike:"http://baike.baidu.com/search/word?word={{query}}&pic=1&sug=1"},a=$("#bdvSearchInput");a.length&&$("#tabsearch .tabs").delegate("a[data-product]","click",function(){var e,i=a.val();i&&i!==a.attr("data-prekey")&&(e=t[this.getAttribute("data-product")],e&&(this.href=e.replace("{{query}}",i)))});var e=$("#tabsearch .tabs a[data-product=ps]");/chrome|firefox|safari|msie 10|rsv:11|msie [89]/i.test(navigator.userAgent)&&(e.prop("href","https://www.baidu.com/"),t.ps="https://www.baidu.com/s?cl=3&wd={{query}}&ie=utf-8",e.one("mouseover",function(){window.BaiduHttps=window.BaiduHttps||{},window.BaiduHttps.callbacks||(window.BaiduHttps.callbacks=function(){}),$.ajax({url:"https://www.baidu.com/con?from=vbaidu&callback=?",dataType:"jsonp",jsonpCallback:"BaiduHttps.callbacks"})}))}});
;define("common:widget/video/userbar/userbar.js",function(require,exports,module){function setRefere(){var e={tvplay:!0,movie:!0,tamasha:!0,cartoon:!0,indsa:!0,cooperate:!0},o=pageTn&&e[pageTn]&&pageTn;if(urlfr)o&&$.cookie.set("bdvref",urlfr+"_"+o,{domain:location.hostname,path:"/"});else if(!$.cookie.get("bdvref")){var i=document.referrer;if(i){var a=document.createElement("a");a.href=i,i=a.hostname.match(/^(v|www)\.(baidu|hao123)\.com$/)?a.hostname:""}i&&o&&"indsa"!==o&&$.cookie.set("bdvref",i+"_"+o,{domain:location.hostname,path:"/"})}}function initUserBar(userinfo){var isBDPlayer=navigator.userAgent.indexOf("BIDUPlayer")>-1;if(isBDPlayer&&window.external&&window.external.bpls_get_login_info)try{var yyUserInfo=eval("("+window.external.bpls_get_login_info()+")");!yyUserInfo.LoginState&&userinfo&&userinfo.value&&(location.href="http://passport.baidu.com/?logout&tpl=vd&u="+encodeURIComponent(location.href))}catch(ex){}var isNewIndex=null!==$.cookie.getRaw("__bdvnindex");$(function(){function e(){var e=$("#userbar .uname"),o=$("#userbar .logout");e.length&&(e.off("mouseenter.userbar").on("mouseenter.userbar",function(){$(this).addClass("uname-hover")}).off("mouseleave.userbar").on("mouseleave.userbar",function(){$(this).removeClass("uname-hover")}),!location.hostname.match(/\.baidu\.com$/)&&o&&(o.click(function(e){e.preventDefault();var o=document.createElement("iframe");o.src="http://v.baidu.com/dev_proxy_logout.html?frp="+location.hostname,o.style.display="none",document.body.appendChild(o),$tip.style.display="none"}),window.video_logout_callback=function(){location.reload()}))}function o(){var e=encodeURIComponent(document.location.href),o=['<ul class="no-login">','<li class="link"><a href="http://pan.baidu.com/" target="_blank">浜戠洏</a></li>','<li class="link"><a href="http://list.video.baidu.com/iph_promote.html" target="_blank">鐧惧害瑙嗛鏃犵嚎鐗�</a></li>','<li class="line">|</li>','<li class="login"><a href="http://passport.baidu.com/v2/?login&tpl=vd&u='+e+'" target="_blank" id="loginbtn" class="link-login">鐧诲綍</a><a href="https://passport.baidu.com/v2/?reg&tpl=vd&regType=1&u='+e+'" target="_blank" class="link-reg">娉ㄥ唽</a></li>',isNewIndex?'<li class="line">|</li>':"",isNewIndex?'<li class="nindex"><a href="/i/?is_old=false" class="link-nindex">瀹氬埗棣栭〉</a></li>':"","</ul>"];$("#userbar").append(o.join("")),location.hostname.match(/baidu\.com$/)&&$("#loginbtn").off("click.userbar").on("click.userbar",function(e){e.preventDefault(),bdPassPop.show()})}function i(o){var i=['<ul class="logged">','<li class="uname"><a class="link-name" href="#{mysite}" target="_blank" id="username"><span id="user_name">#{value}'+(o.user_source&&0!=o.user_source&&1==o.incomplete_user&&0==o.has_uname?'<img src="http://passport.bdimg.com/passApi/img/icon_#{user_source}_16.png" class="show_icons" id="show_icons" />':"")+"</span></a>",'<div class="tip">',"<ul>",'<li class="first"><a href="#{mysite}" target="_blank" class="my-info">涓汉涓績</a></li>','<li><a href="https://#{host}/" target="_blank" class="my-info">甯愬彿璁剧疆</a></li>',isBDPlayer?"":'<li><a href="#{logoutUrl}" class="logout">閫€鍑�</a></li>',"</ul>","</div>","</li>",'<li class="line">|</li>','<li class="link"><a href="http://pan.baidu.com/" target="_blank">浜戠洏</a></li>','<li class="line">|</li>','<li class="link"><a href="http://list.video.baidu.com/iph_promote.html" target="_blank">鐧惧害瑙嗛鏃犵嚎鐗�</a></li>',isNewIndex?'<li class="line">|</li>':"",isNewIndex?'<li class="nindex"><a href="/i/?is_old=false" class="link-nindex">瀹氬埗棣栭〉</a></li>':"","</ul>"];0==o.incomplete_user&&0==o.has_uname?(o.value=o.emailphone,o.mysite="https://passport.baidu.com/v2/?ucenteradduname&to=princess"):o.mysite=1==o.incomplete_user&&0==o.has_uname?"https://passport.baidu.com/v2/?ucenteradduname&to=princess":"http://i.baidu.com/?from=video",o.logoutUrl="http://"+o.host+"/?logout&tpl=vd&u="+encodeURIComponent(document.location.href),$("#userbar").html($.stringFormat(i.join(""),o)).addClass(o&&o.vipinfo&&o.vipinfo.isvalid?"vip-super":"vip"),e()}userinfo&&userinfo.value?i(userinfo):o()})}function globalStatistics(e){var o=location.hostname.match(/(?:\.baidu\.com$)|(?:\.hao123\.com$)/)?$.cookie.get("bdvref"):e.cookie&&e.cookie.bdvref,i=$.cookie.get("bdvshare"),a="http://nsclick.baidu.com/v.gif?pid=104&tn="+pageTn+(o?"&VIDEO_FR="+encodeURIComponent(o)+(i?"__"+i:""):""),n=location.href.match(/baidu\.com\/v\?.*word=([^&]*)/);n?a+="&wd="+n[1]:(n=location.href.match(/baidu\.com\/(tv|comic|show)\/(\d*)\.htm/))&&(a+="&id="+n[2]+"&ty="+n[1]),a+=isLogin?"&login=true&uname="+encodeURIComponent(e.value)+"&utype="+utype:"&login=false",urlfr&&(a+="&fr="+urlfr),$.log(a)}var T=$,loginCheck=require("common:static/ui/loginCheck/loginCheck.js"),bdPassPop=require("common:static/ui/bdPassPop/bdPassPop.js"),ec=require("common:static/ui/eventcenter/eventcenter.js"),isLogin=!1,utype="normal",pageTn="",urlfr=T.url.getQueryValue(location.href,"fr");exports=module.exports=function(e,o){pageTn=e,loginCheck(function(e){e&&e.value&&(isLogin=!0,e.vipinfo&&e.vipinfo.isvalid&&e.vipinfo.isvip&&(utype="vip"),$(document.body).addClass("global-logged")),!o&&initUserBar(e),setRefere(),globalStatistics(e)})},ec.attach("userbar.refresh",function(){exports(pageTn)})});
;define("common:widget/video/userinfo/userinfo.js",function(n,o,e){var t=$,a=n("common:static/ui/bdPassPop/bdPassPop.js"),i=function(n){t(function(){!function(n){if(n&&"object"==typeof n&&n.value&&""!=n.value){window.userinfo={isLogin:!0};var o=['<a href="http://v.baidu.com/user/" target="_blank" id="username">涓汉涓績</a>'];t("#userinfo p").eq(0).append(t.stringFormat(o.join(""),n))}else{var o=['<a href="http://passport.baidu.com/v2/?login&tpl=vd&u=#{Url}" target="_blank" id="loginbtn">鐧诲綍</a>','<span class="line">|</span>','<a href="https://passport.baidu.com/v2/?reg&tpl=vd&regType=1&u=#{Url}">娉ㄥ唽</a>'],n={Url:encodeURIComponent(document.location.href)};t("#userinfo p").eq(0).append(t.stringFormat(o.join(""),n)),location.hostname.match(/baidu\.com$/)&&t("#loginbtn").on("click",function(n){n.preventDefault(n),a.show()})}}(n)})};e.exports=i});