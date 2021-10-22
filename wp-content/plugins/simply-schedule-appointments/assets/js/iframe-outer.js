/*! iFrame Resizer (iframeSizer.min.js ) - v4.3.2 - 2021-04-26
 *  Desc: Force cross domain iframes to size to content.
 *  Requires: iframeResizer.contentWindow.min.js to be loaded into the target frame.
 *  Copyright: (c) 2021 David J. Bradshaw - dave@bradshaw.net
 *  License: MIT
 */

!function(u){var f,l,a,x,M,I,k,r,m,F,t,g,z;function h(){return window.MutationObserver||window.WebKitMutationObserver||window.MozMutationObserver}function O(e,n,t){e.addEventListener(n,t,!1)}function R(e,n,t){e.removeEventListener(n,t,!1)}function o(e){return M+"["+(e="Host page: "+(n=e),e=window.top!==window.self?window.parentIFrame&&window.parentIFrame.getId?window.parentIFrame.getId()+": "+n:"Nested host page: "+n:e)+"]";var n}function i(e){return F[e]?F[e].log:l}function T(e,n){s("log",e,n,i(e))}function E(e,n){s("info",e,n,i(e))}function N(e,n){s("warn",e,n,!0)}function s(e,n,t,i){!0===i&&"object"==typeof window.console&&console[e](o(n),t)}function e(n){function t(){i("Height"),i("Width"),L(function(){A(y),H(v),l("onResized",y)},y,"init")}function e(){var e=b.substr(I).split(":"),n=e[1]?parseInt(e[1],10):0,t=F[e[0]]&&F[e[0]].iframe,i=getComputedStyle(t);return{iframe:t,id:e[0],height:n+function(e){if("border-box"!==e.boxSizing)return 0;var n=e.paddingTop?parseInt(e.paddingTop,10):0,e=e.paddingBottom?parseInt(e.paddingBottom,10):0;return n+e}(i)+function(e){if("border-box"!==e.boxSizing)return 0;var n=e.borderTopWidth?parseInt(e.borderTopWidth,10):0,e=e.borderBottomWidth?parseInt(e.borderBottomWidth,10):0;return n+e}(i),width:e[2],type:e[3]}}function i(e){var n=Number(F[v]["max"+e]),t=Number(F[v]["min"+e]),i=e.toLowerCase(),e=Number(y[i]);T(v,"Checking "+i+" is in range "+t+"-"+n),e<t&&(e=t,T(v,"Set "+i+" to min value")),n<e&&(e=n,T(v,"Set "+i+" to max value")),y[i]=""+e}function o(){function e(){return i.constructor===Array?function(){var e=0,n=!1;for(T(v,"Checking connection is from allowed list of origins: "+i);e<i.length;e++)if(i[e]===t){n=!0;break}return n}():(e=F[v]&&F[v].remoteHost,T(v,"Checking connection is from: "+e),t===e);var e}var t=n.origin,i=F[v]&&F[v].checkOrigin;if(i&&""+t!="null"&&!e())throw new Error("Unexpected message received from: "+t+" for "+y.iframe.id+". Message was: "+n.data+". This error can be disabled by setting the checkOrigin: false option or by providing of array of trusted domains.");return 1}function a(e){return b.substr(b.indexOf(":")+x+e)}function s(t,i){var e,n,o;e=function(){var e,n;B("Send Page Info","pageInfo:"+(e=document.body.getBoundingClientRect(),n=y.iframe.getBoundingClientRect(),JSON.stringify({iframeHeight:n.height,iframeWidth:n.width,clientHeight:Math.max(document.documentElement.clientHeight,window.innerHeight||0),clientWidth:Math.max(document.documentElement.clientWidth,window.innerWidth||0),offsetTop:parseInt(n.top-e.top,10),offsetLeft:parseInt(n.left-e.left,10),scrollTop:window.pageYOffset,scrollLeft:window.pageXOffset,documentHeight:document.documentElement.clientHeight,documentWidth:document.documentElement.clientWidth,windowHeight:window.innerHeight,windowWidth:window.innerWidth})),t,i)},n=32,z[o=i]||(z[o]=setTimeout(function(){z[o]=null,e()},n))}function r(e){e=e.getBoundingClientRect();return S(v),{x:Math.floor(Number(e.left)+Number(k.x)),y:Math.floor(Number(e.top)+Number(k.y))}}function d(e){var n=e?r(y.iframe):{x:0,y:0},t={x:Number(y.width)+n.x,y:Number(y.height)+n.y};T(v,"Reposition requested from iFrame (offset x:"+n.x+" y:"+n.y+")"),window.top!==window.self?window.parentIFrame?window.parentIFrame["scrollTo"+(e?"Offset":"")](t.x,t.y):N(v,"Unable to scroll to requested position, window.parentIFrame not found"):(k=t,c(),T(v,"--"))}function c(){!1!==l("onScroll",k)?H(v):j()}function u(e){var n,t=e.split("#")[1]||"",e=decodeURIComponent(t),i=document.getElementById(e)||document.getElementsByName(e)[0];i?(n=r(i),T(v,"Moving to in page link (#"+t+") at x: "+n.x+" y: "+n.y),k={x:n.x,y:n.y},c(),T(v,"--")):window.top!==window.self?window.parentIFrame?window.parentIFrame.moveToAnchor(t):T(v,"In page link #"+t+" not found and window.parentIFrame not found"):T(v,"In page link #"+t+" not found")}function f(e){var n,t={};t=0===Number(y.width)&&0===Number(y.height)?{x:(n=a(9).split(":"))[1],y:n[0]}:{x:y.width,y:y.height},l(e,{iframe:y.iframe,screenX:Number(t.x),screenY:Number(t.y),type:y.type})}function l(e,n){return W(v,e,n)}function m(){switch(F[v]&&F[v].firstRun&&F[v]&&(F[v].firstRun=!1),y.type){case"close":C(y.iframe);break;case"message":n=a(6),T(v,"onMessage passed: {iframe: "+y.iframe.id+", message: "+n+"}"),l("onMessage",{iframe:y.iframe,message:JSON.parse(n)}),T(v,"--");break;case"mouseenter":f("onMouseEnter");break;case"mouseleave":f("onMouseLeave");break;case"autoResize":F[v].autoResize=JSON.parse(a(9));break;case"scrollTo":d(!1);break;case"scrollToOffset":d(!0);break;case"pageInfo":s(F[v]&&F[v].iframe,v),r=v,e("Add ",O),F[r]&&(F[r].stopPageInfo=o);break;case"pageInfoStop":F[v]&&F[v].stopPageInfo&&(F[v].stopPageInfo(),delete F[v].stopPageInfo);break;case"inPageLink":u(a(9));break;case"reset":P(y);break;case"init":t(),l("onInit",y.iframe);break;default:0===Number(y.width)&&0===Number(y.height)?N("Unsupported message received ("+y.type+"), this is likely due to the iframe containing a later version of iframe-resizer than the parent page"):t()}function e(n,t){function i(){F[r]?s(F[r].iframe,r):o()}["scroll","resize"].forEach(function(e){T(r,n+e+" listener for sendPageInfo"),t(window,e,i)})}function o(){e("Remove ",R)}var r,n}var g,h,p,w,b=n.data,y={},v=null;"[iFrameResizerChild]Ready"===b?function(){for(var e in F)B("iFrame requested init",q(e),F[e].iframe,e)}():M===(""+b).substr(0,I)&&b.substr(I).split(":")[0]in F?(y=e(),v=y.id,F[v]&&(F[v].loaded=!0),(w=y.type in{true:1,false:1,undefined:1})&&T(v,"Ignoring init message from meta parent page"),!w&&(p=!0,F[h=v]||(p=!1,N(y.type+" No settings for "+h+". Message was: "+b)),p)&&(T(v,"Received: "+b),g=!0,null===y.iframe&&(N(v,"IFrame ("+y.id+") not found"),g=!1),g&&o()&&m())):E(v,"Ignored: "+b)}function W(e,n,t){var i=null,o=null;if(F[e]){if("function"!=typeof(i=F[e][n]))throw new TypeError(n+" on iFrame["+e+"] is not a function");o=i(t)}return o}function p(e){e=e.id;delete F[e]}function C(e){var n=e.id;if(!1!==W(n,"onClose",n)){T(n,"Removing iFrame: "+n);try{e.parentNode&&e.parentNode.removeChild(e)}catch(e){N(e)}W(n,"onClosed",n),T(n,"--"),p(e)}else T(n,"Close iframe cancelled by onClose event")}function S(e){null===k&&T(e,"Get page position: "+(k={x:window.pageXOffset!==u?window.pageXOffset:document.documentElement.scrollLeft,y:window.pageYOffset!==u?window.pageYOffset:document.documentElement.scrollTop}).x+","+k.y)}function H(e){null!==k&&(window.scrollTo(k.x,k.y),T(e,"Set page position: "+k.x+","+k.y),j())}function j(){k=null}function P(e){T(e.id,"Size reset requested by "+("init"===e.type?"host page":"iFrame")),S(e.id),L(function(){A(e),B("reset","reset",e.iframe,e.id)},e,"reset")}function A(o){function t(e){function n(){Object.keys(F).forEach(function(e){function n(e){return"0px"===(F[t]&&F[t].iframe.style[e])}var t;F[t=e]&&null!==F[t].iframe.offsetParent&&(n("height")||n("width"))&&B("Visibility change","resize",F[t].iframe,t)})}function t(e){T("window","Mutation observed: "+e[0].target+" "+e[0].type),c(n,16)}var i;a||"0"!==o[e]||(a=!0,T(r,"Hidden iFrame detected, creating visibility listener"),(i=h())&&function(){var e=document.querySelector("body");new i(t).observe(e,{attributes:!0,attributeOldValue:!1,characterData:!0,characterDataOldValue:!1,childList:!0,subtree:!0})}())}function e(e){var n;n=e,o.id?(o.iframe.style[n]=o[n]+"px",T(o.id,"IFrame ("+r+") "+n+" set to "+o[n]+"px")):T("undefined","messageData id not set"),t(e)}var r=o.iframe.id;F[r]&&(F[r].sizeHeight&&e("height"),F[r].sizeWidth&&e("width"))}function L(e,n,t){t!==n.type&&r&&!window.jasmine?(T(n.id,"Requesting animation frame"),r(e)):e()}function B(n,t,i,o,e){function r(){var e;i&&"contentWindow"in i&&null!==i.contentWindow?(e=F[o]&&F[o].targetOrigin,T(o,"["+n+"] Sending msg to iframe["+o+"] ("+t+") targetOrigin: "+e),i.contentWindow.postMessage(M+t,e)):N(o,"["+n+"] IFrame("+o+") not found")}function a(){e&&F[o]&&F[o].warningTimeout&&(F[o].msgTimeout=setTimeout(function(){!F[o]||F[o].loaded||s||(s=!0,N(o,"IFrame has not responded within "+F[o].warningTimeout/1e3+" seconds. Check iFrameResizer.contentWindow.js has been loaded in iFrame. This message can be ignored if everything is working, or you can set the warningTimeout option to a higher value or zero to suppress this warning."))},F[o].warningTimeout))}var s=!1;o=o||i.id,F[o]&&(r(),a())}function q(e){return e+":"+F[e].bodyMarginV1+":"+F[e].sizeWidth+":"+F[e].log+":"+F[e].interval+":"+F[e].enablePublicMethods+":"+F[e].autoResize+":"+F[e].bodyMargin+":"+F[e].heightCalculationMethod+":"+F[e].bodyBackground+":"+F[e].bodyPadding+":"+F[e].tolerance+":"+F[e].inPageLinks+":"+F[e].resizeFrom+":"+F[e].widthCalculationMethod+":"+F[e].mouseEvents}function d(i,e){function n(t){var e,n=h();n&&(e=n,i.parentNode&&new e(function(e){e.forEach(function(e){Array.prototype.slice.call(e.removedNodes).forEach(function(e){e===i&&C(i)})})}).observe(i.parentNode,{childList:!0})),O(i,"load",function(){var e,n;B("iFrame.onload",t,i,u,!0),e=F[s]&&F[s].firstRun,n=F[s]&&F[s].heightCalculationMethod in m,!e&&n&&P({iframe:i,height:0,width:0,type:"init"})}),B("init",t,i,u,!0)}function t(e){var n=e.split("Callback");2===n.length&&(this[n="on"+n[0].charAt(0).toUpperCase()+n[0].slice(1)]=this[e],delete this[e],N(s,"Deprecated: '"+e+"' has been renamed '"+n+"'. The old method will be removed in the next major version."))}function o(e){e=e||{},F[s]={firstRun:!0,iframe:i,remoteHost:i.src&&i.src.split("/").slice(0,3).join("/")},function(e){if("object"!=typeof e)throw new TypeError("Options is not an object")}(e),Object.keys(e).forEach(t,e),function(e){for(var n in g)Object.prototype.hasOwnProperty.call(g,n)&&(F[s][n]=(Object.prototype.hasOwnProperty.call(e,n)?e:g)[n])}(e),F[s]&&(F[s].targetOrigin=!0===F[s].checkOrigin?""===(e=F[s].remoteHost)||null!==e.match(/^(about:blank|javascript:|file:\/\/)/)?"*":e:"*")}var r,a,s=(""===(r=i.id)&&(i.id=(a=e&&e.id||g.id+f++,null!==document.getElementById(a)&&(a+=f++),r=a),l=(e||{}).log,T(r,"Added missing iframe ID: "+r+" ("+i.src+")")),r);function d(e){var n=F[s][e];1/0!==n&&0!==n&&(i.style[e]="number"==typeof n?n+"px":n,T(s,"Set "+e+" = "+i.style[e]))}function c(e){if(F[s]["min"+e]>F[s]["max"+e])throw new Error("Value for min"+e+" can not be greater than max"+e)}s in F&&"iFrameResizer"in i?N(s,"Ignored iFrame, already setup."):(o(e),function(){switch(T(s,"IFrame scrolling "+(F[s]&&F[s].scrolling?"enabled":"disabled")+" for "+s),i.style.overflow=!1===(F[s]&&F[s].scrolling)?"hidden":"auto",F[s]&&F[s].scrolling){case"omit":break;case!0:i.scrolling="yes";break;case!1:i.scrolling="no";break;default:i.scrolling=F[s]?F[s].scrolling:"no"}}(),c("Height"),c("Width"),d("maxHeight"),d("minHeight"),d("maxWidth"),d("minWidth"),"number"!=typeof(F[s]&&F[s].bodyMargin)&&"0"!==(F[s]&&F[s].bodyMargin)||(F[s].bodyMarginV1=F[s].bodyMargin,F[s].bodyMargin=F[s].bodyMargin+"px"),n(q(s)),F[s]&&(F[s].iframe.iFrameResizer={close:C.bind(null,F[s].iframe),removeListeners:p.bind(null,F[s].iframe),resize:B.bind(null,"Window resize","resize",F[s].iframe),moveToAnchor:function(e){B("Move to anchor","moveToAnchor:"+e,F[s].iframe,s)},sendMessage:function(e){B("Send Message","message:"+(e=JSON.stringify(e)),F[s].iframe,s)}}))}function c(e,n){null===t&&(t=setTimeout(function(){t=null,e()},n))}function n(){"hidden"!==document.visibilityState&&(T("document","Trigger event: Visiblity change"),c(function(){w("Tab Visable","resize")},16))}function w(t,i){Object.keys(F).forEach(function(e){var n;F[n=e]&&"parent"===F[n].resizeFrom&&F[n].autoResize&&!F[n].firstRun&&B(t,i,F[e].iframe,e)})}function b(){O(window,"message",e),O(window,"resize",function(){var e;T("window","Trigger event: "+(e="resize")),c(function(){w("Window "+e,"resize")},16)}),O(document,"visibilitychange",n),O(document,"-webkit-visibilitychange",n)}function y(){function i(e,n){n&&(function(){if(!n.tagName)throw new TypeError("Object is not a valid DOM element");if("IFRAME"!==n.tagName.toUpperCase())throw new TypeError("Expected <IFRAME> tag, found <"+n.tagName+">")}(),d(n,e),o.push(n))}var o;return function(){for(var e=["moz","webkit","o","ms"],n=0;n<e.length&&!r;n+=1)r=window[e[n]+"RequestAnimationFrame"];r?r=r.bind(window):T("setup","RequestAnimationFrame not supported")}(),b(),function(e,n){var t;switch(o=[],(t=e)&&t.enablePublicMethods&&N("enablePublicMethods option has been removed, public methods are now always available in the iFrame"),typeof n){case"undefined":case"string":Array.prototype.forEach.call(document.querySelectorAll(n||"iframe"),i.bind(u,e));break;case"object":i(e,n);break;default:throw new TypeError("Unexpected data type ("+typeof n+")")}return o}}function v(e){e.fn?e.fn.iFrameResize||(e.fn.iFrameResize=function(t){return this.filter("iframe").each(function(e,n){d(n,t)}).end()}):E("","Unable to bind to jQuery, it is not fully loaded.")}"undefined"!=typeof window&&(x="message".length,I=(M="[iFrameSizer]").length,r=window.requestAnimationFrame,g={autoResize:!(t=k=null),bodyBackground:null,bodyMargin:null,bodyMarginV1:8,bodyPadding:null,checkOrigin:!(a=l=!1),inPageLinks:!(F={}),enablePublicMethods:!(f=0),heightCalculationMethod:"bodyOffset",id:"iFrameResizer",interval:32,log:!(m={max:1,scroll:1,bodyScroll:1,documentElementScroll:1}),maxHeight:1/0,maxWidth:1/0,minHeight:0,minWidth:0,mouseEvents:!0,resizeFrom:"parent",scrolling:!1,sizeHeight:!0,sizeWidth:!1,warningTimeout:5e3,tolerance:0,widthCalculationMethod:"scroll",onClose:function(){return!0},onClosed:function(){},onInit:function(){},onMessage:function(){N("onMessage function not defined")},onMouseEnter:function(){},onMouseLeave:function(){},onResized:function(){},onScroll:function(){return!0}},z={},window.jQuery&&v(window.jQuery),"function"==typeof define&&define.amd?define([],y):"object"==typeof module&&"object"==typeof module.exports&&(module.exports=y()),window.iFrameResize=window.iFrameResize||y())}();

var bookingIframes = document.querySelectorAll('.ssa_booking_iframe');

// helper function to initialize the IframeResizer
var ssaInitIframeResizer = function() {
	var ssaIframeSettings = {
		heightCalculationMethod: 'bodyScroll',
		checkOrigin: false,
		warningTimeout: 20000
	}

	iFrameResize(ssaIframeSettings, '.ssa_booking_iframe')
}

// call on page load if there are SSA iframes
if (bookingIframes) {
	ssaInitIframeResizer();
}


// Only run Elementor and FF code if jQuery is on the page
if ( window.jQuery ) {

	// Elementor integration
	jQuery( document ).on( 'elementor/popup/show', function(){
		ssaInitIframeResizer()
	});

	jQuery( document ).ready(function($){

		// Formidable Forms integration
		$(document).on( 'frmPageChanged', function( event, form, response ) {
			var $iframes = $( '.frm_forms .ssa_booking_iframe' );

			// if form page has changed, and the current page contains an SSA iframe,
			// initialize IframeResizer
			if( $iframes.length ) {
				ssaInitIframeResizer();
			}
		});

		// Gravity Forms integration
		$( document ).on( 'gform_page_loaded', function( event, form_id, current_page ) {
			var $iframes = $( '.gform_wrapper .ssa_booking_iframe' );

			// if form page has changed, and the current page contains an SSA iframe,
			// initialize IframeResizer
			if( $iframes.length ) {
				ssaInitIframeResizer();
			}
		});
	});

} // end jQuery check

function ssaHandleScroll(e) {
	if (typeof e.data == 'object' && e.data.hasOwnProperty('iframe') && e.data.iframe === 'loaded') {
		if (e.origin !== ssa.api.home_url) { // Ensure the message is from SSA
			return;
		}

		var iframes = document.getElementsByTagName('iframe');
		var targetIframe;
		for ( i = 0; i < iframes.length; i++ ) {
			if (iframes[i].contentWindow === event.source) {
				targetIframe = iframes[i];
				break;
			}
		}

		if (!targetIframe) {
			return; // Something went wrong, bail out without throwing an error
		}

		if (targetIframe) {
			var box = targetIframe.getBoundingClientRect();
			var body = document.body;
			var docEl = document.documentElement;

			var scrollTop = window.pageYOffset || docEl.scrollTop || body.scrollTop;
			var scrollLeft = window.pageXOffset || docEl.scrollLeft || body.scrollLeft;

			var clientTop = docEl.clientTop || body.clientTop || 0;
			var clientLeft = docEl.clientLeft || body.clientLeft || 0;

			var top  = box.top +  scrollTop - clientTop - 50;
			var left = box.left + scrollLeft - clientLeft;

			var coords =  {
				top: Math.round(top),
				left: Math.round(left)
			};

			if (scrollTop || scrollLeft) { // If the page is scrolled
				setTimeout(function () {
          window.scrollTo(left, top);
        }, 150);
			}
		}
	}
}

// Handle scrolling for long booking forms
window.addEventListener('message', ssaHandleScroll, false);
