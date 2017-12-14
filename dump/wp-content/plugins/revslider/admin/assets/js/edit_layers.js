/*!
 * VERSION: beta 0.2.3
 * DATE: 2013-07-10
 * UPDATES AND DOCS AT: http://www.greensock.com
 *
 * @license Copyright (c) 2008-2013, GreenSock. All rights reserved.
 * SplitText is a Club GreenSock membership benefit; You must have a valid membership to use
 * this code without violating the terms of use. Visit http://www.greensock.com/club/ to sign up or get more details.
 * This work is subject to the software agreement that was issued with your membership.
 *
 * @author: Jack Doyle, jack@greensock.com
 */
(function(t){"use strict";var e=t.GreenSockGlobals||t,i=function(t){var i,s=t.split("."),r=e;for(i=0;s.length>i;i++)r[s[i]]=r=r[s[i]]||{};return r},s=i("com.greensock.utils"),r=function(t){var e=t.nodeType,i="";if(1===e||9===e||11===e){if("string"==typeof t.textContent)return t.textContent;for(t=t.firstChild;t;t=t.nextSibling)i+=r(t)}else if(3===e||4===e)return t.nodeValue;return i},n=document,a=n.defaultView?n.defaultView.getComputedStyle:function(){},o=/([A-Z])/g,h=function(t,e,i,s){var r;return(i=i||a(t,null))?(t=i.getPropertyValue(e.replace(o,"-$1").toLowerCase()),r=t||i.length?t:i[e]):t.currentStyle&&(i=t.currentStyle,r=i[e]),s?r:parseInt(r,10)||0},l=function(t){return t.length&&t[0]&&(t[0].nodeType&&t[0].style&&!t.nodeType||t[0].length&&t[0][0])?!0:!1},_=function(t){var e,i,s,r=[],n=t.length;for(e=0;n>e;e++)if(i=t[e],l(i))for(s=i.length,s=0;i.length>s;s++)r.push(i[s]);else r.push(i);return r},u=")eefec303079ad17405c",c=/(?:<br>|<br\/>|<br \/>)/gi,p=n.all&&!n.addEventListener,f="<div style='position:relative;display:inline-block;"+(p?"*display:inline;*zoom:1;'":"'"),m=function(t){t=t||"";var e=-1!==t.indexOf("++"),i=1;return e&&(t=t.split("++").join("")),function(){return f+(t?" class='"+t+(e?i++:"")+"'>":">")}},d=s.SplitText=e.SplitText=function(t,e){if("string"==typeof t&&(t=d.selector(t)),!t)throw"cannot split a null element.";this.elements=l(t)?_(t):[t],this.chars=[],this.words=[],this.lines=[],this._originals=[],this.vars=e||{},this.split(e)},g=function(t,e,i,s,o){c.test(t.innerHTML)&&(t.innerHTML=t.innerHTML.replace(c,u));var l,_,p,f,d,g,v,y,T,w,b,x,P,S=r(t),C=e.type||e.split||"chars,words,lines",k=-1!==C.indexOf("lines")?[]:null,R=-1!==C.indexOf("words"),A=-1!==C.indexOf("chars"),D="absolute"===e.position||e.absolute===!0,O=D?"&#173; ":" ",M=-999,L=a(t),I=h(t,"paddingLeft",L),E=h(t,"borderBottomWidth",L)+h(t,"borderTopWidth",L),N=h(t,"borderLeftWidth",L)+h(t,"borderRightWidth",L),F=h(t,"paddingTop",L)+h(t,"paddingBottom",L),U=h(t,"paddingLeft",L)+h(t,"paddingRight",L),X=h(t,"textAlign",L,!0),z=t.clientHeight,B=t.clientWidth,j=S.length,Y="</div>",q=m(e.wordsClass),V=m(e.charsClass),Q=-1!==(e.linesClass||"").indexOf("++"),G=e.linesClass;for(Q&&(G=G.split("++").join("")),p=q(),f=0;j>f;f++)g=S.charAt(f),")"===g&&S.substr(f,20)===u?(p+=Y+"<BR/>",f!==j-1&&(p+=" "+q()),f+=19):" "===g&&" "!==S.charAt(f-1)&&f!==j-1?(p+=Y,f!==j-1&&(p+=O+q())):p+=A&&" "!==g?V()+g+"</div>":g;for(t.innerHTML=p+Y,d=t.getElementsByTagName("*"),j=d.length,v=[],f=0;j>f;f++)v[f]=d[f];if(k||D)for(f=0;j>f;f++)y=v[f],_=y.parentNode===t,(_||D||A&&!R)&&(T=y.offsetTop,k&&_&&T!==M&&"BR"!==y.nodeName&&(l=[],k.push(l),M=T),D&&(y._x=y.offsetLeft,y._y=T,y._w=y.offsetWidth,y._h=y.offsetHeight),k&&(R!==_&&A||(l.push(y),y._x-=I),_&&f&&(v[f-1]._wordEnd=!0)));for(f=0;j>f;f++)y=v[f],_=y.parentNode===t,"BR"!==y.nodeName?(D&&(b=y.style,R||_||(y._x+=y.parentNode._x,y._y+=y.parentNode._y),b.left=y._x+"px",b.top=y._y+"px",b.position="absolute",b.display="block",b.width=y._w+1+"px",b.height=y._h+"px"),R?_?s.push(y):A&&i.push(y):_?(t.removeChild(y),v.splice(f--,1),j--):!_&&A&&(T=!k&&!D&&y.nextSibling,t.appendChild(y),T||t.appendChild(n.createTextNode(" ")),i.push(y))):k||D?(t.removeChild(y),v.splice(f--,1),j--):R||t.appendChild(y);if(k){for(D&&(w=n.createElement("div"),t.appendChild(w),x=w.offsetWidth+"px",T=w.offsetParent===t?0:t.offsetLeft,t.removeChild(w)),b=t.style.cssText,t.style.cssText="display:none;";t.firstChild;)t.removeChild(t.firstChild);for(P=!D||!R&&!A,f=0;k.length>f;f++){for(l=k[f],w=n.createElement("div"),w.style.cssText="display:block;text-align:"+X+";position:"+(D?"absolute;":"relative;"),G&&(w.className=G+(Q?f+1:"")),o.push(w),j=l.length,d=0;j>d;d++)"BR"!==l[d].nodeName&&(y=l[d],w.appendChild(y),P&&(y._wordEnd||R)&&w.appendChild(n.createTextNode(" ")),D&&(0===d&&(w.style.top=y._y+"px",w.style.left=I+T+"px"),y.style.top="0px",T&&(y.style.left=y._x-T+"px")));R||A||(w.innerHTML=r(w).split(String.fromCharCode(160)).join(" ")),D&&(w.style.width=x,w.style.height=y._h+"px"),t.appendChild(w)}t.style.cssText=b}D&&(z>t.clientHeight&&(t.style.height=z-F+"px",z>t.clientHeight&&(t.style.height=z+E+"px")),B>t.clientWidth&&(t.style.width=B-U+"px",B>t.clientWidth&&(t.style.width=B+N+"px")))},v=d.prototype;v.split=function(t){this.isSplit&&this.revert(),this.vars=t||this.vars,this._originals.length=this.chars.length=this.words.length=this.lines.length=0;for(var e=0;this.elements.length>e;e++)this._originals[e]=this.elements[e].innerHTML,g(this.elements[e],this.vars,this.chars,this.words,this.lines);return this.isSplit=!0,this},v.revert=function(){if(!this._originals)throw"revert() call wasn't scoped properly.";for(var t=this._originals.length;--t>-1;)this.elements[t].innerHTML=this._originals[t];return this.chars=[],this.words=[],this.lines=[],this.isSplit=!1,this},d.selector=t.$||t.jQuery||function(e){return t.$?(d.selector=t.$,t.$(e)):n?n.getElementById("#"===e.charAt(0)?e.substr(1):e):e}})(window||{});

/*! ROTATABLE */
// ROTATABLE
// 
!function(t,e){t.widget("ui.rotatable",t.ui.mouse,{options:{handle:!1,angle:!1,start:null,rotate:null,stop:null},handle:function(t){return t===e?this.options.handle:void(this.options.handle=t)},angle:function(t){return t===e?this.options.angle:(this.options.angle=t,void this.performRotation(this.options.angle))},_create:function(){var e;this.options.handle?e=this.options.handle:(e=t(document.createElement("div")),e.addClass("ui-rotatable-handle")),this.listeners={rotateElement:t.proxy(this.rotateElement,this),startRotate:t.proxy(this.startRotate,this),stopRotate:t.proxy(this.stopRotate,this)},e.draggable({helper:"clone",start:this.dragStart,handle:e}),e.bind("mousedown",this.listeners.startRotate),e.appendTo(this.element),0!=this.options.angle?(this.elementCurrentAngle=this.options.angle,this.performRotation(this.elementCurrentAngle)):this.elementCurrentAngle=0},_destroy:function(){this.element.removeClass("ui-rotatable"),this.element.find(".ui-rotatable-handle").remove()},performRotation:function(t){var e=180*t/Math.PI;punchgs.TweenLite.set(this.element,{rotationZ:e+"deg"})},getElementOffset:function(){this.performRotation(0);var t=this.element.offset();return this.performRotation(this.elementCurrentAngle),t},getElementCenter:function(){var t=this.getElementOffset(),e=t.left+this.element.width()/2,n=t.top+this.element.height()/2;return Array(e,n)},dragStart:function(){return this.element?!1:void 0},startRotate:function(e){var n=this.getElementCenter(),i=e.pageX-n[0],s=e.pageY-n[1];return this.mouseStartAngle=Math.atan2(s,i),this.elementStartAngle=this.elementCurrentAngle,this.hasRotated=!1,this._propagate("start",e),t(document).bind("mousemove",this.listeners.rotateElement),t(document).bind("mouseup",this.listeners.stopRotate),!1},rotateElement:function(t){if(!this.element)return!1;var e=this.getElementCenter(),n=t.pageX-e[0],i=t.pageY-e[1],s=Math.atan2(i,n),o=s-this.mouseStartAngle+this.elementStartAngle;this.performRotation(o);var r=this.elementCurrentAngle;return this.elementCurrentAngle=o,this._propagate("rotate",t),r!=o&&(this._trigger("rotate",t,this.ui()),this.hasRotated=!0),!1},stopRotate:function(e){return this.element?(t(document).unbind("mousemove",this.listeners.rotateElement),t(document).unbind("mouseup",this.listeners.stopRotate),this.elementStopAngle=this.elementCurrentAngle,this.hasRotated&&this._propagate("stop",e),setTimeout(function(){this.element=!1},10),!1):void 0},_propagate:function(e,n){t.ui.plugin.call(this,e,[n,this.ui()]),"rotate"!==e&&this._trigger(e,n,this.ui())},plugins:{},ui:function(){return{element:this.element,angle:{start:this.elementStartAngle,current:this.elementCurrentAngle,stop:this.elementStopAngle}}}})}(jQuery);

jQuery(document).ready(function(){
	UniteLayersRev.setGlobalAction(wp.template( "rs-action-layer-wrap" ));
});

jQuery(document).ready(function(){
	jQuery.widget( "custom.catcomplete", jQuery.ui.autocomplete, {
		_create: function() {
			this._super();
			this.widget().menu( "option", "items", "> :not(.ui-autocomplete-category)" );
		},
		_renderMenu: function( ul, items ) {
			var that = this,
			currentCategory = "";
			if (items)
				jQuery.each( items, function( index, item ) {
					var li;
					if ( item.version != currentCategory ) {
						ul.append( "<li class='ui-autocomplete-category' style='font-size: 24px;'>Version: " + item.version + "</li>" );
						currentCategory = item.version;
					}
					li = that._renderItemData( ul, item );
					if ( item.version ) {
						li.attr( "aria-label", item.version + " : " + item.label );
					}
				});
		}
	});
});

// EDIT Slider Functions
// VERSION: 5.0 
// DATE: 28-04-2015
var UniteLayersRev = new function(){

	var initTop = 100,
		initLeft = 100,
		initSpeed = 300,

		initTopVideo = 20,
		initLeftVideo = 20,
		g_startTime = 500,
		g_stepTime = 0,
		g_slideTime,

		initText = "Caption Text",
		layout = 'desktop', //can be also tablet and mobil
		transSettings = [], //can be also tablet and mobil

		t = this,
		u = tpLayerTimelinesRev,
		initArrFontTypes = [],
		containerID = "#divLayers",
		container,
		arrLayers = {},
		arrLayersDemo = {},
		id_counter = 0,
		initLayers = null,
		initDemoLayers = null,
		initDemoSettings = null,
		layerresized = false,
		layerGeneralParamsStatus = false,
		initLayerAnims = [],
		initLayerAnimsDefault = [],
		currentAnimationType = 'customin',
		curDemoSlideID = 0,
		slideIDs = {};
		
		selectedLayerSerial = -1,
		selectedLayerWidth = 0,
		selectedlayerHeight = 0,
		
		totalWidth = 0,
		totalHeight = 0,
		unique_layer_id = 0,
		add_meta_into = '',
		global_action_template = null,
		updateRevTimer = 0;

	t.ulff_core = 0;
		
	t.setGlobalAction = function (glfunc){
		global_action_template = glfunc;
	}
	
	t.arrLayers = arrLayers;


	t.setInitSlideIds = function(jsonIds){ slideIDs = jQuery.parseJSON(jsonIds); }
	
	// set init layers object (from db)
	t.setInitLayersJson = function(jsonLayers){ initLayers = jQuery.parseJSON(jsonLayers); }

	// set init demo layers object (from db)
	t.setInitDemoLayersJson = function(jsonLayers){ initDemoLayers = jQuery.parseJSON(jsonLayers); }

	// set init demo settings object (from db)
	t.setInitDemoSettingsJson = function(jsonLayers){ initDemoSettings = jQuery.parseJSON(jsonLayers); }

	// set init layer animations (from db)
	t.setInitLayerAnim = function(jsonLayersAnims){ initLayerAnims = jQuery.parseJSON(jsonLayersAnims); }

	// set init layer animations (from db)
	t.setInitLayerAnimsDefault = function(jsonLayersAnims){ initLayerAnimsDefault = jQuery.parseJSON(jsonLayersAnims); }

	// set all settings that need trans
	t.setInitTransSetting = function(jsonTransSett){ transSettings = jQuery.parseJSON(jsonTransSett); }

	// update init layer animations (from db)
	t.updateInitLayerAnim = function(layerAnims){
		initLayerAnims = [];
		initLayerAnims = layerAnims;
	}

	// set init captions classes array (from the captions.css)
	t.setInitCaptionClasses = function(jsonClasses){ initArrCaptionClasses = jQuery.parseJSON(jsonClasses); }
	
	t.setCaptionClasses = function(objClasses){ initArrCaptionClasses = objClasses; }

	// set init font family types array
	t.setInitFontTypes = function(jsonClasses){ initArrFontTypes = jQuery.parseJSON(jsonClasses); }

	// GET / SET MAIN TIME
	t.getMaintime = function() { return g_slideTime; }
	t.setMaintime = function(a) { g_slideTime = a; }


	
	/**
	 * SET option to object depending on which size is choosen
	 */
	t.setVal = function(obj, handle, val, setall, setspecific){
		if(jQuery.inArray(handle, transSettings) !== -1){ //handle is in the list, so save only on the current choosen size
	
			if(setall){
				if(typeof(obj[handle]) !== 'object') obj[handle] = {};
				obj[handle][layout] = val;
				//obj[handle]['desktop'] = val;
				//obj[handle]['notebook'] = val;
				//obj[handle]['tablet'] = val;
				//obj[handle]['mobile'] = val;
			}else if(setspecific !== undefined){
				for(var i in setspecific){
					if(typeof(obj[handle]) !== 'undefined' && typeof(obj[handle][setspecific[i]]) !== 'undefined'){
						obj[handle][setspecific[i]] = val;
					}else{
						if(typeof(obj[handle]) === 'undefined') obj[handle] = {};
						obj[handle][setspecific[i]] = val;
					}
				}
			}else{
				if(typeof(obj[handle]) !== 'undefined' && typeof(obj[handle][layout]) !== 'undefined'){
					obj[handle][layout] = val;
				}else{
					if(typeof(obj[handle]) === 'undefined') obj[handle] = {};
					obj[handle][layout] = val;
				}
			}
		}else{
			obj[handle] = val;
		}		
		
		return obj;
	}
	
	
	/**
	 * GET option to object depending on which size is choosen
	 */
	t.getVal = function(obj, handle){
		if(typeof(obj) === 'undefined') return;
		
		if(jQuery.inArray(handle, transSettings) !== -1){ //handle is in the list, so save only on the current choosen size
			if(typeof(obj[handle]) !== 'undefined' && typeof(obj[handle][layout]) !== 'undefined'){				
				return obj[handle][layout];
			}else{
				if(typeof(obj[handle]) !== 'undefined' && typeof(obj[handle]) !== 'object'){
					return obj[handle];
				}else{
					if(typeof(obj[handle]) !== 'undefined'){
						//return next bigger / smaller value depending on what exists. First check bigger, then check smaller
						var nextval = '',
							returnval = 'novalue',
							calcblayout = "desktop";

						switch(layout){
							case 'desktop':
								if(typeof(obj[handle]['notebook']) !== 'undefined')   {
									returnval = obj[handle]['notebook'];
									calcblayout = "notebook";
								}
								else 
								if(typeof(obj[handle]['tablet']) !== 'undefined') {
									returnval =  obj[handle]['tablet'];
									calcblayout = "tablet";
								}
								else
								if(typeof(obj[handle]['mobile']) !== 'undefined') {
									returnval =  obj[handle]['mobile'];
									calcblayout = "mobile";
								}
							break;
							case 'notebook':
								if(typeof(obj[handle]['desktop']) !== 'undefined') {
									returnval =  obj[handle]['desktop'];
									calcblayout = "desktop";
								}
								else
								if(typeof(obj[handle]['tablet']) !== 'undefined') {
									returnval =  obj[handle]['tablet'];
									calcblayout = "tablet";
								}
								else
								if(typeof(obj[handle]['mobile']) !== 'undefined') {
									returnval =  obj[handle]['mobile'];
									calcblayout = "mobile";
								}
							break;
							case 'tablet':
								if(typeof(obj[handle]['notebook']) !== 'undefined') {
									returnval =  obj[handle]['notebook'];
									calcblayout = "notebook";
								}
								else
								if(typeof(obj[handle]['desktop']) !== 'undefined') {
									returnval =  obj[handle]['desktop'];
									calcblayout = "desktop";
								}
								else
								if(typeof(obj[handle]['mobile']) !== 'undefined') {
									returnval =  obj[handle]['mobile'];
									calcblayout = "mobile";
								}
							break;
							case 'mobile':
								if(typeof(obj[handle]['tablet']) !== 'undefined') {
									returnval =  obj[handle]['tablet'];
									calcblayout = "tablet";
								}
								else
								if(typeof(obj[handle]['notebook']) !== 'undefined') {
									returnval =  obj[handle]['notebook'];
									calcblayout = "notebook";
								}
								else
								if(typeof(obj[handle]['desktop']) !== 'undefined') {
									returnval =  obj[handle]['desktop'];
									calcblayout = "desktop";
								}
							break;
						}
						 
						if (returnval!='novalue') {
							/*if (handle=="top" || handle=="left") returnval = 0;
							if (handle=="align_hor") returnval = "left";
							if (handle=="align_hor") returnval = "left";*/
							return returnval;
						}

					}
					return; //returns undefined
				}
			}
		}else{
			if(typeof(obj[handle]) !== 'undefined' && typeof(obj[handle]) !== 'object'){
				return obj[handle];
			}else{				
				return;
			}
		}
	}


	/**
	 * insert template to editor
	 */
	t.insertTemplate = function(text){
		
		if(add_meta_into === ''){
			if(selectedLayerSerial == -1) return(false);
			jQuery('#layer_text').val(jQuery('#layer_text').val()+'{{'+text+'}}');
			t.updateLayerFromFields();
		}else{
			jQuery('input[name="'+add_meta_into+'"]').val(jQuery('input[name="'+add_meta_into+'"]').val()+'{{'+text+'}}');
		}
		jQuery('#dialog_template_insert').dialog('close');
	}


	/**************************************
		-	Refresh The Grid Size 	-
	***************************************/
	t.refreshGridSize = function(){
		
		var hcl = jQuery('#hor-css-linear .helplines-offsetcontainer'),
			vcl = jQuery('#ver-css-linear .helplines-offsetcontainer'),
			grid_size = jQuery('#rs-grid-sizes option:selected').val(),
			snapto = jQuery('#rs-grid-snapto option:selected').val(),
			wrapper = jQuery('#divLayers');


		if (grid_size!="custom") {
			wrapper.css({position:"relative"});
			wrapper.find('#helpergrid').remove();
			wrapper.append('<div id="helpergrid" style="position:absolute;top:0px;left:0px;position:absolute;z-index:0;width:100%;height:100%"></div>');
			var hg = wrapper.find('#helpergrid');
			if (grid_size>4) {
				for (var i=1;i<(wrapper.height()/grid_size);i++) {
					var jump = i*grid_size;
					hg.append('<div class="helplines" style="background-color:#4affff;width:100%;height:1px;position:absolute;left:0px;top:'+jump+'px"></div>');
				}

				for (var i=1;i<(wrapper.width()/grid_size);i++) {
					var jump = i*grid_size;
					hg.append('<div class="helplines" style="background-color:#4affff;height:100%;width:1px;position:absolute;top:0px;left:'+jump+'px"></div>');
				}
			}
			punchgs.TweenLite.to(hcl,0.3,{autoAlpha:0});
			punchgs.TweenLite.to(vcl,0.3,{autoAlpha:0});
		} else {
				punchgs.TweenLite.to(hcl,0.3,{autoAlpha:1});
				punchgs.TweenLite.to(vcl,0.3,{autoAlpha:1});
				wrapper.find('#helpergrid').remove();
				try{hcl.find('.helplines').draggable("destroy");} catch(e) {}
				try{vcl.find('.helplines').draggable("destroy");} catch(e) {}
				hcl.find('.helplines').draggable({ handle:".helpline-drag", axis:"x" });
				vcl.find('.helplines').draggable({ handle:".helpline-drag", axis:"y" });
		}

		for(var key in arrLayers){
			var layer = t.getHtmlLayerFromSerial(key);			
			layer.draggable({
					drag: onLayerDrag,						
					snap: snapto,
					snapMode:"outer"

				});
		}
	}
	
	/**
	 * Add unique layer ids
	 **/
	t.add_missing_unique_ids = function(){
		var layers = t.getSimpleLayers();
		var addfor = {};
		
		//first round, check which one are missing unique IDs and set the unique_layer_id to be used by new layers
		for(var key in layers){
			if(layers[key].unique_id === undefined){
				addfor[key] = true;
			}else{
				if(layers[key].unique_id > unique_layer_id)
					unique_layer_id = layers[key].unique_id;
			}
		}
		
		for(var key in addfor){
			unique_layer_id++;
			var objUpdate = {};
			objUpdate.unique_id = unique_layer_id;
			
			t.updateLayer(key, objUpdate);
		}
		
	}

	//======================================================
	//	Init Functions
	//======================================================
	t.init = function(slideTime){
		
		if(jQuery().draggable == undefined || jQuery().autocomplete == undefined)
			jQuery("#jqueryui_error_message").show();
		

		g_slideTime = Number(slideTime);
		u.init(g_slideTime);
		container = jQuery(containerID);


		
		//add all layers from init
		if(initDemoLayers){
			var len = initDemoLayers.length;
			if(len){
				for(var i=0;i<len;i++){
					for(var key in initDemoLayers[i]){
						curDemoSlideID = i;
						addLayer(initDemoLayers[i][key],true,true);
					}
				}
			}else{
				for(var i in initDemoLayers){
					for(var key in initDemoLayers[i]){
						curDemoSlideID = i;
						addLayer(initDemoLayers[i][key],true,true);
					}
				}
			}
		}

		
		
		//add all layers from init
		if(initLayers){
			var len = initLayers.length;
			if(len){
				for(var i=0;i<len;i++) {

					addLayer(initLayers[i],true);
				}
			}else{
				for(var key in initLayers) {

					addLayer(initLayers[key],true);
				}
			}
			
			t.add_missing_unique_ids();
			
		}

		
		//disable the properties box
		disableFormFields();

		
		//init elements
		initMainEvents();	// CLICK ON CONTAINER
		initButtons();		// MAIN BUTTONS - ADD LAYERS, DUPLICATE, DELETE
		initHtmlFields();	// HTML FIELD HANDLINGS
		initAlignTable();
		initLoopFunctions();
		scaleImage();
		positionChanged();
		initBackgroundFunctions();
		
		

		// HIDE/SHOW MAIN IMAGE SELECTION
		jQuery('.bgsrcchanger').each(function(){
			if(jQuery(this).is(':checked'))
				initSliderMainOptions(jQuery(this));
		});
		// INIT CLICK HANDLING ON MAIN IMAGE SETTINGS
		jQuery('.bgsrcchanger').click(function() {
			initSliderMainOptions(jQuery(this));
		});
		
		jQuery('#alt_option').change(function(){
			if(jQuery('#alt_option option:selected').val() == 'custom'){
				jQuery('#alt_attr').show();
			}else{
				jQuery('#alt_attr').hide();
			}
		});

		initDisallowCaptionsOnClick();



		// OPEN LEFT PANEL TO FULL
		jQuery('.open_right_panel').click(function() {
			var orp = jQuery('.layer_props_wrapper')
			if (orp.hasClass("openeed")) {
				orp.removeClass("openeed");
			} else {
				orp.addClass("openeed");
			}
		});

		jQuery('#rs-grid-sizes, #rs-grid-snapto').change(function() {
			t.refreshGridSize();
		});
		
		jQuery('#layer_alt_option').change(function(){
			if(jQuery('#layer_alt_option option:selected').val() == 'custom'){
				jQuery('#layer_alt').show();
			}else{
				jQuery('#layer_alt').hide();
			}
		});

		jQuery('select[name="rev_show_the_slides"]').change(function(){
			var nextsh = jQuery('#divbgholder').find('.slotholder');
			
			jQuery('.demo_layer').hide();
			nextsh.addClass("trans_bg");
			nextsh.css("background-image","none");
			nextsh.css("background-color","transparent");
			nextsh.css("background-position","top center");
			nextsh.css("background-size","cover"); //100% 100%
			nextsh.css("background-repeat","no-repeat");
			
			jQuery('.tp-bgimg').css({backgroundImage:"none",backgroundColor:"transparent"});
			//jQuery('#divLayers-wrapper').css({backgroundImage:"none",backgroundColor:"transparent"});
			
			
			if(jQuery(this).val() !== 'none'){
				var sv = jQuery(this).val();
				jQuery('.demo_layer_'+sv).show();

				if(typeof initDemoSettings[sv] !== 'undefined'){
					var bgfit = (initDemoSettings[sv]['bg_fit'] == 'percentage') ? initDemoSettings[sv]['bg_fit_x'] + '% ' + initDemoSettings[sv]['bg_fit_y'] + '% ' : initDemoSettings[sv]['bg_fit'];
					var bgpos = (initDemoSettings[sv]['bg_position'] == 'percentage') ? initDemoSettings[sv]['bg_position_x'] + '% ' + initDemoSettings[sv]['bg_position_y'] + '% ' : initDemoSettings[sv]['bg_position'];

					if(initDemoSettings[sv]['bg_fit'] == 'contain'){
						jQuery('#divLayers-wrapper').css('maxWidth', jQuery('#divbgholder').css('minWidth'));
					}else{
						jQuery('#divLayers-wrapper').css('maxWidth', 'none');
					}

					switch(initDemoSettings[sv]['background_type']){
						case "image":
						case "meta":
						case "streamyoutube":
						case "streamvimeo":
						case "streaminstagram":
						case "youtube":
						case "vimeo":
						case "html5":
							var urlImage = initDemoSettings[sv]['image'];							
							jQuery('#the_image_source_url').html(urlImage);
							nextsh.find('.defaultimg, .slotslidebg').css("background-image","url('"+urlImage+"')");
							nextsh.find('.defaultimg, .slotslidebg').css("background-color","transparent");
							nextsh.find('.defaultimg, .slotslidebg').css("background-size",bgfit);
							nextsh.find('.defaultimg, .slotslidebg').css("background-position",bgpos);
							nextsh.find('.defaultimg, .slotslidebg').css("background-repeat",initDemoSettings[sv]['bg_repeat']);
							nextsh.find('.defaultimg, .slotslidebg').removeClass("trans_bg");
						break;
						case "trans":
							nextsh.find('.defaultimg, .slotslidebg').css("background-image","none");
							nextsh.find('.defaultimg, .slotslidebg').css("background-color","transparent");
							nextsh.find('.defaultimg, .slotslidebg').addClass("trans_bg");
						break;
						case "solid":
							nextsh.find('.defaultimg, .slotslidebg').css("background-image","none");
							nextsh.find('.defaultimg, .slotslidebg').removeClass("trans_bg");
							var bgColor = initDemoSettings[sv]['slide_bg_color'];
							nextsh.find('.defaultimg, .slotslidebg').css("background-color",bgColor);
						break;
						case "external":
							var urlImage = initDemoSettings[sv]['slide_bg_external'];
							jQuery('#the_image_source_url').html(urlImage);
							nextsh.find('.defaultimg, .slotslidebg').css("background-image","url('"+urlImage+"')");
							nextsh.find('.defaultimg, .slotslidebg').css("background-color","transparent");
							nextsh.find('.defaultimg, .slotslidebg').css("background-size",bgfit);
							nextsh.find('.defaultimg, .slotslidebg').css("background-position",bgpos);
							nextsh.find('.defaultimg, .slotslidebg').css("background-repeat",initDemoSettings[sv]['bg_repeat']);
							nextsh.find('.defaultimg, .slotslidebg').removeClass("trans_bg");
						break;
					}
				}
			}
			jQuery('#divbgholder').css({backgroundImage:"none",backgroundColor:"transparent"});
			//t.changeSlotBGs();
		});
		
		
		
		jQuery('.rs-slide-device_selector').click(function(){
			if(rev_adv_resp_sizes == false) return;

			// SAVE THE TOOLBAR :)
			jQuery('#layer-short-toolbar').appendTo(jQuery('#layer-settings-toolbar-bottom'));
			jQuery('#layer_text_wrapper').appendTo(jQuery('#layer_text_holder'));
			
			var changeto = jQuery(this).data('val');
			layout = changeto;
			
			jQuery('.tp-bgimg.defaultimg, #divLayers').css({
				width:rev_sizes[changeto][0], 
				height:rev_sizes[changeto][1]
			})

			jQuery('#divbgholder').css({minWidth:rev_sizes[changeto][0], maxWidth:rev_sizes[changeto][0]});
			jQuery('#divbgholder').css({minHeight:rev_sizes[changeto][1],height:rev_sizes[changeto][1]});
			jQuery('.slotholder .tp-bgimg.defaultimg').css({minWidth:rev_sizes[changeto][0], maxWidth:rev_sizes[changeto][0] })

			
			
			jQuery('#divLayers-wrapper').css('height', rev_sizes[changeto][1] + 1);

			jQuery('.rs-slide-device_selector').removeClass('selected');
			
			jQuery(this).addClass('selected');

			/*for(var serial in arrLayers){
				updateHtmlLayersFromObject(serial,true);
			}

			for(var serial in arrLayersDemo){
				updateHtmlLayersFromObject(serial,true, true);
			}

			if(selectedLayerSerial !== -1)
				updateLayerFormFields(selectedLayerSerial);*/

			//update positions of all other slides elements if static slide is selected
			u.resetSlideAnimations();
			jQuery(window).trigger("resize");			
			redrawAllLayerHtml();
			jQuery('#layer-short-toolbar').appendTo('.slide_layer.layer_selected');
			// update Background Size !!
			
		});

		
		//copy idle to hover
		jQuery('.copy-from-idle').click(function(){
			
			if(confirm(rev_lang.copy_styles_to_idle_from_hover)){
				var layer = t.getCurrentLayer();
				
				layer['deformation-hover']['2d_origin_x'] = layer['deformation']['2d_origin_x'];
				layer['deformation-hover']['2d_origin_y'] = layer['deformation']['2d_origin_y'];
				layer['deformation-hover']['2d_rotation'] = layer['2d_rotation'];
				layer['deformation-hover']['color'] = t.getVal(layer['static_styles'], 'color');
				layer['deformation-hover']['background-color'] = layer['deformation']['background-color'];
				layer['deformation-hover']['background-transparency'] = layer['deformation']['background-transparency'];
				layer['deformation-hover']['border-color'] = layer['deformation']['border-color'];
				layer['deformation-hover']['border-radius'] = layer['deformation']['border-radius'];
				layer['deformation-hover']['border-style'] = layer['deformation']['border-style'];
				layer['deformation-hover']['border-transparency'] = layer['deformation']['border-transparency'];
				layer['deformation-hover']['border-width'] = layer['deformation']['border-width'];
				layer['deformation-hover']['color-transparency'] = layer['deformation']['color-transparency'];
				layer['deformation-hover']['opacity'] = layer['deformation']['opacity'];
				layer['deformation-hover']['scalex'] = layer['deformation']['scalex'];
				layer['deformation-hover']['scaley'] = layer['deformation']['scaley'];
				layer['deformation-hover']['skewx'] = layer['deformation']['skewx'];
				layer['deformation-hover']['skewy'] = layer['deformation']['skewy'];
				layer['deformation-hover']['text-decoration'] = layer['deformation']['text-decoration'];
				layer['deformation-hover']['x'] = layer['deformation']['x'];
				layer['deformation-hover']['xrotate'] = layer['deformation']['xrotate'];
				layer['deformation-hover']['y'] = layer['deformation']['y'];
				layer['deformation-hover']['yrotate'] = layer['deformation']['yrotate'];
				layer['deformation-hover']['z'] = layer['deformation']['z'];
				
				updateLayerFormFields(selectedLayerSerial);
				t.updateLayerFromFields();
			}
		});
		
		
		//copy hover to idle
		jQuery('.copy-from-hover').click(function(){
			if(confirm(rev_lang.copy_styles_to_hover_from_idle)){
				var layer = t.getCurrentLayer();
				
				layer['deformation']['2d_origin_x'] = layer['deformation-hover']['2d_origin_x'];
				layer['deformation']['2d_origin_y'] = layer['deformation-hover']['2d_origin_y'];
				layer['2d_rotation'] = layer['deformation-hover']['2d_rotation'];
				layer['static_styles'] = t.setVal(layer['static_styles'], 'color', layer['deformation-hover']['color'], false);
				layer['deformation']['background-color'] = layer['deformation-hover']['background-color'];
				layer['deformation']['background-transparency'] = layer['deformation-hover']['background-transparency'];
				layer['deformation']['border-color'] = layer['deformation-hover']['border-color'];
				layer['deformation']['border-radius'] = layer['deformation-hover']['border-radius'];
				layer['deformation']['border-style'] = layer['deformation-hover']['border-style'];
				layer['deformation']['border-transparency'] = layer['deformation-hover']['border-transparency'];
				layer['deformation']['border-width'] = layer['deformation-hover']['border-width'];
				layer['deformation']['color-transparency'] = layer['deformation-hover']['color-transparency'];
				layer['deformation']['opacity'] = layer['deformation-hover']['opacity'];
				layer['deformation']['scalex'] = layer['deformation-hover']['scalex'];
				layer['deformation']['scaley'] = layer['deformation-hover']['scaley'];
				layer['deformation']['skewx'] = layer['deformation-hover']['skewx'];
				layer['deformation']['skewy'] = layer['deformation-hover']['skewy'];
				layer['deformation']['text-decoration'] = layer['deformation-hover']['text-decoration'];
				layer['deformation']['x'] = layer['deformation-hover']['x'];
				layer['deformation']['xrotate'] = layer['deformation-hover']['xrotate'];
				layer['deformation']['y'] = layer['deformation-hover']['y'];
				layer['deformation']['yrotate'] = layer['deformation-hover']['yrotate'];
				layer['deformation']['z'] = layer['deformation-hover']['z'];
				
				updateLayerFormFields(selectedLayerSerial);
				t.updateLayerFromFields();
			}
		});
		
		
		jQuery('#button_css_reset').click(function(){
			var layer = t.getCurrentLayer();
			if(layer !== null){
				t.reset_to_default_static_styles(layer);

				// Reset Fields from Style Template
				updateSubStyleParameters(layer, true);
				t.updateLayerFromFields();
			}
		});

		var getGridDimension = function() {
			var d = jQuery('#divLayers'),
				dl = d.offset(),
				dlw = jQuery('#divLayers-wrapper').offset(),
				rp = {top:dl.top-dlw.top, left:dl.left-dlw.left, bottom:dl.top-dlw.top+d.height(),right:dl.left-dlw.left+d.width()};
			return rp;
		}

		
		function edit_content_current_layer(){
			

			var layer = t.getCurrentLayer();
			if(layer !== null){

				switch(layer.type){
					case 'text':
					case 'button':
						jQuery('#layer_text_wrapper').appendTo(jQuery('.layer_selected.slide_layer'));
						t.showHideContentEditor(true);
						jQuery('#layer_text').focus();	
						
					break;
					case 'video':
						var objVideoData = layer.video_data;
						
						jQuery('#layer-short-toolbar').appendTo(jQuery('#layer-settings-toolbar-bottom'));
						jQuery('#layer_text_wrapper').appendTo(jQuery('#layer_text_holder'));
						//open video dialog
						UniteAdminRev.openVideoDialog(function(videoData){
							//update video layer
							var objLayer = getVideoObjLayer(videoData);
							
							updateCurrentLayer(objLayer);
							updateHtmlLayersFromObject(selectedLayerSerial);
							updateLayerFormFields(selectedLayerSerial);
							redrawLayerHtml(selectedLayerSerial);
							jQuery('#layer-short-toolbar').appendTo('.slide_layer.layer_selected');
							scaleNormalVideo();
						}, objVideoData);
						jQuery('#layer-short-toolbar').appendTo('.slide_layer.layer_selected');
						
					break;
					case 'shape':
					break;
					case 'typeA':
					break;
					case 'typeB':
					break;
					//case 'no_edit':
						//do nothing!!
					//break;
				}
			}
		}
		

		jQuery('body').on('dblclick','.layer_selected',edit_content_current_layer);
		jQuery('#button_edit_layer, #button_change_video_settings').click(edit_content_current_layer);
		
		t.showHideContentEditor(false);

		
		t.changeSlotBGs();
		jQuery("#hide_layer_content_editor").click(function() {
			t.showHideContentEditor(false);
		});

		jQuery('#layer_animation, #layer_endanimation').change(function(){
			//set values from elements if existing
			var set_anim = (jQuery(this).attr('id') == 'layer_animation') ? 'in' : 'out';
			var anim_handle = jQuery(this).val();
			
			var found = false;
			for(var key in initLayerAnims){				
				if('custom'+set_anim+'-'+initLayerAnims[key]['id'] == anim_handle){
					switch(set_anim){
						case 'in':
							setNewAnimFromObj('start', initLayerAnims[key]['params']);
						break;
						case 'out':
							setNewAnimFromObj('end', initLayerAnims[key]['params']);
						break;
					}
					found = true;
					break;
				}
			}
			
			if(found == false){
				for(var key in initLayerAnimsDefault){				
					if(key == anim_handle){
						switch(set_anim){
							case 'in':
								setNewAnimFromObj('start', initLayerAnimsDefault[key]['params']);
							break;
							case 'out':
								setNewAnimFromObj('end', initLayerAnimsDefault[key]['params']);
							break;
						}
						break;
					}
				}
			}
			
			if(set_anim == 'out' && anim_handle == 'auto'){ //check if masking is enabled for in-animation, if yes do it here, too
				if(jQuery('input[name="masking-start"]').is(':checked')){
					if(jQuery('input[name="masking-start"]').is(':checked') == true){
						jQuery('input[name="masking-end"]').attr('checked', true);
						RevSliderSettings.onoffStatus(jQuery('input[name="masking-end"]'));	
						jQuery('.mask-end-settings').show();
					}
				}
			}
			
			
			
			t.updateLayerFromFields();
		});

		
		jQuery('#layer_caption').change(function(){
			//check if style is existing. If not, change fields from rename to save
			if(UniteCssEditorRev.checkIfHandleExists(jQuery(this).val())){
				jQuery('#extra_style_settings').removeClass('normal_rename normal_save save_rename save_save').addClass('normal_rename');
			}else{
				jQuery('#extra_style_settings').removeClass('normal_rename normal_save save_rename save_save').addClass('normal_save');
			}
		});

		
		jQuery('.save-current-animin, .save-current-animout').click(function() {
			var what = (jQuery(this).hasClass('save-current-animin')) ? 'start' : 'end';
			var handle = (what == 'start') ? jQuery('select[name="layer_animation"] option:selected').val() : jQuery('select[name="layer_endanimation"] option:selected').val();
			
			if(handle !== 'custom'){
				for(var key in initLayerAnimsDefault){
					if(key === handle){
						alert(rev_lang.cant_modify_default_anims);
						return false;
					}
				}
			}
			var anim = {};
			
			anim['params'] = createNewAnimObj(what);
			
			if(handle == 'custom'){
				//add dialog box asking for name. Then check if name exists or is default
				jQuery('#rs-save-under-animation').val('');
				
				var objLayer = t.getCurrentLayer();
				
				if(what == 'start' && typeof(objLayer['orig-anim']) !== 'undefined') jQuery('#rs-save-under-animation').val(objLayer['orig-anim']);
				if(what == 'end' && typeof(objLayer['orig-endanim']) !== 'undefined') jQuery('#rs-save-under-animation').val(objLayer['orig-endanim']);
				
				jQuery('#dialog_save_animation').dialog({
					modal: true,
					width: 300,
					height: 200,
					resizable: false,
					buttons:{
						'Save':function(){
							var new_name = jQuery('#rs-save-under-animation').val();
							var new_handle = UniteAdminRev.sanitize_input(new_name);
							
							var do_insert = true;
							for(var key in initLayerAnimsDefault){
								//if(key == new_handle){
								if(initLayerAnimsDefault[key]['handle'] == new_handle){
									alert(rev_lang.name_is_default_animations_cant_be_changed);
									return false;
								}
							}
							
							for(var key in initLayerAnims){
								//if(key == new_handle){
								if(initLayerAnims[key]['handle'] == new_handle){
									do_insert = (what == 'start') ? 'customin' : 'customout';
									do_insert += '-'+initLayerAnims[key]['id'];
									break;
								}
							}
							
							anim['handle'] = new_handle;
							
							anim['params']['type'] = (what == 'start') ? 'customin' : 'customout';
							
							if(do_insert === true){
								updateAnimInDb(new_handle, anim, false);
								jQuery(this).dialog('close');
							}else{
								if(confirm(rev_lang.override_animation)){
									updateAnimInDb(do_insert, anim, true);
									jQuery(this).dialog('close');
								}
							}
						},
						'Cancel':function(){
							jQuery(this).dialog('close');
						}
					}
				});
			}else{
				anim['params']['type'] = (what == 'start') ? 'customin' : 'customout';
				
				if(confirm(rev_lang.overwrite_animation)){
					updateAnimInDb(handle, anim, true);
				}
			}
			
			
		});
		
		jQuery('.save-as-current-animin, .save-as-current-animout').click(function() {
			var what = (jQuery(this).hasClass('save-as-current-animin')) ? 'start' : 'end';
			
			var curAnimHandle = (jQuery(this).hasClass('save-as-current-animin')) ? jQuery('#layer_animation option:selected').val() : jQuery('#layer_endanimation option:selected').val();
			
			currentAnimationType = (jQuery(this).hasClass('save-as-current-animin')) ? 'customin' : 'customout';
			
			/*if(curAnimHandle !== 'custom'){
				for(var key in initLayerAnimsDefault){
					if(key === curAnimHandle){
						alert(rev_lang.cant_modify_default_anims);
						return false;
					}
				}
			}*/
			
			jQuery('#dialog_save_as_animation').dialog({
				modal: true,
				width: 300,
				height: 200,
				resizable: false,
				buttons:{
					'Save':function(){
						var new_name = jQuery('#rs-save-as-animation').val();
						var new_handle = UniteAdminRev.sanitize_input(new_name);
						
						var is_new_handle_existing = false;
						var anim = {};
						
						for(var key in initLayerAnimsDefault){
							if(key == new_handle){
								is_new_handle_existing = true;
								break;
							}
						}
						
						for(var key in initLayerAnims){
							if(initLayerAnims[key]['handle'] == new_handle){
								is_new_handle_existing = true;
								break;
							}
						}
						
						if(is_new_handle_existing){
							alert(rev_lang.anim_with_handle_exists);
							return false;
						}
						
						anim['params'] = createNewAnimObj(what);
						anim['handle'] = new_handle;
						anim['params']['type'] = currentAnimationType;
						
						updateAnimInDb(new_handle, anim, false);
						jQuery(this).dialog('close');
					},
					'Cancel':function(){
						jQuery(this).dialog('close');
					}
				}
			});

		
			
		});
		
		//jQuery('.rename-current-anim').click(function() {
		jQuery('.rename-current-animin, .rename-current-animout').click(function() {
			var curAnimHandle = (jQuery(this).hasClass('rename-current-animin')) ? jQuery('#layer_animation option:selected').val() : jQuery('#layer_endanimation option:selected').val();
			var curAnimText = (jQuery(this).hasClass('rename-current-animin')) ? jQuery('#layer_animation option:selected').text() : jQuery('#layer_endanimation option:selected').text();
			
			if(curAnimHandle !== 'custom'){
				for(var key in initLayerAnimsDefault){
					if(key === curAnimHandle){
						alert(rev_lang.cant_modify_default_anims);
						return false;
					}
				}
			}
			if(curAnimHandle == 'custom') return false;
			
			var curAnimID = curAnimHandle.replace('customin-', '').replace('customout-', '');
			
			jQuery('#rs-rename-animation').val(curAnimText);
			
			jQuery('#dialog_rename_animation').dialog({
				modal: true,
				width: 300,
				height: 200,
				resizable: false,
				buttons:{
					'Rename':function(){
						var new_name = jQuery('#rs-rename-animation').val();
						var new_handle = UniteAdminRev.sanitize_input(new_name);
						
						var is_found = false;
						var is_new_handle_existing = false;
						
						for(var key in initLayerAnimsDefault){
							if(key == new_handle){
								is_new_handle_existing = true;
								break;
							}
						}
						
						var id = '';
						
						for(var key in initLayerAnims){
							if(key == new_handle) is_new_handle_existing = true;
							if(initLayerAnims[key]['id'] == curAnimID){
								is_found = true;
								id = initLayerAnims[key]['id'];
								break;
							}
						}
						
						if(is_new_handle_existing){
							alert(rev_lang.anim_with_handle_exists);
							return false;
						}
						
						if(is_found){
							renameAnimInDb(id, new_handle);
							
							jQuery(this).dialog('close');
							return false;
						}
					},
					'Cancel':function(){
						jQuery(this).dialog('close');
					}
				}
			});
		});
		
		//jQuery('#delete-current-start-anim, #delete-current-end-anim').click(function(){
		jQuery('#delete-current-animin, #delete-current-animout').click(function(){
			var curAnimHandle = (jQuery(this).attr('id') == 'delete-current-animin') ? jQuery('#layer_animation').val() : jQuery('#layer_endanimation').val();
			var curAnimText = (jQuery(this).attr('id') == 'delete-current-animin') ? jQuery('#layer_animation option:selected').text() : jQuery('#layer_endanimation option:selected').text();

			var isOriginal = (curAnimHandle.indexOf('custom') > -1) ? false : true;

			if(isOriginal || curAnimHandle == 'custom'){
				alert(rev_lang.default_animations_cant_delete);
			}else{
				if(confirm(rev_lang.really_delete_anim+' "'+curAnimText+'"?')){
					deleteAnimInDb(curAnimHandle);
				}
			}

		});

		//SHOW / HIDE EXTRA ANIMATION SETTINGS
		jQuery('#add_customanimation_in').click(function() {
			currentAnimationType = 'customin';
			var btn = jQuery(this);

			var sc = jQuery('#extra_start_animation_settings'),
				ec = jQuery('#extra_end_animation_settings');
			if (sc.css("display")=="block") {
				sc.hide(200);
				btn.removeClass("selected");
			} else {
				sc.show(200);
				btn.addClass("selected");				
			}
			ec.hide(200);
		});


		jQuery('#add_customanimation_out').click(function() {
			currentAnimationType = 'customout';
			var btn = jQuery(this);
			var sc = jQuery('#extra_start_animation_settings'),
				ec = jQuery('#extra_end_animation_settings');
			if (ec.css("display")=="block") {
				ec.hide(200);
				btn.removeClass("selected");				
			} else {
				ec.show(200);
				btn.addClass("selected");				
			}
			sc.hide(200);

		});
		
		
		jQuery('#reset-current-animin, #reset-current-animout').click(function(){
			var what = (jQuery(this).hasClass('reset-current-animin')) ? 'start' : 'end';

			var objLayer = t.getCurrentLayer();

			if(what == 'start' && typeof(objLayer['orig-anim-handle']) !== 'undefined'){
				jQuery('#layer_animation option[value="'+objLayer['orig-anim-handle']+'"]').attr('selected', true).change();
			}
			if(what == 'end' && typeof(objLayer['orig-endanim-handle']) !== 'undefined'){
				jQuery('#layer_endanimation option[value="'+objLayer['orig-endanim-handle']+'"]').attr('selected', true).change();
			}
		});
		
		
		jQuery('#button_edit_css, #style-morestyle, .close_extra_settings').click(function() {
			
			var es = jQuery('#extra_style_settings'),
				btn = jQuery('#button_edit_css'),
				mainbt = jQuery("#style-morestyle");
				
			if (es.css("display")=="block") {
				es.hide(200);
				btn.removeClass("selected");
				mainbt.removeClass("showmore");
			} else {
				es.show(200);
				btn.addClass("selected");
				mainbt.addClass("showmore");				
			}
		});

		


		// SWITCH BACK AND FORWARDS BETWEEN SETTINGS AND SAVE DPENEDING IF RENAME OF SAVE BUTTON IS VISIBLE
		jQuery('.rename-current-css').click(function() {
			
			jQuery('#rs-rename-css').val(jQuery('#layer_caption').val());
			
			jQuery('#dialog_rename_css').dialog({
				modal:true,
				resizable:false,
				width:400,
				closeOnEscape:true,
				buttons:{
					'Rename':function(){
						jQuery('#rs-rename-css').val(UniteAdminRev.sanitize_input(jQuery('#rs-rename-css').val()));
						var new_css_name = jQuery('#rs-rename-css').val();
						if(new_css_name != ''){
							var curStyleName = jQuery('#layer_caption').val();
							
							var	is_existing = UniteCssEditorRev.checkIfHandleExists(new_css_name);
							var	is_existing_old = UniteCssEditorRev.checkIfHandleExists(curStyleName);
							
							if(is_existing_old === false){ alert(rev_lang.css_orig_name_does_not_exists); return false; }
							
							if(is_existing !== false || curStyleName == new_css_name){ alert(rev_lang.css_name_already_exists); return false; } //cant rename to another existing name
							
							UniteCssEditorRev.renameStylesInDb(curStyleName, new_css_name);
						}
					}
				}
			});
			
		});
		
		
		/**
		 * Save As Current CSS
		 **/
		jQuery('.save-as-current-css').click(function() {
			//modal with selection of new style name
			jQuery('#rs-save-as-css').val(jQuery('#layer_caption').val());
			
			jQuery('#dialog_save_as_css').dialog({
				modal:true,
				resizable:false,
				width:400,
				closeOnEscape:true,
				buttons:{
					'Save':function(){
						jQuery('#rs-save-as-css').val(UniteAdminRev.sanitize_input(jQuery('#rs-save-as-css').val()));
						var new_css_name = jQuery('#rs-save-as-css').val();
						if(new_css_name != ''){
							
							var	is_existing = UniteCssEditorRev.checkIfHandleExists(new_css_name);
							if(is_existing !== false){ alert(rev_lang.css_name_already_exists); return false; } //cant rename to another existing name
							
							UniteCssEditorRev.saveStylesInDb(new_css_name, true, jQuery('#dialog_save_as_css'));
						}
					}
				}
			});
			
		});
		
		
		/**
		 * Delete Current CSS
		 **/
		jQuery('.delete-current-css').click(function() {
			if(confirm(rev_lang.delete_this_caption) === false) return false;
			
			var curStyleName = jQuery('#layer_caption').val();
			
			var	is_existing = UniteCssEditorRev.checkIfHandleExists(curStyleName);
			if(is_existing === false){ alert(rev_lang.css_name_does_not_exists); return false; } //cant rename to another existing name
			
			//rename the css name or create new one depending on what is set
			UniteCssEditorRev.deleteStylesInDb(curStyleName, is_existing);
			
		});
		
		
		/**
		 * Save Current CSS
		 **/
		jQuery('.save-current-css').click(function() {
			if(confirm(rev_lang.this_will_change_the_class)){
				var save_handle = jQuery('#layer_caption').val();
				var id = UniteCssEditorRev.checkIfHandleExists(save_handle);
				
				if(id !== false){ //update existing style
					UniteCssEditorRev.saveStylesInDb(save_handle, false);
				}else{ //save as new
					UniteCssEditorRev.saveStylesInDb(save_handle, true);
				}
			}
		});


		jQuery('.reset-current-css').click(function() {
			
			jQuery('input[name="rs-css-set-on[]"]').each(function(){
				jQuery(this).attr('checked', true);
			});
			jQuery('input[name="rs-css-include[]"]').each(function(){
				jQuery(this).attr('checked', true);
			});
			
			var layer = t.getCurrentLayer();
			if(layer.style !== undefined){
				//open modal and ask for changes
				jQuery('#dialog-change-style-from-css').dialog({
					buttons:{'OK':function(){
						//check what should be updated on which device types
						var is_allowed = false;
						var set_to = {'device':[],'include':[]};
						
						jQuery('input[name="rs-css-set-on[]"]').each(function(){
							if(jQuery(this).is(':checked')){
								is_allowed = true;
								set_to['device'].push(jQuery(this).val());
							}
						});
						
						jQuery('input[name="rs-css-include[]"]').each(function(){
							if(jQuery(this).is(':checked')){
								set_to['include'].push(jQuery(this).val());
							}
						});
						
						if(!is_allowed){
							alert(rev_lang.select_at_least_one_device_type);
							return true;
						}
						
						layer.style = jQuery('#layer_caption').val();
						
						
						//update values that can be set without changing the class
						t.reset_to_default_static_styles(layer, set_to['include'], set_to['device']);
						// Reset Fields from Style Template
						
						updateSubStyleParameters(layer, true);
						
						jQuery('#layer_caption').change();
						t.updateLayerFromFields();

						jQuery('#dialog-change-style-from-css').dialog('close');
					},'Close':function(){
						jQuery('#dialog-change-style-from-css').dialog('close');
					}},
					minWidth: 275,
					minHeight: 365,
					modal: true,
					dialogClass: 'tpdialogs',
					close: function( event, ui ){
					},
					open:function() {
						jQuery('.rs-style-device_input').each(function() {
							var inp = jQuery(this);
							if (inp.attr('checked')==="checked")
								inp.siblings('.rs-style-device_selector_prev').addClass("selected");
							else 
								inp.siblings('.rs-style-device_selector_prev').removeClass("selected");
						})
					}
				});
				
			}
		});

		jQuery('body').on('click change','.rs-style-device_input',function() {
			var inp = jQuery(this);
			if (inp.attr('checked')==="checked")
				inp.siblings('.rs-style-device_selector_prev').addClass("selected");
			else 
				inp.siblings('.rs-style-device_selector_prev').removeClass("selected");
		});




		// TAB CHANGES IN SLIDER SETTINGS AND IN ANIMATION EXTRAS
		jQuery('.rs-layer-animation-settings-tabs li, .rs-layer-settings-tabs li').click(function() {

			var tn = jQuery(this),
				tw = tn.closest('ul').find('.selected');
			jQuery(tw.data('content')).hide(0);
			tw.removeClass("selected");
			tn.addClass("selected");
			jQuery(tn.data('content')).show(0);
			
			if(tn.data('content') == '#rs-hover-content-wrapper'){
				//4 u kriki
			}
			
		});

		// MOUSE MOVE SHOULD MOVE THE SMALL BLUE LINES ON RULERS
		jQuery('body').on('mousemove','#thelayer-editor-wrapper',function(event) {

			var mx = event.pageX - jQuery(this).offset().left,
				my = event.pageY - jQuery(this).offset().top,
				dl = jQuery('#divLayers'),
				l = parseInt(dl.offset().left,0) - parseInt(jQuery('#thelayer-editor-wrapper').offset().left,0);

			jQuery('#verlinie').css({left:mx+"px"});
			jQuery('#horlinie').css({top:my+"px"});

			jQuery('#verlinetext').html(Math.round(mx-l));
			jQuery('#horlinetext').html(Math.round(my-40));

			jQuery('#hor-css-linear .helplines-offsetcontainer').data('x',event.pageX-jQuery('#hor-css-linear .helplines-offsetcontainer').offset().left);
			jQuery('#hor-css-linear .helplines-offsetcontainer').data('y',my);
		});

		// ON CLICK DRAW SOME HELP GRID LINES
		jQuery('#hor-css-linear, #ver-css-linear, #verlinie, #horlinie').click(function() {
			var hcl = jQuery('#hor-css-linear .helplines-offsetcontainer'),
				vcl = jQuery('#ver-css-linear .helplines-offsetcontainer'),
				x = hcl.data('x'),
				y = hcl.data('y')-40,
				ph = jQuery('#thelayer-editor-wrapper').outerHeight(true),
				pw = jQuery('#thelayer-editor-wrapper').outerWidth(true);

			jQuery('#helpergrid').remove();
			jQuery('#rs-grid-sizes').val('custom');
			punchgs.TweenLite.to(hcl,0.3,{autoAlpha:1});
			punchgs.TweenLite.to(vcl,0.3,{autoAlpha:1});

			if (y<40 && x>0)
				hcl.append('<div class="helplines" data-left="'+x+'" data-top="'+y+'" style="position:absolute;width:1px;height:'+(ph-41)+'px;background:#4AFFFF;left:'+x+'px;top:-15px"><i class="helpline-drag eg-icon-move"></i><i class="helpline-remove eg-icon-cancel"></i></div>');
			if (x<40 && y>0)
				vcl.append('<div class="helplines" data-left="'+x+'" data-top="'+y+'"  style="position:absolute;width:'+(pw-35)+'px;height:1px;background:#4AFFFF;top:'+y+'px;left:-15px"><i class="helpline-drag eg-icon-move"></i><i class="helpline-remove eg-icon-cancel"></i></div>');

			try{hcl.find('.helplines').draggable("destroy");} catch(e) {}
			try{vcl.find('.helplines').draggable("destroy");} catch(e) {}
			hcl.find('.helplines').draggable({ handle:".helpline-drag", axis:"x" });
			vcl.find('.helplines').draggable({ handle:".helpline-drag", axis:"y" });
		});

		// REMOVE HELPLINE MOVERS
		jQuery('body').on("click",".helpline-remove",function() {
			jQuery(this).parent().remove();
		});

		jQuery('.extra_sub_settings_wrapper').addClass("normal_rename");

		jQuery('#extra_start_animation_settings input, #extra_end_animation_settings input').change(function(){
			if(jQuery(this).attr('id') === 'new_start_animation_name' || jQuery(this).attr('id') === 'new_end_animation_name') return false;

			var my_sel = (currentAnimationType == 'customin') ? jQuery('#layer_animation') : jQuery('#layer_endanimation'); //customout
			if(currentAnimationType == 'customin')
				jQuery(this).closest('.extra_start_animation_settings').removeClass('normal_rename normal_save save_rename save_save').addClass('normal_save');
			else
				jQuery(this).closest('.extra_end_animation_settings').removeClass('normal_rename normal_save save_rename save_save').addClass('normal_save');
			
			var original = my_sel.find('option:selected').text();
			var original_handle = my_sel.find('option:selected').val();
			if(my_sel.find('option:selected').val() !== 'custom'){
				my_sel.find('option[value="custom"]').attr('selected', true).change();
				//set animation fallback to the original
				if(currentAnimationType == 'customin')
					updateCurrentLayer({'animation':'custom','orig-anim':original,'orig-anim-handle':original_handle});
				else
					updateCurrentLayer({'endanimation':'custom','orig-endanim':original,'orig-endanim-handle':original_handle});
					
			}
		});


		jQuery('#extra_style_settings input, #extra_style_settings select').change(function(){
			if(jQuery(this).attr('id') === 'overwrite_style_name' || jQuery(this).attr('id') === 'new_style_name') return false;
			jQuery('#extra_style_settings').removeClass('normal_rename normal_save save_rename save_save').addClass('normal_save');
		});
		
		// CHANGES ON BACKGROUND ELEMENTS SHOULD REDRAW THE SLIDE TIMELINE 
		jQuery('input[name="background_type"], #slide_bg_fit, input[name="bg_fit_x"], input[name="bg_fit_y"], #slide_bg_position,input[name="bg_position_x"],input[name="bg_position_y"],#slide_bg_repeat ').change(function() {
			t.changeSlotBGs();
		})

				
		jQuery('body').on('blur','.timer-layer-text',function() {
			t.updateLayerFromFields(); 			
		});
	}
	
	
	t.showHideContentEditor = function(show) {
		if (show) {
			jQuery('#button_edit_layer').hide();
			jQuery('#button_delete_layer').hide();
			jQuery('#button_duplicate_layer').hide();
			jQuery('#tp-addiconbutton').show();
			jQuery('#hide_layer_content_editor').show();
			jQuery('#linkInsertTemplate').show();
			jQuery('#layer_text_wrapper').show();
			jQuery('#button_reset_size').hide();
			jQuery('#button_change_video_settings').hide();
			jQuery('#layer_text_wrapper').addClass('currently_editing_txt');
		} else {
			jQuery('#button_edit_layer').show();
			jQuery('#button_delete_layer').show();
			jQuery('#button_duplicate_layer').show();
			jQuery('#tp-addiconbutton').hide();
			jQuery('#hide_layer_content_editor').hide();
			jQuery('#linkInsertTemplate').hide();
			jQuery('#layer_text_wrapper').hide();
			jQuery('#button_reset_size').show();
			jQuery('#button_change_video_settings').show();
			jQuery('#layer_text_wrapper').removeClass('currently_editing_txt');
			
		}
		
		var objLayer = selectedLayerSerial === -1 ? "" : t.getLayer(selectedLayerSerial);	
		t.toolbarInPos(objLayer);
	}
	
	t.changeSlotBGs = function() {
		
		// MOVE ALL DATA TO THE RIGHT CONTAINER TO ANIMATE IT 
		var nextsh = jQuery('#divbgholder').find('.slotholder'),
			
			bgimg = jQuery("#image_url").val(),
			bgpos = (jQuery('#slide_bg_position').val() == 'percentage') ? jQuery("input[name='bg_position_x']").val() + '% ' + jQuery("input[name='bg_position_y']").val() + '% ' : jQuery('#slide_bg_position').val(),
			bgfit = (jQuery('#slide_bg_fit').val() == 'percentage') ? jQuery("input[name='bg_fit_x']").val() + '% ' + jQuery("input[name='bg_fit_y']").val() + '% ' : jQuery('#slide_bg_fit').val(),
			bgcolor = jQuery('#slide_bg_color').val();
			gallery_type = jQuery('input[name="rs-gallery-type"]').val();

			jQuery('#the_image_source_url').html(bgimg);
		
		jQuery('#video-settings').hide();
		jQuery('#bg-setting-wrap').show();
		jQuery('#vid-rev-youtube-options').hide();
		jQuery('#vid-rev-vimeo-options').hide();
		jQuery('#streamvideo_cover').hide();
		jQuery('#streamvideo_cover_both').hide();
		
		jQuery('#button_change_image').show();
		
		jQuery('.video_volume_wrapper').hide();
		
		switch(jQuery('input[name="background_type"]:checked').data('bgtype')){
			case "image":
				jQuery('#button_change_image').hide();
				
				switch(gallery_type){
					case 'gallery':
						jQuery('#button_change_image').show();
					break;
					case 'posts':
						bgimg = rs_plugin_url+'public/assets/assets/sources/post.png';
					break;
					case 'facebook':
						bgimg = rs_plugin_url+'public/assets/assets/sources/fb.png';
					break;
					case 'twitter':
						bgimg = rs_plugin_url+'public/assets/assets/sources/tw.png';
					break;
					case 'instagram':
						bgimg = rs_plugin_url+'public/assets/assets/sources/ig.png';
					break;
					case 'flickr':
						bgimg = rs_plugin_url+'public/assets/assets/sources/fr.png';
					break;
					case 'youtube':
						bgimg = rs_plugin_url+'public/assets/assets/sources/yt.png';
					break;
					case 'vimeo':
						bgimg = rs_plugin_url+'public/assets/assets/sources/vm.png';
					break;
				}
				
				jQuery('.mainbg-sub-kenburns-selector').show();
				jQuery('.mainbg-sub-parallax-selector').show();
				jQuery('.mainbg-sub-settings-selector').show();
				jQuery('#button_change_image').appendTo(jQuery('#tp-bgimagewpsrc'));
				
				nextsh.find('.defaultimg, .slotslidebg').css({
						backgroundImage:("url("+bgimg+")"),
						backgroundPosition:bgpos,
						backgroundSize:bgfit,
						backgroundColor:"transparent"});		
			break;
			case "trans":
				nextsh.find('.defaultimg, .slotslidebg').css({
						backgroundImage:"none",
						backgroundPosition:bgpos,
						backgroundSize:bgfit,
						backgroundColor:"transparent"});	
				jQuery('.mainbg-sub-kenburns-selector').hide();
				jQuery('.mainbg-sub-parallax-selector').hide();
				jQuery('.mainbg-sub-settings-selector').hide();
			break;
			case "solid":
				nextsh.find('.defaultimg, .slotslidebg').css({
						backgroundImage:"none",
						backgroundPosition:bgpos,
						backgroundSize:bgfit,
						backgroundColor:bgcolor});	
				jQuery('.mainbg-sub-kenburns-selector').hide();
				jQuery('.mainbg-sub-parallax-selector').hide();
				jQuery('.mainbg-sub-settings-selector').hide();
			break;
			case "external":
				bgimg = jQuery('#slide_bg_external').val();

				jQuery('#the_image_source_url').html(bgimg);
				nextsh.find('.defaultimg, .slotslidebg').css({
						backgroundImage:("url("+bgimg+")"),
						backgroundPosition:bgpos,
						backgroundSize:bgfit,
						backgroundColor:"transparent"});
				jQuery('.mainbg-sub-kenburns-selector').show();
				jQuery('.mainbg-sub-parallax-selector').show();
				jQuery('.mainbg-sub-settings-selector').show();
			break;
			case "streamtwitter":
			case "streamtwitterboth":
				jQuery('#streamvideo_cover').show();
				jQuery('#streamvideo_cover_both').show();
				bgimg = rs_plugin_url+'public/assets/assets/sources/tw.png';
				nextsh.find('.defaultimg, .slotslidebg').css({
						backgroundImage:("url("+bgimg+")"),
						backgroundPosition:'center center',
						backgroundSize:'cover',
						backgroundColor:"transparent"});
				jQuery('.mainbg-sub-kenburns-selector').hide();
				jQuery('.mainbg-sub-parallax-selector').show();
				jQuery('.mainbg-sub-settings-selector').show();
				jQuery('#button_change_image').appendTo(jQuery('#vimeo-image-picker'));
				jQuery('#video-settings').show();
				jQuery('#bg-setting-wrap').hide();
				jQuery('#vid-rev-vimeo-options').show();
				jQuery('#vid-rev-youtube-options').show();
				jQuery('.video_volume_wrapper').show();
				
			break;
			case "streamyoutube":
			case "streamyoutubeboth":
				jQuery('#streamvideo_cover').show();
				jQuery('#streamvideo_cover_both').show();
				bgimg = rs_plugin_url+'public/assets/assets/sources/yt.png';
			case "youtube":
				nextsh.find('.defaultimg, .slotslidebg').css({
						backgroundImage:("url("+bgimg+")"),
						backgroundPosition:'center center',
						backgroundSize:'cover',
						backgroundColor:"transparent"});
				jQuery('.mainbg-sub-kenburns-selector').hide();
				jQuery('.mainbg-sub-parallax-selector').show();
				jQuery('.mainbg-sub-settings-selector').show();
				jQuery('#button_change_image').appendTo(jQuery('#youtube-image-picker'));
				jQuery('#video-settings').show();
				jQuery('#bg-setting-wrap').hide();
				jQuery('#vid-rev-youtube-options').show();
				jQuery('.video_volume_wrapper').show();
			break;
			case "streamvimeo":
			case "streamvimeoboth":
				jQuery('#streamvideo_cover').show();
				jQuery('#streamvideo_cover_both').show();
				bgimg = rs_plugin_url+'public/assets/assets/sources/vm.png';
			case "vimeo":
				nextsh.find('.defaultimg, .slotslidebg').css({
						backgroundImage:("url("+bgimg+")"),
						backgroundPosition:'center center',
						backgroundSize:'cover',
						backgroundColor:"transparent"});
				jQuery('.mainbg-sub-kenburns-selector').hide();
				jQuery('.mainbg-sub-parallax-selector').show();
				jQuery('.mainbg-sub-settings-selector').show();
				jQuery('#button_change_image').appendTo(jQuery('#vimeo-image-picker'));
				jQuery('#video-settings').show();
				jQuery('#bg-setting-wrap').hide();
				jQuery('#vid-rev-vimeo-options').show();
				jQuery('.video_volume_wrapper').show();
			break;
			case "streaminstagram":
			case "streaminstagramboth":
				jQuery('#streamvideo_cover').show();
				jQuery('#streamvideo_cover_both').show();
				bgimg = rs_plugin_url+'public/assets/assets/sources/ig.png';
			case "html5":
				nextsh.find('.defaultimg, .slotslidebg').css({
						backgroundImage:("url("+bgimg+")"),
						backgroundPosition:'center center',
						backgroundSize:'cover',
						backgroundColor:"transparent"});
				jQuery('.mainbg-sub-kenburns-selector').hide();
				jQuery('.mainbg-sub-parallax-selector').show();
				jQuery('.mainbg-sub-settings-selector').show();
				jQuery('#button_change_image').appendTo(jQuery('#html5video-image-picker'));
				jQuery('#video-settings').show();
				jQuery('#bg-setting-wrap').hide();

			break;
		}

		jQuery('#divbgholder').css({background:"none",backgroundImage:"none",backgroundColor:"transparent"});
		
		u.resetSlideAnimations(false);
	}
	

	var initDisallowCaptionsOnClick = function(){

		jQuery('.slide_layer.tp-caption a').on('click', function(){
			return false;
		});

	}


	function initSliderMainOptions(jQueryObj){
		
		var t=jQueryObj;
		jQuery('.bgsrcchanger-div').each(function() {
			if (jQuery(this).attr('id') !="tp-bgimagesettings" || (jQuery(this).attr('id') =="tp-bgimagesettings" && t.data('imgsettings')!="on")) {
				if (jQuery(this).attr('id') =="tp-bgimagesettings")
					jQuery(this).slideUp(200);
				else
					jQuery(this).css({display:"none"});
			}
		});
		jQuery('#'+t.data('callid')).css({display:"inline-block"});
		if (t.data('imgsettings')=="on")
			jQuery('#tp-bgimagesettings').slideDown(200);
		
		if(jQuery('input[name="background_type"]:checked').val() == 'image'){
			jQuery('.rs-img-source-size').show();
			jQuery('#alt_option').show();
			if(jQuery('#alt_option option:selected').val() == 'custom'){
				jQuery('#alt_attr').show();
			}else{
				jQuery('#alt_attr').hide();
			}
		}else{
			jQuery('#alt_option').hide();
			jQuery('#alt_attr').show();
			jQuery('.rs-img-source-size').hide();
		}
		
		if(jQuery('input[name="background_type"]:checked').val() == 'external'){
			jQuery('.ext_setting').show();
		}else{
			jQuery('.ext_setting').hide();
		}
		
	}


	/*! ALIGN TABLE HANDLING */
	/**
	 * init the align table
	 */
	var initAlignTable = function(){
		
		jQuery('.rs-new-align-button').click(function(){
			var obj = jQuery(this);
			if(jQuery(obj).parent().hasClass('table_disabled')) return(false);
			
			var inpX = jQuery("#layer_left_text"),
				inpY = jQuery("#layer_top_text"),				
				alignHor = obj.data('hor'),
				alignVert = obj.data('ver');

			if(alignVert === undefined){ //we are in horizontal
				jQuery('#rs-align-wrapper').find('.selected').removeClass("selected");				
				switch(alignHor){
					case "left":
						inpX.html(inpX.data("textnormal")).css("width","auto");
						jQuery("#layer_left").val("10");
					break;
					case "right":
						inpX.html(inpX.data("textoffset")).css("width","42px");
						jQuery("#layer_left").val("10");
					break;
					case "center":
						inpX.html(inpX.data("textoffset")).css("width","42px");
						jQuery("#layer_left").val("0");
					break;
				}

				jQuery("#layer_align_hor").val(alignHor);
				
			}else{ //we are in vertical
				jQuery('#rs-align-wrapper-ver').find('.selected').removeClass("selected");
				switch(alignVert){
					case "top":
						inpY.html(inpY.data("textnormal")).css("width","auto");
						jQuery("#layer_top").val("10");
					break;
					case "bottom":
						inpY.html(inpY.data("textoffset")).css("width","42px");
						jQuery("#layer_top").val("10");
					break;
					case "middle":
						inpY.html(inpY.data("textoffset")).css("width","42px");
						jQuery("#layer_top").val("0");
					break;
				}
				jQuery("#layer_align_vert").val(alignVert);
			}

			obj.addClass('selected');
			t.updateLayerFromFields();
			t.toolbarInPos();
			//updateHtmlLayersFromObject(selectedLayerSerial,true); (Already managed due updateLayerFromFields)
		});
	}


	/**
	 * init general events
	 */
	var initMainEvents = function(){		
		//unselect layers on container click
		container.click(function() {			
			if (!layerresized)
				unselectLayers();
			else
				layerresized=false;
		});
	}


	/**
	 * show / hide offset row accorging the slide link value
	 */
	var showHideLinkActions = function(v){

		var li = v.closest('li'),
			value = v.val();
			
		li.find('.action-link-wrapper').hide();
		li.find('.action-jump-to-slide').hide();
		li.find('.action-scrollofset').hide();
		li.find('.action-target-layer').hide();
		li.find('.action-callback').hide();
		li.find('.action-toggle_layer').hide();
		
		switch (value) {
			case "link":
				li.find('.action-link-wrapper').show();
			break;
			case "jumpto":
				li.find('.action-jump-to-slide').show();
			break;
			
			case "scroll_under":
				li.find('.action-scrollofset').show();
			break;

			case "callback":
				li.find('.action-callback').show();
			break;

			case "start_in":
			case "start_out":
			case "start_video":
			case "stop_video":
				li.find('.action-target-layer').show();
			break;
			case "toggle_layer":
				li.find('.action-target-layer').show();
				li.find('.action-toggle_layer').show();
			break;
			case "toggle_video":
				li.find('.action-target-layer').show();
			break;
			case "simulate_click":
				li.find('.action-target-layer').show();
			break;
			case "toggle_class":
				li.find('.action-target-layer').show();
				li.find('.action-toggleclass').show();
			break;
		}
		
		switch (value) {
			case 'start_in':
			case 'start_out':
			case 'toggle_layer':
				li.find('.action-triggerstates').show();
			break;
			default:
				li.find('.action-triggerstates').hide();
			break;
		}
		switch (value) {
			case "toggle_video":
			case "start_video":
			case "stop_video":
				li.find('.action-target-layer').find('select[name="layer_target[]"] option').each(function(){
					if(jQuery(this).data('mytype') !== 'video'){
						jQuery(this).hide();
					}else{
						jQuery(this).show();
					}
				});
			break;
			default:
				li.find('.action-target-layer').find('select[name="layer_target[]"] option').each(function(){
					jQuery(this).show();
				});
			break;
		}
	}
	
	var showHideToolTip = function(){
		var value = jQuery("#layer_tooltip_event").val(),
			tpat = jQuery(".tooltip-parrent-part"),
			tchi = jQuery('.tooltip-child-part');

		switch (value) {
			case "none":
				tpat.hide();
				tchi.hide();
			break;
			case "parrent":
				tpat.show();
				tchi.hide();		
			break;			
			case "child":
				tpat.hide();
				tchi.show();				
			break;
		}
	}

	// SET SUFFIX FOR INPUT FIELD OR LEAVE THE CURRENT VALUE 
	var specOrVal = function(putin,possiblevalues,suffix) {			

		var result = jQuery.inArray(putin,possiblevalues)>=0 ? putin : putin===undefined || !jQuery.isNumeric(parseInt(putin,0)) || putin.length===0 ? "" : parseInt(putin,0)+suffix;		
		return result;
	}

	/*****************************************************
					INIT HTML FIELDS 
	  init events (update) for html properties change.
	*****************************************************/
	var initHtmlFields = function(){
		
		//show / hide slide link offset
		
		jQuery('body').on('change', 'select[name="layer_action[]"], select[name="no_layer_action[]"]',function() {
			showHideLinkActions(jQuery(this));
		});

		jQuery('#layer_tooltip_event').change(showHideToolTip);

		
		//set layers autocompolete
		jQuery("#layer_caption").catcomplete({
			source: initArrCaptionClasses,
			minLength:0,
			appendTo:"#tp-thelistofclasses",
			open:function(event,ui) {
				if (jQuery('#tp-thelistofclasses ul').height()>450)
					jQuery('#tp-thelistofclasses ul').perfectScrollbar("destroy").perfectScrollbar({wheelPropagation:true, suppressScrollX:true});
			},
			close: function(event, ui){
				var layer = t.getCurrentLayer();
				
				if(layer === false || layer == null){
					return false;
				}
				
				if(layer.style !== undefined && layer.style !== jQuery('#layer_caption').val()){
					layer.style = jQuery('#layer_caption').val();
					//update values that can be set without changing the class
					t.reset_to_default_static_styles(layer);
					// Reset Fields from Style Template
					updateSubStyleParameters(layer, true);
				}
				jQuery('#layer_caption').change();
				
				t.updateLayerFromFields();
			}/*,
			change: function(event,ui){
				if (ui.item==null){
					jQuery("#layer_caption").val('');
					jQuery("#layer_caption").focus();
				}
			}*/
		}).data("customCatcomplete")._renderItem = function(ul, item) {
			var listItem = jQuery("<li></li>")
				.data("item.autocomplete", item)
				.append("<a>" + item.label + "</a>")
				.appendTo(ul);

			listItem.attr('original-title', item.value);
			return listItem;
		};


		//open the list on right button
		jQuery( "#layer_captions_down" ).click(function(event){
			event.stopPropagation();

			jQuery("#css_editor_expert").hide();
			jQuery("#css_editor_wrap").hide();

			//if opened - close autocomplete
			if(jQuery('#layer_caption').data("is_open") == true)
				jQuery( "#layer_caption" ).catcomplete("close");
			else   //else open autocomplete
			if(jQuery(this).hasClass("ui-state-active"))
				jQuery( "#layer_caption" ).catcomplete( "search", "" ).data("customCatcomplete")._renderItem = function(ul, item) {
					var listItem = jQuery("<li></li>")
						.data("item.autocomplete", item)
						.append("<a>" + item.label + "</a>")
						.appendTo(ul);
					listItem.attr('original-title', item.value);
					return listItem;
				};
		});

		//handle autocomplete close
		jQuery('#layer_caption').bind('catcompleteopen', function() {
			jQuery(this).data('is_open',true);

			//handle tooltip
			jQuery('.ui-autocomplete li').tipsy({
		        delayIn: 70,
		        //delayOut:100000,
				html: true,
				gravity:"w",
				//trigger:"manual",
				title: function(){
					setTimeout(function() {
						jQuery('.tp-present-caption-small').parent().addClass("tp-present-wrapper-small");
						jQuery('.tp-present-caption-small').parent().parent().addClass("tp-present-wrapper-parent-small");
					},10);
					return '<div class="tp-present-caption-small"><div class="tp-caption '+this.getAttribute('original-title')+'">example</div></div>';
				}
			});
		});

		jQuery('#layer_caption').bind('catcompleteclose', function() {
			jQuery(this).data('is_open',false);
		});

		//set layers autocompolete
		jQuery('input[name="css_font-family"]').autocomplete({
			source: initArrFontTypes,
			minLength:0,
			close:t.updateLayerFromFields
		});
		
		//set layers autocompolete
		jQuery('input[name="adbutton-fontfamily"]').autocomplete({
			source: initArrFontTypes,
			minLength:0
		});

		//open the list on right button
		jQuery("#font_family_down").click(function(event){
			event.stopPropagation();

			//if opened - close autocomplete
			if(jQuery('input[name="css_font-family"]').data("is_open") == true)
				jQuery('input[name="css_font-family"]').autocomplete("close");
			else   //else open autocomplete
			if(jQuery(this).hasClass("ui-state-active"))
				jQuery('input[name="css_font-family"]').autocomplete( "search", "" ).data("ui-autocomplete");
		});

		//handle autocomplete close
		jQuery('input[name="css_font-family"]').bind('autocompleteopen', function() {
			jQuery(this).data('is_open',true);
		});

		jQuery('input[name="css_font-family"]').bind('autocompleteclose', function() {
			jQuery(this).data('is_open',false);
		});


		jQuery("body").click(function(){
			jQuery( "#layer_caption" ).catcomplete("close");
			jQuery('input[name="css_font-family"]').autocomplete("close");
		});

		//set events:
		jQuery('body').on('change', ".form_layers select, #layer_proportional_scale, #layer_auto_line_break", function(){
			t.updateLayerFromFields();
		});

		jQuery('#layer_proportional_scale, #layer_auto_line_break').change(function(){
			if(jQuery(this).is(':checked'))
				jQuery(this).parent().removeClass("notselected")
			else
				jQuery(this).parent().addClass("notselected")
		});

		var keyuprefresh;
		// UPDATE LAYER TEXT FIELD
		jQuery("#layer_text").keyup(function(){
			clearTimeout(keyuprefresh);
			var v = jQuery(this).val();
			keyuprefresh = setTimeout(function() {						
				updateLayerTextField("",jQuery('.sortlist li.ui-state-hover .tl-fullanim'),v);						
				t.toolbarInPos();
				t.updateLayerFromFields();
			},150);
		});

		jQuery('.rev-visibility-on-sizes input').click(function(){
			t.updateLayerFromFields();
		});

		jQuery('body').on('blur', ".form_layers input, .form_layers textarea", function(){
			//var cname = jQuery(this).attr('name');
			//if(cname == 'layer_action_delay[]' || cname == 'layer_image_link[]' || cname == 'layer_actioncallback[]' || cname == 'layer_scrolloffset[]' || cname == 'layer_toggleclass[]') return false; //layer actions deny the blur
			
			t.updateLayerFromFields();
		});
		jQuery('body').on('change', ".form_layers input, .form_layers textarea", function(){
			t.updateLayerFromFields();
		});
		jQuery('body').on('keypress', ".form_layers input, .form_layers textarea", function(event){
			if(event.keyCode == 13){
				t.updateLayerFromFields();
			}
		});

		
		jQuery("#delay").keypress(function(event){
			if (Number(jQuery('#delay').val())>0) g_slideTime = jQuery('#delay').val();
		});

		jQuery("#delay").blur(function(){
			if (Number(jQuery('#delay').val())>0) g_slideTime = jQuery('#delay').val();

			var w = g_slideTime/10;
			jQuery('#mastertimer-maxtime').css({left:(w+15)+"px"});
			jQuery('#mastertimer-maxcurtime').html(u.convToTime(w));
			jQuery('.slide-idle-section').css({left:w+15});
			jQuery('.mastertimer-slide .tl-fullanim').css({width:(w)+"px"});
		});
		
		jQuery('.form_layers input').on("click",function() {
			jQuery(this).select();
		})

		// MIN, MAX VALUES - SUFFIX ADD ONS
		jQuery('.form_layers input').on("change blur focus",function() {	

			var inp = jQuery(this),
				cv = parseFloat(inp.val()),
				min = parseFloat(inp.data("min")),
				max = parseFloat(inp.data("max"));
			
			if (inp.data('suffix')!=undefined) {
				if (jQuery.isNumeric(cv) && cv > -9999999 && cv<9999999 ) {
					if (min!=undefined && cv<min) cv = min;
					if (min!=undefined && cv>max) cv = max;					
					if (isNaN(cv)) cv = 0;
					cv = Math.round(cv*100)/100;
					inp.val(cv+inp.data('suffix'));
				}
			}
			
			
		});

		jQuery('#clayer_start_time, #clayer_end_time, #clayer_start_speed, #clayer_end_speed').on("change blur", function() {
			var objLayer = t.getLayer(selectedLayerSerial);
			
			objLayer.time = jQuery('#clayer_start_time').val();
			objLayer.endtime = jQuery('#clayer_end_time').val();
			objLayer.speed = jQuery('#clayer_start_speed').val();
			objLayer.endspeed = jQuery('#clayer_end_speed').val();
			
			jQuery('#layer_speed').val(objLayer.speed);
			jQuery('#layer_endspeed').val(objLayer.endspeed);
			t.updateLayerFromFields();
		});


		jQuery('body').on('click','.timer-manual-edit, #layers-right .sortablelayers',function() {
			jQuery('#timline-manual-dialog').show();
		});

		jQuery('#timline-manual-closer').on('click',function() {
			jQuery('#timline-manual-dialog').hide();
		});

	}


	/**
	 * init buttons actions
	 */
	var initButtons = function(){
		
		//set event buttons actions:
		jQuery('#button_add_layer').click(function(){	
			//ADDING NEW TEXT LAYER		
			addLayerText(jQuery(this).data('isstatic') == true ? 'static' : null);			
		});

		// add image layer
		jQuery('#button_add_layer_image').click(function(){
			var targ = jQuery(this).data('isstatic') == true ? rev_lang.select_static_layer_image : rev_lang.select_layer_image,
				par = jQuery(this).data('isstatic') == true ? 'static' : null;
			
			UniteAdminRev.openAddImageDialog(targ,function(urlImage,imgid,imgwidth,imgheight){
				var imgobj = {imgurl: urlImage, imgid: imgid, imgwidth: imgwidth, imgheight: imgheight};
				addLayerImage(imgobj, par);
			});			
		});

		//add youtube actions:
		jQuery('#button_add_layer_video').click(function(){
			
			jQuery('#video_dialog_form').trigger("reset");
			jQuery('#reset_video_dialog_tab').click();
			
			//check if we are youtubestream or vimeostream. If yes, change what can be seen and edited
			var gallery_type = jQuery('input[name="rs-gallery-type"]').val();
			
			switch(gallery_type){
				case 'youtube':
					jQuery('.rs-show-when-youtube-stream').show();
					jQuery('.rs-hide-when-youtube-stream').show();
				break;
				case 'vimeo':
					jQuery('.rs-show-when-vimeo-stream').show();
					jQuery('.rs-hide-when-vimeo-stream').show();
				break;
				case 'instagram':
					jQuery('.rs-show-when-instagram-stream').show();
					jQuery('.rs-hide-when-instagram-stream').show();
				break;
			}
			
			
			var par = jQuery(this).data('isstatic') == true ? 'static' : null;
			UniteAdminRev.openVideoDialog(function(videoData){ 
				addLayerVideo(videoData, par); 
			});
			
		});
		
		
		jQuery('#button_add_layer_button').click(function(){
			setExampleButtons();
			jQuery('#dialog_addbutton').dialog({
				buttons:{'Close':function(){jQuery('#dialog_addbutton').dialog('close');}},
				minWidth: 830,
				minHeight: 500,
				modal: true,
				dialogClass: 'tpdialogs'
			});
		});
		
		
		jQuery('#button_add_layer_shape').click(function(){
			setExampleShape();
			jQuery('#dialog_addshape').dialog({
				buttons:{'Add':function(){
					//get values for shape
					var data = {};
					data['static_styles'] = {};
					data['deformation'] = {};
					data['deformation-hover'] = {};
					data.text = ' ';
					data.alias = 'Shape';
					data.type = 'shape';
					//data.style = 'tp-shape tp-shapewrapper';
					data.style = '';
					
					data.internal_class = 'tp-shape tp-shapewrapper';
					
					data.autolinebreak = false;
					
					data['deformation']['background-color'] = jQuery('input[name="adshape-color-1"]').val();
					data['deformation']['background-transparency'] = jQuery('input[name="adshape-opacity-1"]').val();
					data['deformation']['border-color'] = jQuery('input[name="adshape-border-color"]').val();
					data['deformation']['border-opacity'] = jQuery('input[name="adshape-border-opacity"]').val();
					data['deformation']['border-transparency'] = jQuery('input[name="adshape-border-opacity"]').val();
					data['deformation']['border-width'] = jQuery('input[name="adshape-border-width"]').val();
					data['deformation']['border-style'] = 'solid';
					data['deformation']['border-radius'] = [jQuery('.example-shape').css('borderTopLeftRadius'),jQuery('.example-shape').css('borderTopRightRadius'),jQuery('.example-shape').css('borderBottomRightRadius'),jQuery('.example-shape').css('borderBottomLeftRadius')];
					
					if(jQuery('input[name="shape_fullwidth"]').is(':checked')){
						data['max_width'] = '100%';
						data['cover_mode'] = 'fullwidth';
					}else{
						data['max_width'] = jQuery('input[name="shape_width"]').val();
					}
					
					if(jQuery('input[name="shape_fullheight"]').is(':checked')){
						data['max_height'] = '100%';
						data['cover_mode'] = 'fullheight';
					}else{
						data['max_height'] = jQuery('input[name="shape_height"]').val();
					}
					
					if(jQuery('input[name="shape_fullheight"]').is(':checked') && jQuery('input[name="shape_fullwidth"]').is(':checked')){
						data['cover_mode'] = 'cover';
					}
					
					//if(jQuery('input[name="shape_fullwidth"]').is(':checked') && jQuery('input[name="shape_fullheight"]').is(':checked')){
					//	data['deformation']['padding'] = [jQuery('.example-shape').css('paddingTop'), jQuery('.example-shape').css('paddingRight'), jQuery('.example-shape').css('paddingBottom'), jQuery('.example-shape').css('paddingLeft')];
					//}
					
					addLayer(data);
					
					jQuery('#dialog_addshape').dialog('close');
			
				},'Close':function(){jQuery('#dialog_addshape').dialog('close');}},
				minWidth: 830,
				minHeight: 500,
				modal: true,
				dialogClass: 'tpdialogs'
			});
		});
		
		
		jQuery('body').on('click', '.addbutton-examples-wrapper a.rev-btn', function(){
			//get values for buttons
			var data = {};
			data['static_styles'] = {};
			data['inline'] = {'idle':{}, 'hover':{}};
			data['deformation'] = {};
			data['deformation-hover'] = {};
			data.text = jQuery('input[name="adbutton-text"]').val();
			data.type = 'button';
			data.subtype = 'roundbutton'; //no_edit
			data.specialsettings = {};
			
			data.alias = 'Button';
			data.style = '';//'rev-btn';//jQuery(this).attr('class');
			
			data.internal_class = jQuery(this).data('needclass');
			
			//missing class needs to be added here as some special!
			
			data['resize-full'] = false;
			data['resizeme'] = false;
			
			data['static_styles']['color'] = jQuery('input[name="adbutton-color-2"]').val();
			data['static_styles']['font-size'] = jQuery(this).css('font-size');
			data['static_styles']['line-height'] = jQuery(this).css('font-size');
			//data['static_styles']['line-height'] = jQuery(this).css('line-height');
			data['static_styles']['font-weight'] = jQuery(this).css('font-weight');
			
			data['max_width'] = 'auto';
			data['max_height'] = 'auto';
			
			data['autolinebreak'] = false;
			
			data['deformation']['padding'] = [jQuery(this).css('paddingTop'), jQuery(this).css('paddingRight'), jQuery(this).css('paddingBottom'), jQuery(this).css('paddingLeft')];
			
			data['deformation']['font-family'] = jQuery('input[name="adbutton-fontfamily"]').val();
			data['deformation']['background-color'] = jQuery('input[name="adbutton-color-1"]').val();
			data['deformation']['background-transparency'] = jQuery('input[name="adbutton-opacity-1"]').val();
			data['deformation']['color-transparency'] = jQuery('input[name="adbutton-opacity-2"]').val();
			data['deformation']['border-radius'] = [jQuery(this).css('borderTopLeftRadius'),jQuery(this).css('borderTopRightRadius'),jQuery(this).css('borderBottomRightRadius'),jQuery(this).css('borderBottomLeftRadius')];
			data['deformation']['border-color'] = jQuery('input[name="adbutton-border-color"]').val();
			data['deformation']['border-transparency'] = jQuery('input[name="adbutton-border-opacity"]').val();
			data['deformation']['border-opacity'] = jQuery('input[name="adbutton-border-opacity"]').val();
			data['deformation']['border-width'] = jQuery('input[name="adbutton-border-width"]').val();
			data['deformation']['border-style'] = 'solid';
			
			if(jQuery(this).hasClass('rev-withicon')){
				data['deformation']['icon-class'] = jQuery('.addbutton-icon i').attr('class'); //needs to be added
				data.text += '<i class="' + data['deformation']['icon-class'] + '"></i>';
			}else{
				data['deformation']['icon-class'] = '';
			}
			
			data['hover'] = true;
			data['deformation-hover']['background-color'] = jQuery('input[name="adbutton-color-1-h"]').val();
			data['deformation-hover']['background-transparency'] = jQuery('input[name="adbutton-opacity-1-h"]').val();
			data['deformation-hover']['color'] = jQuery('input[name="adbutton-color-2-h"]').val();
			data['deformation-hover']['color-transparency'] = jQuery('input[name="adbutton-opacity-2-h"]').val();
			data['deformation-hover']['border-radius'] = [jQuery(this).css('borderTopLeftRadius'),jQuery(this).css('borderTopRightRadius'),jQuery(this).css('borderBottomRightRadius'),jQuery(this).css('borderBottomLeftRadius')];
			data['deformation-hover']['border-color'] = jQuery('input[name="adbutton-border-color-h"]').val();
			data['deformation-hover']['border-transparency'] = jQuery('input[name="adbutton-border-opacity-h"]').val();
			data['deformation-hover']['border-opacity'] = jQuery('input[name="adbutton-border-opacity-h"]').val();
			data['deformation-hover']['border-width'] = jQuery('input[name="adbutton-border-width-h"]').val();
			data['deformation-hover']['border-style'] = 'solid';
			
			if(jQuery(this).hasClass('rev-hiddenicon')){
				data['deformation-hover']['icon-class'] = jQuery('.addbutton-icon i').attr('class'); //needs to be added
				data.text += ' <i class="' + data['deformation-hover']['icon-class'] + '"></i>';
			}else{
				data['deformation-hover']['icon-class'] = '';
			}
			
			
			//add stylings depending on classes it has
			if(jQuery(this).hasClass('rev-btn')){
				data['deformation']['text-decoration'] = 'none';
				data['deformation-hover']['css_cursor'] = 'pointer';
				data['inline']['idle']['outline'] = 'none';
				data['inline']['idle']['box-shadow'] = 'none';
				data['inline']['idle']['box-sizing'] = 'border-box';
				data['inline']['idle']['-moz-box-sizing'] = 'border-box';
				data['inline']['idle']['-webkit-box-sizing'] = 'border-box';
			}
			
			if(jQuery(this).hasClass('rev-uppercase')){
				data['inline']['idle']['text-transform'] = 'uppercase';
				data['inline']['idle']['letter-spacing'] = '1px';
			}
			
			/*
			if(jQuery(this).hasClass('rev-medium')){
			}
			
			if(jQuery(this).hasClass('rev-small')){
			}
			
			if(jQuery(this).hasClass('rev-maxround')){
			}
			
			if(jQuery(this).hasClass('rev-minround')){
			}
			*/
			
			addLayer(data);
			
			jQuery('#dialog_addbutton').dialog('close');
			
		});
		
		var addSpecialButton = function(btn) {
			//get values for buttons
			var data = {};
			data['static_styles'] = {};
			data['inline'] = {'idle':{}, 'hover':{}};
			data['deformation'] = {};
			data['deformation-hover'] = {};
			data.text = btn.html();
			data.type = 'button'; //no_edit
			
			data.specialsettings = {};
			
			
			data.style = ''; //jQuery(this).attr('class');
			data.internal_class = btn.data('needclass');
			
			data['resize-full'] = false;
			data['resizeme'] = false;
			
			data['max_width'] = btn.css('width');
			data['max_height'] = btn.css('height');
			
			data['deformation']['padding'] = [btn.css('paddingTop'), btn.css('paddingRight'), btn.css('paddingBottom'), btn.css('paddingLeft')];
			
			data['deformation']['background-color'] = UniteAdminRev.rgb2hex(btn.css('backgroundColor'));
			var bgOpacity = UniteAdminRev.getTransparencyFromRgba(btn.css('backgroundColor'));
		    bgOpacity = bgOpacity === false ? 1 : bgOpacity;
			if(bgOpacity == 0) data['deformation']['background-color'] = 'transparent';
			
			data['deformation']['background-opacity'] = bgOpacity;
			data['deformation']['background-transparency'] = bgOpacity;
			
			
			data['deformation']['border-color'] = UniteAdminRev.rgb2hex(btn.css('borderTopColor'));
			var borOpacity = UniteAdminRev.getTransparencyFromRgba(btn.css('borderTopColor'));
		    borOpacity = borOpacity === false ? 1 : borOpacity;
			if(borOpacity == 0) data['deformation']['border-color'] = 'transparent';
			
			data['deformation']['border-opacity'] = borOpacity;
			data['deformation']['border-transparency'] = borOpacity;
			
			
			data['deformation']['border-radius'] = [btn.css('borderTopLeftRadius'),btn.css('borderTopRightRadius'),btn.css('borderBottomRightRadius'),btn.css('borderBottomLeftRadius')];
			
			data['deformation']['border-width'] = btn.css('borderTopWidth');
			data['deformation']['border-style'] = btn.css('borderTopStyle');
			
			data['deformation-hover']['css_cursor'] = btn.css('cursor');
			
			data['inline']['idle']['box-sizing'] = 'border-box';
			data['inline']['idle']['-moz-box-sizing'] = 'border-box';
			data['inline']['idle']['-webkit-box-sizing'] = 'border-box';
			
			return data;
		}


		jQuery('body').on('click', '.addbutton-examples-wrapper div.rev-burger', function(){			
			var data = addSpecialButton(jQuery(this));			
			data.alias = 'Burger Button';
			data.subtype = 'burgerbutton'; //no_edit
			//add actions here
			data['layer_action'] = {};
			data['layer_action'].tooltip_event = [];
			data['layer_action'].tooltip_event.push('click');
			data['layer_action'].action = [];
			data['layer_action'].action.push('toggle_class');
			data['layer_action'].layer_target = [];
			data['layer_action'].layer_target.push('self');
			data['layer_action'].action_delay = [];
			data['layer_action'].action_delay.push(0);
			data['layer_action'].toggle_class = [];
			data['layer_action'].toggle_class.push('open');			
			addLayer(data);			
			jQuery('#dialog_addbutton').dialog('close');			
		});
		
		jQuery('body').on('click', '.addbutton-examples-wrapper span.rev-control-btn', function(){			
			var data = addSpecialButton(jQuery(this));			
			data.alias = 'Control Button';
			data.subtype = 'controlbutton';
			
			if(data['static_styles'] === undefined) data['static_styles'] = {};
			
			data['static_styles']['font-size'] = jQuery(this).css('font-size');
			data['static_styles']['line-height'] = jQuery(this).css('line-height');
			data['static_styles']['font-weight'] = jQuery(this).css('font-weight');
			data['static_styles']['color'] = UniteAdminRev.rgb2hex(jQuery(this).css('color'));
			
			data['deformation']['font-family'] = jQuery(this).css('font-family');
			data['deformation']['text-align'] = jQuery(this).css('text-align');
			
			
			
			addLayer(data);			
			jQuery('#dialog_addbutton').dialog('close');			
		});
		
		
		jQuery('body').on('click', '.addbutton-examples-wrapper span.rev-scroll-btn', function(){
			var data = addSpecialButton(jQuery(this));			
			data.subtype = 'scrollbutton'; //no_edit
			data.alias = 'Scroll Button';
			addLayer(data);			
			jQuery('#dialog_addbutton').dialog('close');
		});
		
		
		//change image source actions
		jQuery('#button_change_image_source').click(function(){
			
			jQuery('#layer-short-toolbar').appendTo(jQuery('#layer-settings-toolbar-bottom'));
			jQuery('#layer_text_wrapper').appendTo(jQuery('#layer_text_holder'));
			UniteAdminRev.openAddImageDialog(rev_lang.select_layer_image,function(urlImage){
				var objData = {};
				objData.image_url = urlImage;

				updateCurrentLayer(objData);
				jQuery('#layer-short-toolbar').appendTo(jQuery('#layer-settings-toolbar-bottom'));
				jQuery('#layer_text_wrapper').appendTo(jQuery('#layer_text_holder'));
				redrawLayerHtml(selectedLayerSerial);
				jQuery('#layer-short-toolbar').appendTo('.slide_layer.layer_selected');										
			});
			jQuery('#layer-short-toolbar').appendTo('.slide_layer.layer_selected');			
		});
		

		//insert button link - open the dialog
		jQuery("#linkInsertTemplate").click(function(){
			if(jQuery(this).hasClass("disabled"))
				return(false);
			
			add_meta_into = '';
			
			var buttons = {"Cancel":function(){jQuery("#dialog_template_insert").dialog("close")}}
			jQuery("#dialog_template_insert").dialog({
				buttons:buttons,
				minWidth:500,
				dialogClass:"tpdialogs",
				modal:true
			});

		});
		
		//insert button link - open the dialog
		jQuery(".rs-param-meta-open").click(function(){
			
			add_meta_into = 'params_'+jQuery(this).data('curid');
			
			var buttons = {"Cancel":function(){jQuery("#dialog_template_insert").dialog("close")}}
			jQuery("#dialog_template_insert").dialog({
				buttons:buttons,
				minWidth:500,
				dialogClass:"tpdialogs",
				modal:true
			});

		});

		//delete layer actions:
		jQuery("#button_delete_layer").click(function(){
			if(jQuery(this).hasClass("button-disabled")) return(false);
			//delete selected layer
			deleteCurrentLayer();
		});

		//delete layer actions:
		jQuery("#button_duplicate_layer").click(function(){
			if(jQuery(this).hasClass("button-disabled")) return(false);
			//duplicate selected layer			
			duplicateCurrentLayer();			
			return false;
		});
		
	}

	//======================================================
	//		Init Function End
	//======================================================

	
	/**
	 * get the values of custom animation dialog
	 */
	var createNewAnimObj = function(what){
		
		var customAnim = new Object;
		if(what == 'start'){
			customAnim['movex'] = jQuery('input[name="layer_anim_xstart"]').val();
			customAnim['movey'] = jQuery('input[name="layer_anim_ystart"]').val();
			customAnim['mask'] = jQuery('input[name="masking-start"]').is(':checked');
			customAnim['movez'] = jQuery('input[name="layer_anim_zstart"]').val();
			customAnim['rotationx'] = jQuery('input[name="layer_anim_xrotate"]').val();
			customAnim['rotationy'] = jQuery('input[name="layer_anim_yrotate"]').val();
			customAnim['rotationz'] = jQuery('input[name="layer_anim_zrotate"]').val();
			customAnim['scalex'] = jQuery('input[name="layer_scale_xstart"]').val();
			customAnim['scaley'] = jQuery('input[name="layer_scale_ystart"]').val();
			customAnim['skewx'] = jQuery('input[name="layer_skew_xstart"]').val();
			customAnim['skewy'] = jQuery('input[name="layer_skew_ystart"]').val();
			customAnim['captionopacity'] = jQuery('input[name="layer_opacity_start"]').val();
			customAnim['mask'] = jQuery('input[name="masking-start"]').is(':checked');
			customAnim['mask_x'] = jQuery('input[name="mask_anim_xstart"]').val();
			customAnim['mask_y'] = jQuery('input[name="mask_anim_ystart"]').val();
			customAnim['mask_ease'] = jQuery('input[name="mask_easing"]').val();
			customAnim['mask_speed'] = jQuery('input[name="mask_speed"]').val();
			customAnim['easing'] = jQuery('select[name="layer_easing"] option:selected').val();
			customAnim['speed'] = jQuery('input[name="layer_speed"]').val();
			customAnim['split'] = jQuery('select[name="layer_split"] option:selected').val();
			customAnim['splitdelay'] = jQuery('input[name="layer_splitdelay"]').val();
			
			customAnim['movex_reverse'] = jQuery('input[name="layer_anim_xstart_reverse"]').is(':checked');
			customAnim['movey_reverse'] = jQuery('input[name="layer_anim_ystart_reverse"]').is(':checked');
			customAnim['rotationx_reverse'] = jQuery('input[name="layer_anim_xrotate_reverse"]').is(':checked');
			customAnim['rotationy_reverse'] = jQuery('input[name="layer_anim_yrotate_reverse"]').is(':checked');
			customAnim['rotationz_reverse'] = jQuery('input[name="layer_anim_zrotate_reverse"]').is(':checked');
			customAnim['scalex_reverse'] = jQuery('input[name="layer_scale_xstart_reverse"]').is(':checked');
			customAnim['scaley_reverse'] = jQuery('input[name="layer_scale_ystart_reverse"]').is(':checked');
			customAnim['skewx_reverse'] = jQuery('input[name="layer_skew_xstart_reverse"]').is(':checked');
			customAnim['skewy_reverse'] = jQuery('input[name="layer_skew_ystart_reverse"]').is(':checked');
			customAnim['mask_x_reverse'] = jQuery('input[name="mask_anim_xstart_reverse"]').is(':checked');
			customAnim['mask_y_reverse'] = jQuery('input[name="mask_anim_ystart_reverse"]').is(':checked');
			
		}else{
			customAnim['movex'] = jQuery('input[name="layer_anim_xend"]').val();
			customAnim['movey'] = jQuery('input[name="layer_anim_yend"]').val();
			customAnim['movez'] = jQuery('input[name="layer_anim_zend"]').val();
			customAnim['rotationx'] = jQuery('input[name="layer_anim_xrotate_end"]').val();
			customAnim['rotationy'] = jQuery('input[name="layer_anim_yrotate_end"]').val();
			customAnim['rotationz'] = jQuery('input[name="layer_anim_zrotate_end"]').val();
			customAnim['scalex'] = jQuery('input[name="layer_scale_xend"]').val();
			customAnim['scaley'] = jQuery('input[name="layer_scale_yend"]').val();
			customAnim['skewx'] = jQuery('input[name="layer_skew_xend"]').val();
			customAnim['skewy'] = jQuery('input[name="layer_skew_yend"]').val();
			customAnim['captionopacity'] = jQuery('input[name="layer_opacity_end"]').val();
			customAnim['mask'] = jQuery('input[name="masking-end"]').is(':checked');
			customAnim['mask_x'] = jQuery('input[name="mask_anim_xend"]').val();
			customAnim['mask_y'] = jQuery('input[name="mask_anim_yend"]').val();
			customAnim['mask_ease'] = jQuery('input[name="mask_easing_end"]').val();
			customAnim['mask_speed'] = jQuery('input[name="mask_speed_end"]').val();
			customAnim['easing'] = jQuery('select[name="layer_endeasing"] option:selected').val();
			customAnim['speed'] = jQuery('input[name="layer_endspeed"]').val();
			customAnim['split'] = jQuery('select[name="layer_endsplit"] option:selected').val();
			customAnim['splitdelay'] = jQuery('input[name="layer_endsplitdelay"]').val();
			
			customAnim['movex_reverse'] = jQuery('input[name="layer_anim_xend_reverse"]').is(':checked');
			customAnim['movey_reverse'] = jQuery('input[name="layer_anim_yend_reverse"]').is(':checked');
			customAnim['rotationx_reverse'] = jQuery('input[name="layer_anim_xrotate_end_reverse"]').is(':checked');
			customAnim['rotationy_reverse'] = jQuery('input[name="layer_anim_yrotate_end_reverse"]').is(':checked');
			customAnim['rotationz_reverse'] = jQuery('input[name="layer_anim_zrotate_end_reverse"]').is(':checked');
			customAnim['scalex_reverse'] = jQuery('input[name="layer_scale_xend_reverse"]').is(':checked');
			customAnim['scaley_reverse'] = jQuery('input[name="layer_scale_yend_reverse"]').is(':checked');
			customAnim['skewx_reverse'] = jQuery('input[name="layer_skew_xend_reverse"]').is(':checked');
			customAnim['skewy_reverse'] = jQuery('input[name="layer_skew_yend_reverse"]').is(':checked');
			customAnim['mask_x_reverse'] = jQuery('input[name="mask_anim_xend_reverse"]').is(':checked');
			customAnim['mask_y_reverse'] = jQuery('input[name="mask_anim_xend_reverse"]').is(':checked');
		}
		return customAnim;

	}


	/**
	 * set the values of custom animation dialog
	 */
	var setNewAnimFromObj = function(what, obj_v){
		
		if(obj_v == undefined) return true;
		
		if(what == 'start'){
			if(obj_v['movex'] !== undefined) { jQuery('input[name="layer_anim_xstart"]').val(obj_v['movex']); }else{ jQuery('input[name="layer_anim_xstart"]').val(0); }
			if(obj_v['movey'] !== undefined) { jQuery('input[name="layer_anim_ystart"]').val(obj_v['movey']); }else{ jQuery('input[name="layer_anim_ystart"]').val(0); }
			if(obj_v['movez'] !== undefined) { jQuery('input[name="layer_anim_zstart"]').val(obj_v['movez']); }else{ jQuery('input[name="layer_anim_zstart"]').val(0); }
			if(obj_v['rotationx'] !== undefined) { jQuery('input[name="layer_anim_xrotate"]').val(obj_v['rotationx']); }else{ jQuery('input[name="layer_anim_xrotate"]').val(0); }
			if(obj_v['rotationy'] !== undefined) { jQuery('input[name="layer_anim_yrotate"]').val(obj_v['rotationy']); }else{ jQuery('input[name="layer_anim_yrotate"]').val(0); }
			if(obj_v['rotationz'] !== undefined) { jQuery('input[name="layer_anim_zrotate"]').val(obj_v['rotationz']); }else{ jQuery('input[name="layer_anim_zrotate"]').val(0); }
			if(obj_v['scalex'] !== undefined) { jQuery('input[name="layer_scale_xstart"]').val(obj_v['scalex']); }else{ jQuery('input[name="layer_scale_xstart"]').val(0); }
			if(obj_v['scaley'] !== undefined) { jQuery('input[name="layer_scale_ystart"]').val(obj_v['scaley']); }else{ jQuery('input[name="layer_scale_ystart"]').val(0); }
			if(obj_v['skewx'] !== undefined) { jQuery('input[name="layer_skew_xstart"]').val(obj_v['skewx']); }else{ jQuery('input[name="layer_skew_xstart"]').val(0); }
			if(obj_v['skewy'] !== undefined) { jQuery('input[name="layer_skew_ystart"]').val(obj_v['skewy']); }else{ jQuery('input[name="layer_skew_ystart"]').val(0); }
			if(obj_v['captionopacity'] !== undefined) { jQuery('input[name="layer_opacity_start"]').val(obj_v['captionopacity']); }else{ jQuery('input[name="layer_opacity_start"]').val(0); }
			if(obj_v['mask'] !== undefined && (obj_v['mask'] == 'true' || obj_v['mask'] == true)) { jQuery('input[name="masking-start"]').attr('checked', true); }else{ jQuery('input[name="masking-start"]').attr('checked', false); }
			if(obj_v['mask_x'] !== undefined) { jQuery('input[name="mask_anim_xstart"]').val(obj_v['mask_x']); }else{ jQuery('input[name="mask_anim_xstart"]').val(0); }
			if(obj_v['mask_y'] !== undefined) { jQuery('input[name="mask_anim_ystart"]').val(obj_v['mask_y']); }else{ jQuery('input[name="mask_anim_ystart"]').val(0); }
			if(obj_v['mask_ease'] !== undefined) { jQuery('input[name="mask_easing"]').val(obj_v['mask_ease']); }else{ jQuery('input[name="mask_easing"]').val(0); }
			if(obj_v['mask_speed'] !== undefined) { jQuery('input[name="mask_speed"]').val(obj_v['mask_speed']); }else{ jQuery('input[name="mask_speed"]').val(0); }
			
			if(obj_v['easing'] !== undefined) { jQuery('select[name="layer_easing"] option[value="'+obj_v['easing']+'"]').attr('selected', 'selected'); }
			if(obj_v['speed'] !== undefined) { jQuery('input[name="layer_speed"]').val(obj_v['speed']); }
			if(obj_v['split'] !== undefined) { jQuery('select[name="layer_split"] option[value="'+obj_v['split']+'"]').attr('selected', 'selected'); }
			if(obj_v['splitdelay'] !== undefined) { jQuery('input[name="layer_splitdelay"]').val(obj_v['splitdelay']); }
			


			if(obj_v['movex_reverse'] !== undefined && (obj_v['movex_reverse'] == 'true' || obj_v['movex_reverse'] == true)) { jQuery('input[name="layer_anim_xstart_reverse"]').attr('checked', true); }else{ jQuery('input[name="layer_anim_xstart_reverse"]').attr('checked', false); }
			if(obj_v['movey_reverse'] !== undefined && (obj_v['movey_reverse'] == 'true' || obj_v['movey_reverse'] == true)) { jQuery('input[name="layer_anim_ystart_reverse"]').attr('checked', true); }else{ jQuery('input[name="layer_anim_ystart_reverse"]').attr('checked', false); }
			if(obj_v['rotationx_reverse'] !== undefined && (obj_v['rotationx_reverse'] == 'true' || obj_v['rotationx_reverse'] == true)) { jQuery('input[name="layer_anim_xrotate_start_reverse"]').attr('checked', true); }else{ jQuery('input[name="layer_anim_xrotate_start_reverse"]').attr('checked', false); }
			if(obj_v['rotationy_reverse'] !== undefined && (obj_v['rotationy_reverse'] == 'true' || obj_v['rotationy_reverse'] == true)) { jQuery('input[name="layer_anim_yrotate_start_reverse"]').attr('checked', true); }else{ jQuery('input[name="layer_anim_yrotate_start_reverse"]').attr('checked', false); }
			if(obj_v['rotationz_reverse'] !== undefined && (obj_v['rotationz_reverse'] == 'true' || obj_v['rotationz_reverse'] == true)) { jQuery('input[name="layer_anim_zrotate_start_reverse"]').attr('checked', true); }else{ jQuery('input[name="layer_anim_zrotate_start_reverse"]').attr('checked', false); }
			if(obj_v['scalex_reverse'] !== undefined && (obj_v['scalex_reverse'] == 'true' || obj_v['scalex_reverse'] == true)) { jQuery('input[name="layer_scale_xstart_reverse"]').attr('checked', true); }else{ jQuery('input[name="layer_scale_xstart_reverse"]').attr('checked', false); }
			if(obj_v['scaley_reverse'] !== undefined && (obj_v['scaley_reverse'] == 'true' || obj_v['scaley_reverse'] == true)) { jQuery('input[name="layer_scale_ystart_reverse"]').attr('checked', true); }else{ jQuery('input[name="layer_scale_ystart_reverse"]').attr('checked', false); }
			if(obj_v['skewx_reverse'] !== undefined && (obj_v['skewx_reverse'] == 'true' || obj_v['skewx_reverse'] == true)) { jQuery('input[name="layer_skew_xstart_reverse"]').attr('checked', true); }else{ jQuery('input[name="layer_skew_xstart_reverse"]').attr('checked', false); }
			if(obj_v['skewy_reverse'] !== undefined && (obj_v['skewy_reverse'] == 'true' || obj_v['skewy_reverse'] == true)) { jQuery('input[name="layer_skew_ystart_reverse"]').attr('checked', true); }else{ jQuery('input[name="layer_skew_ystart_reverse"]').attr('checked', false); }
			if(obj_v['mask_x_reverse'] !== undefined && (obj_v['mask_x_reverse'] == 'true' || obj_v['mask_x_reverse'] == true)) { jQuery('input[name="mask_anim_xstart_reverse"]').attr('checked', true); }else{ jQuery('input[name="mask_anim_xstart_reverse"]').attr('checked', false); }
			if(obj_v['mask_y_reverse'] !== undefined && (obj_v['mask_y_reverse'] == 'true' || obj_v['mask_y_reverse'] == true)) { jQuery('input[name="mask_anim_ystart_reverse"]').attr('checked', true); }else{ jQuery('input[name="mask_anim_ystart_reverse"]').attr('checked', false); }
					
		}else{
			if(obj_v['movex'] !== undefined) { jQuery('input[name="layer_anim_xend"]').val(obj_v['movex']); }else{ jQuery('input[name="layer_anim_xend"]').val(0); }
			if(obj_v['movey'] !== undefined) { jQuery('input[name="layer_anim_yend"]').val(obj_v['movey']); }else{ jQuery('input[name="layer_anim_yend"]').val(0); }
			if(obj_v['movez'] !== undefined) { jQuery('input[name="layer_anim_zend"]').val(obj_v['movez']); }else{ jQuery('input[name="layer_anim_zend"]').val(0); }
			if(obj_v['rotationx'] !== undefined) { jQuery('input[name="layer_anim_xrotate_end"]').val(obj_v['rotationx']); }else{ jQuery('input[name="layer_anim_xrotate_end"]').val(0); }
			if(obj_v['rotationy'] !== undefined) { jQuery('input[name="layer_anim_yrotate_end"]').val(obj_v['rotationy']); }else{ jQuery('input[name="layer_anim_yrotate_end"]').val(0); }
			if(obj_v['rotationz'] !== undefined) { jQuery('input[name="layer_anim_zrotate_end"]').val(obj_v['rotationz']); }else{ jQuery('input[name="layer_anim_zrotate_end"]').val(0); }
			if(obj_v['scalex'] !== undefined) { jQuery('input[name="layer_scale_xend"]').val(obj_v['scalex']); }else{ jQuery('input[name="layer_scale_xend"]').val(0); }
			if(obj_v['scaley'] !== undefined) { jQuery('input[name="layer_scale_yend"]').val(obj_v['scaley']); }else{ jQuery('input[name="layer_scale_yend"]').val(0); }
			if(obj_v['skewx'] !== undefined) { jQuery('input[name="layer_skew_xend"]').val(obj_v['skewx']); }else{ jQuery('input[name="layer_skew_xend"]').val(0); }
			if(obj_v['skewy'] !== undefined) { jQuery('input[name="layer_skew_yend"]').val(obj_v['skewy']); }else{ jQuery('input[name="layer_skew_yend"]').val(0); }
			if(obj_v['captionopacity'] !== undefined) { jQuery('input[name="layer_opacity_end"]').val(obj_v['captionopacity']); }else{ jQuery('input[name="layer_opacity_end"]').val(0); }
			if(obj_v['mask'] !== undefined && (obj_v['mask'] == 'true' || obj_v['mask'] == true)) { jQuery('input[name="masking-end"]').attr('checked', true); }else{ jQuery('input[name="masking-end"]').attr('checked', false); }
			if(obj_v['mask_x'] !== undefined) { jQuery('input[name="mask_anim_xend"]').val(obj_v['mask_x']); }else{ jQuery('input[name="mask_anim_xend"]').val(0); }
			if(obj_v['mask_y'] !== undefined) { jQuery('input[name="mask_anim_yend"]').val(obj_v['mask_y']); }else{ jQuery('input[name="mask_anim_yend"]').val(0); }
			if(obj_v['mask_ease'] !== undefined) { jQuery('input[name="mask_easing_end"]').val(obj_v['mask_ease']); }else{ jQuery('input[name="mask_easing_end"]').val(0); }
			if(obj_v['mask_speed'] !== undefined) { jQuery('input[name="mask_speed_end"]').val(obj_v['mask_speed']); }else{ jQuery('input[name="mask_speed_end"]').val(0); }
			
			if(obj_v['easing'] !== undefined) { jQuery('select[name="layer_endeasing"] option[value="'+obj_v['easing']+'"]').attr('selected', 'selected'); }
			if(obj_v['speed'] !== undefined) { jQuery('input[name="layer_endspeed"]').val(obj_v['speed']); }
			if(obj_v['split'] !== undefined) { jQuery('select[name="layer_endsplit"] option[value="'+obj_v['split']+'"]').attr('selected', 'selected'); }
			if(obj_v['splitdelay'] !== undefined) { jQuery('input[name="layer_endsplitdelay"]').val(obj_v['splitdelay']); }
			
			if(obj_v['movex_reverse'] !== undefined && (obj_v['movex_reverse'] == 'true' || obj_v['movex_reverse'] == true)) { jQuery('input[name="layer_anim_xend_reverse"]').attr('checked', true); }else{ jQuery('input[name="layer_anim_xend_reverse"]').attr('checked', false); }
			if(obj_v['movey_reverse'] !== undefined && (obj_v['movey_reverse'] == 'true' || obj_v['movey_reverse'] == true)) { jQuery('input[name="layer_anim_yend_reverse"]').attr('checked', true); }else{ jQuery('input[name="layer_anim_yend_reverse"]').attr('checked', false); }
			if(obj_v['rotationx_reverse'] !== undefined && (obj_v['rotationx_reverse'] == 'true' || obj_v['rotationx_reverse'] == true)) { jQuery('input[name="layer_anim_xrotate_end_reverse"]').attr('checked', true); }else{ jQuery('input[name="layer_anim_xrotate_end_reverse"]').attr('checked', false); }
			if(obj_v['rotationy_reverse'] !== undefined && (obj_v['rotationy_reverse'] == 'true' || obj_v['rotationy_reverse'] == true)) { jQuery('input[name="layer_anim_yrotate_end_reverse"]').attr('checked', true); }else{ jQuery('input[name="layer_anim_yrotate_end_reverse"]').attr('checked', false); }
			if(obj_v['rotationz_reverse'] !== undefined && (obj_v['rotationz_reverse'] == 'true' || obj_v['rotationz_reverse'] == true)) { jQuery('input[name="layer_anim_zrotate_end_reverse"]').attr('checked', true); }else{ jQuery('input[name="layer_anim_zrotate_end_reverse"]').attr('checked', false); }
			if(obj_v['scalex_reverse'] !== undefined && (obj_v['scalex_reverse'] == 'true' || obj_v['scalex_reverse'] == true)) { jQuery('input[name="layer_scale_xend_reverse"]').attr('checked', true); }else{ jQuery('input[name="layer_scale_xend_reverse"]').attr('checked', false); }
			if(obj_v['scaley_reverse'] !== undefined && (obj_v['scaley_reverse'] == 'true' || obj_v['scaley_reverse'] == true)) { jQuery('input[name="layer_scale_yend_reverse"]').attr('checked', true); }else{ jQuery('input[name="layer_scale_yend_reverse"]').attr('checked', false); }
			if(obj_v['skewx_reverse'] !== undefined && (obj_v['skewx_reverse'] == 'true' || obj_v['skewx_reverse'] == true)) { jQuery('input[name="layer_skew_xend_reverse"]').attr('checked', true); }else{ jQuery('input[name="layer_skew_xend_reverse"]').attr('checked', false); }
			if(obj_v['skewy_reverse'] !== undefined && (obj_v['skewy_reverse'] == 'true' || obj_v['skewy_reverse'] == true)) { jQuery('input[name="layer_skew_yend_reverse"]').attr('checked', true); }else{ jQuery('input[name="layer_skew_yend_reverse"]').attr('checked', false); }
			if(obj_v['mask_x_reverse'] !== undefined && (obj_v['mask_x_reverse'] == 'true' || obj_v['mask_x_reverse'] == true)) { jQuery('input[name="mask_anim_xend_reverse"]').attr('checked', true); }else{ jQuery('input[name="mask_anim_xend_reverse"]').attr('checked', false); }
			if(obj_v['mask_y_reverse'] !== undefined && (obj_v['mask_y_reverse'] == 'true' || obj_v['mask_y_reverse'] == true)) { jQuery('input[name="mask_anim_yend_reverse"]').attr('checked', true); }else{ jQuery('input[name="mask_anim_yend_reverse"]').attr('checked', false); }
			
		}
	
		if(typeof(obj_v['mask']) !== 'undefined' && (obj_v['mask'] == 'true' || obj_v['mask'] == true)){
			jQuery('.mask-start-settings').show();
		}else{
			jQuery('.mask-start-settings').hide();
		}
		
		RevSliderSettings.onoffStatus(jQuery('input[name="masking-start"]'));
		RevSliderSettings.onoffStatus(jQuery('input[name="masking-end"]'));
		
		t.updateReverseList();
		
	}


	var checkMaskingAvailabity = function() {		
		if (jQuery('#layer__scalex').val()!=1 || jQuery('#layer__scaley').val()!=1 ||
			parseInt(jQuery('#layer__skewx').val(),0)!=0 || parseInt(jQuery('#layer__skewy').val(),0)!=0 ||
			parseInt(jQuery('#layer__xrotate').val(),0)!=0 || parseInt(jQuery('#layer__yrotate').val(),0)!=0 || parseInt(jQuery('#layer_2d_rotation').val(),0)!=0) {
				jQuery('.mask-not-available').show();
				jQuery('.mask-is-available').hide();
				jQuery('input[name="masking-start"]').removeAttr("checked");
				jQuery('input[name="masking-end"]').removeAttr("checked");
				jQuery('.mask-start-settings').hide();
				jQuery('.mask-end-settings').hide();
				jQuery('.tp-showmask').removeClass('tp-showmask');
				RevSliderSettings.onoffStatus(jQuery('input[name="masking-start"]'));
				RevSliderSettings.onoffStatus(jQuery('input[name="masking-end"]'));	
				u.rebuildLayerIdle(getjQueryLayer());
				
				t.updateLayerFromFields();

		} else {

			jQuery('.mask-not-available').hide();
			jQuery('.mask-is-available').show();
		}
		
	}

	/**
	 * check if anim handle already exists
	 */
	var checkIfAnimExists = function(handle){
		
		if(typeof initLayerAnims === 'object' && !jQuery.isEmptyObject(initLayerAnims)){
			for(var key in initLayerAnims){
				if(initLayerAnims[key]['handle'] == handle) return initLayerAnims[key]['id'];
			}
		}

		return false;

	}

	
	var checkIfAnimIsEditable = function(handle){
		
		if(typeof initLayerAnims === 'object' && !jQuery.isEmptyObject(initLayerAnims)){
			for(var key in initLayerAnims){
				if(initLayerAnims[key]['handle'] == handle) return initLayerAnims[key]['id'];
			}
		}

		return false;

	}
	
	
	/**
	 * update animation in database
	 */
	var deleteAnimInDb = function(handle){
		
		UniteAdminRev.setErrorMessageID("dialog_error_message");
		handle = jQuery.trim(handle);
		if(handle != ''){
			var animSelect = (currentAnimationType == 'customin') ? jQuery('#layer_animation option') : jQuery('#layer_endanimation option');

			UniteAdminRev.ajaxRequest("delete_custom_anim",handle,function(response){
				jQuery("#dialog_success_message").show().html(response.message);

				if(jQuery('#layer_animation option:selected') == handle || jQuery('#layer_animation option:selected') == handle.replace('customout', 'customin')){
					jQuery('#layer_animation option[value="tp-fade"]').attr('selected', true);
				}
				if(jQuery('#layer_endanimation option:selected') == handle || jQuery('#layer_endanimation option:selected') == handle.replace('customin', 'customout')){
					jQuery('#layer_endanimation option[value="tp-fade"]').attr('selected', true);
				}

				//update html select (got from response)
				t.updateInitLayerAnim(response.customfull);
				updateLayerAnimsInput(response.customin, 'customin');
				updateLayerAnimsInput(response.customout, 'customout');

			});
		}
	}


	/**
	 * rename animation in database
	 */
	var renameAnimInDb = function(id, new_name){
		
		var data = {};
		data['id'] = id;
		data['handle'] = new_name;

		UniteAdminRev.ajaxRequest("update_custom_anim_name",data,function(response){
			//update html select (got from response)
			t.updateInitLayerAnim(response.customfull);
			updateLayerAnimsInput(response.customin, 'customin');
			updateLayerAnimsInput(response.customout, 'customout');

			selectLayerAnim(new_name);
		});
	}


	/**
	 * update animation in database
	 */
	var updateAnimInDb = function(handle, animObj, id){
		
		UniteAdminRev.setErrorMessageID("dialog_error_message");
		animObj['handle'] = handle;

		if(id === false){ //create new
			//insert in database
			UniteAdminRev.ajaxRequest("insert_custom_anim",animObj,function(response){
				jQuery("#dialog_success_message").show().html(response.message);

				//update html select (got from response)
				t.updateInitLayerAnim(response.customfull);
				updateLayerAnimsInput(response.customin, 'customin');
				updateLayerAnimsInput(response.customout, 'customout');

				selectLayerAnim(handle);
			});

		}else{ //update existing

			//update to database
			UniteAdminRev.ajaxRequest("update_custom_anim",animObj,function(response){
				jQuery("#dialog_success_message").show().html(response.message);

				//update html select (got from response)
				t.updateInitLayerAnim(response.customfull);
				updateLayerAnimsInput(response.customin, 'customin');
				updateLayerAnimsInput(response.customout, 'customout');

				selectLayerAnim(handle);
			});
		}
	}

	/**
	 * update the layer animation inputs
	 */
	var selectLayerAnim = function(handle){
		
		var animSelect = (currentAnimationType == 'customin') ? jQuery('#layer_animation option') : jQuery('#layer_endanimation option');
		animSelect.each(function(){
			if(jQuery(this).text() == handle || jQuery(this).val() == handle)
				jQuery(this).prop('selected', true);
			else
				jQuery(this).prop('selected', false);
		});
		animSelect.change();		
	}

	/**
	 * update the layer animation inputs
	 */
	var updateLayerAnimsInput = function(customAnim, type){
		
		if(type == 'customin'){
			var animSelect = jQuery('#layer_animation');
			var animOption = jQuery('#layer_animation option');
			var current = jQuery('#layer_animation option:selected').val();
		}else{
			var animSelect = jQuery('#layer_endanimation');
			var animOption = jQuery('#layer_endanimation option');
			var current = jQuery('#layer_endanimation option:selected').val();
		}

		animOption.each(function(){
			if(jQuery(this).val().indexOf(type) > -1){
				jQuery(this).remove();
			}
		});

		if(typeof customAnim === 'object' && !jQuery.isEmptyObject(customAnim)){
			for(key in customAnim){
				animSelect.append(new Option(customAnim[key], key));
			}
		}
		animSelect.val(current);
		animSelect.change();
	}

	



	/**
	 * get the first style from the styles list (from autocomplete)
	 */
	var getFirstStyle = function(){
		
		var arrClasses = jQuery( "#layer_caption" ).catcomplete("option","source");
		var firstStyle = "";

		if(arrClasses == null || arrClasses.length == 0)
			return("");
		
		var firstStyle = arrClasses[0]['label'];
		return(firstStyle);
	}


	/**
	 * clear layer html fields, and disable buttons
	 */
	var disableFormFields = function(){
		

		//clear html form
		jQuery(".form_layers")[0].reset();
		jQuery(".form_layers input, .form_layers select, .form_layers textarea").attr("disabled", "disabled").addClass("setting-disabled");

		jQuery("#button_delete_layer").addClass("button-disabled");
		jQuery("#button_duplicate_layer").addClass("button-disabled");

		jQuery(".form_layers label, .form_layers .setting_text, .form_layers .setting_unit").addClass("text-disabled");

		jQuery("#layer_captions_down").removeClass("ui-state-active").addClass("ui-state-default");
		jQuery("#font_family_down").removeClass("ui-state-active").addClass("ui-state-default");

		
		jQuery("#linkInsertTemplate").addClass("disabled");


		jQuery("#rs-align-wrapper").addClass("table_disabled");
		jQuery("#rs-align-wrapper-ver").addClass("table_disabled");

		if(!jQuery('#preview_looper').hasClass("deactivated")) jQuery('#preview_looper').click();

		layerGeneralParamsStatus = false;
	}

	/**
	 * enable buttons and form fields.
	 */
	var enableFormFields = function(){
		
		jQuery(".form_layers input, .form_layers select, .form_layers textarea").not(".rs_disabled_field").removeAttr("disabled").removeClass("setting-disabled");

		jQuery("#button_delete_layer").removeClass("button-disabled");
		jQuery("#button_duplicate_layer").removeClass("button-disabled");

		jQuery(".form_layers label, .form_layers .setting_text, .form_layers .setting_unit").removeClass("text-disabled");

		jQuery("#layer_captions_down").removeClass("ui-state-default").addClass("ui-state-active");
		jQuery("#font_family_down").removeClass("ui-state-default").addClass("ui-state-active");

		
		jQuery("#linkInsertTemplate").removeClass("disabled");

		jQuery("#rs-align-wrapper").removeClass("table_disabled");
		jQuery("#rs-align-wrapper-ver").removeClass("table_disabled");

		if(jQuery('#preview_looper').hasClass("deactivated")) jQuery('#preview_looper').click();

		layerGeneralParamsStatus = true;
	}




	/**
	 * get layers array
	 */
	t.getLayers = function(){
		
		if(selectedLayerSerial != -1){
			t.updateLayerFromFields();
		}
		//update sizes in images
		updateLayersImageSizes();
		return(arrLayers);
	}
	
	
	/**
	 * get only layers array
	 */
	t.getSimpleLayers = function(){
		return(arrLayers);
	}


	/**
	 * update image sizes
	 */
	var updateLayersImageSizes = function(){
		

		for (serial in arrLayers){
			var layer = arrLayers[serial];
			if(layer.type == "image"){
				var htmlLayer = t.getHtmlLayerFromSerial(serial);
				var objUpdate = {};

				objUpdate = t.setVal(objUpdate, 'width', htmlLayer.width());
				objUpdate = t.setVal(objUpdate, 'height', htmlLayer.height());
				t.updateLayer(serial,objUpdate);
			}
		}
	}

	/*! LAYER EVENTS */

	/**
	 * refresh layer events
	 */
	var refreshEvents = function(serial){
		var layer = t.getHtmlLayerFromSerial(serial);

		var grid_size = jQuery('#rs-grid-sizes option:selected').val();

		//update layer events.
		layer.draggable({
					start:onLayerDragStart,
					drag: onLayerDrag,				//set ondrag event
					cancel:"#layer-short-toolbar, textbox, #layer_text, .layer_on_lock",
					grid: [grid_size,grid_size],
					stop: onLayerDragEnd	
				});

		layer.click(function(event){


			//if (!u.isLayerLocked(serial)) {
				
				t.setLayerSelected(serial);
				event.stopPropagation();
				// IF ANIMATION TAB IS VISIBLE, AND PLAY IS SELECTED, WE CAN ALLOW TO ANIMATE THE SINGLE LAYER
				if (u.checkAnimationTab()) {
						u.stopAllLayerAnimation();
						u.animateCurrentSelectedLayer(0);
				}
				else
				if (u.checkLoopTab()) {
					u.stopAllLayerAnimation();
					u.callCaptionLoops();
				}


			//}
		});



	}




	/**
	 * get layer serial from id
	 */
	t.getSerialFromID = function(layerID){
		
		if (layerID == undefined) return false;
		var layerSerial = layerID.replace("slide_layer_","").replace("demo_layer_","");
		return(layerSerial);
	}

	/**
	 * get serial from sortID
	 */
	t.getSerialFromSortID = function(sortID){
		

		var layerSerial = sortID.replace("layer_sort_time_","");
		
		layerSerial = layerSerial.replace("layer_sort_","");
		layerSerial = layerSerial.replace("layer_quicksort_","");
		return(layerSerial);
	}


	/**
	 * hide in html and sortbox
	 */
	t.lockAllLayers = function(serial){

		for (serial in arrLayers) 			
			u.lockLayer(serial);		
	}


	/**
	 * show layer in html and sortbox
	 */
	t.unlockAllLayers = function(serial){

		for (serial in arrLayers)
			u.unlockLayer(serial);
	}

	/**
	 * show all layers
	 */
	t.showAllLayers = function(){
		for (serial in arrLayers)
			u.showLayer(serial,true);
	}

	/**
	 * hide all layers
	 */
	t.hideAllLayers = function(){
		for (serial in arrLayers)
			u.hideLayer(serial,true);
	}


	/**
	 * get html layer from serial
	 */
	t.getHtmlLayerFromSerial = function(serial, isDemo){
		
		if(!isDemo)
			isDemo = false;

		if(!isDemo)
			var htmlLayer = jQuery("#slide_layer_"+serial);
		else
			var htmlLayer = jQuery("#demo_layer_"+serial);

		if(htmlLayer.length == 0)
			UniteAdminRev.showErrorMessage("Html Layer with serial: "+serial+" not found!");

		return(htmlLayer);
	}

	
	/**
	 * get layer object by the new Unique Id
	 */
	t.getLayerByUniqueId = function(uid){
		for(var key in arrLayers){
			if(arrLayers[key]['unique_id'] == uid) return t.getLayer(key);
		}
		
		return false;
	}
	
	/**
	 * get layer object by the new Unique Id
	 */
	t.getLayerIdByUniqueId = function(uid){
		for(var key in arrLayers){
			if(arrLayers[key]['unique_id'] == uid) return key;
		}
		
		return false;
	}
	
	
	
	/**
	 * get layer object by id
	 */
	t.getLayer = function(serial, isDemo){
		
		if(isDemo){
			var layer = arrLayersDemo[serial];
		}else{
			var layer = arrLayers[serial];
		}
		if(!layer){ //check if still maybe demo layer
			var layer = arrLayersDemo[serial];
		}
		
		if(!layer){
			return false;
			UniteAdminRev.showErrorMessage("getLayer error, Layer with serial: "+serial+" not found");
		}else{
			//modify some data
			layer.speed = Number(layer.speed);
			layer.endspeed = Number(layer.endspeed);
			return layer;
		}
		return false;
	}

	/**
	 * get current layer object
	 */
	t.getCurrentLayer = function(){
		if(selectedLayerSerial == -1){
			return false;
			UniteAdminRev.showErrorMessage(rev_lang.sel_layer_not_set);
			return(null);
		}
		return t.getLayer(selectedLayerSerial);
	}



	/*! MAKE HTML LAYER */

	/**
	 * make layer html, with params from the object
	 */
	t.makeLayerHtml = function(serial,objLayer,isDemo){
		
		if(!isDemo)
			isDemo = false;

		var type = "text";
		if(objLayer.type)
			type = objLayer.type;


		var zIndex = Number(objLayer.order)+1;

		var style = "z-index:"+zIndex+";position:absolute;";
		var stylerot ="";
		
		
		if(t.getVal(objLayer, 'max_width') !== 'auto')
			style += ' width: '+t.getVal(objLayer, 'max_width')+';';

		
		if(t.getVal(objLayer, 'max_height') !== 'auto') 
			style += ' height: '+t.getVal(objLayer, 'max_height')+';';

		//if(objLayer.whitespace !== 'normal')
			style += ' white-space: '+t.getVal(objLayer, 'whitespace')+';';

		var static_class = '';
		
		if(typeof objLayer.special_type !== 'undefined' && objLayer.special_type == 'static') static_class = ' static_layer';
		
		var internal_class = '';
		if(typeof objLayer.type !== 'undefined' && (objLayer.type == 'button' || objLayer.type == 'shape')) internal_class = ' '+objLayer.internal_class; // || objLayer.type == 'no_edit' 
		

		if(type == "image") style += "line-height:0;";

		if(!isDemo){
			var html = '<div id="slide_layer_' + serial + '" style="' + style + '" class="slide_layer"><div style="'+stylerot+'" class="innerslide_layer tp-caption '+objLayer.style+static_class+internal_class+'" >';
		}else{
			if(rev_adv_resp_sizes === true){

			}
			if(objLayer['static_styles'] != undefined){
				style += ' font-size: '+t.getVal(objLayer['static_styles'],'font-size')+';';
				style += ' line-height: '+t.getVal(objLayer['static_styles'],'line-height')+';';
				style += ' font-weight: '+t.getVal(objLayer['static_styles'],'font-weight')+';';
				style += ' color: '+t.getVal(objLayer['static_styles'],'color')+';';
			}
			var html = '<div id="demo_layer_' + serial + '" style="' + style + ' display: none;" class="demo_layer demo_layer_'+curDemoSlideID+' slide_layer" ><div class="innerslide_layer tp-caption '+objLayer.style+static_class+internal_class+'" >';
		}

		//add layer specific html
		switch(type){
			case "image":
				var addStyle = '';
				if(t.getVal(objLayer,'scaleX') != "") addStyle += "width: " + t.getVal(objLayer,'scaleX') + "px; ";
				if(t.getVal(objLayer,'scaleY') != "") addStyle += "height: " + t.getVal(objLayer,'scaleY') + "px;";
				
				html += '<img src="'+objLayer.image_url+'" alt="'+objLayer.alt+'" style="'+addStyle+'"></img>';
			break;
			default:
			case "text":
			case "button":
			//case 'no_edit':
				html += objLayer.text;
			break;
			case "video":				
				//var styleVideo = "width:"+parseInt(t.getVal(objLayer, 'video_width'),0)+"px;height:"+parseInt(t.getVal(objLayer, 'video_height'),0)+"px;";
				var styleVideo = "width:100%;height:100%";
				if(typeof (objLayer.video_data) !== "undefined"){
					var useImage = (jQuery.trim(objLayer.video_data.previewimage) != '') ? objLayer.video_data.previewimage : objLayer.video_image_url;
				}else{
					var useImage = objLayer.video_image_url;
				}
				
				var videoIcon = objLayer.video_type;
				
				switch(objLayer.video_type){
					case "youtube":
					case "vimeo":
						styleVideo += ";background-image:url("+useImage+");";
					break;
					case "html5":
						if(useImage !== undefined && useImage != "")
							styleVideo += ";background-image:url("+useImage+");";
					break;
					case 'streamyoutube':
						videoIcon = 'youtube';
					break;
					case 'streamvimeo':
						videoIcon = 'vimeo';
					break;
					case 'streaminstagram':
						videoIcon = 'html5';
					break;
				}
				
				html += "<div class='slide_layer_video' style='"+styleVideo+"'><div class='video-layer-inner video-icon-"+videoIcon+"'>"
				html += "<div class='layer-video-title'>" + objLayer.video_title + "</div>";
				html += "</div></div>";
			break;
		}

		html +="</div>";

		//add cross icon:
		html += "<div class='icon_cross'></div>";
		html += '</div>';
		return(html);
	}


	/**
	 * Reset values that can be set without changing the class to default
	 */
	t.reset_to_default_static_styles = function(layer, exclude, devices){
		
		if(layer.style !== undefined){
			//get css styles from choosen class
			var foundstyles = UniteCssEditorRev.getStyleSettingsByHandle(layer.style);

			if(foundstyles !== false){

				if(foundstyles.params['font-size'] !== undefined){
					if(exclude !== undefined && devices !== undefined){
						if(jQuery.inArray('font-size', exclude) !== -1){
							//set for specific devices now
							layer['static_styles'] = t.setVal(layer['static_styles'], 'font-size', foundstyles.params['font-size'], false, devices);
							
							if(jQuery.inArray(layout, devices) !== -1){
								jQuery('#layer_font_size_s').val(foundstyles.params['font-size']);
							}
						}
					}else{
						jQuery('#layer_font_size_s').val(foundstyles.params['font-size']);
					}
				}
				
				if(foundstyles.params['line-height'] !== undefined){
					if(exclude !== undefined && devices !== undefined){
						if(jQuery.inArray('line-height', exclude) !== -1){
							//set for specific devices now
							layer['static_styles'] = t.setVal(layer['static_styles'], 'line-height', foundstyles.params['line-height'], false, devices);
							
							if(jQuery.inArray(layout, devices) !== -1){
								jQuery('#layer_line_height_s').val(foundstyles.params['line-height']);
							}
						}
					}else{
						jQuery('#layer_line_height_s').val(foundstyles.params['line-height']);
					}
				}
				
				if(foundstyles.params['font-weight'] !== undefined){
					if(exclude !== undefined && devices !== undefined){
						if(jQuery.inArray('font-weight', exclude) !== -1){
							//set for specific devices now
							layer['static_styles'] = t.setVal(layer['static_styles'], 'font-weight', foundstyles.params['font-weight'], false, devices);
							
							if(jQuery.inArray(layout, devices) !== -1){
								jQuery('#layer_font_weight_s option[value="'+foundstyles.params['font-weight']+'"]').attr('selected', true);
							}
						}
					}else{
						jQuery('#layer_font_weight_s option[value="'+foundstyles.params['font-weight']+'"]').attr('selected', true);
					}
				}
				
				if(foundstyles.params['color'] !== undefined){
					if(exclude !== undefined && devices !== undefined){
						if(jQuery.inArray('color', exclude) !== -1){
							//set for specific devices now
							layer['static_styles'] = t.setVal(layer['static_styles'], 'color', UniteAdminRev.rgb2hex(foundstyles.params['color']), false, devices);
						}
						if(jQuery.inArray(layout, devices) !== -1){
							jQuery('#layer_color_s').val(UniteAdminRev.rgb2hex(foundstyles.params['color']));
							//trigger color change on the colorpicker
							jQuery('.wp-color-result').each(function(){
								jQuery(this).css('backgroundColor', jQuery(this).parent().find('.my-color-field').val());
							});
						}
					}else{
						jQuery('#layer_color_s').val(UniteAdminRev.rgb2hex(foundstyles.params['color']));
						//trigger color change on the colorpicker
						jQuery('.wp-color-result').each(function(){
							jQuery(this).css('backgroundColor', jQuery(this).parent().find('.my-color-field').val());
						});
					}
				}
			}

		}
	}


	/**
	 * update layer by data object
	 */
	t.updateLayer = function(serial,objData,del_certain){
		
		var layer = t.getLayer(serial);
		if(!layer){
			return(false);
		}

		var do_reset_static = false;

		if(objData.style !== undefined && objData.style !== layer['style'] && jQuery('#dialog-change-style-from-css').css('display') == 'none'){ //check if dialog is open, if yes then do not change static
			//update values that can be set without changing the class
			do_reset_static = true;
		}
		
		
		if(del_certain !== undefined){
			for(var key in del_certain){
				delete layer[del_certain[key]];
			}
		}
		
		
		for(var key in objData){
			if(typeof(objData[key]) === 'object'){
				for(var okey in objData[key]){
					if(typeof(layer[key]) === 'object'){
						if(typeof(layer[key][okey]) === 'object'){
							for(var mk in objData[key][okey]){
								layer[key][okey][mk] = objData[key][okey][mk];
							}
						}else{
							layer[key][okey] = objData[key][okey];
						}
					}else{
						layer[key] = {};
						layer[key][okey] = objData[key][okey];
					}
				}
			}else{
				layer[key] = objData[key];
			}
		}
		
		if(do_reset_static){
			t.reset_to_default_static_styles(layer);
			// Reset Fields from Style Template
			updateSubStyleParameters(layer);
		}

		if(!arrLayers[serial]){
			UniteAdminRev.showErrorMessage("setLayer error, Layer with ID: "+serial+" not found");
			return(false);
		}

		arrLayers[serial] = jQuery.extend({},layer);
		
		t.updateReverseList();
		
	}
	
	

	t.updateReverseList = function() {
		clearTimeout(updateRevTimer);
		updateRevTimer =  setTimeout(function() {
			RevSliderSettings.onoffStatus(jQuery('input[name="layer_anim_xstart_reverse"]'));
			RevSliderSettings.onoffStatus(jQuery('input[name="layer_anim_ystart_reverse"]'));
			RevSliderSettings.onoffStatus(jQuery('input[name="layer_anim_xrotate_reverse"]'));
			RevSliderSettings.onoffStatus(jQuery('input[name="layer_anim_yrotate_reverse"]'));
			RevSliderSettings.onoffStatus(jQuery('input[name="layer_anim_zrotate_reverse"]'));
			RevSliderSettings.onoffStatus(jQuery('input[name="layer_scale_xstart_reverse"]'));
			RevSliderSettings.onoffStatus(jQuery('input[name="layer_scale_ystart_reverse"]'));
			RevSliderSettings.onoffStatus(jQuery('input[name="layer_skew_xstart_reverse"]'));
			RevSliderSettings.onoffStatus(jQuery('input[name="layer_skew_ystart_reverse"]'));
			RevSliderSettings.onoffStatus(jQuery('input[name="mask_anim_xstart_reverse"]'));
			RevSliderSettings.onoffStatus(jQuery('input[name="mask_anim_ystart_reverse"]'));

			RevSliderSettings.onoffStatus(jQuery('input[name="layer_anim_xend_reverse"]'));
			RevSliderSettings.onoffStatus(jQuery('input[name="layer_anim_yend_reverse"]'));
			RevSliderSettings.onoffStatus(jQuery('input[name="layer_anim_xrotate_end_reverse"]'));
			RevSliderSettings.onoffStatus(jQuery('input[name="layer_anim_yrotate_end_reverse"]'));
			RevSliderSettings.onoffStatus(jQuery('input[name="layer_anim_zrotate_end_reverse"]'));
			RevSliderSettings.onoffStatus(jQuery('input[name="layer_scale_xend_reverse"]'));
			RevSliderSettings.onoffStatus(jQuery('input[name="layer_scale_yend_reverse"]'));
			RevSliderSettings.onoffStatus(jQuery('input[name="layer_skew_xend_reverse"]'));
			RevSliderSettings.onoffStatus(jQuery('input[name="layer_skew_yend_reverse"]'));
			RevSliderSettings.onoffStatus(jQuery('input[name="mask_anim_xend_reverse"]'));
			RevSliderSettings.onoffStatus(jQuery('input[name="mask_anim_yend_reverse"]'));
		},100);
	}

	/**
	 * update current layer
	 */
	var updateCurrentLayer = function(objData,del_certain){
		
		if(!arrLayers[selectedLayerSerial]){
			UniteAdminRev.showErrorMessage("error! the layer with serial: "+selectedLayerSerial+" don't exists");
			return(false);
		}

		t.updateLayer(selectedLayerSerial,objData,del_certain);

	}


	/**
	 * add image layer
	 */
	var addLayerImage = function(imgobj, special_type){
		
		objLayer = {
			style: "",
			text: "Image " + (id_counter+1),
			type: "image",
			image_url: imgobj.imgurl
		};
		
		objLayer = t.setVal(objLayer, 'scaleX', imgobj.imgwidth, true);
		objLayer = t.setVal(objLayer, 'scaleY', imgobj.imgheight, true);

		objLayer = t.setVal(objLayer, 'originalWidth', imgobj.imgwidth, true);
		objLayer = t.setVal(objLayer, 'originalHeight', imgobj.imgheight, true);
		
		if(typeof special_type !== 'undefined')
			objLayer['special_type'] = special_type;

		addLayer(objLayer);

		//KRIKI jQuery('#layer_text_wrapper').show();
	}


	/**
	 * get video layer object from video data
	 */
	var getVideoObjLayer = function(videoData, adding){
		
		var objLayer = {
				type:"video",
				style : "",
				video_type: videoData.video_type,
				video_data:videoData
			};
			
		objLayer.video_data.autoplayonlyfirsttime = false; //needed for v5 as prior to v4, this was existing and is now in autoplay
		
		if(typeof(adding) !== 'undefined'){
			objLayer.video_width = videoData.video_width;
			objLayer.video_height = videoData.video_height;
		}

		if(objLayer.video_type == "youtube" || objLayer.video_type == "vimeo"){
			objLayer.video_id = videoData.id;
			objLayer.video_title = videoData.title;
			objLayer.video_image_url = videoData.thumb_medium.url;
			objLayer.video_args = videoData.args;
		}

		//set sortbox text
		switch(objLayer.video_type){
			case "youtube":
				objLayer.text = "Youtube: " + videoData.title;
			break;
			case "vimeo":
				objLayer.text = "Vimeo: " + videoData.title;
			break;
			case "streamyoutube":
				objLayer.text = "YouTube Stream";
				objLayer.video_title = objLayer.text;
				objLayer.video_image_url = "";
				if(videoData.urlPoster != "")
					objLayer.video_image_url = videoData.urlPoster;
			break;
			case "streamvimeo":
				objLayer.text = "Vimeo Stream";
				objLayer.video_title = objLayer.text;
				objLayer.video_image_url = "";
				if(videoData.urlPoster != "")
					objLayer.video_image_url = videoData.urlPoster;
			break;
			case "streaminstagram":
				objLayer.text = "Instagram Stream";
				objLayer.video_title = objLayer.text;
				objLayer.video_image_url = "";
				if(videoData.urlPoster != "")
					objLayer.video_image_url = videoData.urlPoster;
			break;
			case "html5":
				objLayer.text = "Html5 Video";
				objLayer.video_title = objLayer.text;
				objLayer.video_image_url = "";

				if(videoData.urlPoster != "")
					objLayer.video_image_url = videoData.urlPoster;

			break;
		}
		return(objLayer);
	}


	/**
	 * add video layer
	 */
	var addLayerVideo = function(videoData, special_type){
		
		var objLayer = getVideoObjLayer(videoData, true);

		if(typeof special_type !== 'undefined')
			objLayer['special_type'] = special_type;

		addLayer(objLayer);
	}


	/**
	 * add text layer
	 */
	var addLayerText = function(special_type){
		
		var objLayer = {
			text:initText + (id_counter+1),
			type:"text"
		};

		if(typeof special_type !== 'undefined')
			objLayer['special_type'] = special_type;

		addLayer(objLayer);

		setTimeout(function() {
				jQuery('#layer_text_wrapper').appendTo(jQuery('.layer_selected.slide_layer'));																	
				t.showHideContentEditor(true);				
				jQuery('#layer_text').data('new_content',true);
				jQuery('#layer_text').focus();						
			},50);
	}


	/*! ADD LAYER */
	/**
	 * add layer
	 */
	///////////////////////////
	// ADD ONE SINGLE LAYER  //
	///////////////////////////
	var addLayer = function(objLayer, isInit, isDemo){
		
		isInit = isInit || false;
		isDemo = isDemo || false;
		
		var do_style_reset = false;
		
		if(objLayer.subtype == undefined)
			objLayer.subtype = '';
		
		if(objLayer.specialsettings == undefined)
			objLayer.specialsettings = {};
		
		if(objLayer.order == undefined)
			objLayer.order = (id_counter);
		
		objLayer.order = Number(objLayer.order);
		
		if(isInit == false && !isDemo){ //add unique layer ID only if not init and not demo
			unique_layer_id++;
			objLayer.unique_id = unique_layer_id;
		}
		
		//set init position
		if(objLayer.type == "video"){
			objLayer = t.getVal(objLayer, 'left') == undefined ? t.setVal(objLayer, 'left', initLeftVideo, true) : typeof(objLayer.left) !== 'object' ? t.setVal(objLayer, 'left', objLayer.left, true) : objLayer;
			objLayer = t.getVal(objLayer, 'top') == undefined ? t.setVal(objLayer, 'top', initTopVideo, true) : typeof(objLayer.top) !== 'object' ? t.setVal(objLayer, 'top', objLayer.top, true) : objLayer;			
			objLayer = checkUpdateFullwidthVideo(objLayer);
		}else{			
			objLayer = t.getVal(objLayer, 'left') == undefined ? t.setVal(objLayer, 'left', initLeft, true) : typeof(objLayer.left) !== 'object' ? t.setVal(objLayer, 'left', objLayer.left, true) : objLayer;
			objLayer = t.getVal(objLayer, 'top') == undefined ? t.setVal(objLayer, 'top', initTop, true) : typeof(objLayer.top) !== 'object' ? t.setVal(objLayer, 'top', objLayer.top, true) : objLayer;
		}
		
		/*if(objLayer.type == 'no_edit'){
			
		}*/
		
		if(objLayer['layer_action'] !== undefined){ //check for each action if layer is set to self, if yes, change it to objLayer.unique_id
			if(objLayer['layer_action'].layer_target !== undefined){
				for(var key in objLayer['layer_action'].layer_target){
					if(objLayer['layer_action'].layer_target[key] == 'self')
						objLayer['layer_action'].layer_target[key] = objLayer.unique_id;
				}
			}
		}
		
		objLayer.internal_class = objLayer.internal_class || '';
		
		// Enabled Hover ?		
		objLayer['hover'] = objLayer['hover'] || false;

		objLayer['alias'] = objLayer['alias'] || u.getSortboxText(objLayer.text).toLowerCase();

		//set Loop Animations
		objLayer.loop_animation = objLayer.loop_animation 	|| "disabled";//jQuery("#layer_loop_animation option:selected").val();
		objLayer.loop_easing = objLayer.loop_easing 		|| "linearEaseNone";//jQuery("#layer_loop_easing").val();
		objLayer.loop_speed = objLayer.loop_speed 			|| 2;//jQuery("#layer_loop_speed").val();
		objLayer.loop_startdeg = objLayer.loop_startdeg 	|| -20;//jQuery("#layer_loop_startdeg").val();
		objLayer.loop_enddeg = objLayer.loop_enddeg 		|| 20;//jQuery("#layer_loop_enddeg").val();
		objLayer.loop_xorigin = objLayer.loop_xorigin 		|| 50;//jQuery("#layer_loop_xorigin").val();
		objLayer.loop_yorigin = objLayer.loop_yorigin 		|| 50;//jQuery("#layer_loop_yorigin").val();
		objLayer.loop_xstart = objLayer.loop_xstart 		|| 0;//jQuery("#layer_loop_xstart").val();
		objLayer.loop_xend = objLayer.loop_xend 			|| 0;//jQuery("#layer_loop_xend").val();
		objLayer.loop_ystart = objLayer.loop_ystart 		|| 0;//jQuery("#layer_loop_ystart").val();
		objLayer.loop_yend = objLayer.loop_yend 			|| 0;//jQuery("#layer_loop_yend").val();
		objLayer.loop_zoomstart = objLayer.loop_zoomstart 	|| 1;//jQuery("#layer_loop_zoomstart").val();
		objLayer.loop_zoomend = objLayer.loop_zoomend 		|| 1;//jQuery("#layer_loop_zoomend").val();
		objLayer.loop_angle = objLayer.loop_angle 			|| 0;//jQuery("#layer_loop_angle").val();
		objLayer.loop_radius = objLayer.loop_radius 		|| 10;//jQuery("#layer_loop_radius").val();
		
		// set Mask Animation
		objLayer.mask_start = objLayer.mask_start	 		|| false;//jQuery('input[name="masking-start"]').is(':checked');
		objLayer.mask_end = objLayer.mask_end		 		|| false;//jQuery('input[name="masking-end"]').is(':checked');
		

		// Set Reverse Basics					
		objLayer.x_start_reverse = objLayer.x_start_reverse || false;
		objLayer.y_start_reverse = objLayer.y_start_reverse || false;
		objLayer.x_end_reverse = objLayer.x_end_reverse || false;
		objLayer.y_end_reverse = objLayer.y_end_reverse || false;
		objLayer.x_rotate_start_reverse = objLayer.x_rotate_start_reverse || false;
		objLayer.y_rotate_start_reverse = objLayer.y_rotate_start_reverse || false;
		objLayer.z_rotate_start_reverse = objLayer.z_rotate_start_reverse || false;
		objLayer.x_rotate_end_reverse = objLayer.x_rotate_end_reverse || false;
		objLayer.y_rotate_end_reverse = objLayer.y_rotate_end_reverse || false;
		objLayer.z_rotate_end_reverse = objLayer.z_rotate_end_reverse || false;
		objLayer.scale_x_start_reverse = objLayer.scale_x_start_reverse || false;
		objLayer.scale_y_start_reverse = objLayer.scale_y_start_reverse || false;
		objLayer.scale_x_end_reverse = objLayer.scale_x_end_reverse || false;
		objLayer.scale_y_end_reverse = objLayer.scale_y_end_reverse || false;
		objLayer.skew_x_start_reverse = objLayer.skew_x_start_reverse || false;
		objLayer.skew_y_start_reverse = objLayer.skew_y_start_reverse || false;
		objLayer.skew_x_end_reverse = objLayer.skew_x_end_reverse || false;
		objLayer.skew_y_end_reverse = objLayer.skew_y_end_reverse || false;
		objLayer.mask_x_start_reverse = objLayer.mask_x_start_reverse || false;
		objLayer.mask_y_start_reverse = objLayer.mask_y_start_reverse || false;
		objLayer.mask_x_end_reverse = objLayer.mask_x_end_reverse || false;
		objLayer.mask_y_end_reverse = objLayer.mask_y_end_reverse || false;


		objLayer.mask_x_start = objLayer.mask_x_start 			|| 0;//jQuery("#mask_anim_xstart").val();
		objLayer.mask_y_start = objLayer.mask_y_start 			|| 0;//jQuery("#mask_anim_ystart").val();
		objLayer.mask_speed_start = objLayer.mask_speed_start 	|| "inherit";//jQuery("#mask_speed").val();
		objLayer.mask_ease_start = objLayer.mask_ease_start 	|| "inherit";//jQuery("#mask_easing").val();
		
		objLayer.mask_x_end = objLayer.mask_x_end 			|| 0;//jQuery("#mask_anim_xend").val();
		objLayer.mask_y_end = objLayer.mask_y_end 			|| 0;//jQuery("#mask_anim_yend").val();
		objLayer.mask_speed_end = objLayer.mask_speed_end 	|| "inherit";//jQuery("#mask_speed_end").val();
		objLayer.mask_ease_end = objLayer.mask_ease_end 	|| "inherit";//jQuery("#mask_easing_end").val();
		
		objLayer.alt_option = objLayer.alt_option			|| 'media_library';
		objLayer.alt = objLayer.alt							|| '';
		
		//set animation:		
		objLayer.animation = objLayer.animation || 'tp-fade';

		//set easing:
		objLayer.easing = objLayer.easing || "Power2.easeInOut";
		objLayer.split = objLayer.split || "none";
		objLayer.endsplit = objLayer.endsplit || "none";
		objLayer.splitdelay = objLayer.splitdelay || 10;
		objLayer.endsplitdelay = objLayer.endsplitdelay || 10;
		
		objLayer = t.getVal(objLayer, 'max_height') == undefined ? 
			t.setVal(objLayer, 'max_height', jQuery("#layer_max_height").val(), true) : 
			typeof(objLayer.max_height) !== 'object' ? 
				t.setVal(objLayer, 'max_height', objLayer.max_height, true) : 
				objLayer;
				
		if(t.getVal(objLayer, 'max_width') == undefined){
			objLayer = t.setVal(objLayer, 'max_width', jQuery("#layer_max_width").val(), true);
		}else{
			if(typeof(objLayer.max_width) !== 'object'){
				objLayer = t.setVal(objLayer, 'max_width', objLayer.max_width, true);
			}
		}
		
		if(objLayer.type == 'video' && typeof(objLayer.video_width) == 'undefined' && typeof(objLayer.video_data.width) !== 'undefined') objLayer.video_width = objLayer.video_data.width; //fallback to RS video prior 5.0
		if(t.getVal(objLayer, 'video_width') == undefined){
			objLayer = t.setVal(objLayer, 'video_width', 480, true);
		}else{
			if(typeof(objLayer.video_width) !== 'object'){
				objLayer = t.setVal(objLayer, 'video_width', objLayer.video_width, true);
			}
		}
		
		if(objLayer.type == 'video' && typeof(objLayer.video_height) == 'undefined' && typeof(objLayer.video_data.height) !== 'undefined') objLayer.video_height = objLayer.video_data.height; //fallback to RS video prior 5.0
		if(t.getVal(objLayer, 'video_height') == undefined){
			objLayer = t.setVal(objLayer, 'video_height', 360, true);
		}else{
			if(typeof(objLayer.video_height) !== 'object'){
				objLayer = t.setVal(objLayer, 'video_height', objLayer.video_height, true);
			}
		}

		if(objLayer['2d_rotation'] == undefined && isInit)
			objLayer['2d_rotation'] = "inherit";

		if(objLayer['2d_origin_x'] == undefined)
			objLayer['2d_origin_x'] = "inherit";

		if(objLayer['2d_origin_y'] == undefined)
			objLayer['2d_origin_y'] = "inherit";

		if(t.getVal(objLayer, 'whitespace') == undefined){
			objLayer = t.setVal(objLayer, 'whitespace', jQuery("#layer_whitespace option:selected").val(), true);
		}else{
			if(typeof(objLayer.whitespace) !== 'object'){
				objLayer = t.setVal(objLayer, 'whitespace', objLayer.whitespace, true);
			}
		}

		if(objLayer.static_start == undefined)
			objLayer.static_start = jQuery("#layer_static_start option:selected").val();

		if(objLayer.static_end == undefined)
			objLayer.static_end = 'last'; //jQuery("#layer_static_end option:selected").val();

		//set speed:
		if(objLayer.speed == undefined)
			objLayer.speed = initSpeed;

		if(t.getVal(objLayer, 'align_hor') == undefined){
			objLayer = t.setVal(objLayer, 'align_hor', 'left', true);
		}else{
			if(typeof(objLayer.align_hor) !== 'object'){
				objLayer = t.setVal(objLayer, 'align_hor', objLayer.align_hor, true);
			}
		}
		if(t.getVal(objLayer, 'align_vert') == undefined){
			objLayer = t.setVal(objLayer, 'align_vert', 'top', true);
		}else{
			if(typeof(objLayer.align_vert) !== 'object'){
				objLayer = t.setVal(objLayer, 'align_vert', objLayer.align_vert, true);
			}
		}

		//set animation:
		if(objLayer.hiddenunder == undefined)
			objLayer.hiddenunder = "";

		if(objLayer.resizeme == undefined)
			objLayer.resizeme = "true";
		
		if(objLayer['seo-optimized'] == undefined)
			objLayer['seo-optimized'] = false;
		
		//set image link
		if(objLayer.link == undefined)
			objLayer.link = "";

		//set image link open in
		if(objLayer.link_open_in == undefined)
			objLayer.link_open_in = "same";

		//set slide link:
		if(objLayer.link_slide == undefined)
			objLayer.link_slide = "nothing";

		//set scroll under offset
		if(objLayer.scrollunder_offset == undefined)
			objLayer.scrollunder_offset = "";

		//set style, if empty, add first style from the list
		if(objLayer.style == undefined)
			objLayer.style = '';//jQuery("#layer_caption").val();

		if(objLayer['visible-desktop'] == undefined)
			objLayer['visible-desktop'] = true;
		if(objLayer['visible-notebook'] == undefined)
			objLayer['visible-notebook'] = true;
		if(objLayer['visible-tablet'] == undefined)
			objLayer['visible-tablet'] = true;
		if(objLayer['visible-mobile'] == undefined)
			objLayer['visible-mobile'] = true;
		
		if(objLayer['resize-full'] == undefined)
			objLayer['resize-full'] = true;
		
		if(objLayer['show-on-hover'] == undefined)
			objLayer['show-on-hover'] = false;
		
		if(objLayer.basealign == undefined)
			objLayer.basealign = 'grid';
		
		
		if(objLayer.responsive_offset == undefined)
			objLayer.responsive_offset = true;
		
		
		objLayer.style = jQuery.trim(objLayer.style);
		if(isInit == false && objLayer.type == "text" && (!objLayer.style || objLayer.style == "")){
			objLayer.style = getFirstStyle();
			do_style_reset = true;
		}

		if(objLayer['lazy-load'] == undefined)
			objLayer['lazy-load'] = 'auto';
		
		if(objLayer['image-size'] == undefined)
			objLayer['image-size'] = 'auto';

		
		//add time
		if(objLayer.time == undefined)
			objLayer.time = getNextTime();

		objLayer.time = Number(objLayer.time);	//casting

		
		if(objLayer.endspeed == undefined)
			objLayer.endspeed = initSpeed;

		//end time:
		if(objLayer.endtime == undefined)
			objLayer.endtime = parseInt(g_slideTime,0)+parseInt(objLayer.endspeed,0);

		//set end animation:
		if(objLayer.endanimation == undefined) //objLayer.endanimation = jQuery("#layer_endanimation").val();
			objLayer.endanimation = 'fadeout';

		//set end easing:
		if(objLayer.endeasing == undefined)
			objLayer.endeasing = jQuery("#layer_endeasing").val();

		if(t.getVal(objLayer, 'width') == undefined){
			objLayer = t.setVal(objLayer, 'width', -1, true);
		}else{
			if(typeof(objLayer.width) !== 'object'){
				objLayer = t.setVal(objLayer, 'width', objLayer.width, true);
			}
		}

		if(t.getVal(objLayer, 'height') == undefined){
			objLayer = t.setVal(objLayer, 'height', -1, true);
		}else{
			if(typeof(objLayer.height) !== 'object'){
				objLayer = t.setVal(objLayer, 'height', objLayer.height, true);
			}
		}
		
		if(t.getVal(objLayer, 'cover_mode') == undefined){
			objLayer = t.setVal(objLayer, 'cover_mode', 'custom', true); 
		}
		
		if(objLayer['static_styles'] == undefined){
			objLayer['static_styles'] = {};
			t.reset_to_default_static_styles(objLayer);

			if(t.getVal(objLayer['static_styles'], 'font-size') == undefined){
				objLayer['static_styles'] = t.setVal(objLayer['static_styles'], 'font-size', 20, true);
			}

			if(t.getVal(objLayer['static_styles'], 'line-height') == undefined){
				objLayer['static_styles'] = t.setVal(objLayer['static_styles'], 'line-height', 22, true);
			}

			if(t.getVal(objLayer['static_styles'], 'font-weight') == undefined){
				objLayer['static_styles'] = t.setVal(objLayer['static_styles'], 'font-weight', 400, true);
			}

			if(t.getVal(objLayer['static_styles'], 'color') == undefined){
				objLayer['static_styles'] = t.setVal(objLayer['static_styles'], 'color', "#ffffff", true);
			}
		}else{
			if(typeof(objLayer['static_styles']['font-size']) !== 'object'){
				objLayer['static_styles'] = t.setVal(objLayer['static_styles'], 'font-size', objLayer['static_styles']['font-size'], true);
			}
			if(typeof(objLayer['static_styles']['line-height']) !== 'object'){
				objLayer['static_styles'] = t.setVal(objLayer['static_styles'], 'line-height', objLayer['static_styles']['line-height'], true);
			}
			if(typeof(objLayer['static_styles']['font-weight']) !== 'object'){
				objLayer['static_styles'] = t.setVal(objLayer['static_styles'], 'font-weight', objLayer['static_styles']['font-weight'], true);
			}
			if(typeof(objLayer['static_styles']['color']) !== 'object'){
				objLayer['static_styles'] = t.setVal(objLayer['static_styles'], 'color', objLayer['static_styles']['color'], true);
			}
		}

		//round position
		objLayer = t.setVal(objLayer, 'top', Math.round(t.getVal(objLayer, 'top')));
		objLayer = t.setVal(objLayer, 'left', Math.round(t.getVal(objLayer, 'left')));
		

		if(objLayer.x_start == undefined)
			objLayer.x_start = "inherit";

		if(objLayer.y_start == undefined)
			objLayer.y_start = "inherit";

		if(objLayer.z_start == undefined)
			objLayer.z_start = "inherit";

		if(objLayer.x_end == undefined)
			objLayer.x_end = "inherit";

		if(objLayer.y_end == undefined)
			objLayer.y_end = "inherit";

		if(objLayer.z_end == undefined)
			objLayer.z_end = "inherit";

		if(objLayer.opacity_start == undefined){
			if(isInit == false){
				objLayer.opacity_start = "0";
			}else{
				objLayer.opacity_start = "inherit";
			}
		}

		if(objLayer.opacity_end == undefined){
			if(isInit == false){
				objLayer.opacity_end = "0";
			}else{
				objLayer.opacity_end = "inherit";
			}
		}

		if(objLayer.x_rotate_start == undefined)
			objLayer.x_rotate_start = "inherit";

		if(objLayer.y_rotate_start == undefined)
			objLayer.y_rotate_start = "inherit";

		if(objLayer.z_rotate_start == undefined)
			objLayer.z_rotate_start = "inherit";

		if(objLayer.x_rotate_end == undefined)
			objLayer.x_rotate_end = "inherit";

		if(objLayer.y_rotate_end == undefined)
			objLayer.y_rotate_end = "inherit";

		if(objLayer.z_rotate_end == undefined)
			objLayer.z_rotate_end = "inherit";

		if(objLayer.scale_x_start == undefined)
			objLayer.scale_x_start = "inherit";

		if(objLayer.scale_y_start == undefined)
			objLayer.scale_y_start = "inherit";

		if(objLayer.scale_x_end == undefined)
			objLayer.scale_x_end = "inherit";

		if(objLayer.scale_y_end == undefined)
			objLayer.scale_y_end = "inherit";

		if(objLayer.skew_x_start == undefined)
			objLayer.skew_x_start = "inherit";

		if(objLayer.skew_y_start == undefined)
			objLayer.skew_y_start = "inherit";

		if(objLayer.skew_x_end == undefined)
			objLayer.skew_x_end = "inherit";

		if(objLayer.skew_y_end == undefined)
			objLayer.skew_y_end = "inherit";

		if(objLayer.x_origin_start == undefined)
			objLayer.x_origin_start = "inherit";

		if(objLayer.y_origin_start == undefined)
			objLayer.y_origin_start = "inherit";

		if(objLayer.x_origin_end == undefined)
			objLayer.x_origin_end = "inherit";

		if(objLayer.y_origin_end == undefined)
			objLayer.y_origin_end = "inherit";

		if(objLayer.pers_start == undefined)
			objLayer.pers_start = "inherit";

		if(objLayer.pers_end == undefined)
			objLayer.pers_end = "inherit";


		// KRISZTAN  -  ( NEW LAYER GETS ADDED ) deformation part
		if(objLayer.deformation == undefined || jQuery.isEmptyObject(objLayer['deformation']))
			objLayer.deformation = {};

		if(objLayer['deformation']['font-family'] == undefined)
			objLayer['deformation']['font-family'] = "";

		if(objLayer['deformation']['padding'] == undefined){
			var cur_pad = [];
			jQuery('input[name="css_padding[]"]').each(function(){
				cur_pad.push(0);
			});
			objLayer['deformation']['padding'] = cur_pad; //4 []
		}
		if(objLayer['deformation']['font-style'] == undefined){
			objLayer['deformation']['font-style'] = 'normal'; // checkbox
		}

		if(objLayer['deformation']['color-transparency'] == undefined)
			objLayer['deformation']['color-transparency'] = 1;
		
		if(objLayer['deformation']['text-decoration'] == undefined)
			objLayer['deformation']['text-decoration'] = "none";

		if(objLayer['deformation']['text-align'] == undefined)
			objLayer['deformation']['text-align'] = "left";

		if(objLayer['deformation']['background-color'] == undefined)
			objLayer['deformation']['background-color'] = "transparent";
		
		if(objLayer['deformation']['background-transparency'] == undefined)
			objLayer['deformation']['background-transparency'] = 1;

		if(objLayer['deformation']['border-color'] == undefined)
			objLayer['deformation']['border-color'] = "transparent";

		if(objLayer['deformation']['border-transparency'] == undefined)
			objLayer['deformation']['border-transparency'] = 1;

		if(objLayer['deformation']['border-style'] == undefined)
			objLayer['deformation']['border-style'] = "none";

		if(objLayer['deformation']['border-width'] == undefined)
			objLayer['deformation']['border-width'] = "0"

		if(objLayer['deformation']['border-radius'] == undefined){
			var cur_bor = [];
			jQuery('input[name="css_border-radius[]"]').each(function(){
				cur_bor.push(0);
			});
			objLayer['deformation']['border-radius'] = cur_bor; //4 []
		}
		if(objLayer['deformation']['x'] == undefined)
			objLayer['deformation']['x'] = 0;

		if(objLayer['deformation']['y'] == undefined)
			objLayer['deformation']['y'] = 0;

		if(objLayer['deformation']['z'] == undefined)
			objLayer['deformation']['z'] = 0;

		if(objLayer['deformation']['skewx'] == undefined)
			objLayer['deformation']['skewx'] = 0;

		if(objLayer['deformation']['skewy'] == undefined)
			objLayer['deformation']['skewy'] = 0;

		if(objLayer['deformation']['scalex'] == undefined)
			objLayer['deformation']['scalex'] = 1;

		if(objLayer['deformation']['scaley'] == undefined)
			objLayer['deformation']['scaley'] = 1;

		if(objLayer['deformation']['opacity'] == undefined)
			objLayer['deformation']['opacity'] = 1;

		if(objLayer['deformation']['xrotate'] == undefined)
			objLayer['deformation']['xrotate'] = 0;

		if(objLayer['deformation']['yrotate'] == undefined)
			objLayer['deformation']['yrotate'] = 0;

		if(objLayer['2d_rotation'] == undefined)
			objLayer['2d_rotation'] = 0;

		if(objLayer['deformation']['2d_origin_x'] == undefined)
			objLayer['deformation']['2d_origin_x'] = 50;

		if(objLayer['deformation']['2d_origin_y'] == undefined)
			objLayer['deformation']['2d_origin_y'] = 50;

		if(objLayer['deformation']['pers'] == undefined)
			objLayer['deformation']['pers'] = 600;

		if(objLayer['deformation']['corner_left'] == undefined)
			objLayer['deformation']['corner_left'] = 'nothing';

		if(objLayer['deformation']['corner_right'] == undefined)
			objLayer['deformation']['corner_right'] = 'nothing';

		if(objLayer['deformation']['parallax'] == undefined)
			objLayer['deformation']['parallax'] = '-';
		
		//deformation part end
		
		//deformation hover part start
		if(objLayer['deformation-hover'] == undefined || jQuery.isEmptyObject(objLayer['deformation-hover'])){
			objLayer['deformation-hover'] = {};
		}
		
		if(objLayer['deformation-hover']['color'] == undefined)
			objLayer['deformation-hover']['color'] = "#ffffff";
			
		if(objLayer['deformation-hover']['color-transparency'] == undefined)
			objLayer['deformation-hover']['color-transparency'] = "1";
		
		if(objLayer['deformation-hover']['text-decoration'] == undefined)
			objLayer['deformation-hover']['text-decoration'] = "none";

		if(objLayer['deformation-hover']['background-color'] == undefined)
			objLayer['deformation-hover']['background-color'] = "transparent";

		if(objLayer['deformation-hover']['background-transparency'] == undefined)
			objLayer['deformation-hover']['background-transparency'] = 0;

		if(objLayer['deformation-hover']['border-color'] == undefined)
			objLayer['deformation-hover']['border-color'] = "transparent";
		
		if(objLayer['deformation-hover']['border-transparency'] == undefined)
			objLayer['deformation-hover']['border-transparency'] = "1";

		if(objLayer['deformation-hover']['border-style'] == undefined)
			objLayer['deformation-hover']['border-style'] = "none";

		if(objLayer['deformation-hover']['border-width'] == undefined)
			objLayer['deformation-hover']['border-width'] = 0;

		if(objLayer['deformation-hover']['border-radius'] == undefined){
			var cur_bor = [];
			jQuery('input[name="hover_css_border-radius[]"]').each(function(){
				cur_bor.push(0);
			});
			objLayer['deformation-hover']['border-radius'] = cur_bor; //4 []
		}
		if(objLayer['deformation-hover']['x'] == undefined)
			objLayer['deformation-hover']['x'] = 0;

		if(objLayer['deformation-hover']['y'] == undefined)
			objLayer['deformation-hover']['y'] = 0;

		if(objLayer['deformation-hover']['z'] == undefined)
			objLayer['deformation-hover']['z'] = 0;

		if(objLayer['deformation-hover']['skewx'] == undefined)
			objLayer['deformation-hover']['skewx'] = 0;

		if(objLayer['deformation-hover']['skewy'] == undefined)
			objLayer['deformation-hover']['skewy'] = 0;

		if(objLayer['deformation-hover']['scalex'] == undefined)
			objLayer['deformation-hover']['scalex'] = 1;

		if(objLayer['deformation-hover']['scaley'] == undefined)
			objLayer['deformation-hover']['scaley'] = 1;

		if(objLayer['deformation-hover']['opacity'] == undefined)
			objLayer['deformation-hover']['opacity'] = 1;

		if(objLayer['deformation-hover']['xrotate'] == undefined)
			objLayer['deformation-hover']['xrotate'] = 0;

		if(objLayer['deformation-hover']['yrotate'] == undefined)
			objLayer['deformation-hover']['yrotate'] = 0;

		if(objLayer['deformation-hover']['2d_rotation'] == undefined)
			objLayer['deformation-hover']['2d_rotation'] = 0;

		if(objLayer['deformation-hover']['2d_origin_x'] == undefined)
			objLayer['deformation-hover']['2d_origin_x'] = 50;

		if(objLayer['deformation-hover']['2d_origin_y'] == undefined)
			objLayer['deformation-hover']['2d_origin_y'] = 50;
		
		if(objLayer['deformation-hover']['speed'] == undefined)
			objLayer['deformation-hover']['speed'] = jQuery('input[name="hover_speed"]').val();
		
		if(objLayer['deformation-hover']['easing'] == undefined)
			objLayer['deformation-hover']['easing'] = jQuery('select[name="hover_easing"] option:selected').val();
		
		if(objLayer['deformation-hover']['css_cursor'] == undefined)
			objLayer['deformation-hover']['css_cursor'] = jQuery('select[name="css_cursor"] option:selected').val();
		//deformation hover part end
		
		if(objLayer['visible'] == undefined) objLayer['visible'] = true;
		
		if(objLayer.animation_overwrite == undefined)
			objLayer.animation_overwrite = 'wait';
		
		if(objLayer.trigger_memory == undefined)
			objLayer.trigger_memory = 'keep';
		
		objLayer.serial = id_counter;
		
		if(!isDemo){
			arrLayers[id_counter] = jQuery.extend({},objLayer);
		}else{
			arrLayersDemo[id_counter] = jQuery.extend({},objLayer);
		}
		//add html
		var htmlLayer = t.makeLayerHtml(id_counter,objLayer,isDemo);
		container.append(htmlLayer);
		var objHtmlLayer = t.getHtmlLayerFromSerial(id_counter,isDemo);
		
		//add layer to sortbox
		if(!isDemo) {
			u.addToSortbox(id_counter,objLayer);
		}
		
		
		if(objLayer['visible'] == false && !isDemo){
			u.hideLayer(id_counter);
		}
		
		//refresh draggables
		if(!isDemo)
			refreshEvents(id_counter);

		id_counter++;

		//enable "delete all" button, not event, but anyway :)
		jQuery("#button_delete_all").removeClass("button-disabled");
		
		
		u.rebuildLayerIdle(objHtmlLayer,0,isDemo);
	

		//select the layer
		if(isInit == false && !isDemo){
			t.setLayerSelected(objLayer.serial);
			jQuery("#layer_text").focus();
		}
		
		if(do_style_reset){ //trigger change event so that element gets first styles
			t.reset_to_default_static_styles(objLayer);
			// Reset Fields from Style Template
			updateSubStyleParameters(objLayer, true);
		}
	}



	/**
	 *
	 * delete layer from layers object
	 */
	var deleteLayerFromObject = function(serial){
		
		var arrLayersNew = {};
		var flagFound = false;
		for (key in arrLayers){
			if(key != serial)
				arrLayersNew[key] = arrLayers[key];
			else
				flagFound = true;
		}

		if(flagFound == false)
			UniteAdminRev.showErrorMessage("Can't delete layer, serial: "+serial+" not found");

		arrLayers = jQuery.extend({},arrLayersNew);
	}

	/**
	 * delete the layer from html.
	 */
	var deleteLayerFromHtml = function(serial){
		
		var htmlLayer = t.getHtmlLayerFromSerial(serial);
		htmlLayer.remove();
	}


	/**
	 * delete all representation of some layer
	 */
	var deleteLayer = function(serial){
		
		deleteLayerFromObject(serial);
		deleteLayerFromHtml(serial);
		u.deleteLayerFromSortbox(serial);
	}



	/**
	 *
	 * call "deleteLayer" function with selected serial
	 */
	var deleteCurrentLayer = function(){
		
		if(selectedLayerSerial == -1) {
			return(false);
		}

		jQuery('#layer-short-toolbar').appendTo(jQuery('#layer-settings-toolbar-bottom'));
		jQuery('#layer_text_wrapper').appendTo(jQuery('#layer_text_holder'));
		deleteLayer(selectedLayerSerial);

		//set unselected
		selectedLayerSerial = -1;

		//clear form and disable buttons
		disableFormFields();
	}


	/**
	 * duplicate layer, set it a little aside of the layer position
	 */
	var duplicateLayer = function(serial){
		
		var obj = arrLayers[serial];
		var obj2 = jQuery.extend(true, {}, obj);	//duplicate object
		t.getVal(objLayer, 'top');

		obj2 = t.setVal(obj2, 'left', t.getVal(obj2, 'left')+5);
		obj2 = t.setVal(obj2, 'top', t.getVal(obj2, 'top')+5);
		obj2.order = undefined;
		obj2.time = undefined;

		addLayer(obj2);		
		initDisallowCaptionsOnClick();
		var key;
		jQuery.each(t.getLayers(),function(k,layer) {
			key = k;
		});
		
		t.setLayerSelected(key);
		
	}


	/**
	 * call "duplicateLayer" function with selected serial
	 */
	var duplicateCurrentLayer = function(){		
		if(selectedLayerSerial == -1)
			return(false);
		duplicateLayer(selectedLayerSerial);		
	}

	

	/**
	 * update the corners
	 */
	t.updateHtmlLayerCorners = function(htmlLayer,objLayer){
		
		htmlLayer = htmlLayer.find('.innerslide_layer');
		var ncch = htmlLayer.outerHeight(),
			bgcol = htmlLayer.css('backgroundColor'),
			bgOpacity = UniteAdminRev.getTransparencyFromRgba(htmlLayer.css('backgroundColor'));
		bgOpacity = bgOpacity === false ? 1 : bgOpacity;
				
		htmlLayer.find('.frontcorner').remove();
		htmlLayer.find('.frontcornertop').remove();
		htmlLayer.find('.backcorner').remove();
		htmlLayer.find('.backcornertop').remove();

		switch(objLayer['deformation']['corner_left']){
			case "curved":

				htmlLayer.append("<div class='frontcorner'></div>");
			break;
			case "reverced":
				htmlLayer.append("<div class='frontcornertop'></div>");
			break;
		}

		switch(objLayer['deformation']['corner_right']){
			case "curved":
				htmlLayer.append("<div class='backcorner'></div>");
			break;
			case "reverced":
				htmlLayer.append("<div class='backcornertop'></div>");
			break;
		}


		htmlLayer.find(".frontcorner").css({
            'borderWidth':ncch+"px",
            'left':(0-ncch)+'px',
            'borderRight':'0px solid transparent',
            'borderTopColor':bgcol
		});

		htmlLayer.find(".frontcornertop").css({
            'borderWidth':ncch+"px",
            'left':(0-ncch)+'px',
            'borderRight':'0px solid transparent',
            'borderBottomColor':bgcol
		});

		htmlLayer.find('.backcorner').css({
            'borderWidth':ncch+"px",
            'right':(0-ncch)+'px',
            'borderLeft':'0px solid transparent',
            'borderBottomColor':bgcol
        });

		htmlLayer.find('.backcornertop').css({
             'borderWidth':ncch+"px",
             'right':(0-ncch)+'px',
             'borderLeft':'0px solid transparent',
             'borderTopColor':bgcol
         });
	}
	

	// DELIVER THE SELECTED JQUERY OBJECT BASED ON SERIAL
	var getjQueryLayer = function() {
		return jQuery('#slide_layer_'+selectedLayerSerial);
	}

	


	// UPDATE LAYER TEXT ON WRITE && UPDATE TITLE OF LAYER
	var updateLayerTextField = function(event,timerobj,txt) {
		var jobj = getjQueryLayer();
		
		if (selectedLayerSerial!=-1 && jobj.length>0) jobj.find('.innerslide_layer.tp-caption').html(txt);			
		var li = timerobj.closest("li");
		txt = u.getSortboxText(txt);
		li.find('.timer-layer-text').html(txt);				
	}

	/**
	 * update the position of html cross
	 */
	t.updateCrossIconPosition = function(objHtmlLayer,objLayer){
		
		var htmlCross = objHtmlLayer.find(".icon_cross");
		var crossWidth = htmlCross.width();
		var crossHeight = htmlCross.height();
		var totalWidth = objHtmlLayer.outerWidth();
		var totalHeight = objHtmlLayer.outerHeight();
		var crossHalfW = Math.round(crossWidth / 2);
		var crossHalfH = Math.round(crossHeight / 2);

		var posx = 0;
		var posy = 0;
		switch(t.getVal(objLayer, 'align_hor')){
			case "left":
				posx = - crossHalfW;
			break;
			case "center":
				posx = (totalWidth - crossWidth) / 2;
			break;
			case "right":
				posx = totalWidth - crossHalfW;
			break;
		}

		switch(t.getVal(objLayer, 'align_vert')){
			case "top":
				posy = - crossHalfH;
			break;
			case "middle":
				posy = (totalHeight - crossHeight) / 2;
			break;
			case "bottom":
				posy = totalHeight - crossHalfH;
			break;
		}

		htmlCross.css({"left":posx+"px","top":posy+"px"});
		
	}


	

	/**
	 * check / update full width video position and size
	 */
	var checkUpdateFullwidthVideo = function(objLayer){
		
		if(objLayer.type != "video") {
			return(objLayer);
		}

		if(typeof (objLayer.video_data) !== "undefined"){
			if(objLayer.video_data && objLayer.video_data.fullwidth && objLayer.video_data.fullwidth == true){

				objLayer = t.setVal(objLayer, 'top', 0);
				objLayer = t.setVal(objLayer, 'left', 0);

				objLayer = t.setVal(objLayer, 'align_hor', 'left', true);
				objLayer = t.setVal(objLayer, 'align_vert', 'top', true);
				objLayer.video_width = container.width();
				objLayer.video_height = container.height();
			}
		}
		return(objLayer);
	}

	/*! UPDATE HTML LAYER */
	/**
	 * update html layers from object
	 */
	var updateHtmlLayersFromObject = function(serial,posresets,isDemo){
		
		
		if(!serial) serial = selectedLayerSerial;

		var objLayer = t.getLayer(serial, isDemo);

		if(!objLayer) return(false);
		
		var htmlLayer = t.getHtmlLayerFromSerial(serial,isDemo);

		//set class name
		var className = "innerslide_layer tp-caption";
		if(serial == selectedLayerSerial) htmlLayer.addClass("layer_selected");
		
		className += " "+objLayer.style;
		
		switch(objLayer.type){
			case 'button':
			case 'shape':
				className += ' '+objLayer.internal_class;
			break;
			/*case 'no_edit':
				className += objLayer.internal_class;
			break;*/
		}
		
		htmlLayer.find('.innerslide_layer').attr("class",className);


		//set html
		var type = objLayer.type || "text";
		
		//update layer by type:
		switch(type){
			case "image":
			break;
			case "video":	//update fullwidth position
				objLayer = checkUpdateFullwidthVideo(objLayer);
			break;
			default:
			case "text":				
			case "button":		
				
				htmlLayer.find('.innerslide_layer').html(objLayer.text);
				t.makeCurrentLayerRotatable(serial);
				t.updateHtmlLayerCorners(htmlLayer,objLayer);				
			break;
			/*case 'no_edit':
				
			break;*/
		}					
		u.rebuildLayerIdle(getjQueryLayer());		
	}
	
	// MAKE THINGS ROTATABLE
	t.makeCurrentLayerRotatable = function(serial) {
		
		if (u.checkLoopTab() || u.checkAnimationTab()) {
			t.removeCurrentLayerRotatable();		
			return false;
		}

		var el = jQuery('.slide_layer.layer_selected .innerslide_layer');


		if (el!=undefined && el.length>0) {
			try{el.rotatable("destroy");} 
			catch(e) {}
			el.rotatable({
				angle:el.data('angle'),
				start:function(event,ui) {
				},
				rotate:function(event,ui) {
					jQuery('#layer_2d_rotation').val(getRotationDegrees(ui.element));
					ui.element.data('angle',ui.angle.current);
				},
				stop:function(event,ui) {
					t.updateLayerFromFields();
				}
			});		
		}
	}
	
	t.removeCurrentLayerRotatable = function() {
		
		jQuery('.slide_layer .ui-rotatable-handle').each(function() {
			var el = jQuery(this);
			setTimeout(function() {
				try{el.parent().rotatable("destroy");} catch(e) {}
				try{el.remove();} catch(e) {}
				try{el.parent().find('.ui-rotatable-handle').remove();} catch(e) {}
			},50);
		})
	}


	/**
	 THE CHANGE OF POSITION FIELD TRIGGERS THE REPOSITIONINNG OF THE LAYER
	**/
	var positionChanged = function() {
		
		jQuery("#layer_top, #layer_left").change(function() {			
			setTimeout(function() {
				updateHtmlLayersFromObject(t.getSerialFromID(jQuery('.layer_selected').attr('id')),true);
			},19);
		});
	}
	
	
	t.set_cover_mode = function(){
		var objLayer = t.getLayer(selectedLayerSerial);
		
		jQuery('#layer_scaleX').removeAttr('disabled');
		jQuery('#layer_scaleY').removeAttr('disabled');
		jQuery('#layer_max_width').removeAttr('disabled');
		jQuery('#layer_max_height').removeAttr('disabled');
		
		switch(objLayer.type) {
			case 'shape':
			case 'image':
				switch(jQuery('#layer_cover_mode option:selected').val()){
					case 'custom':
						//already removed disable
					break;
					case 'fullwidth':
						jQuery('#layer_scaleX').attr('disabled', 'disabled');
						jQuery('#layer_max_width').attr('disabled', 'disabled');
					break;
					case 'fullheight':
						jQuery('#layer_scaleY').attr('disabled', 'disabled');
						jQuery('#layer_max_height').attr('disabled', 'disabled');
					break;
					case 'cover':
					case 'cover-proportional':
						jQuery('#layer_scaleX').attr('disabled', 'disabled');
						jQuery('#layer_scaleY').attr('disabled', 'disabled');
						jQuery('#layer_max_width').attr('disabled', 'disabled');
						jQuery('#layer_max_height').attr('disabled', 'disabled');
				}
				break;
			default:
		}
	}
	
	

	/**
	 * update layer from html fields
	 */

	 t.updateLayerFromFields = function() {
	 	//clearTimeout(t.ulff_core);
	 	//t.ulff_core = setTimeout(function() {
	 		t.updateLayerFromFields_Core();
	 	//},350);
	 }

	t.updateLayerFromFields_Core = function(){

		if(selectedLayerSerial == -1) return(false);
		
		UniteCssEditorRev.compare_to_original(); //compare style changes and mark elements depending on state

		var objUpdate = {};

		objUpdate.style = jQuery("#layer_caption").val();			
		objUpdate['hover'] = jQuery('input[name="hover_allow"]').is(":checked");
		
		objUpdate['visible-desktop'] = jQuery('input[name="visible-desktop"]').is(":checked");
		objUpdate['visible-notebook'] = jQuery('input[name="visible-notebook"]').is(":checked");
		objUpdate['visible-tablet'] = jQuery('input[name="visible-tablet"]').is(":checked");
		objUpdate['visible-mobile'] = jQuery('input[name="visible-mobile"]').is(":checked");
		
		objUpdate['show-on-hover'] = jQuery('input[name="layer_on_slider_hover"]').is(":checked");
		
		objUpdate['lazy-load'] = jQuery('#layer-lazy-loading option:selected').val();
		objUpdate['image-size'] = jQuery('#layer-image-size option:selected').val();
				
		objUpdate.text = jQuery("#layer_text").val();
		
		objUpdate.alias = jQuery('#layer_sort_'+selectedLayerSerial+" .timer-layer-text").val();

		// IF NEW CONTENT IS EDITED FIRST TIME, USE THE SAME CONTENT FOR LAYER DESCRIPTION
		if (jQuery('#layer_text').data('new_content'))
			jQuery('#layer_sort_'+selectedLayerSerial+" .timer-layer-text").val(objUpdate.text);

		
		jQuery('#layer_quicksort_'+selectedLayerSerial+" .add-layer-txt").html(objUpdate.alias);
		
		objUpdate = t.setVal(objUpdate, 'top', Number(parseInt(jQuery("#layer_top").val(),0)));
		objUpdate = t.setVal(objUpdate, 'left', Number(parseInt(jQuery("#layer_left").val(),0)));
		
		
		objUpdate = t.setVal(objUpdate, 'whitespace', jQuery("#layer_whitespace option:selected").val());
		objUpdate = t.setVal(objUpdate, 'max_height', jQuery("#layer_max_height").val());
		objUpdate = t.setVal(objUpdate, 'max_width', jQuery("#layer_max_width").val());
		
		objUpdate = t.setVal(objUpdate, 'video_height', jQuery("#layer_video_height").val());
		objUpdate = t.setVal(objUpdate, 'video_width', jQuery("#layer_video_width").val());
		

		objUpdate = t.setVal(objUpdate, 'scaleX', jQuery("#layer_scaleX").val());
		objUpdate = t.setVal(objUpdate, 'scaleY', jQuery("#layer_scaleY").val());
		
		objUpdate = t.setVal(objUpdate, 'cover_mode', jQuery("#layer_cover_mode option:selected").val());

		objUpdate['2d_rotation'] =  parseInt(jQuery("#layer_2d_rotation").val(),0);

		objUpdate['2d_origin_x'] =  parseInt(jQuery("#layer_2d_origin_x").val(),0);
		objUpdate['2d_origin_y'] =  parseInt(jQuery("#layer_2d_origin_y").val(),0);
		objUpdate['static_start'] = jQuery("#layer_static_start option:selected").val();
		objUpdate['static_end'] = jQuery("#layer_static_end option:selected").val();


		//set Loop Animations
		objUpdate.loop_animation = jQuery("#layer_loop_animation option:selected").val();
		objUpdate.loop_easing = jQuery("#layer_loop_easing").val();
		objUpdate.loop_speed = jQuery("#layer_loop_speed").val();
		objUpdate.loop_startdeg =  parseInt(jQuery("#layer_loop_startdeg").val(),0);
		objUpdate.loop_enddeg =  parseInt(jQuery("#layer_loop_enddeg").val(),0);
		objUpdate.loop_xorigin =  parseInt(jQuery("#layer_loop_xorigin").val(),0);
		objUpdate.loop_yorigin =  parseInt(jQuery("#layer_loop_yorigin").val(),0);
		objUpdate.loop_xstart =  parseInt(jQuery("#layer_loop_xstart").val(),0);
		objUpdate.loop_xend =  parseInt(jQuery("#layer_loop_xend").val(),0);
		objUpdate.loop_ystart =  parseInt(jQuery("#layer_loop_ystart").val(),0);
		objUpdate.loop_yend =  parseInt(jQuery("#layer_loop_yend").val(),0);
		objUpdate.loop_zoomstart = jQuery("#layer_loop_zoomstart").val();
		objUpdate.loop_zoomend = jQuery("#layer_loop_zoomend").val();
		objUpdate.loop_angle = jQuery("#layer_loop_angle").val();
		objUpdate.loop_radius = jQuery("#layer_loop_radius").val();
		
		
		if (jQuery('#layer__scalex').val()!=1 || jQuery('#layer__scaley').val()!=1 || parseInt(jQuery('#layer__skewx').val(),0)!=0 || parseInt(jQuery('#layer__skewy').val(),0)!=0 || parseInt(jQuery('#layer__xrotate').val(),0)!=0 || parseInt(jQuery('#layer__yrotate').val(),0)!=0 || parseInt(jQuery('#layer_2d_rotation').val(),0)!=0) {
				jQuery('.mask-not-available').show();
				jQuery('.mask-is-available').hide();
				jQuery('input[name="masking-start"]').removeAttr("checked");
				jQuery('input[name="masking-end"]').removeAttr("checked");
				jQuery('.mask-start-settings').hide();
				jQuery('.mask-end-settings').hide();
				jQuery('.tp-showmask').removeClass('tp-showmask');
				RevSliderSettings.onoffStatus(jQuery('input[name="masking-start"]'));
				RevSliderSettings.onoffStatus(jQuery('input[name="masking-end"]'));			
				jQuery(t.getHtmlLayerFromSerial(selectedLayerSerial)).find('.tp-mask-wrap').css({overflow:"visible"});
		} else {
			jQuery('.mask-not-available').hide();
			jQuery('.mask-is-available').show();
		}

		//set Mask Animations
		objUpdate.mask_start = jQuery('input[name="masking-start"]').is(':checked');
		objUpdate.mask_end = jQuery('input[name="masking-end"]').is(':checked');
		objUpdate.mask_x_start = jQuery("#mask_anim_xstart").val();
		objUpdate.mask_y_start =  jQuery("#mask_anim_ystart").val();
		objUpdate.mask_speed_start =  jQuery("#mask_speed").val();
		objUpdate.mask_ease_start =  jQuery("#mask_easing").val();
		objUpdate.mask_x_end =  jQuery("#mask_anim_xend").val();
		objUpdate.mask_y_end = jQuery("#mask_anim_yend").val();
		objUpdate.mask_speed_end = jQuery("#mask_speed_end").val();
		objUpdate.mask_ease_end =  jQuery("#mask_easing_end").val();



		
		objUpdate.animation = jQuery("#layer_animation option:selected").val();
		objUpdate.speed = jQuery("#layer_speed").val();
		
		
		
		objUpdate = t.setVal(objUpdate, 'align_hor', jQuery("#layer_align_hor").val());
		objUpdate = t.setVal(objUpdate, 'align_vert', jQuery("#layer_align_vert").val());
		
		objUpdate.hiddenunder = jQuery("#layer_hidden").is(":checked");
		objUpdate.resizeme = jQuery("#layer_resizeme").is(":checked");
		objUpdate['resize-full'] = jQuery("#layer_resize-full").is(":checked");

		objUpdate['seo-optimized'] = jQuery("#layer-seo-optimized").is(":checked");
		
		objUpdate.basealign = jQuery("#layer_align_base").val();
		objUpdate.responsive_offset = jQuery("#layer_resp_offset").is(':checked');
		
		objUpdate.easing = jQuery("#layer_easing").val();
		objUpdate.split = jQuery("#layer_split").val();
		objUpdate.endsplit = jQuery("#layer_endsplit").val();
		objUpdate.splitdelay = jQuery("#layer_splitdelay").val();
		objUpdate.endsplitdelay = jQuery("#layer_endsplitdelay").val();
		
		objUpdate.alt_option = jQuery("#layer_alt_option option:selected").val();
		objUpdate.alt = jQuery("#layer_alt").val();
		objUpdate = t.setVal(objUpdate, 'scaleX', jQuery("#layer_scaleX").val());
		objUpdate = t.setVal(objUpdate, 'scaleY', jQuery("#layer_scaleY").val());

		objUpdate.x_start =  jQuery("#layer_anim_xstart").val();
		objUpdate.y_start =  jQuery("#layer_anim_ystart").val();
		objUpdate.z_start =  jQuery("#layer_anim_zstart").val();
		objUpdate.x_end =  jQuery("#layer_anim_xend").val();
		objUpdate.y_end =  jQuery("#layer_anim_yend").val();
		objUpdate.z_end =  jQuery("#layer_anim_zend").val();
		objUpdate.opacity_start = jQuery("#layer_opacity_start").val();
		objUpdate.opacity_end = jQuery("#layer_opacity_end").val();
		objUpdate.x_rotate_start =  jQuery("#layer_anim_xrotate").val();
		objUpdate.y_rotate_start =  jQuery("#layer_anim_yrotate").val();
		objUpdate.z_rotate_start =  jQuery("#layer_anim_zrotate").val();
		objUpdate.x_rotate_end =  jQuery("#layer_anim_xrotate_end").val();
		objUpdate.y_rotate_end =  jQuery("#layer_anim_yrotate_end").val();
		objUpdate.z_rotate_end =  jQuery("#layer_anim_zrotate_end").val();
		objUpdate.scale_x_start = jQuery("#layer_scale_xstart").val();
		objUpdate.scale_y_start = jQuery("#layer_scale_ystart").val();
		objUpdate.scale_x_end = jQuery("#layer_scale_xend").val();
		objUpdate.scale_y_end = jQuery("#layer_scale_yend").val();
		objUpdate.skew_x_start = jQuery("#layer_skew_xstart").val();
		objUpdate.skew_y_start = jQuery("#layer_skew_ystart").val();
		objUpdate.skew_x_end = jQuery("#layer_skew_xend").val();
		objUpdate.skew_y_end = jQuery("#layer_skew_yend").val();
		objUpdate.x_origin_start = jQuery('input[name="layer_2d_origin_x"]').val(); //jQuery("#layer_anim_xoriginstart").val();
		objUpdate.y_origin_start = jQuery('input[name="layer_2d_origin_y"]').val(); //jQuery("#layer_anim_yoriginstart").val();
		objUpdate.x_origin_end = jQuery('input[name="layer_2d_origin_x"]').val(); //jQuery("#layer_anim_xoriginend").val();
		objUpdate.y_origin_end = jQuery('input[name="layer_2d_origin_y"]').val(); //jQuery("#layer_anim_yoriginend").val();
		
		
		objUpdate.x_start_reverse =  jQuery('input[name="layer_anim_xstart_reverse"]').is(':checked') || false;
		objUpdate.y_start_reverse =  jQuery('input[name="layer_anim_ystart_reverse"]').is(':checked') || false;
		objUpdate.x_end_reverse =  jQuery('input[name="layer_anim_xend_reverse"]').is(':checked') || false;
		objUpdate.y_end_reverse =  jQuery('input[name="layer_anim_yend_reverse"]').is(':checked') || false;
		objUpdate.x_rotate_start_reverse =  jQuery('input[name="layer_anim_xrotate_reverse"]').is(':checked') || false;
		objUpdate.y_rotate_start_reverse =  jQuery('input[name="layer_anim_yrotate_reverse"]').is(':checked') || false;
		objUpdate.z_rotate_start_reverse =  jQuery('input[name="layer_anim_zrotate_reverse"]').is(':checked') || false;
		objUpdate.x_rotate_end_reverse =  jQuery('input[name="layer_anim_xrotate_end_reverse"]').is(':checked') || false;
		objUpdate.y_rotate_end_reverse =  jQuery('input[name="layer_anim_yrotate_end_reverse"]').is(':checked') || false;
		objUpdate.z_rotate_end_reverse =  jQuery('input[name="layer_anim_zrotate_end_reverse"]').is(':checked') || false;
		objUpdate.scale_x_start_reverse = jQuery('input[name="layer_scale_xstart_reverse"]').is(':checked') || false;
		objUpdate.scale_y_start_reverse = jQuery('input[name="layer_scale_ystart_reverse"]').is(':checked') || false;
		objUpdate.scale_x_end_reverse = jQuery('input[name="layer_scale_xend_reverse"]').is(':checked') || false;
		objUpdate.scale_y_end_reverse = jQuery('input[name="layer_scale_yend_reverse"]').is(':checked') || false;
		objUpdate.skew_x_start_reverse = jQuery('input[name="layer_skew_xstart_reverse"]').is(':checked') || false;
		objUpdate.skew_y_start_reverse = jQuery('input[name="layer_skew_ystart_reverse"]').is(':checked') || false;
		objUpdate.skew_x_end_reverse = jQuery('input[name="layer_skew_xend_reverse"]').is(':checked') || false;
		objUpdate.skew_y_end_reverse = jQuery('input[name="layer_skew_yend_reverse"]').is(':checked') || false;
		objUpdate.mask_x_start_reverse = jQuery('input[name="mask_anim_xstart_reverse"]').is(':checked') || false;
		objUpdate.mask_y_start_reverse =  jQuery('input[name="mask_anim_ystart_reverse"]').is(':checked') || false;
		objUpdate.mask_x_end_reverse =  jQuery('input[name="mask_anim_xend_reverse"]').is(':checked') || false;
		objUpdate.mask_y_end_reverse =  jQuery('input[name="mask_anim_yend_reverse"]').is(':checked') || false;
		
		
		objUpdate.autolinebreak = jQuery("#layer_auto_line_break").is(":checked");

		objUpdate.pers_start = jQuery("#layer_pers_start").val();
		objUpdate.pers_end = jQuery("#layer_pers_end").val();

		objUpdate.scaleProportional = jQuery("#layer_proportional_scale").is(":checked");

		objUpdate.attrID = jQuery("#layer_id").val();
		objUpdate.attrClasses = jQuery("#layer_classes").val();
		objUpdate.attrTitle = jQuery("#layer_title").val();
		objUpdate.attrRel = jQuery("#layer_rel").val();
		objUpdate.link = jQuery("#layer_image_link").val();
		objUpdate.link_open_in = jQuery("#layer_link_open_in").val();
		objUpdate.link_id = jQuery("#layer_link_id").val();
		objUpdate.link_class = jQuery("#layer_link_class").val();
		objUpdate.link_title = jQuery("#layer_link_title").val();
		objUpdate.link_rel = jQuery("#layer_link_rel").val();

		objUpdate.endanimation = jQuery("#layer_endanimation").val();
		objUpdate.endspeed = jQuery("#layer_endspeed").val();
		objUpdate.endeasing = jQuery("#layer_endeasing").val();

		objUpdate = t.setVal(objUpdate, 'scaleY', jQuery("#layer_scaleY").val());


		jQuery('#clayer_start_speed').val(objUpdate.speed);
		jQuery('#clayer_end_speed').val(objUpdate.endspeed);
		
		if(objUpdate['static_styles'] == undefined) objUpdate['static_styles'] = {};
		objUpdate['static_styles'] = t.setVal(objUpdate['static_styles'], 'font-size', jQuery("#layer_font_size_s").val());
		objUpdate['static_styles'] = t.setVal(objUpdate['static_styles'], 'line-height', jQuery("#layer_line_height_s").val());
		objUpdate['static_styles'] = t.setVal(objUpdate['static_styles'], 'font-weight', jQuery("#layer_font_weight_s option:selected").val());
		objUpdate['static_styles'] = t.setVal(objUpdate['static_styles'], 'color', jQuery("#layer_color_s").val());

		//deformation part
		if(objUpdate.deformation == undefined) objUpdate.deformation = {};
		if (objUpdate["deformation"]["padding"] == undefined) objUpdate["deformation"]["padding"]=["0","0","0","0"];
		if (objUpdate["deformation"]["border-radius"] == undefined) objUpdate["deformation"]["border-radius"]=["0","0","0","0"];

		//objUpdate = updateSubStyleParameters(objUpdate);
		objUpdate['deformation']['color-transparency'] = jQuery('input[name="css_font-transparency"]').val();
		objUpdate['deformation']['font-style'] = (jQuery('input[name="css_font-style"]').is(':checked')) ? 'italic' : 'normal';
		objUpdate['deformation']['font-family'] = jQuery('input[name="css_font-family"]').val()
		jQuery('input[name="css_padding[]"]').each(function(i){ objUpdate['deformation']['padding'][i] = jQuery(this).val();});
		objUpdate['deformation']['text-decoration'] = jQuery('select[name="css_text-decoration"] option:selected').val();
		objUpdate['deformation']['text-align'] = jQuery('select[name="css_text-align"] option:selected').val();
		objUpdate['deformation']['background-color'] = jQuery('input[name="css_background-color"]').val();
		objUpdate['deformation']['background-transparency'] = jQuery('input[name="css_background-transparency"]').val();
		objUpdate['deformation']['border-color'] = jQuery('input[name="css_border-color-show"]').val();
		objUpdate['deformation']['border-transparency'] = jQuery('input[name="css_border-transparency"]').val();
		objUpdate['deformation']['border-style'] = jQuery('select[name="css_border-style"] option:selected').val();
		objUpdate['deformation']['border-width'] = jQuery('input[name="css_border-width"]').val();
		if(objUpdate.deformation['border-radius'] == undefined) objUpdate.deformation['border-radius'] = new Array();
		jQuery('input[name="css_border-radius[]"]').each(function(i){ objUpdate['deformation']['border-radius'][i] = jQuery(this).val();});
		objUpdate['deformation']['x'] = 0; //parseInt(jQuery('input[name="layer__x"]').val(),0);
		objUpdate['deformation']['y'] = 0; //parseInt(jQuery('input[name="layer__y"]').val(),0);
		objUpdate['deformation']['z'] = parseInt(jQuery('input[name="layer__z"]').val(),0);
		objUpdate['deformation']['skewx'] = jQuery('input[name="layer__skewx"]').val();
		objUpdate['deformation']['skewy'] = jQuery('input[name="layer__skewy"]').val();
		objUpdate['deformation']['scalex'] = jQuery('input[name="layer__scalex"]').val();
		objUpdate['deformation']['scaley'] = jQuery('input[name="layer__scaley"]').val();
		objUpdate['deformation']['opacity'] = jQuery('input[name="layer__opacity"]').val();
		objUpdate['deformation']['xrotate'] = parseInt(jQuery('input[name="layer__xrotate"]').val(),0);
		objUpdate['deformation']['yrotate'] = parseInt(jQuery('input[name="layer__yrotate"]').val(),0);
		objUpdate['2d_rotation'] = parseInt(jQuery('input[name="layer_2d_rotation"]').val(),0);
		objUpdate['deformation']['2d_origin_x'] = jQuery('input[name="layer_2d_origin_x"]').val();
		objUpdate['deformation']['2d_origin_y'] = jQuery('input[name="layer_2d_origin_y"]').val();
		objUpdate['deformation']['pers'] = jQuery('input[name="layer__pers"]').val();
		objUpdate['deformation']['corner_left'] = jQuery('select[name="layer_cornerleft"] option:selected').val();
		objUpdate['deformation']['corner_right'] = jQuery('select[name="layer_cornerright"] option:selected').val();
		objUpdate['deformation']['parallax'] = jQuery('select[name="parallax_level"] option:selected').val();
		
		//deformation hover part start
		if(objUpdate['deformation-hover'] == undefined || jQuery.isEmptyObject(objUpdate['deformation-hover'])) objUpdate['deformation-hover'] = {};
		objUpdate['deformation-hover']['color'] = jQuery('input[name="hover_color_static"]').val();
		objUpdate['deformation-hover']['color-transparency'] = jQuery('input[name="hover_css_font-transparency"]').val();
		objUpdate['deformation-hover']['text-decoration'] = jQuery('select[name="hover_css_text-decoration"] option:selected').val();
		objUpdate['deformation-hover']['background-color'] = jQuery('input[name="hover_css_background-color"]').val();
		objUpdate['deformation-hover']['background-transparency'] = jQuery('input[name="hover_css_background-transparency"]').val();
		objUpdate['deformation-hover']['border-color'] = jQuery('input[name="hover_css_border-color-show"]').val();
		objUpdate['deformation-hover']['border-transparency'] = jQuery('input[name="hover_css_border-transparency"]').val();
		objUpdate['deformation-hover']['border-style'] = jQuery('select[name="hover_css_border-style"] option:selected').val();
		objUpdate['deformation-hover']['border-width'] = jQuery('input[name="hover_css_border-width"]').val();
		if(objUpdate['deformation-hover']['border-radius'] == undefined) objUpdate['deformation-hover']['border-radius'] = new Array();
		jQuery('input[name="hover_css_border-radius[]"]').each(function(i){ objUpdate['deformation-hover']['border-radius'][i] = jQuery(this).val(); });
		objUpdate['deformation-hover']['skewx'] = jQuery('input[name="hover_layer__skewx"]').val();
		objUpdate['deformation-hover']['skewy'] = jQuery('input[name="hover_layer__skewy"]').val();
		objUpdate['deformation-hover']['scalex'] = jQuery('input[name="hover_layer__scalex"]').val();
		objUpdate['deformation-hover']['scaley'] = jQuery('input[name="hover_layer__scaley"]').val();
		objUpdate['deformation-hover']['opacity'] = jQuery('input[name="hover_layer__opacity"]').val();
		objUpdate['deformation-hover']['xrotate'] = parseInt(jQuery('input[name="hover_layer__xrotate"]').val(),0);
		objUpdate['deformation-hover']['yrotate'] = parseInt(jQuery('input[name="hover_layer__yrotate"]').val(),0);
		objUpdate['deformation-hover']['2d_rotation'] = parseInt(jQuery('input[name="hover_layer_2d_rotation"]').val(),0); //z rotate
		
		objUpdate['deformation-hover']['speed'] = jQuery('input[name="hover_speed"]').val();
		objUpdate['deformation-hover']['easing'] = jQuery('select[name="hover_easing"] option:selected').val();
		objUpdate['deformation-hover']['css_cursor'] = jQuery('select[name="css_cursor"] option:selected').val();
		
		//deformation hover part end
		
		if(objUpdate['layer_action'] == undefined || jQuery.isEmptyObject(objUpdate['layer_action'])) objUpdate['layer_action'] = {};
		
		objUpdate['layer_action'].tooltip_event = [];
		jQuery('select[name="layer_tooltip_event[]"] option:selected').each(function(){
			objUpdate['layer_action'].tooltip_event.push(jQuery(this).val());
		});
		
		objUpdate['layer_action'].action = [];
		jQuery('select[name="layer_action[]"] option:selected').each(function(){
			objUpdate['layer_action'].action.push(jQuery(this).val());
		});
		
		objUpdate['layer_action'].image_link = [];
		jQuery('input[name="layer_image_link[]"]').each(function(){
			objUpdate['layer_action'].image_link.push(jQuery(this).val());
		});
		
		objUpdate['layer_action'].link_open_in = [];
		jQuery('select[name="layer_link_open_in[]"] option:selected').each(function(){
			objUpdate['layer_action'].link_open_in.push(jQuery(this).val());
		});
		
		objUpdate['layer_action'].jump_to_slide = [];
		jQuery('select[name="jump_to_slide[]"] option:selected').each(function(){
			objUpdate['layer_action'].jump_to_slide.push(jQuery(this).val());
		});
		
		objUpdate['layer_action'].scrollunder_offset = [];
		jQuery('input[name="layer_scrolloffset[]"]').each(function(){
			objUpdate['layer_action'].scrollunder_offset.push(jQuery(this).val());
		});
		
		objUpdate['layer_action'].actioncallback = [];
		jQuery('input[name="layer_actioncallback[]"]').each(function(){
			objUpdate['layer_action'].actioncallback.push(jQuery(this).val());
		});
		
		objUpdate['layer_action'].layer_target = [];
		jQuery('select[name="layer_target[]"] option:selected').each(function(){
			objUpdate['layer_action'].layer_target.push(jQuery(this).val());
		});
		
		objUpdate['layer_action'].link_type = [];
		jQuery('select[name="layer_link_type[]"] option:selected').each(function(){
			objUpdate['layer_action'].link_type.push(jQuery(this).val());
		});
		
		objUpdate['layer_action'].action_delay = [];
		jQuery('input[name="layer_action_delay[]"]').each(function(){
			objUpdate['layer_action'].action_delay.push(jQuery(this).val());
		});
		
		objUpdate['layer_action'].toggle_layer_type = [];
		jQuery('select[name="toggle_layer_type[]"] option:selected').each(function(){
			objUpdate['layer_action'].toggle_layer_type.push(jQuery(this).val());
		});
		
		objUpdate['layer_action'].toggle_class = [];
		jQuery('input[name="layer_toggleclass[]"]').each(function(){
			objUpdate['layer_action'].toggle_class.push(jQuery(this).val());
		});
		
		objUpdate.animation_overwrite = jQuery('#layer-animation-overwrite option:selected').val();
		objUpdate.trigger_memory = jQuery('#layer-tigger-memory option:selected').val();
		
		//ONLY FOR DEBUG!!
		//objUpdate.internal_class = jQuery('#internal_classes').val();
		//objUpdate.type = jQuery('#layer_type option:selected').val();
		
		

		//update object - Write back changes in ObjArray
		updateCurrentLayer(objUpdate, ['layer_action']);


		//update html layers
		updateHtmlLayersFromObject();


		//update html sortbox
		updateHtmlSortboxFromObject();
		
		//event on element for href
		initDisallowCaptionsOnClick();

		var type = objLayer.type || "text";
		
		u.rebuildLayerIdle(t.getHtmlLayerFromSerial(selectedLayerSerial));

		//update the timeline with the new data
		u.updateCurrentLayerTimeline();
		
		t.set_cover_mode();		

	}



	/**
	 *
	 * update sortbox text from object
	 */
	var updateHtmlSortboxFromObject = function(serial){


		serial = serial || selectedLayerSerial;

		var objLayer = t.getLayer(serial),
			htmlSortItem = u.getHtmlSortItemFromSerial(serial);

		
		if(!objLayer || !htmlSortItem) return(false);
				
		var sortboxText = u.getSortboxText(objLayer.alias);
		
		htmlSortItem.find(".timer-layer-text").text(sortboxText);

	}

	/**
	 *
	 * redraw all Layer HTML 
	 *
	 */
	var redrawAllLayerHtml = function() {
		jQuery.each(arrLayers,function(i,obj) {
			redrawLayerHtml(obj.serial);
		});
		jQuery.each(arrLayersDemo,function(i,obj) {
			redrawLayerHtml(obj.serial, true);
		})
		jQuery('.slide_layer').each(function() {
			u.rebuildLayerIdle(jQuery(this));
		});
		updateLayerFormFields(selectedLayerSerial);
	}

	/**
	 * redraw some layer html
	 */
	var redrawLayerHtml = function(serial, isDemo){
		
		if(isDemo == undefined) isDemo = false;
		
		var objLayer = t.getLayer(serial, isDemo);
		var html = t.makeLayerHtml(serial,objLayer, isDemo)
		var htmlInner = jQuery(html).html();
		var htmlLayer = t.getHtmlLayerFromSerial(serial, isDemo);		
		
		htmlLayer.html(htmlInner);
	}

	/**
	 * check if there is value in the object, and it is defined
	 */
	var nix = function(a) {
		return (a===undefined || a.length==0);
	}


	/**
	 * Reset Fields of Styling based on current selected Template
	 */
	var updateSubStyleParameters = function(objLayer, reset_full) {
		
		var reset = (typeof(reset_full) !== 'undefined' && reset_full === true) ? true : false;
		
		var fullstyles = UniteCssEditorRev.getStyleSettingsByHandle(objLayer["style"]);

		var styles = fullstyles.params;
		var hover_styles = fullstyles.hover;
		if(hover_styles == undefined) hover_styles = {};
		
		var is_hover = false;
		
		try{
			is_hover = fullstyles!==undefined ? fullstyles.hasOwnProperty('settings') ? (typeof(fullstyles.settings) !== 'undefined' && typeof(fullstyles.settings.hover) !== undefined) ? fullstyles.settings.hover : false : false : false;
		} catch(e) { 
		}
		
		// INSERT STANDART SETTINGS FROM TEMPLATE STYLE
		if(objLayer.deformation != undefined && styles !== undefined){
			// COLOR TRANSPARENCY
			if(nix(objLayer['deformation']['color-transparency']) || reset)
				objLayer['deformation']['color-transparency'] = !nix(styles["color-transparency"]) ? styles["color-transparency"] : "1";
			
			// FONT STYLE
			if(nix(objLayer['deformation']['font-style']) || reset)
				objLayer['deformation']['font-style'] = !nix(styles["font-style"]) ? styles["font-style"] : "normal";

			// FONT FAMILY
			if(nix(objLayer['deformation']['font-family']) || reset)
				objLayer['deformation']['font-family'] = !nix(styles["font-family"]) ? styles["font-family"] : "Arial"


			// PADDING SETTINGS
			if (nix(objLayer['deformation']['padding']) || (nix(objLayer['deformation']['padding'][0]) && nix(objLayer['deformation']['padding'][1]) &&  nix(objLayer['deformation']['padding'][2]) &&  nix(objLayer['deformation']['padding'][3])) || reset) {
				
				var pads = !nix(styles['padding']) ? typeof(styles['padding']) !== 'object' ? styles['padding'].split(" ") : styles['padding'] : ["0px","0px","0px","0px"];											
				objLayer['deformation']['padding'] = ["0","0","0","0"];

				jQuery(objLayer['deformation']['padding']).each(function(i){
					objLayer['deformation']['padding'][i] = pads.length<2 ? pads[0] : pads.length<4 ? i==0 || i==2 ? pads[0] : pads[1] : pads[i];									
				});
			}

			// TEXT DECORATION
			if (nix(objLayer['deformation']['text-decoration']) || reset)
				objLayer['deformation']['text-decoration'] = !nix(styles["text-decoration"]) ? styles["text-decoration"] : "none";
				
			// TEXT ALIGNMENT
			if (nix(objLayer['deformation']['text-align']) || reset)
				objLayer['deformation']['text-align'] = !nix(styles["text-align"]) ? styles["text-align"] : "left";
				

			// BACKGROUND COLOR
			if (nix(objLayer['deformation']['background-color']) || reset) {
				var hex = !nix(styles['background-color']) ? UniteAdminRev.rgb2hex(styles['background-color']) : "transparent",
					transparency = !nix(styles['background-color']) ? UniteAdminRev.getTransparencyFromRgba(styles['background-color']) : 1;
				transparency = transparency==undefined || transparency == false || transparency == "false" ? 1 : transparency;	
				
				if(!nix(styles['background-transparency'])) transparency = styles['background-transparency'];
				
				objLayer['deformation']['background-color'] = hex;
				objLayer['deformation']['background-transparency'] = transparency;
			}

			// BORDER COLOR
			if (nix(objLayer['deformation']['border-color']) || reset)
				objLayer['deformation']['border-color'] = !nix(styles['border-color']) ? UniteAdminRev.rgb2hex(styles['border-color']) : "transparent";
			
			// BORDER TRANSPARENCY
			if(nix(objLayer['deformation']['border-transparency']) || reset)
				objLayer['deformation']['border-transparency'] = !nix(styles["border-transparency"]) ? styles["border-transparency"] : "1";
			
			// BORDER STYLE
			if (nix(objLayer['deformation']['border-style']) || reset)
				objLayer['deformation']['border-style'] = !nix(styles['border-style']) ? styles['border-style'] : "none";
				
			// BORDER WIDTH
			if (nix(objLayer['deformation']['border-width']) || reset)
				objLayer['deformation']['border-width'] = !nix(styles['border-width']) ? styles['border-width'] : "0";
				
			// BORDER RADIUS
			if (nix(objLayer['deformation']['border-radius']) || (nix(objLayer['deformation']['border-radius'][0]) && nix(objLayer['deformation']['border-radius'][1]) && nix(objLayer['deformation']['border-radius'][2]) && nix(objLayer['deformation']['border-radius'][3])) || reset) {

				var cor = !nix(styles['border-radius']) ? typeof(styles['border-radius']) !== 'object' ? styles['border-radius'].split(" ") : styles['border-radius'] : ["0px","0px","0px","0px"];											
				objLayer['deformation']['border-radius'] = ["0","0","0","0"];

				jQuery(objLayer['deformation']['border-radius']).each(function(i){
					objLayer['deformation']['border-radius'][i] = cor.length<2 ? cor[0] : cor.length<4 ? i==0 || i==2 ? cor[0] : cor[1] : cor[i];									
				});
			}
			
			// CORNER LEFT
			if (nix(objLayer['deformation']['corner_left']) || reset)
				objLayer['deformation']['corner_left'] = !nix(styles['corner_left']) ? styles['corner_left'] : "nothing";
			
			// CORNER RIGHT
			if (nix(objLayer['deformation']['corner_right']) || reset)
				objLayer['deformation']['corner_right'] = !nix(styles['corner_right']) ? styles['corner_right'] : "nothing";
			
			// PARALLAX
			if (nix(objLayer['deformation']['parallax']) || reset)
				objLayer['deformation']['parallax'] = !nix(styles['parallax']) ? styles['parallax'] : "-";
			
			if (nix(objLayer['deformation']['x']) || reset) objLayer['deformation']['x'] = !nix(styles['x']) ? styles['x'] : 0;
			if (nix(objLayer['deformation']['y']) || reset) objLayer['deformation']['y'] = !nix(styles['y']) ? styles['y'] : 0;
			if (nix(objLayer['deformation']['z']) || reset) objLayer['deformation']['z'] = !nix(styles['z']) ? styles['z'] : 0;
			if (nix(objLayer['deformation']['skewx']) || reset) objLayer['deformation']['skewx'] = !nix(styles['skewx']) ? styles['skewx'] : 0;
			if (nix(objLayer['deformation']['skewy']) || reset) objLayer['deformation']['skewy'] = !nix(styles['skewy']) ? styles['skewy'] : 0;
			if (nix(objLayer['deformation']['scalex']) || reset) objLayer['deformation']['scalex'] = !nix(styles['scalex']) ? styles['scalex'] : 1;
			if (nix(objLayer['deformation']['scaley']) || reset) objLayer['deformation']['scaley'] = !nix(styles['scaley']) ? styles['scaley'] : 1;
			if (nix(objLayer['deformation']['opacity']) || reset) objLayer['deformation']['opacity'] = !nix(styles['opacity']) ? styles['opacity'] : 1;
			if (nix(objLayer['deformation']['xrotate']) || reset) objLayer['deformation']['xrotate'] = !nix(styles['xrotate']) ? styles['xrotate'] : 0;
			if (nix(objLayer['deformation']['yrotate']) || reset) objLayer['deformation']['yrotate'] = !nix(styles['yrotate']) ? styles['yrotate'] : 0;
			if (nix(objLayer['2d_rotation']) || reset) objLayer['2d_rotation'] =  !nix(styles['2d_rotation']) ? styles['2d_rotation'] : 0;
			if (nix(objLayer['deformation']['2d_origin_x']) || reset) objLayer['deformation']['2d_origin_x'] = !nix(styles['2d_origin_x']) ? styles['2d_origin_x'] : 50;
			if (nix(objLayer['deformation']['2d_origin_y']) || reset) objLayer['deformation']['2d_origin_y'] = !nix(styles['2d_origin_y']) ? styles['2d_origin_y'] : 50;
			if (nix(objLayer['deformation']['pers']) || reset) objLayer['deformation']['pers'] = !nix(styles['pers']) ? styles['pers'] : 600;
			
			
		}

		if(reset){
			if(is_hover === 'true' || is_hover === true){
				jQuery('input[name="hover_allow"]').attr('checked', true);
				jQuery('#idle-hover-swapper').show();
			}else{
				jQuery('input[name="hover_allow"]').attr('checked', false);
				jQuery('#idle-hover-swapper').hide();
			}
		}
		RevSliderSettings.onoffStatus(jQuery('input[name="hover_allow"]'));
		
		if(objLayer.deformation != undefined){
			jQuery('input[name="css_font-family"]').val(objLayer['deformation']['font-family']);
			jQuery('input[name="css_padding[]"]').each(function(i){ jQuery(this).val(objLayer['deformation']['padding'][i]);});
			if(objLayer['deformation']['font-style'] == 'italic')
				jQuery('input[name="css_font-style"]').attr('checked', true); // checkbox
			else
				jQuery('input[name="css_font-style"]').attr('checked', false); // checkbox
			
			RevSliderSettings.onoffStatus(jQuery('input[name="css_font-style"]'));
			
			jQuery('input[name="css_font-transparency"]').val(objLayer['deformation']['color-transparency']);
			jQuery('select[name="css_text-decoration"] option[value="'+objLayer['deformation']['text-decoration']+'"]').attr('selected', true);
			jQuery('select[name="css_text-align"] option[value="'+objLayer['deformation']['text-align']+'"]').attr('selected', true);
			jQuery('input[name="css_background-color"]').val(objLayer['deformation']['background-color']);
			jQuery('input[name="css_background-transparency"]').val(objLayer['deformation']['background-transparency']);
			jQuery('input[name="css_border-color-show"]').val(objLayer['deformation']['border-color']);
			jQuery('input[name="css_border-transparency"]').val(objLayer['deformation']['border-transparency']);
			jQuery('select[name="css_border-style"] option[value="'+objLayer['deformation']['border-style']+'"]').attr('selected', true);
			jQuery('input[name="css_border-width"]').val(objLayer['deformation']['border-width']);
			jQuery('input[name="css_border-radius[]"]').each(function(i){ jQuery(this).val(objLayer['deformation']['border-radius'][i]);});
			jQuery('input[name="layer__x"]').val(objLayer['deformation']['x']);
			jQuery('input[name="layer__y"]').val(objLayer['deformation']['y']);
			jQuery('input[name="layer__z"]').val(objLayer['deformation']['z']);
			jQuery('input[name="layer__skewx"]').val(objLayer['deformation']['skewx']);
			jQuery('input[name="layer__skewy"]').val(objLayer['deformation']['skewy']);
			jQuery('input[name="layer__scalex"]').val(objLayer['deformation']['scalex']);
			jQuery('input[name="layer__scaley"]').val(objLayer['deformation']['scaley']);
			jQuery('input[name="layer__opacity"]').val(objLayer['deformation']['opacity']);
			jQuery('input[name="layer__xrotate"]').val(objLayer['deformation']['xrotate']);
			jQuery('input[name="layer__yrotate"]').val(objLayer['deformation']['yrotate']);
			jQuery('input[name="layer_2d_rotation"]').val(objLayer['2d_rotation']);
			jQuery('input[name="layer_2d_origin_x"]').val(objLayer['deformation']['2d_origin_x']);//
			jQuery('input[name="layer_2d_origin_y"]').val(objLayer['deformation']['2d_origin_y']);//
			jQuery('input[name="layer__pers"]').val(objLayer['deformation']['pers']);
			jQuery('select[name="layer_cornerleft"] option[value="'+objLayer['deformation']['corner_left']+'"]').attr('selected', true);
			jQuery('select[name="layer_cornerright"] option[value="'+objLayer['deformation']['corner_right']+'"]').attr('selected', true);
			jQuery('select[name="parallax_level"] option[value="'+objLayer['deformation']['parallax']+'"]').attr('selected', true);
			
			
			
			
			if (nix(objLayer['deformation-hover']['color']) || reset)
				objLayer['deformation-hover']['color'] = !nix(hover_styles['color']) ? hover_styles['color'] : '#000000';
			
			if (nix(objLayer['deformation-hover']['color-transparency']) || reset)
				objLayer['deformation-hover']['color-transparency'] = !nix(hover_styles['color-transparency']) ? hover_styles['color-transparency'] : '1';
			
			if (nix(objLayer['deformation-hover']['text-decoration']) || reset)
				objLayer['deformation-hover']['text-decoration'] = !nix(hover_styles['text-decoration']) ? hover_styles['text-decoration'] : 'none';
			
			if (nix(objLayer['deformation-hover']['background-color']) || reset)
				objLayer['deformation-hover']['background-color'] = !nix(hover_styles['background-color']) ? hover_styles['background-color'] : 'transparent';
			
			if (nix(objLayer['deformation-hover']['background-transparency']) || reset)
				objLayer['deformation-hover']['background-transparency'] = !nix(hover_styles['background-transparency']) ? hover_styles['background-transparency'] : '1';
			
			if (nix(objLayer['deformation-hover']['border-color']) || reset)
				objLayer['deformation-hover']['border-color'] = !nix(hover_styles['border-color']) ? hover_styles['border-color'] : 'transparent';
			
			if (nix(objLayer['deformation-hover']['border-transparency']) || reset)
				objLayer['deformation-hover']['border-transparency'] = !nix(hover_styles['border-transparency']) ? hover_styles['border-transparency'] : '1';
			
			if (nix(objLayer['deformation-hover']['border-style']) || reset)
				objLayer['deformation-hover']['border-style'] = !nix(hover_styles['border-style']) ? hover_styles['border-style'] : 'none';
			
			if (nix(objLayer['deformation-hover']['border-width']) || reset)
				objLayer['deformation-hover']['border-width'] = !nix(hover_styles['border-width']) ? hover_styles['border-width'] : '0';
			
			
			if (nix(objLayer['deformation-hover']['border-radius']) || (nix(objLayer['deformation-hover']['border-radius'][0]) && nix(objLayer['deformation-hover']['border-radius'][1]) && nix(objLayer['deformation-hover']['border-radius'][2]) && nix(objLayer['deformation-hover']['border-radius'][3])) || reset) {

				var cor = !nix(hover_styles['border-radius']) ? typeof(hover_styles['border-radius']) !== 'object' ? hover_styles['border-radius'].split(" ") : hover_styles['border-radius'] : ["0px","0px","0px","0px"];											
				objLayer['deformation-hover']['border-radius'] = ["0","0","0","0"];

				jQuery(objLayer['deformation-hover']['border-radius']).each(function(i){
					objLayer['deformation-hover']['border-radius'][i] = cor.length<2 ? cor[0] : cor.length<4 ? i==0 || i==2 ? cor[0] : cor[1] : cor[i];									
				});
			}
			
			if (nix(objLayer['deformation-hover']['skewx']) || reset)
				objLayer['deformation-hover']['skewx'] = !nix(hover_styles['skewx']) ? hover_styles['skewx'] : 0;
			if (nix(objLayer['deformation-hover']['skewy']) || reset)
				objLayer['deformation-hover']['skewy'] = !nix(hover_styles['skewy']) ? hover_styles['skewy'] : 0;
			if (nix(objLayer['deformation-hover']['scalex']) || reset)
				objLayer['deformation-hover']['scalex'] = !nix(hover_styles['scalex']) ? hover_styles['scalex'] : 1;
			if (nix(objLayer['deformation-hover']['scaley']) || reset)
				objLayer['deformation-hover']['scaley'] = !nix(hover_styles['scaley']) ? hover_styles['scaley'] : 1;
			if (nix(objLayer['deformation-hover']['opacity']) || reset)
				objLayer['deformation-hover']['opacity'] = !nix(hover_styles['opacity']) ? hover_styles['opacity'] : 1;
			if (nix(objLayer['deformation-hover']['xrotate']) || reset)
				objLayer['deformation-hover']['xrotate'] = !nix(hover_styles['xrotate']) ? hover_styles['xrotate'] : 0;
			if (nix(objLayer['deformation-hover']['yrotate']) || reset)
				objLayer['deformation-hover']['yrotate'] = !nix(hover_styles['yrotate']) ? hover_styles['yrotate'] : 0;
			if (nix(objLayer['deformation-hover']['2d_rotation']) || reset)
				objLayer['deformation-hover']['2d_rotation'] = !nix(hover_styles['2d_rotation']) ? hover_styles['2d_rotation'] : 0;
			if (nix(objLayer['deformation-hover']['css_cursor']) || reset)
				objLayer['deformation-hover']['css_cursor'] = !nix(hover_styles['css_cursor']) ? hover_styles['css_cursor'] : 'auto';
			
			/* not included yet, missing values */
			if (nix(objLayer['deformation-hover']['speed']) || reset)
				objLayer['deformation-hover']['speed'] = !nix(hover_styles['speed']) ? hover_styles['speed'] : '0';
			
			if (nix(objLayer['deformation-hover']['easing']) || reset)
				objLayer['deformation-hover']['easing'] = !nix(hover_styles['easing']) ? hover_styles['easing'] : 'Linear.easeNone';
			
			/* ENDE not included yet, missing values */
			
			if(objLayer['deformation-hover'] != undefined){
				jQuery('input[name="hover_layer_color_s"]').val(objLayer['deformation-hover']['color']);
				jQuery('input[name="hover_css_font-transparency"]').val(objLayer['deformation-hover']['color-transparency']);
				jQuery('input[name="hover_color_static"]').val(objLayer['deformation-hover']['color']);
				jQuery('select[name="hover_css_text-decoration"] option[value="'+objLayer['deformation-hover']['text-decoration']+'"]').attr('selected', true);
				jQuery('input[name="hover_css_background-color"]').val(objLayer['deformation-hover']['background-color']);
				jQuery('input[name="hover_css_background-transparency"]').val(objLayer['deformation-hover']['background-transparency']);
				jQuery('input[name="hover_css_border-color-show"]').val(objLayer['deformation-hover']['border-color']);
				jQuery('input[name="hover_css_border-transparency"]').val(objLayer['deformation-hover']['border-transparency']);
				jQuery('select[name="hover_css_border-style"] option[value="'+objLayer['deformation-hover']['border-style']+'"]').attr('selected', true);
				jQuery('input[name="hover_css_border-width"]').val(objLayer['deformation-hover']['border-width']);
				jQuery('input[name="hover_css_border-radius[]"]').each(function(i){ jQuery(this).val(objLayer['deformation-hover']['border-radius'][i]); });
				jQuery('input[name="hover_layer__skewx"]').val(objLayer['deformation-hover']['skewx']);
				jQuery('input[name="hover_layer__skewy"]').val(objLayer['deformation-hover']['skewy']);
				jQuery('input[name="hover_layer__scalex"]').val(objLayer['deformation-hover']['scalex']);
				jQuery('input[name="hover_layer__scaley"]').val(objLayer['deformation-hover']['scaley']);
				jQuery('input[name="hover_layer__opacity"]').val(objLayer['deformation-hover']['opacity']);
				jQuery('input[name="hover_layer__xrotate"]').val(objLayer['deformation-hover']['xrotate']);
				jQuery('input[name="hover_layer__yrotate"]').val(objLayer['deformation-hover']['yrotate']);
				jQuery('input[name="hover_layer_2d_rotation"]').val(objLayer['deformation-hover']['2d_rotation']); //z rotate
				jQuery('select[name="css_cursor"] option[value="'+objLayer['deformation-hover']['css_cursor']+'"]').attr('selected', true);
				
				jQuery('input[name="hover_speed"]').val(objLayer['deformation-hover']['speed']);
				jQuery('select[name="hover_easing"] option[value="'+objLayer['deformation-hover']['easing']+'"]').attr('selected', true);
				
			}
		}
		
		jQuery('.wp-color-result').each(function(){
			jQuery(this).css('backgroundColor', jQuery(this).parent().find('.my-color-field').val());
		});
		return objLayer;

	}




	/**
	 * update layer parameters from the object
	 */
	var updateLayerFormFields = function(serial){

		var objLayer = arrLayers[serial];
		
		if(typeof(objLayer) == 'undefined') return true;
		
		jQuery('#internal_classes').val(objLayer.internal_class);
		
		//ONLY FOR DEBUG!!
		//jQuery('#layer_type option[value="'+objLayer.type+'"]').attr('selected', true);
		
		jQuery('.rs-internal-class-wrapper').text(objLayer.internal_class);
		
		jQuery('#layer_caption').val(objLayer.style);

		jQuery('#layer_text').val(UniteAdminRev.stripslashes(objLayer.text));				
		jQuery('#layer_text').data('new_content',false)
		jQuery('#layer_alt_option option[value="'+objLayer.alt_option+'"]').attr('selected', 'selected');
		jQuery('#layer_alt').val(objLayer.alt);
		
		jQuery('#layer_alias_name').val(objLayer.alias);
		
		if(objLayer['hover'] == 'true' || objLayer['hover'] == true){
			jQuery('input[name="hover_allow"]').prop("checked", true);
			jQuery('#idle-hover-swapper').show();
		}else{
			jQuery('input[name="hover_allow"]').prop("checked", false);
			jQuery('#idle-hover-swapper').hide();
		}
		
		if(objLayer['visible-notebook'] == 'true' || objLayer['visible-notebook'] == true)
			jQuery('input[name="visible-notebook"]').prop('checked',true);
		else
			jQuery('input[name="visible-notebook"]').prop('checked',false);

		if(objLayer['visible-desktop'] == "true" || objLayer['visible-desktop'] == true)
			jQuery('input[name="visible-desktop"]').prop('checked',true);
		else
			jQuery('input[name="visible-desktop"]').prop('checked',false);

		if(objLayer['visible-tablet'] == "true" || objLayer['visible-tablet'] == true)
			jQuery('input[name="visible-tablet"]').prop('checked',true);
		else
			jQuery('input[name="visible-tablet"]').prop('checked',false);

		if(objLayer['visible-mobile'] == "true" || objLayer['visible-mobile'] == true)
			jQuery('input[name="visible-mobile"]').prop('checked',true);
		else
			jQuery('input[name="visible-mobile"]').prop('checked',false);
		
		if(objLayer['show-on-hover'] == "true" || objLayer['show-on-hover'] == true)
			jQuery('input[name="layer_on_slider_hover"]').prop('checked',true);
		else
			jQuery('input[name="layer_on_slider_hover"]').prop('checked',false);
		
		jQuery('#layer-lazy-loading option[value="'+objLayer['lazy-load']+'"]').attr('selected', 'selected');
		jQuery('#layer-image-size option[value="'+objLayer['image-size']+'"]').attr('selected', 'selected');
		
		RevSliderSettings.onoffStatus(jQuery('input[name="hover_allow"]'));
		RevSliderSettings.onoffStatus(jQuery('input[name="visible-desktop"]'));
		RevSliderSettings.onoffStatus(jQuery('input[name="visible-notebook"]'));
		RevSliderSettings.onoffStatus(jQuery('input[name="visible-tablet"]'));
		RevSliderSettings.onoffStatus(jQuery('input[name="visible-mobile"]'));
		RevSliderSettings.onoffStatus(jQuery('input[name="layer_on_slider_hover"]'));
		
		jQuery("#layer_scaleX").val(specOrVal(t.getVal(objLayer,'scaleX'),["auto"],"px"));
		jQuery("#layer_scaleY").val(specOrVal(t.getVal(objLayer,'scaleY'),["auto"],"px"));
		
		jQuery('#layer_cover_mode option[value="'+objLayer['cover_mode']+'"]').attr('selected', 'selected');
		
		jQuery("#layer_max_height").val(specOrVal(t.getVal(objLayer,'max_height'),["auto"],"px"));
		jQuery("#layer_max_width").val(specOrVal(t.getVal(objLayer,'max_width'),["auto"],"px"));
		
		jQuery("#layer_video_height").val(t.getVal(objLayer,'video_height'),["auto"],"px");
		jQuery("#layer_video_width").val(t.getVal(objLayer,'video_width'),["auto"],"px");
		
		jQuery("#layer_2d_rotation").val(objLayer['2d_rotation']);
		jQuery("#layer_2d_origin_x").val(objLayer['2d_origin_x']);
		jQuery("#layer_2d_origin_y").val(objLayer['2d_origin_y']);

		jQuery("#layer_static_start option[value='"+objLayer.static_start+"']").attr('selected', 'selected');
		changeEndStaticFunctions();
		jQuery("#layer_static_end option[value='"+objLayer.static_end+"']").attr('selected', 'selected');

		jQuery("#layer_whitespace option[value='"+t.getVal(objLayer, 'whitespace')+"']").attr('selected', 'selected');

		if(objLayer.scaleProportional == "true" || objLayer.scaleProportional == true){
			jQuery('.rs-proportion-check').removeClass('notselected');
			jQuery("#layer_proportional_scale").prop("checked",true);
		}else{
			jQuery("#layer_proportional_scale").prop("checked",false);
			jQuery('.rs-proportion-check').addClass('notselected');
		}


		if (t.getVal(objLayer, 'whitespace') ==="normal") {
			jQuery('.rs-linebreak-check').removeClass("notselected");
			jQuery('#layer_auto_line_break').prop("checked",true);
		} else {
			jQuery('.rs-linebreak-check').addClass("notselected");
			jQuery('#layer_auto_line_break').prop("checked",false);
		}
		
		RevSliderSettings.onoffStatus(jQuery('.rs-proportion-check'));
		RevSliderSettings.onoffStatus(jQuery('.rs-linebreak-check'));
		
		jQuery("#layer_top").val(parseInt(t.getVal(objLayer, 'top'),0)+"px");
		jQuery("#layer_left").val(parseInt(t.getVal(objLayer, 'left'),0)+"px");
		
		//set Loop Animations
		jQuery("#layer_loop_animation option[value='"+objLayer.loop_animation+"']").attr('selected', 'selected');
		jQuery("#layer_loop_easing").val(objLayer.loop_easing);
		jQuery("#layer_loop_speed").val(objLayer.loop_speed);
		jQuery("#layer_loop_startdeg").val(objLayer.loop_startdeg);
		jQuery("#layer_loop_enddeg").val(objLayer.loop_enddeg);
		jQuery("#layer_loop_xorigin").val(objLayer.loop_xorigin);
		jQuery("#layer_loop_yorigin").val(objLayer.loop_yorigin);
		jQuery("#layer_loop_xstart").val(objLayer.loop_xstart);
		jQuery("#layer_loop_xend").val(objLayer.loop_xend);
		jQuery("#layer_loop_ystart").val(objLayer.loop_ystart);
		jQuery("#layer_loop_yend").val(objLayer.loop_yend);
		jQuery("#layer_loop_zoomstart").val(objLayer.loop_zoomstart);
		jQuery("#layer_loop_zoomend").val(objLayer.loop_zoomend);
		jQuery("#layer_loop_angle").val(objLayer.loop_angle);
		jQuery("#layer_loop_radius").val(objLayer.loop_radius);
		
		if(objLayer.mask_start == "true" || objLayer.mask_start == true) {
			jQuery('input[name="masking-start"]').attr("checked", true);
			jQuery('.mask-start-settings').show();						 	
		}
		else {
			jQuery('input[name="masking-start"]').removeAttr("checked");
			jQuery('.mask-start-settings').hide();
		}
		
		

		if(objLayer.mask_end == "true" || objLayer.mask_end == true) {
			jQuery('input[name="masking-end"]').attr("checked", true);
			jQuery('.mask-end-settings').show();
		}
		else {
			jQuery('input[name="masking-end"]').removeAttr("checked");
			jQuery('.mask-end-settings').hide();
		}
	
		

		RevSliderSettings.onoffStatus(jQuery('input[name="masking-start"]'));		
		RevSliderSettings.onoffStatus(jQuery('input[name="masking-end"]'));
		


		jQuery("#mask_anim_xstart").val(objLayer.mask_x_start);
		jQuery("#mask_anim_ystart").val(objLayer.mask_y_start);
		jQuery("#mask_speed").val(objLayer.mask_speed_start);
		jQuery("#mask_easing").val(objLayer.mask_ease_start);
		
		jQuery("#mask_anim_xend").val(objLayer.mask_x_end);
		jQuery("#mask_anim_yend").val(objLayer.mask_y_end);
		jQuery("#mask_speed_end").val(objLayer.mask_speed_end);
		jQuery("#mask_easing_end").val(objLayer.mask_ease_end);
		
		jQuery("#layer_animation option[value='"+objLayer.animation+"']").attr('selected', 'selected');

		jQuery("#layer_easing").val(objLayer.easing);

		jQuery("#layer_split").val(objLayer.split);
		jQuery("#layer_endsplit").val(objLayer.endsplit);
		jQuery("#layer_splitdelay").val(objLayer.splitdelay);
		jQuery("#layer_endsplitdelay").val(objLayer.endsplitdelay);

		
		jQuery("#layer_speed").val(objLayer.speed);

		jQuery("#layer_align_hor").val(t.getVal(objLayer,'align_hor'));
		jQuery("#layer_align_vert").val(t.getVal(objLayer,'align_vert'));

		if(objLayer.hiddenunder == "true" || objLayer.hiddenunder == true)
			jQuery("#layer_hidden").prop("checked",true);
		else
			jQuery("#layer_hidden").prop("checked",false);
		
		if(objLayer.resizeme == "true" || objLayer.resizeme == true)
			jQuery("#layer_resizeme").prop("checked",true);
		else
			jQuery("#layer_resizeme").prop("checked",false);
		
		if(objLayer['seo-optimized'] == "true" || objLayer['seo-optimized'] == true)
			jQuery("#layer-seo-optimized").prop("checked",true);
		else
			jQuery("#layer-seo-optimized").prop("checked",false);
		
		if(objLayer['resize-full'] == "true" || objLayer['resize-full'] == true){
			jQuery("#layer_resize-full").prop("checked",true);
		}else{
			jQuery("#layer_resize-full").prop("checked",false);
			jQuery("#layer_resizeme").prop("checked",false);
			objLayer.resizeme = false; //remove checked state!
		}
		
		jQuery("#layer_align_base").val(objLayer.basealign);
		
		if(objLayer.responsive_offset == "true" || objLayer.responsive_offset == true)
			jQuery("#layer_resp_offset").prop("checked",true);
		else
			jQuery("#layer_resp_offset").prop("checked",false);
		
		RevSliderSettings.onoffStatus(jQuery("#layer_hidden"));
		RevSliderSettings.onoffStatus(jQuery("#layer_resizeme"));
		RevSliderSettings.onoffStatus(jQuery("#layer_resize-full"));
		RevSliderSettings.onoffStatus(jQuery("#layer_resp_offset"));
		RevSliderSettings.onoffStatus(jQuery("#layer-seo-optimized"));
		
		jQuery("#layer_image_link").val(objLayer.link);
		jQuery("#layer_link_open_in").val(objLayer.link_open_in);
		jQuery("#layer_link_id").val(objLayer.link_id);
		jQuery("#layer_link_class").val(objLayer.link_class);
		jQuery("#layer_link_title").val(objLayer.link_title);
		jQuery("#layer_link_rel").val(objLayer.link_rel);

		jQuery('#layer_auto_line_break').val(objLayer.autolinebreak);

		jQuery("#layer_endanimation").val(objLayer.endanimation);
		jQuery("#layer_endeasing").val(objLayer.endeasing);
		jQuery("#layer_endspeed").val(objLayer.endspeed);

		jQuery("#layer_anim_xstart").val(objLayer.x_start);
		jQuery("#layer_anim_ystart").val(objLayer.y_start);
		jQuery("#layer_anim_zstart").val(objLayer.z_start);
		jQuery("#layer_anim_xend").val(objLayer.x_end);
		jQuery("#layer_anim_yend").val(objLayer.y_end);
		jQuery("#layer_anim_zend").val(objLayer.z_end);
		jQuery("#layer_opacity_start").val(objLayer.opacity_start);
		jQuery("#layer_opacity_end").val(objLayer.opacity_end);
		jQuery("#layer_anim_xrotate").val(objLayer.x_rotate_start);
		jQuery("#layer_anim_yrotate").val(objLayer.y_rotate_start);
		jQuery("#layer_anim_zrotate").val(objLayer.z_rotate_start);
		jQuery("#layer_anim_xrotate_end").val(objLayer.x_rotate_end);
		jQuery("#layer_anim_yrotate_end").val(objLayer.y_rotate_end);
		jQuery("#layer_anim_zrotate_end").val(objLayer.z_rotate_end);
		jQuery("#layer_scale_xstart").val(objLayer.scale_x_start);
		jQuery("#layer_scale_ystart").val(objLayer.scale_y_start);
		jQuery("#layer_scale_xend").val(objLayer.scale_x_end);
		jQuery("#layer_scale_yend").val(objLayer.scale_y_end);
		jQuery("#layer_skew_xstart").val(objLayer.skew_x_start);
		jQuery("#layer_skew_ystart").val(objLayer.skew_y_start);
		jQuery("#layer_skew_xend").val(objLayer.skew_x_end);
		jQuery("#layer_skew_yend").val(objLayer.skew_y_end);
		jQuery("#layer_anim_xoriginstart").val(objLayer.loop_xorigin); //objLayer.x_origin_start
		jQuery("#layer_anim_yoriginstart").val(objLayer.loop_yorigin); //objLayer.y_origin_start
		jQuery("#layer_anim_xoriginend").val(); //objLayer.x_origin_end
		jQuery("#layer_anim_yoriginend").val(objLayer.loop_yorigin); //objLayer.y_origin_end

		jQuery("#layer_pers_start").val(objLayer.pers_start);
		jQuery("#layer_pers_end").val(objLayer.pers_end);
				
		if(typeof(objLayer.x_start_reverse) !== 'undefined' && (objLayer.x_start_reverse == "true" || objLayer.x_start_reverse == true)) { jQuery('input[name="layer_anim_xstart_reverse"]').attr('checked',true); }else{ jQuery('input[name="layer_anim_xstart_reverse"]').removeAttr('checked'); }
		if(typeof(objLayer.y_start_reverse) !== 'undefined' && (objLayer.y_start_reverse == "true" || objLayer.y_start_reverse == true)) { jQuery('input[name="layer_anim_ystart_reverse"]').attr('checked',true); }else{ jQuery('input[name="layer_anim_ystart_reverse"]').removeAttr('checked'); }
		if(typeof(objLayer.x_end_reverse) !== 'undefined' && (objLayer.x_end_reverse == "true" || objLayer.x_end_reverse == true)) { jQuery('input[name="layer_anim_xend_reverse"]').attr('checked',true); }else{ jQuery('input[name="layer_anim_xend_reverse"]').removeAttr('checked'); }
		if(typeof(objLayer.y_end_reverse) !== 'undefined' && (objLayer.y_end_reverse == "true" || objLayer.y_end_reverse == true)) { jQuery('input[name="layer_anim_yend_reverse"]').attr('checked',true); }else{ jQuery('input[name="layer_anim_yend_reverse"]').removeAttr('checked'); }
		if(typeof(objLayer.x_rotate_start_reverse) !== 'undefined' && (objLayer.x_rotate_start_reverse == "true" || objLayer.x_rotate_start_reverse == true)) { jQuery('input[name="layer_anim_xrotate_reverse"]').attr('checked',true); }else{ jQuery('input[name="layer_anim_xrotate_reverse"]').removeAttr('checked'); }
		if(typeof(objLayer.y_rotate_start_reverse) !== 'undefined' && (objLayer.y_rotate_start_reverse == "true" || objLayer.y_rotate_start_reverse == true)) { jQuery('input[name="layer_anim_yrotate_reverse"]').attr('checked',true); }else{ jQuery('input[name="layer_anim_yrotate_reverse"]').removeAttr('checked'); }
		if(typeof(objLayer.z_rotate_start_reverse) !== 'undefined' && (objLayer.z_rotate_start_reverse == "true" || objLayer.z_rotate_start_reverse == true)) { jQuery('input[name="layer_anim_zrotate_reverse"]').attr('checked',true); }else{ jQuery('input[name="layer_anim_zrotate_reverse"]').removeAttr('checked'); }
		if(typeof(objLayer.x_rotate_end_reverse) !== 'undefined' && (objLayer.x_rotate_end_reverse == "true" || objLayer.x_rotate_end_reverse == true)) { jQuery('input[name="layer_anim_xrotate_end_reverse"]').attr('checked',true); }else{ jQuery('input[name="layer_anim_xrotate_end_reverse"]').removeAttr('checked'); }
		if(typeof(objLayer.y_rotate_end_reverse) !== 'undefined' && (objLayer.y_rotate_end_reverse == "true" || objLayer.y_rotate_end_reverse == true)) { jQuery('input[name="layer_anim_yrotate_end_reverse"]').attr('checked',true); }else{ jQuery('input[name="layer_anim_yrotate_end_reverse"]').removeAttr('checked'); }
		if(typeof(objLayer.z_rotate_end_reverse) !== 'undefined' && (objLayer.z_rotate_end_reverse == "true" || objLayer.z_rotate_end_reverse == true)) { jQuery('input[name="layer_anim_zrotate_end_reverse"]').attr('checked',true); }else{ jQuery('input[name="layer_anim_zrotate_end_reverse"]').removeAttr('checked'); }
		if(typeof(objLayer.scale_x_start_reverse) !== 'undefined' && (objLayer.scale_x_start_reverse == "true" || objLayer.scale_x_start_reverse == true)) { jQuery('input[name="layer_scale_xstart_reverse"]').attr('checked',true); }else{ jQuery('input[name="layer_scale_xstart_reverse"]').removeAttr('checked'); }
		if(typeof(objLayer.scale_y_start_reverse) !== 'undefined' && (objLayer.scale_y_start_reverse == "true" || objLayer.scale_y_start_reverse == true)) { jQuery('input[name="layer_scale_ystart_reverse"]').attr('checked',true); }else{ jQuery('input[name="layer_scale_ystart_reverse"]').removeAttr('checked'); }
		if(typeof(objLayer.scale_x_end_reverse) !== 'undefined' && (objLayer.scale_x_end_reverse == "true" || objLayer.scale_x_end_reverse == true)) { jQuery('input[name="layer_scale_xend_reverse"]').attr('checked',true); }else{ jQuery('input[name="layer_scale_xend_reverse"]').removeAttr('checked'); }
		if(typeof(objLayer.scale_y_end_reverse) !== 'undefined' && (objLayer.scale_y_end_reverse == "true" || objLayer.scale_y_end_reverse == true)) { jQuery('input[name="layer_scale_yend_reverse"]').attr('checked',true); }else{ jQuery('input[name="layer_scale_yend_reverse"]').removeAttr('checked'); }
		if(typeof(objLayer.skew_x_start_reverse) !== 'undefined' && (objLayer.skew_x_start_reverse == "true" || objLayer.skew_x_start_reverse == true)) { jQuery('input[name="layer_skew_xstart_reverse"]').attr('checked',true); }else{ jQuery('input[name="layer_skew_xstart_reverse"]').removeAttr('checked'); }
		if(typeof(objLayer.skew_y_start_reverse) !== 'undefined' && (objLayer.skew_y_start_reverse == "true" || objLayer.skew_y_start_reverse == true)) { jQuery('input[name="layer_skew_ystart_reverse"]').attr('checked',true); }else{ jQuery('input[name="layer_skew_ystart_reverse"]').removeAttr('checked'); }
		if(typeof(objLayer.skew_x_end_reverse) !== 'undefined' && (objLayer.skew_x_end_reverse == "true" || objLayer.skew_x_end_reverse == true)) { jQuery('input[name="layer_skew_xend_reverse"]').attr('checked',true); }else{ jQuery('input[name="layer_skew_xend_reverse"]').removeAttr('checked'); }
		if(typeof(objLayer.skew_y_end_reverse) !== 'undefined' && (objLayer.skew_y_end_reverse == "true" || objLayer.skew_y_end_reverse == true)) { jQuery('input[name="layer_skew_yend_reverse"]').attr('checked',true); }else{ jQuery('input[name="layer_skew_yend_reverse"]').removeAttr('checked'); }
		if(typeof(objLayer.mask_x_start_reverse) !== 'undefined' && (objLayer.mask_x_start_reverse == "true" || objLayer.mask_x_start_reverse == true)) { jQuery('input[name="mask_anim_xstart_reverse"]').attr('checked',true); }else{ jQuery('input[name="mask_anim_xstart_reverse"]').removeAttr('checked'); }
		if(typeof(objLayer.mask_y_start_reverse) !== 'undefined' && (objLayer.mask_y_start_reverse == "true" || objLayer.mask_y_start_reverse == true)) { jQuery('input[name="mask_anim_ystart_reverse"]').attr('checked',true); }else{ jQuery('input[name="mask_anim_ystart_reverse"]').removeAttr('checked'); }
		if(typeof(objLayer.mask_x_end_reverse) !== 'undefined' && (objLayer.mask_x_end_reverse == "true" || objLayer.mask_x_end_reverse == true)) { jQuery('input[name="mask_anim_xend_reverse"]').attr('checked',true); }else{ jQuery('input[name="mask_anim_xend_reverse"]').removeAttr('checked'); }
		if(typeof(objLayer.mask_y_end_reverse) !== 'undefined' && (objLayer.mask_y_end_reverse == "true" || objLayer.mask_y_end_reverse == true)) { jQuery('input[name="mask_anim_yend_reverse"]').attr('checked',true); }else{ jQuery('input[name="mask_anim_yend_reverse"]').removeAttr('checked'); }
		
		t.updateReverseList();


		//set advanced params

		// SET CURRENT TIMING HELPERS
		jQuery('#clayer_start_time').val(objLayer.time);
		jQuery('#clayer_end_time').val(objLayer.endtime);
		jQuery('#clayer_start_speed').val(objLayer.speed);
		jQuery('#clayer_end_speed').val(objLayer.endspeed);


		if(objLayer['static_styles'] != undefined){
			jQuery("#layer_font_size_s").val(t.getVal(objLayer['static_styles'], 'font-size'));
			jQuery("#layer_line_height_s").val(t.getVal(objLayer['static_styles'], 'line-height'));
			jQuery("#layer_font_weight_s option[value='"+t.getVal(objLayer['static_styles'], 'font-weight')+"']").attr('selected', true);
			jQuery("#layer_color_s").val(t.getVal(objLayer['static_styles'], 'color'));
		}
		
		
		if(objLayer.animation_overwrite != undefined)
			jQuery('#layer-animation-overwrite option[value="'+objLayer.animation_overwrite+'"]').attr('selected', true);
		else
			jQuery('#layer-animation-overwrite option[value="wait"]').attr('selected', true);
			
		
		if(objLayer.trigger_memory != undefined)
			jQuery('#layer-tigger-memory option[value="'+objLayer.trigger_memory+'"]').attr('selected', true);
		else
			jQuery('#layer-tigger-memory option[value="keep"]').attr('selected', true);

		//deformation part

		
		//jQuery("#layer_slide_link").val(objLayer.link_slide);
		//jQuery("#layer_scrolloffset").val(objLayer.scrollunder_offset);
		
		//remove all html actions first
		t.remove_layer_actions();
		
		t.add_layer_actions(objLayer);

		// Reset Fields from Style Template
		objLayer = updateSubStyleParameters(objLayer);
		//KRISZTIAN ( UPDATE FIELDS VON LAYERS ON FOCUS) "Further Changes" From Deformations


		var vaHor = t.getVal(objLayer,'align_hor'),
			vaVer = t.getVal(objLayer,'align_vert');

		jQuery("#rs-align-wrapper a").removeClass("selected");
		jQuery("#rs-align-wrapper-ver a").removeClass("selected");
		
		jQuery("#rs-align-wrapper a[data-hor='"+vaHor+"']").addClass("selected");
		jQuery("#rs-align-wrapper-ver a[data-ver='"+vaVer+"']").addClass("selected");

		jQuery("#layer_id").val(objLayer.attrID);
		jQuery("#layer_classes").val(objLayer.attrClasses);
		jQuery("#layer_title").val(objLayer.attrTitle);
		jQuery("#layer_rel").val(objLayer.attrRel);

		//show / hide go under slider offset row
		jQuery('select[name="layer_action[]"], select[name="no_layer_action[]"]').each(function() {
			showHideLinkActions(jQuery(this));
		});
		showHideToolTip();
		showHideLoopFunctions(); //has to be the last thing done to not interrupt settings
	}


	/**
	 * unselect all html layers
	 */
	var unselectHtmlLayers = function(){
		
		jQuery(containerID + " .slide_layer.layer_selected").each(function() {
			try {
				jQuery(this).resizable("destroy");
				jQuery(this).find('.innerslide_layer').rotatable("destroy");
			} catch(e) {}
		});

		jQuery(containerID + " .slide_layer").removeClass("layer_selected");
		
	}


	/**
	 * set all layers unselected
	 */
	var unselectLayers = function(){
		
		unselectHtmlLayers();
		u.unselectSortboxItems();
		selectedLayerSerial = -1;
		disableFormFields();
		jQuery('#layer-short-toolbar').appendTo(jQuery('#layer-settings-toolbar-bottom'));
		jQuery('#layer_text_wrapper').appendTo(jQuery('#layer_text_holder'));

		//reset elements
		jQuery("#layer_alt_row").hide();
		jQuery("#layer_scale_title_row").hide();
		jQuery("#layer_max_width").show();
		jQuery("#layer_max_height").show();
		jQuery("#layer_whitespace_row").hide();
		jQuery("#layer_scaleX").hide();
		jQuery("#layer_scaleY").hide();
		jQuery("#layer_proportional_scale").parent().css('visibility', 'hidden');
		jQuery("#reset-scale").css('visibility', 'hidden');

		jQuery("#layer_image_link_row").hide();
		jQuery("#layer_link_id_row").hide();
		jQuery("#layer_link_class_row").hide();
		jQuery("#layer_link_title_row").hide();
		jQuery("#layer_link_rel_row").hide();
		jQuery("#layer_link_open_in_row").hide();

		
		t.showHideContentEditor(false);
		
		jQuery('.form_layers').addClass('notselected');
		
		jQuery('#toggle-idle-hover .icon-styleidle').trigger("click");
		
		jQuery('#idle-hover-swapper').hide();

		u.allLayerToIdle();
		
	}

	t.toolbarInPos = function(objLayer) {		
		if (objLayer)
			switch (objLayer.type) {
				case "image":
					jQuery('#button_change_image_source').show();
					jQuery('#button_edit_layer').hide();
					jQuery('#button_reset_size').show();
					jQuery('#button_change_video_settings').hide();
				break;
				case "text":
				case "button":
					if (!jQuery('#layer_text_wrapper').hasClass("currently_editing_txt")) {
						jQuery('#button_edit_layer').show();
						jQuery('#button_reset_size').show();
					} else {

						jQuery('#button_edit_layer').hide();
						jQuery('#button_reset_size').hide();
					}
					jQuery('#button_change_image_source').hide();
					
					jQuery('#button_change_video_settings').hide();
				break;
				case "video":
					jQuery('#button_edit_layer').hide();
					jQuery('#button_change_image_source').hide();
					jQuery('#button_reset_size').hide();
					jQuery('#button_change_video_settings').show();					
				break;
				case 'shape':
					jQuery('#button_change_image_source').hide();
					jQuery('#button_change_video_settings').hide();
					jQuery('#button_edit_layer').hide();
					jQuery('#button_reset_size').hide();
				break;
				/*case 'no_edit':
					jQuery('#button_change_image_source').hide();
					jQuery('#button_change_video_settings').hide();
					jQuery('#button_edit_layer').hide();
					jQuery('#button_reset_size').hide();
				break;*/
				case 'typeA':
				break;
				case 'typeB':
				break;
			}

		setTimeout(function() {
			var layer = jQuery('.slide_layer.layer_selected');
			
			if (layer!==undefined && layer.length>0) {
				var pos = layer.position();	


				if (pos.top<35)
					jQuery('#layer-short-toolbar').addClass("tobottom")
				else
					jQuery('#layer-short-toolbar').removeClass("tobottom")
				
				

				var dw = jQuery('#divLayers').width(),
					dh = jQuery('#divLayers').height(),
					lw = layer.width(),
					lwc = lw<115 ? 115 : lw,
					rl = pos.left+lwc-jQuery('#divLayers').position().left;

				
				
			

				if (rl>dw) {
					jQuery('#layer-short-toolbar').addClass("toleft")
					if (lw<115)
						jQuery('#layer-short-toolbar').css({right:(115-lw)+"px",left:"auto"});
					else
						jQuery('#layer-short-toolbar').css({right:"auto",left:"0px"});
				}
				else {
					jQuery('#layer-short-toolbar').removeClass("toleft")
					jQuery('#layer-short-toolbar').css({right:"0px",left:"auto"})
				}

				if (lw>dw) {
					jQuery('#layer-short-toolbar').removeClass("toleft");
					jQuery('#layer-short-toolbar').css({right:"50%",left:"auto"})
				}

				if (layer.height()>=dh-30) {
					jQuery('#layer-short-toolbar').removeClass("tobottom");					
					if (pos.top<0)
						jQuery('#layer-short-toolbar').removeClass("toinside").addClass("tobottominside");
					else
						jQuery('#layer-short-toolbar').removeClass("tobottominside").addClass("toinside");
				} else {
					jQuery('#layer-short-toolbar').removeClass("toinside").removeClass("tobottominside");
				}
			}

		},50);

	}
	
	
	t.remove_layer_actions = function(){
		
		jQuery('.layer_action_wrap').each(function(){
			jQuery(this).remove();
		});
		
	}
	
	t.remove_action = function(o){
		if(confirm(rev_lang.remove_this_action)){
			o.closest('li').remove();
			t.updateLayerFromFields();
		}
	}
	
	t.add_layer_actions = function(obj){
		var clayers = t.getSimpleLayers();
		
		if(obj === undefined){
			var content = global_action_template({'edit': true});
		
			jQuery('.layer_action_add_template').before(content);
			t.updateLayerFromFields();
		}else{
			jQuery('#triggered-element-behavior').hide();
			
			//add all actions from other layers that are directed to the currentlayer
			var current_layer = t.getCurrentLayer();
			
			for(var key in clayers){
				if(clayers[key]['layer_action'] !== undefined){
					var has_trigger = false;
					for(var a in clayers[key]['layer_action']['action']){
						switch(clayers[key]['layer_action']['action'][a]){
							case 'start_in':
							case 'start_out':
							case 'start_video':
							case 'stop_video':
							case 'toggle_layer':
							case 'toggle_video':
							case 'simulate_click':
							case 'toggle_class':
								var target_layer = clayers[key]['layer_action']['layer_target'][a];
								if(current_layer.unique_id == target_layer){
									switch(clayers[key]['layer_action']['action'][a]){
										case 'start_in':
										case 'start_out':
										case 'toggle_layer':
											has_trigger = true;
										break;
									}
									
									//if(clayers[key]['layer_action']['action'][a] == 'simulate_click') has_trigger = false;
									
									/*
									var data = {};
									data.edit = false;
									data.tooltip_event = (clayers[key]['layer_action'].tooltip_event !== undefined && clayers[key]['layer_action'].tooltip_event[a] !== undefined) ? clayers[key]['layer_action'].tooltip_event[a] : 'click';
									data.action = (clayers[key]['layer_action'].action !== undefined && clayers[key]['layer_action'].action[a] !== undefined) ? clayers[key]['layer_action'].action[a] : 'none';
									data.image_link = (clayers[key]['layer_action'].image_link !== undefined && clayers[key]['layer_action'].image_link[a] !== undefined) ? clayers[key]['layer_action'].image_link[a] : '';
									data.link_open_in = (clayers[key]['layer_action'].link_open_in !== undefined && clayers[key]['layer_action'].link_open_in[a] !== undefined) ? clayers[key]['layer_action'].link_open_in[a] : 'same';
									data.jump_to_slide = (clayers[key]['layer_action'].jump_to_slide !== undefined && clayers[key]['layer_action'].jump_to_slide[a] !== undefined) ? clayers[key]['layer_action'].jump_to_slide[a] : '';
									data.scrolloffset = (clayers[key]['layer_action'].scrollunder_offset !== undefined && clayers[key]['layer_action'].scrollunder_offset[a] !== undefined) ? clayers[key]['layer_action'].scrollunder_offset[a] : '';
									data.actioncallback = (clayers[key]['layer_action'].actioncallback !== undefined && clayers[key]['layer_action'].actioncallback[a] !== undefined) ? clayers[key]['layer_action'].actioncallback[a] : '';
									data.layer_target = (clayers[key]['layer_action'].layer_target !== undefined && clayers[key]['layer_action'].layer_target[a] !== undefined) ? clayers[key]['layer_action'].layer_target[a] : '';
									data.action_delay = (clayers[key]['layer_action'].action_delay !== undefined && clayers[key]['layer_action'].action_delay[a] !== undefined) ? clayers[key]['layer_action'].action_delay[a] : '';
									data.link_type = (clayers[key]['layer_action'].link_type !== undefined && clayers[key]['layer_action'].link_type[a] !== undefined) ? clayers[key]['layer_action'].link_type[a] : 'jquery';
									data.toggle_layer_type = (clayers[key]['layer_action'].toggle_layer_type !== undefined && clayers[key]['layer_action'].toggle_layer_type[a] !== undefined) ? clayers[key]['layer_action'].toggle_layer_type[a] : 'visible';
									data.toggle_class = (clayers[key]['layer_action'].toggle_class !== undefined && clayers[key]['layer_action'].toggle_class[a] !== undefined) ? clayers[key]['layer_action'].toggle_class[a] : '';
									
									var content = global_action_template(data);
									
									jQuery('.layer_action_add_template').before(content);
									*/
									var act = '';
									switch(clayers[key]['layer_action']['action'][a]){
										case 'start_in':
											act = rev_lang.start_layer_in;
										break;
										case 'start_out':
											act = rev_lang.start_layer_out;
										break;
										case 'start_video':
											act = rev_lang.start_video;
										break;
										case 'stop_video':
											act = rev_lang.stop_video;
										break;
										case 'toggle_layer':
											act = rev_lang.toggle_layer_anim;
										break;
										case 'toggle_video':
											act = rev_lang.toggle_video;
										break;
										case 'simulate_click':
											act = rev_lang.simulate_click;
										break;
										case 'toggle_class':
											act = rev_lang.toggle_class;
										break;
									}
									
									jQuery('.layer_action_add_template').before('<li class="layer_is_triggered layer_action_wrap">'+rev_lang.layer_action_by+' <a href="javascript:UniteLayersRev.setLayerSelected(\''+key+'\');void(0);">'+clayers[key]['alias']+'</a> '+rev_lang.due_to_action+' '+act+'</li>');
								}
							break;
						}
					}
					
					if(has_trigger){
						jQuery('#triggered-element-behavior').show();
					}
					
				}
			}
			jQuery('.rs_disabled_field').each(function(){
				jQuery(this).attr('disabled', 'disabled'); //Disable
			});
		}


		if(obj !== undefined && obj['layer_action'] !== undefined && obj['layer_action'].action !== undefined){
			
			for(var key in obj['layer_action'].action){
				var data = {};
				data.edit = true;
				data.tooltip_event = (obj['layer_action'].tooltip_event !== undefined && obj['layer_action'].tooltip_event[key] !== undefined) ? obj['layer_action'].tooltip_event[key] : 'click';
				data.action = (obj['layer_action'].action !== undefined && obj['layer_action'].action[key] !== undefined) ? obj['layer_action'].action[key] : 'none';
				data.image_link = (obj['layer_action'].image_link !== undefined && obj['layer_action'].image_link[key] !== undefined) ? obj['layer_action'].image_link[key] : '';
				data.link_open_in = (obj['layer_action'].link_open_in !== undefined && obj['layer_action'].link_open_in[key] !== undefined) ? obj['layer_action'].link_open_in[key] : 'same';
				data.jump_to_slide = (obj['layer_action'].jump_to_slide !== undefined && obj['layer_action'].jump_to_slide[key] !== undefined) ? obj['layer_action'].jump_to_slide[key] : '';
				data.scrolloffset = (obj['layer_action'].scrollunder_offset !== undefined && obj['layer_action'].scrollunder_offset[key] !== undefined) ? obj['layer_action'].scrollunder_offset[key] : '';
				data.actioncallback = (obj['layer_action'].actioncallback !== undefined && obj['layer_action'].actioncallback[key] !== undefined) ? obj['layer_action'].actioncallback[key] : '';
				data.layer_target = (obj['layer_action'].layer_target !== undefined && obj['layer_action'].layer_target[key] !== undefined) ? obj['layer_action'].layer_target[key] : '';
				data.action_delay = (obj['layer_action'].action_delay !== undefined && obj['layer_action'].action_delay[key] !== undefined) ? obj['layer_action'].action_delay[key] : '';
				data.link_type = (obj['layer_action'].link_type !== undefined && obj['layer_action'].link_type[key] !== undefined) ? obj['layer_action'].link_type[key] : 'jquery';
				data.toggle_layer_type = (obj['layer_action'].toggle_layer_type !== undefined && obj['layer_action'].toggle_layer_type[key] !== undefined) ? obj['layer_action'].toggle_layer_type[key] : 'visible';
				data.toggle_class = (obj['layer_action'].toggle_class !== undefined && obj['layer_action'].toggle_class[key] !== undefined) ? obj['layer_action'].toggle_class[key] : '';
				
				var content = global_action_template(data);
			
				jQuery('.layer_action_add_template').before(content);
				
			}
		}
		
	
		//add Slides and Layer into select fields, set these values again
		jQuery('select[name="jump_to_slide[]"], select[name="no_jump_to_slide[]"]').each(function(){
			jQuery(this).html('');
			for(var key in slideIDs){
				for(var mkey in slideIDs[key])
					jQuery(this).append(jQuery('<option></option>').val(mkey).text('Slide: '+slideIDs[key][mkey]));
			}
			
			var do_sel = jQuery(this).data('selectoption');
			
			jQuery(this).find('option[value="'+do_sel+'"]').attr('selected', true);
			
		}); //slide
		
		jQuery('select[name="layer_target[]"], select[name="no_layer_target[]"]').each(function(k){
			jQuery(this).html('');
			for(var key in clayers){
				jQuery(this).append(jQuery('<option data-mytype="'+clayers[key].type+'"></option>').val(clayers[key]['unique_id']).text(clayers[key].alias));
			}
			
			var do_sel = jQuery(this).data('selectoption');
			
			jQuery(this).find('option[value="'+do_sel+'"]').attr('selected', true);
			
			var dataAnim = t.getAnimTimingAndTrigger(do_sel);
			
			jQuery(this).closest('li').find('select[name="do-layer-animation-overwrite[]"] option[value="'+dataAnim['animation_overwrite']+'"]').attr('selected', true);
			jQuery(this).closest('li').find('select[name="do-layer-trigger-memory[]"] option[value="'+dataAnim['trigger_memory']+'"]').attr('selected', true);
		}); //layer
		
	}
	
	
	/**
	 * set animation and trigger timings for actions
	 **/
	t.getAnimTimingAndTrigger = function(cur_id){
		
		var clayer = t.getLayerByUniqueId(cur_id);
		
		return {'animation_overwrite': clayer.animation_overwrite,'trigger_memory': clayer.trigger_memory};
		
	}
	
	/**
	 * set animation and trigger timings for actions
	 **/
	t.setAnimTimingAndTrigger = function(cur_id){
		
		var clayer = t.getLayerByUniqueId(cur_id);
		
		return {'animation_overwrite': clayer.animation_overwrite,'trigger_memory': clayer.trigger_memory};
		
	}
	
	
	/*! SET LAYER SELECTED */
	/**
	 * set layer selected representation
	 */
	t.setLayerSelected = function(serial){
		
		if(selectedLayerSerial == serial)
			return(false);
			
		jQuery('#toggle-idle-hover .icon-styleidle').trigger("click");
		if (selectedLayerSerial!=-1) 
			u.rebuildLayerIdle(t.getHtmlLayerFromSerial(selectedLayerSerial),0);

		jQuery('.timer-layer-text:focus').blur();

		t.remove_layer_actions();
		
		objLayer = t.getLayer(serial);
		
		t.showHideContentEditor(false);
		t.toolbarInPos(objLayer);
		
		var layer = t.getHtmlLayerFromSerial(serial);
		
		
		/*if(objLayer.type == 'no_edit'){
			
		}*/
		
		jQuery('#layer-short-toolbar').appendTo(layer);

		//unselect all other layers
		unselectHtmlLayers();

		//set selected class
		layer.addClass("layer_selected");		

		u.setSortboxItemSelected(serial);

		//update selected serial var
		selectedLayerSerial = serial;
		//update bottom fields
		updateLayerFormFields(serial);

		//enable form fields
		enableFormFields();

		jQuery('#layer_text_wrapper').removeClass('currently_editing_txt');
		
		jQuery('.rs-lazy-load-images-wrap').hide();
		
		jQuery("#layer_video_width").hide();
		jQuery("#layer_video_height").hide();
		
		jQuery("#layer_proportional_scale").parent().css('visibility', 'hidden');
		jQuery("#reset-scale").css('visibility', 'hidden');
		jQuery('#layer-linebreak-wrapper').hide();
		
		
		jQuery("#layer_cornerleft_row").hide();
		jQuery("#layer_cornerright_row").hide();
		jQuery("#layer_resizeme_row").hide();
		jQuery("#layer_max_width").hide();
		jQuery("#layer_max_height").hide();
		jQuery("#layer_whitespace_row").hide();
		
		jQuery("#layer-covermode-wrapper").hide();
		
		//do specific operations depends on type
		switch(objLayer.type){
			case "video":	//show edit video button
				
				jQuery("#linkInsertTemplate").addClass("disabled");
				jQuery("#layer_2d_rotation_row").hide();
				jQuery("#layer_2d_origin_x_row").hide();
				jQuery("#layer_2d_origin_y_row").hide();
				jQuery("#layer_2d_title_row").hide();
				
				t.showHideContentEditor(false);
				
				layer.resizable({
					aspectRatio:aspectratio,
					handles:"all",
					start:function(event,ui) {
						if(jQuery("#layer_proportional_scale").is(":checked")) {
							punchgs.TweenLite.set(ui.element,{width:ui.originalSize.width,height:ui.originalSize.height})
							punchgs.TweenLite.set(ui.element.find('img'),{width:"100%",height:"auto"})
							punchgs.TweenLite.set(ui.element.find('.innerslide_layer'),{width:"100%",height:"auto"})
						} else {
							punchgs.TweenLite.set(ui.element,{width:ui.originalSize.width,height:ui.originalSize.height})
							punchgs.TweenLite.set(ui.element.find('img'),{width:"100%",height:"100%"})
							punchgs.TweenLite.set(ui.element.find('.innerslide_layer'),{width:"100%",height:"100%"})						
						}
					},					
					resize:function(event,ui) {						
						jQuery('#layer_video_width').val(ui.size.width);
						jQuery('#layer_video_height').val(ui.size.height);
						if(jQuery("#layer_proportional_scale").is(":checked")) {
							punchgs.TweenLite.set(ui.element.find('img'),{width:"100%",height:"auto"})
							punchgs.TweenLite.set(ui.element.find('.innerslide_layer'),{maxWidth:"none",maxHeight:"none",width:"100%",height:"auto"});							
						} else {
							punchgs.TweenLite.set(ui.element.find('img'),{width:"100%",height:"100%"})
							punchgs.TweenLite.set(ui.element.find('.innerslide_layer'),{maxWidth:"none",maxHeight:"none",width:"100%",height:"100%"})						
						}					

						punchgs.TweenLite.set(ui.element.find('.slide_layer_video'),{width:"100%",height:"100%"});
					},
					stop:function(event,ui) {
						layerresized = true;
						setTimeout(function() {
							layerresized = false;							
						},200);
						t.updateLayerFromFields();						
					}
				});
				
				jQuery("#layer_video_width").show();
				jQuery("#layer_video_height").show();				
				if (layer.width()>=jQuery('#divLayers').width() && layer.height()>=jQuery('#divLayers').height())
					layer.addClass("fullscreen-video-layer");
				else
					layer.removeClass("fullscreen-video-layer");
				
				t.makeCurrentLayerRotatable();
				
			break;
			case "image":
				//disable the insert button
				
				jQuery("#linkInsertTemplate").addClass("disabled");

				//show / hide some elements
				jQuery("#layer_alt_row").show();
				jQuery("#layer_scale_title_row").show();
				jQuery("#layer_scaleX").show();
				jQuery("#layer_scaleY").show();
				//initScaleImage();
				jQuery("#layer_proportional_scale").parent().css('visibility', 'visible');
				jQuery("#reset-scale").css('visibility', 'visible');
				jQuery("#layer_image_link_row").show();
				jQuery("#layer_link_open_in_row").show();
				jQuery("#layer_link_id_row").show();
				jQuery("#layer_link_class_row").show();
				jQuery("#layer_link_title_row").show();
				jQuery("#layer_link_rel_row").show();
				jQuery("#layer_2d_rotation_row").show();
				jQuery("#layer_2d_origin_x_row").show();
				jQuery("#layer_2d_origin_y_row").show();
				
				jQuery("#layer-covermode-wrapper").show();
				
				jQuery('.rs-lazy-load-images-wrap').show();

				if(jQuery("#layer_proportional_scale").is(":checked"))
					var aspectratio = true;
				else
					var aspectratio = false;

				layer.resizable({
					aspectRatio:aspectratio,
					handles:"all",
					start:function(event,ui) {
						// IF IMAGE IS IN ASPECT RATIO MODE
						if(jQuery("#layer_proportional_scale").is(":checked")) {
							punchgs.TweenLite.set(ui.element,{width:ui.originalSize.width,height:ui.originalSize.height})
							punchgs.TweenLite.set(ui.element.find('img'),{width:"100%",height:"auto"})
							punchgs.TweenLite.set(ui.element.find('.innerslide_layer'),{width:"100%",height:"auto"})
						} else {
							punchgs.TweenLite.set(ui.element,{width:ui.originalSize.width,height:ui.originalSize.height})
							punchgs.TweenLite.set(ui.element.find('img'),{width:"100%",height:"100%"})
							punchgs.TweenLite.set(ui.element.find('.innerslide_layer'),{width:"100%",height:"100%"})						
						}
					},					
					resize:function(event,ui) {
						jQuery('#layer_scaleX').val(ui.size.width);
						jQuery('#layer_scaleY').val(ui.size.height);
						if(jQuery("#layer_proportional_scale").is(":checked")) {
							punchgs.TweenLite.set(ui.element.find('img'),{width:"100%",height:"auto"})
							punchgs.TweenLite.set(ui.element.find('.innerslide_layer'),{width:"100%",height:"auto"})
						} else {
							punchgs.TweenLite.set(ui.element.find('img'),{width:"100%",height:"100%"})
							punchgs.TweenLite.set(ui.element.find('.innerslide_layer'),{width:"100%",height:"100%"})						
						}					
					},
					stop:function(event,ui) {
						layerresized = true;
						setTimeout(function() {
							layerresized = false;							
						},200);
						t.updateLayerFromFields();
						
					}
				});


				t.makeCurrentLayerRotatable();


			break;
			case 'shape':
				jQuery("#layer-covermode-wrapper").show();
			//case 'no_edit':
			case 'typeA':
			case 'typeB':
			case 'button':
			default:  //set layer text to default height
				jQuery("#layer_max_width").show();
				jQuery("#layer_max_height").show();
				jQuery("#layer_whitespace_row").show();
				jQuery("#layer_2d_rotation_row").show();
				jQuery("#layer_2d_origin_x_row").show();
				jQuery("#layer_2d_origin_y_row").show();
				jQuery('#layer_text_wrapper').addClass('currently_editing_txt');
				jQuery('#layer-linebreak-wrapper').show();
				
				if(objLayer.type == 'shape'){
					jQuery('#layer-linebreak-wrapper').hide();
					jQuery('#layer_text_wrapper').removeClass('currently_editing_txt');
				}
				if(objLayer.type == 'typeA'){}
				if(objLayer.type == 'typeB'){}
				//if(objLayer.type == 'no_edit'){}
					
				layer.resizable({
					handles:"all",
					start:function() {
						jQuery('.rs-linebreak-check').removeClass('notselected');					
						jQuery("#layer_whitespace option[value='normal']").attr('selected', 'selected');
						
						var mw = layer.outerWidth(),
							mh = layer.outerHeight();
						layer.css({height:"auto"});
						
						//if(objLayer.type == 'shape')
							
						layer.find('.innerslide_layer.tp-caption').css({height:"auto",maxHeight:"none",minHeight:mh,maxWidth:mw});
						
					},
					resize:function(event,ui) {
						var il = ui.element.find('.innerslide_layer'),
							minheight = il.outerHeight(),
							ilwidth = il.outerWidth(),
							maxheight = ui.size.height+1,
							maxwidth = ui.size.width+1;
							
						il.css({"maxWidth":maxwidth});

						maxheight = minheight>=maxheight ? "auto" : maxheight+"px";
						jQuery('#layer_max_width').val(maxwidth+"px");
						jQuery('#layer_max_height').val(maxheight);
					},
					stop:function(event,ui) {
						layerresized = true;
						setTimeout(function() {
							layerresized = false;							
						},200);
						t.updateLayerFromFields();
					}
				});

				t.makeCurrentLayerRotatable();
			break;
		}
		
		
		if(jQuery('#layer_alt_option option:selected').val() == 'custom'){
			jQuery('#layer_alt').show();
		}else{
			jQuery('#layer_alt').hide();
		}
		
		//hide image layer related fields
		if(objLayer.type != "image"){
			//if(objLayer.type == "no_edit"){}
			
			jQuery("#layer_alt_row").hide();
			jQuery("#layer_scale_title_row").hide();
			jQuery("#layer_scaleX").hide();
			jQuery("#layer_scaleY").hide();
			
			jQuery("#layer_image_link_row").hide();
			jQuery("#layer_link_open_in_row").hide();
			jQuery('#layer_alt_option').parent().hide();
		}else{
			jQuery('#layer_alt_option').parent().show();
			
			if(jQuery('#layer_alt_option option:selected').val() == 'custom'){
				jQuery('#layer_alt').show();
			}else{
				jQuery('#layer_alt').hide();
			}
		}

		//show/hide text related layers
		if(objLayer.type == "text" || objLayer.type == "button"){
			jQuery("#layer_cornerleft_row").show();
			jQuery("#layer_cornerright_row").show();
			jQuery("#layer_resizeme_row").show();
			jQuery("#layer_max_width").show();
			jQuery("#layer_max_height").show();
			jQuery("#layer_whitespace_row").show();
			jQuery("#reset-scale").show();			
		}
		
		if(jQuery('input[name="hover_allow"]').is(':checked')){
			jQuery('#idle-hover-swapper').show();
		}else{
			jQuery('#idle-hover-swapper').hide();
		}
		RevSliderSettings.onoffStatus(jQuery('input[name="hover_allow"]'));

		//hide autocomplete
		jQuery("#layer_caption").catcomplete("close");


		//update timeline of the layer
		u.updateCurrentLayerTimeline();
		
		reAlignAndrePosition();

		//reset all color picker fields to the corresponding colors
		jQuery('.wp-color-result').each(function(){
			jQuery(this).css('backgroundColor', jQuery(this).parent().find('.my-color-field').val());
		});
		//set focus to text editor
		var objHtmlLayer = t.getHtmlLayerFromSerial(serial);
		
		checkMaskingAvailabity();
						
		u.rebuildLayerIdle(objHtmlLayer);
		
		jQuery('.form_layers').removeClass('notselected');

		t.set_cover_mode();
		
		//change the style classes available depending on .type
		
		UniteCssEditorRev.updateCaptionsInput(initArrCaptionClasses);

		t.updateReverseList();
	}


	var  getRotationDegrees = function(obj) {
	    var matrix = obj.css("-webkit-transform") ||
	    obj.css("-moz-transform")    ||
	    obj.css("-ms-transform")     ||
	    obj.css("-o-transform")      ||
	    obj.css("transform");
	    if(matrix !== 'none') {
	        var values = matrix.split('(')[1].split(')')[0].split(',');
	        var a = values[0];
	        var b = values[1];
	        var angle = Math.round(Math.atan2(b, a) * (180/Math.PI));
	    } else { var angle = 0; }
	    return (angle < 0) ? angle +=360 : angle;
	}
	/**
	 *
	 * return if the layer is selected or not
	 */
	var isLayerSelected = function(serial){
		return(serial == selectedLayerSerial);
	}



	var reAlignAndrePosition = function() {
		
	}



//======================================================
//			Time Functions
//======================================================

	/**
	 * get next available time
	 */
	var getNextTime = function(){
		var maxTime = 0;

		//get max time
		for (key in arrLayers){
			var layer = arrLayers[key];

			layerTime = (layer.time)?Number(layer.time):0;

			if(layerTime > maxTime)
					maxTime = layerTime;
		}

		var outputTime;
		if(maxTime == 0)
			outputTime = g_startTime;
		else
			outputTime = Number(maxTime) + Number(g_stepTime);

		return(outputTime);
	}


//======================================================
//				Time Functions End
//======================================================



//======================================================
//				HTML LAYER POSITION UPDATE
//======================================================
	t.updateHtmlLayerPosition = function(isInit,htmlLayer,objLayer,top,left,align_hor,align_vert){
		


		//update positions by align
		var width = htmlLayer.outerWidth(),
			height = htmlLayer.outerHeight();


		totalWidth = container.width();		
		totalHeight = container.height();


		//get sizes from saved if on get
		if(isInit == true && objLayer.type == "image"){
			if(t.getVal(objLayer,'width') != -1)
				width = t.getVal(objLayer,'width');

			if(t.getVal(objLayer,'height') != -1)
				height = t.getVal(objLayer,'height');
		}
		
		var objCss = {};

		
		//handle horizontal
		switch(align_hor){
			default:
			case "left":
				objCss["right"] = "auto";
				objCss["left"] = left+"px";
			break;
			case "right":
				objCss["left"] = "auto";
				objCss["right"] = left+"px";
			break;
			case "center":				
				var realLeft = (totalWidth - width)/2;
				realLeft = Math.round(realLeft) + left;
				objCss["left"] = realLeft + "px";
				objCss["right"] = "auto";
			break;
		}

		//handle vertical
		switch(align_vert){
			default:
			case "top":
				objCss["bottom"] = "auto";
				objCss["top"] = top+"px";
			break;
			case "middle":
				var realTop = (totalHeight - height)/2;
				realTop = Math.round(realTop)+top;
				objCss["top"] = realTop + "px";
				objCss["bottom"] = "auto";
			break;
			case "bottom":
				objCss["top"] = "auto";
				objCss["bottom"] = top+"px";
			break;
		}

		punchgs.TweenLite.set(htmlLayer,objCss);
		
	}




//======================================================
//				Events Functions
//======================================================

	/**
	 *
	 * on layer drag event - update layer position
	 */
	var onLayerDragStart = function() {
		t.showHideContentEditor(false);
		
		var layerSerial = t.getSerialFromID(this.id),
			htmlLayer = jQuery(this),
			objLayer = t.getLayer(layerSerial);
		
		selectedLayerWidth = htmlLayer.outerWidth();
		totalWidth = container.width();
		selectedlayerHeight = htmlLayer.outerHeight();
		totalHeight = container.height();
		
		jQuery('#layer_text_wrapper').removeClass("currently_editing_txt");
		t.setLayerSelected(layerSerial);
	}

	var onLayerDragEnd = function() {
		var layerSerial = t.getSerialFromID(this.id),
			htmlLayer = jQuery(this);
		onLayerDrag("ende",layerSerial,htmlLayer)
	}

	var onLayerDrag = function(end,layerSerial,htmlLayer){
		htmlLayer = htmlLayer || jQuery(this);
		
		var position = htmlLayer.position(),
			posTop = Math.round(position.top),
			posLeft = Math.round(position.left),
			updateY = 0,
			updateX = 0,
			objLayer = t.getLayer(selectedLayerSerial);
		jQuery('#layer_text_wrapper').removeClass("currently_editing_txt");
		t.toolbarInPos(objLayer);

		switch(t.getVal(objLayer,'align_hor')){
			case "left":
				updateX = posLeft;
			break;
			case "right":
				updateX = totalWidth - posLeft - selectedLayerWidth;
			break;
			case "center":
				updateX = posLeft - (totalWidth - selectedLayerWidth)/2;
				updateX = Math.round(updateX);
			break;
			case "left":
			default:
				updateX = posLeft;
			break;
		}

		switch(t.getVal(objLayer,'align_vert')){
			case "bottom":
				updateY = totalHeight - posTop - selectedlayerHeight;
			break;
			case "middle":
				updateY = posTop - (totalHeight - selectedlayerHeight)/2;
				updateY = Math.round(updateY);
			break;
			case "top":
			default:
				updateY = posTop;
			break;
		}



		jQuery('#layer_left').val(updateX);
		jQuery('#layer_top').val(updateY);

		if (end==="ende") {
			var objUpdate = {};

			objUpdate = t.setVal(objUpdate, 'left', updateX);
			objUpdate = t.setVal(objUpdate, 'top', updateY);
			objUpdate = t.setVal(objUpdate, 'width', selectedLayerWidth);
			objUpdate = t.setVal(objUpdate, 'height', selectedlayerHeight);		

			
			t.updateLayer(layerSerial,objUpdate);			
			t.updateHtmlLayerPosition(false,htmlLayer,objLayer,t.getVal(objUpdate, 'top'),t.getVal(objUpdate, 'left'),t.getVal(objLayer,'align_hor'),t.getVal(objLayer,'align_vert'));
		
			if(isLayerSelected(layerSerial))			
				updateLayerFormFields(layerSerial);
		}

	}


	/**
	 * move some layer
	 */
	var moveLayer = function(serial,dir,step){
		var layer = t.getLayer(serial);

		if(!layer)
			return(false);


		switch(dir){
			case "down":
				arrLayers[serial] = t.setVal(arrLayers[serial], 'top', t.getVal(arrLayers[serial], 'top') + step);
			break;
			case "up":
				arrLayers[serial] = t.setVal(arrLayers[serial], 'top', t.getVal(arrLayers[serial], 'top') - step);
			break;
			case "right":
				arrLayers[serial] = t.setVal(arrLayers[serial], 'left', t.getVal(arrLayers[serial], 'left') + step);
			break;
			case "left":
				arrLayers[serial] = t.setVal(arrLayers[serial], 'left', t.getVal(arrLayers[serial], 'left') - step);
			break;
			default:
				UniteAdminRev.showErrorMessage("wrong direction: "+dir);
				return(false);
			break;
		}

		
		
		updateHtmlLayersFromObject(serial);

		if(isLayerSelected(serial))
			updateLayerFormFields(serial);
	}


//======================================================
//		Events Functions End
//======================================================




	//======================================================
	//	Scale Functions
	//======================================================
		/**
		 * calculate image height/width
		 */

		var scaleImage = function(){

			jQuery("#layer_scaleX").change(function(){
				if(jQuery("#layer_proportional_scale").is(":checked"))
					scaleProportional(true);
				else
					scaleNormal();
			});

			jQuery("#layer_scaleY").change(function(){
				if(jQuery("#layer_proportional_scale").is(":checked"))
					scaleProportional(false);
				else
					scaleNormal();
			});
			
			jQuery("#layer_video_width").change(function(){

				if(jQuery("#layer_proportional_scale").is(":checked"))
					scaleProportionalVideo(true);
				else
					scaleNormalVideo();
			});

			jQuery("#layer_video_height").change(function(){
				if(jQuery("#layer_proportional_scale").is(":checked"))
					scaleProportionalVideo(false);
				else
					scaleNormalVideo();
			});
			
			jQuery("#layer_proportional_scale").click(function(){
				var serial = selectedLayerSerial;
				var layer = jQuery("#slide_layer_" + serial);
				var objLayer = t.getLayer(selectedLayerSerial);

				if(jQuery(this).is(":checked")){
					jQuery('#layer_cover_mode option[value="custom"]').attr('selected', true);
					jQuery('.rs-proportion-check').removeClass('notselected');
					scaleProportional(true);
					var aspectratio = true;
				}else{
					jQuery('.rs-proportion-check').addClass('notselected');
					scaleNormal();
					var aspectratio = false;
				}
				
				//only do on images, not videos
				if(objLayer.type == 'image'){
					layer.resizable("destroy").resizable({
						aspectRatio:aspectratio,
						start:function(event,ui) {
							// IF IMAGE IS IN ASPECT RATIO MODE
							if(jQuery("#layer_proportional_scale").is(":checked")) {								
								punchgs.TweenLite.set(ui.element,{width:ui.originalSize.width,height:ui.originalSize.height})
								punchgs.TweenLite.set(ui.element.find('img'),{width:"100%",height:"auto"})
								punchgs.TweenLite.set(ui.element.find('.innerslide_layer'),{width:"100%",height:"auto"})
							} else {								
								punchgs.TweenLite.set(ui.element,{width:ui.originalSize.width,height:ui.originalSize.height})
								punchgs.TweenLite.set(ui.element.find('img'),{width:"100%",height:"100%"})
								punchgs.TweenLite.set(ui.element.find('.innerslide_layer'),{width:"100%",height:"100%"})						
							}
						},
						resize:function(event,ui) {
							jQuery('#layer_scaleX').val(ui.size.width);
							jQuery('#layer_scaleY').val(ui.size.height);	
							if(jQuery("#layer_proportional_scale").is(":checked")) {									
								punchgs.TweenLite.set(ui.element.find('img'),{width:"100%",height:"auto"})
								punchgs.TweenLite.set(ui.element.find('.innerslide_layer'),{width:"100%",height:"auto"})
							} else {								
								punchgs.TweenLite.set(ui.element.find('img'),{width:"100%",height:"100%"})
								punchgs.TweenLite.set(ui.element.find('.innerslide_layer'),{width:"100%",height:"100%"})						
							}					
						},
						stop:function(event,ui) {
							t.updateLayerFromFields();
						}
					});
				}
			});


			// AUTO LINE BREAK on/off
			jQuery("#layer_auto_line_break").click(function(){
				var serial = selectedLayerSerial;
				var layer = jQuery("#slide_layer_" + serial);
				var objLayer = t.getLayer(selectedLayerSerial);

				if(jQuery(this).is(":checked")){
					jQuery('.rs-linebreak-check').removeClass('notselected');					
					jQuery("#layer_whitespace option[value='normal']").attr('selected', 'selected');
					
					var mw = layer.outerWidth(),
						mh = layer.outerHeight();
					layer.css({height:"auto"});
					layer.find('.innerslide_layer.tp-caption').css({maxHeight:"none",minHeight:mh,maxWidth:mw});
					jQuery('#layer_max_width').val(mw);
					jQuery('#layer_max_height').val("auto");
				}else{
					jQuery('.rs-linebreak-check').addClass('notselected');										
					jQuery("#layer_whitespace option[value='nowrap']").attr('selected', 'selected');
					jQuery('#layer_max_width').val("auto");
					jQuery('#layer_max_height').val("auto");
					layer.css({width:"auto"});
					layer.find('.innerslide_layer.tp-caption').css({maxHeight:"none",minHeight:"none",maxWidth:"none"});
				}
				t.updateLayerFromFields();
				
			});

			jQuery('#layer_cover_mode').change(function(){
				t.set_cover_mode();
			});

			jQuery("#reset-scale, #button_reset_size").click(function(){

				var objLayer = t.getLayer(selectedLayerSerial);
				
				if (objLayer.type == "shape") {
					return false;
				}else /*if (objLayer.type == "no_edit") {
					return false;
				}else*/ if (objLayer.type == "typeA") {
					return false;
				}else if (objLayer.type == "typeB") {
					return false;
				}else if (objLayer.type=="text" || objLayer.type=="button") {
					var ww = jQuery('.slide_layer.layer_selected .innerslide_layer').outerWidth();
					if (parseInt(jQuery("#layer_max_width").val(),0)>ww) {
						ww = ww === undefined ? "auto" : ww+"px"
						jQuery("#layer_max_width").val("auto");
					}
					jQuery("#layer_max_height").val("auto");
					
					
				} else {
					if (objLayer.type == "image") {
						jQuery('#layer_cover_mode option[value="custom"]').attr('selected', true);
					}
					
					resetImageDimensions();
					jQuery("#layer_proportional_scale").attr('checked', false);
					jQuery('.rs-proportion-check').addClass('notselected');
	
					jQuery("#layer_scaleX_text").html(jQuery("#layer_scaleX_text").data("textnormal")).css("width", "10px");
					
					var mwidth = specOrVal(t.getVal(objLayer,'originalWidth'),["auto"],"px");
					var mheight = specOrVal(t.getVal(objLayer,'originalHeight'),["auto"],"px");
					
					jQuery("#layer_scaleX").val(mwidth);
					jQuery("#layer_scaleY").val(mheight);
					
					jQuery("#slide_layer_" + selectedLayerSerial + " img").css("width", mwidth);
					jQuery("#slide_layer_" + selectedLayerSerial + " img").css("height", mheight);					
				}
				t.updateLayerFromFields();
			});

		}

		var scaleProportional = function(useX){
			var serial = selectedLayerSerial;

			resetImageDimensions();

			var imgObj = new Image();
			imgObj.src = jQuery("#slide_layer_" + serial + " img").attr("src");

			if(useX){
				var x = parseInt(jQuery("#layer_scaleX").val());
				if(isNaN(x)) x = imgObj.width;
				var y = Math.round(100 / imgObj.width * x / 100 * imgObj.height, 0);
			}else{
				var y = parseInt(jQuery("#layer_scaleY").val());
				if(isNaN(y)) y = imgObj.height;
				var x = Math.round(100 / imgObj.height * y / 100 * imgObj.width, 0);

			}


			jQuery("#slide_layer_" + serial + " img").css("width", x + "px");
			jQuery("#slide_layer_" + serial + " img").css("height", y + "px");

			jQuery("#slide_layer_" + serial).css("width", jQuery("#slide_layer_" + serial + " img").width() + "px");
			jQuery("#slide_layer_" + serial).css("height", jQuery("#slide_layer_" + serial + " img").height() + "px");


			jQuery("#slide_layer_" + serial + " img").css("width", "100%");
			jQuery("#slide_layer_" + serial + " img").css("height", "100%");

			jQuery("#layer_scaleX").val(x);
			jQuery("#layer_scaleY").val(y);
		}
		
		var scaleNormal = function(){

			var serial = selectedLayerSerial,
				imgdims = resetImageDimensions(),
				layer = jQuery("#slide_layer_" + serial),
				ww = parseInt(jQuery("#layer_scaleX").val(),0),
				hh = parseInt(jQuery("#layer_scaleY").val(),0);

			punchgs.TweenLite.set(layer,{width:ww+"px",height:hh+"px"});
			punchgs.TweenLite.set(layer.find('.innerslide_layer'),{width:ww+"px",height:hh+"px"});
			punchgs.TweenLite.set(layer.find('img'),{width:ww+"px",height:hh+"px"});			
		}

		/**
		 * Scale Videos to the choosen proportion on width/height change
		 * @since: 5.0
		 **/
		var scaleProportionalVideo = function(useX){
			var serial = selectedLayerSerial;

			var cur_video = jQuery("#slide_layer_" + serial).find('.slide_layer_video');

			if(useX){
				var x = parseInt(jQuery("#layer_video_width").val());
				if(isNaN(x)) x = cur_video.width();
				var y = Math.round(100 / cur_video.width() * x / 100 * cur_video.height(), 0);
			}else{
				var y = parseInt(jQuery("#layer_video_height").val());
				if(isNaN(y)) y = cur_video.height();
				var x = Math.round(100 / cur_video.height() * y / 100 * cur_video.width(), 0);
			}

			
			jQuery("#slide_layer_" + serial).find('.slide_layer_video').css("width", x + "px");
			jQuery("#slide_layer_" + serial).find('.slide_layer_video').css("height", y + "px");
			
			jQuery("#slide_layer_" + serial).css("width", x + "px");
			jQuery("#slide_layer_" + serial).css("height", y + "px");

			jQuery("#layer_video_width").val(x);
			jQuery("#layer_video_height").val(y);
		}

		var scaleNormalVideo = function(){
			var serial = selectedLayerSerial;
			

			jQuery("#slide_layer_" + serial).find('.slide_layer_video').css("width", parseInt(jQuery("#layer_video_width").val(),0) + "px");
			jQuery("#slide_layer_" + serial).find('.slide_layer_video').css("height", parseInt(jQuery("#layer_video_height").val(),0) + "px");

			jQuery("#slide_layer_" + serial).css("width", parseInt(jQuery("#layer_video_width").val(),0) + "px");
			jQuery("#slide_layer_" + serial).css("height", parseInt(jQuery("#layer_video_height").val(),0) + "px");

		}
		
		
		var resetImageDimensions = function(){
			var imgObj = new Image();
			imgObj.src = jQuery("#slide_layer_" + selectedLayerSerial + " img").attr("src");
			jQuery("#slide_layer_" + selectedLayerSerial).css("width", imgObj.width + "px");
			jQuery("#slide_layer_" + selectedLayerSerial).css("height", imgObj.height + "px");

			var imgdims = {width:imgObj.width, height:imgObj.height};
			return imgdims;
		}

	//======================================================
	//	Scale Functions End
	//======================================================

	t.getLayerGeneralParamsStatus = function(){
		return layerGeneralParamsStatus;
	}

	//======================================================
	//	Main Background Image Functions
	//======================================================

	
	var initBackgroundFunctions = function(){
		jQuery('body').on('change', 'select[name="layer_target[]"]', function(){
			jQuery(this).data('selectoption', jQuery(this).find('option:selected').val());
			
			
			//update do-layer-animation-overwrite and do-layer-trigger-memory
			var dataAnim = t.getAnimTimingAndTrigger(jQuery(this).find('option:selected').val());
			
			jQuery(this).closest('li').find('select[name="do-layer-animation-overwrite[]"] option[value="'+dataAnim['animation_overwrite']+'"]').attr('selected', true);
			jQuery(this).closest('li').find('select[name="do-layer-trigger-memory[]"] option[value="'+dataAnim['trigger_memory']+'"]').attr('selected', true);
		});
		jQuery('body').on('change', 'select[name="jump_to_slide[]"]', function(){
			jQuery(this).data('selectoption', jQuery(this).find('option:selected').val());
		});
		
		
		jQuery('body').on('change', 'select[name="do-layer-animation-overwrite[]"]', function(){
			var do_sel = jQuery(this).closest('li').find('select[name="layer_target[]"] option:selected').val();
			var new_val = jQuery(this).val();
			
			var lid = t.getLayerIdByUniqueId(do_sel);
			
			var objUpdate = {};
			
			objUpdate.animation_overwrite = new_val;
			
			t.updateLayer(lid,objUpdate);
			
			jQuery('select[name="layer_target[]"] option:selected').each(function(){
				if(jQuery(this).val() == do_sel){
					jQuery(this).closest('li').find('select[name="do-layer-animation-overwrite[]"] option[value="'+new_val+'"]').attr('selected', true);
				}
			});
		});
		
		
		jQuery('body').on('change', 'select[name="do-layer-trigger-memory[]"]', function(){
			var do_sel = jQuery(this).closest('li').find('select[name="layer_target[]"] option:selected').val();
			var new_val = jQuery(this).val();
			var lid = t.getLayerIdByUniqueId(do_sel);
			
			var objUpdate = {};
			
			objUpdate.trigger_memory = new_val;
			
			t.updateLayer(lid,objUpdate);
			
			jQuery('select[name="layer_target[]"] option:selected').each(function(){
				if(jQuery(this).val() == do_sel){
					jQuery(this).closest('li').find('select[name="do-layer-trigger-memory[]"] option[value="'+new_val+'"]').attr('selected', true);
				}
			});
		});
		
		jQuery('#slide_bg_fit').change(function(){
			if(jQuery(this).val() == 'percentage'){
				jQuery('input[name="bg_fit_x"]').show();
				jQuery('input[name="bg_fit_y"]').show();

				jQuery('#divbgholder').css('background-size', jQuery('input[name="bg_fit_x"]').val()+'% '+jQuery('input[name="bg_fit_y"]').val()+'%');
			}else{
				jQuery('input[name="bg_fit_x"]').hide();
				jQuery('input[name="bg_fit_y"]').hide();

				jQuery('#divbgholder').css('background-size', jQuery(this).val());
			}


			if(jQuery(this).val() == 'contain'){
				jQuery('#divLayers-wrapper').css('maxWidth', jQuery('#divbgholder').css('minWidth'));
			}else{
				jQuery('#divLayers-wrapper').css('maxWidth', '100%');
			}
			
			
		});
		jQuery('#slide_bg_fit').change();

		jQuery('input[name="bg_fit_x"]').change(function(){
			jQuery('#divbgholder').css('background-size', jQuery('input[name="bg_fit_x"]').val()+'% '+jQuery('input[name="bg_fit_y"]').val()+'%');
		});

		jQuery('input[name="bg_fit_y"]').change(function(){
			jQuery('#divbgholder').css('background-size', jQuery('input[name="bg_fit_x"]').val()+'% '+jQuery('input[name="bg_fit_y"]').val()+'%');
		});

		jQuery('#slide_bg_position').change(function(){
			if(jQuery(this).val() == 'percentage'){
				jQuery('input[name="bg_position_x"]').show();
				jQuery('input[name="bg_position_y"]').show();

				jQuery('#divbgholder').css('background-position', jQuery('input[name="bg_fit_x"]').val()+'% '+jQuery('input[name="bg_fit_y"]').val()+'%');
			}else{
				jQuery('input[name="bg_position_x"]').hide();
				jQuery('input[name="bg_position_y"]').hide();

				jQuery('#divbgholder').css('background-position', jQuery(this).val());
			}
			
		});

		jQuery('input[name="bg_position_x"]').change(function(){
			jQuery('#divbgholder').css('background-position', jQuery('input[name="bg_position_x"]').val()+'% '+jQuery('input[name="bg_position_y"]').val()+'%');
		});

		jQuery('input[name="bg_position_y"]').change(function(){
			jQuery('#divbgholder').css('background-position', jQuery('input[name="bg_position_x"]').val()+'% '+jQuery('input[name="bg_position_y"]').val()+'%');
		});

		jQuery('#slide_bg_repeat').change(function(){
			jQuery('#divbgholder').css('background-repeat', jQuery(this).val());		
		});

		jQuery('input[name="kenburn_effect"]').change(function(){
			
			if(jQuery(this).is(':checked')){
				jQuery('#kenburn_wrapper').show();
				//jQuery('#bg-position-lbl').hide();
				//jQuery('#bg-start-position-wrapper').children().appendTo(jQuery('#bg-start-position-wrapper-kb'));
				//jQuery('#bg-setting-wrap').hide();

				jQuery('#divbgholder').css('background-repeat', '');
				jQuery('#divbgholder').css('background-position', '');
				jQuery('#divbgholder').css('background-size', '');

				jQuery('input[name="kb_start_fit"]').change();

				jQuery('#divLayers-wrapper').css('maxWidth', 'none');

				jQuery('#slide_bg_position').change();
			}else{
				jQuery('#kenburn_wrapper').hide();
				//jQuery('#bg-position-lbl').show();
				//jQuery('#bg-start-position-wrapper-kb').children().appendTo(jQuery('#bg-start-position-wrapper'));
				//jQuery('#bg-setting-wrap').show();

				jQuery('#slide_bg_repeat').change();
				jQuery('#slide_bg_position').change();
				jQuery('#slide_bg_fit').change();

				if(jQuery('#slide_bg_fit').val() == 'contain'){
					jQuery('#divLayers-wrapper').css('maxWidth', jQuery('#divbgholder').css('minWidth'));
				}else{
					jQuery('#divLayers-wrapper').css('maxWidth', '100%');
				}
			}
			t.changeSlotBGs();
		});
		jQuery('input[name="kenburn_effect"]:checked').change();


		jQuery('#slide_bg_end_position').change(function(){
			if(jQuery(this).val() == 'percentage'){
				jQuery('input[name="bg_end_position_x"]').show();
				jQuery('input[name="bg_end_position_y"]').show();
			}else{
				jQuery('input[name="bg_end_position_x"]').hide();
				jQuery('input[name="bg_end_position_y"]').hide();
			}
		});


		jQuery('input[name="kb_start_fit"]').change(function(){
			var fitVal = parseInt(jQuery(this).val());
			var limg = new Image();
			limg.onload = function() {
				calculateKenBurnScales(fitVal, limg.width, limg.height, jQuery('#divbgholder'));
			}

			var urlImage = '';
			if(jQuery('#radio_back_image').is(':checked'))
				urlImage = jQuery("#image_url").val();
			else if(jQuery('#radio_back_external').is(':checked'))
				urlImage = jQuery("#slide_bg_external").val();

			if(urlImage != ''){
				limg.src = urlImage;
			}
			t.changeSlotBGs();
		});

		var calculateKenBurnScales = function(proc,owidth,oheight,opt) {
		   var ow = owidth;
		   var oh = oheight;


		   var factor = (opt.width() /ow);
		   var factorh = (opt.height() / oh);

		 //  if (factor>=1) {

				var nheight = oh * factor;
				proc = proc + "%";
				var hfactor = "auto"; //(nheight / opt.height())*proc;
			/*} else {

				var nwidth = "auto" //ow * factorh;
				var hfactor = proc+"%";
				proc = "auto";
				//proc = (nwidth / opt.width()) * proc;

			}*/

		   jQuery('#divbgholder').css('background-size', proc+" "+hfactor);
			t.changeSlotBGs();		   
		}

		jQuery("#layer_resize-full").change(function(){
			if(!jQuery(this).is(':checked')){
				jQuery('#layer_resizeme').prop('checked', false);
				RevSliderSettings.onoffStatus(jQuery("#layer_resizeme"));
			}
		});
		
		jQuery("#layer_resizeme").change(function(){
			if(jQuery(this).is(':checked')){
				jQuery('#layer_resize-full').prop('checked', true);
				RevSliderSettings.onoffStatus(jQuery("#layer_resize-full"));
			}
		});
		
		
		jQuery(window).resize(function(){
			if(jQuery('input[name="kenburn_effect"]:checked').val() == 'on'){
				var fitVal = parseInt(jQuery('input[name="kb_start_fit"]').val());
				var limg = new Image();
				limg.onload = function() {
					calculateKenBurnScales(fitVal, limg.width, limg.height, jQuery('#divbgholder'));
				}

				var urlImage = '';
				if(jQuery('#radio_back_image').is(':checked'))
					urlImage = jQuery("#image_url").val();
				else if(jQuery('#radio_back_external').is(':checked'))
					urlImage = jQuery("#slide_bg_external").val();

				if(urlImage != ''){
					limg.src = urlImage;
				}

			}
		});
	}

	//======================================================
	//	Main Background Image Functions End
	//======================================================

	var initLoopFunctions = function(){

		jQuery('select[name="layer_loop_animation"]').change(function(){
			showHideLoopFunctions();
		});

		jQuery('#layer_static_start').change(function(){
			changeEndStaticFunctions();
		});
	}

	var showHideLoopFunctions = function(){

		jQuery('select[name="layer_loop_animation"]').each(function(){
			jQuery("#layer_easing_wrapper").hide();
			jQuery("#layer_speed_wrapper").hide();
			jQuery("#layer_parameters_wrapper").hide();
			jQuery("#layer_degree_wrapper").hide();
			jQuery("#layer_origin_wrapper").hide();
			jQuery("#layer_x_wrapper").hide();
			jQuery("#layer_y_wrapper").hide();
			jQuery("#layer_zoom_wrapper").hide();
			jQuery("#layer_angle_wrapper").hide();
			jQuery("#layer_radius_wrapper").hide();

			switch(jQuery(this).val()){
				case 'none':
				break;
				case 'rs-pendulum':
					jQuery("#layer_easing_wrapper").show();
					jQuery("#layer_speed_wrapper").show();
					jQuery("#layer_parameters_wrapper").show();
					jQuery("#layer_degree_wrapper").show();
					jQuery("#layer_origin_wrapper").show();
				break;
				case 'rs-rotate':
					jQuery("#layer_easing_wrapper").show();
					jQuery("#layer_speed_wrapper").show();
					jQuery("#layer_parameters_wrapper").show();
					jQuery("#layer_degree_wrapper").show();
					jQuery("#layer_origin_wrapper").show();
				break;

				case 'rs-slideloop':
					jQuery("#layer_easing_wrapper").show();
					jQuery("#layer_speed_wrapper").show();
					jQuery("#layer_parameters_wrapper").show();
					jQuery("#layer_x_wrapper").show();
					jQuery("#layer_y_wrapper").show();
				break;
				case 'rs-pulse':
					jQuery("#layer_easing_wrapper").show();
					jQuery("#layer_speed_wrapper").show();
					jQuery("#layer_parameters_wrapper").show();
					jQuery("#layer_zoom_wrapper").show();
				break;
				case 'rs-wave':
					jQuery("#layer_speed_wrapper").show();
					jQuery("#layer_parameters_wrapper").show();
					jQuery("#layer_angle_wrapper").show();
					jQuery("#layer_radius_wrapper").show();
					jQuery("#layer_origin_wrapper").show();
				break;
			}
		});
	}

	var changeEndStaticFunctions = function(){

		jQuery('#layer_static_start').each(function(){
			var cur_att = parseInt(jQuery(this).val());
			var cur_end = jQuery('#layer_static_end option:selected').val();
			var go_max_up_to = parseInt(jQuery('#layer_static_start option:last-child').val());

			jQuery('#layer_static_end').empty();

			for(var cur=cur_att+ 1; cur<=go_max_up_to; cur++){
				jQuery("#layer_static_end").append('<option value="'+cur+'">'+cur+'</option>');
			}
			jQuery("#layer_static_end").append('<option value="last">'+rev_lang.last_slide+'</option>');

			jQuery("#layer_static_end option[value='"+cur_end+"']").attr('selected', 'selected');
		});
	}
	
	t.get_current_selected_layer = function(){
		return selectedLayerSerial;
	}
	
}