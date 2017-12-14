function setCookie( c_name, value, exdays ) {
	var exdate = new Date();
	exdate.setDate( exdate.getDate() + exdays );
	var c_value = encodeURIComponent( value ) + ((null === exdays) ? "" : "; expires=" + exdate.toUTCString());
	document.cookie = c_name + "=" + c_value;
}

function getCookie( c_name ) {
	var i, x, y, ARRcookies = document.cookie.split( ";" );
	for ( i = 0;
		  i < ARRcookies.length;
		  i ++ ) {
		x = ARRcookies[ i ].substr( 0, ARRcookies[ i ].indexOf( "=" ) );
		y = ARRcookies[ i ].substr( ARRcookies[ i ].indexOf( "=" ) + 1 );
		x = x.replace( /^\s+|\s+$/g, "" );
		if ( x == c_name ) {
			return decodeURIComponent( y );
		}
	}
}

/**
 * Helper function to transform to Title Case
 * @since 4.4
 */
function vc_toTitleCase( str ) {
	return str.replace( /\w\S*/g, function ( txt ) {
		return txt.charAt( 0 ).toUpperCase() + txt.substr( 1 ).toLowerCase();
	} );
}

(function ( $ ) {
	$.expr[ ':' ].containsi = function ( a, i, m ) {
		return 0 <= jQuery( a ).text().toUpperCase().indexOf( m[ 3 ].toUpperCase() );
	};
	$( '#vc_license-activation-close' ).click( function ( e ) {
		e.preventDefault();
		window.setCookie( 'vchideactivationmsg', 1, 14 );
		$( this ).parent().slideUp();
	} );

	/** Custom Css EDITOR
	 *
	 */
	window.Vc_postSettingsEditor = Backbone.View.extend( {
		$editor: false,
		sel: 'wpb_csseditor',
		ace_enabled: false,
		initialize: function ( sel ) {
			if ( sel && 0 < sel.length ) {
				this.sel = sel;
			}
			this.ace_enabled = true;
		},
		aceEnabled: function () {
			return this.ace_enabled && window.ace && window.ace.edit;
		},
		setEditor: function ( value ) {
			if ( this.aceEnabled() ) {
				this.setEditorAce( value );
			} else {
				this.setEditorTextarea( value );
			}
			return this.$editor;
		},
		focus: function () {
			if ( this.aceEnabled() ) {
				this.$editor.focus();
				var count = this.$editor.session.getLength();
				this.$editor.gotoLine( count, this.$editor.session.getLine( count - 1 ).length );
			} else {
				this.$editor.focus();
			}
		},
		setEditorAce: function ( value ) {
			if ( ! this.$editor ) {
				this.$editor = ace.edit( this.sel );
				this.$editor.getSession().setMode( "ace/mode/css" );
				this.$editor.setTheme( "ace/theme/chrome" );
			}
			this.$editor.setValue( value );
			this.$editor.clearSelection();
			this.$editor.focus();
			var count = this.$editor.getSession().getLength();
			this.$editor.gotoLine( count, this.$editor.getSession().getLine( count - 1 ).length );
			return this.$editor;
		},
		setEditorTextarea: function ( value ) {
			if ( ! this.$editor ) {
				this.$editor = $( '<textarea></textarea>' ).css( {
					'width': '100%',
					'height': '100%',
					'minHeight': '300px'
				} );
				$( '#' + this.sel ).empty().append( this.$editor ).css( {
					'overflowLeft': 'hidden',
					'width': '100%',
					'height': '100%'
				} );
			}
			this.$editor.val( value );
			this.$editor.focus();
			this.$editor.parent().css( { 'overflow': 'auto' } );
			return this.$editor;
		},
		setSize: function () {
			var height = $( window ).height() - 380; // @fix ACE editor
			if ( this.aceEnabled() ) {
				$( '#' + this.sel ).css( { 'height': height, 'minHeight': height } );
			} else {
				this.$editor.parent().css( { 'height': height, 'minHeight': height } );
				this.$editor.css( { 'height': '98%', 'width': '98%' } );
			}
		},
		setSizeResizable: function () {
			var height, editorPositionTop, footerPositionTop,
				$editor = $( '#' + this.sel );
			editorPositionTop = $editor.offset().top;
			footerPositionTop = vc.active_panel.$el.find( '[data-vc-ui-element="panel-footer"]' ).offset().top;
			height = footerPositionTop - editorPositionTop - 70;
			if ( this.aceEnabled() ) {
				$editor.css( { 'height': height, 'minHeight': height } );
			} else {
				this.$editor.parent().css( { 'height': height, 'minHeight': height } );
				this.$editor.css( { 'height': '98%', 'width': '98%' } );
			}
		},
		getEditor: function () {
			return this.$editor;
		},
		getValue: function () {
			if ( this.aceEnabled() ) {
				return this.$editor.getValue();
			} else {
				return this.$editor.val();
			}
		}
	} );
})( window.jQuery );

function vc_convert_column_size( width ) {
	var prefix = 'vc_col-sm-',
		numbers = width ? width.split( '/' ) : [
			1,
			1
		],
		range = _.range( 1, 13 ),
		num = ! _.isUndefined( numbers[ 0 ] ) && 0 <= _.indexOf( range,
			parseInt( numbers[ 0 ], 10 ) ) ? parseInt( numbers[ 0 ], 10 ) : false,
		dev = ! _.isUndefined( numbers[ 1 ] ) && 0 <= _.indexOf( range,
			parseInt( numbers[ 1 ], 10 ) ) ? parseInt( numbers[ 1 ], 10 ) : false;
	if ( false !== num && false !== dev ) {
		return prefix + (12 * num / dev);
	}
	return prefix + '12';
}
function vc_convert_column_span_size( width ) {
	width = width.replace( /^vc_/, '' );
	if ( "span12" === width ) {
		return '1/1';
	} else if ( "span11" === width ) {
		return '11/12';
	} else if ( "span10" === width ) //three-fourth
	{
		return '5/6';
	} else if ( "span9" === width ) //three-fourth
	{
		return '3/4';
	} else if ( "span8" === width ) //two-third
	{
		return '2/3';
	} else if ( "span7" === width ) {
		return '7/12';
	} else if ( "span6" === width ) //one-half
	{
		return '1/2';
	} else if ( "span5" === width ) //one-half
	{
		return '5/12';
	} else if ( "span4" === width ) // one-third
	{
		return '1/3';
	} else if ( "span3" === width ) // one-fourth
	{
		return '1/4';
	} else if ( "span2" === width ) // one-fourth
	{
		return '1/6';
	} else if ( "span1" === width ) {
		return '1/12';
	}

	return false;
}

function vc_get_column_mask( cells ) {
	var columns = cells.split( '_' ),
		columns_count = columns.length,
		numbers_sum = 0,
		i;

	for ( i in
		columns ) {
		if ( ! isNaN( parseFloat( columns[ i ] ) ) && isFinite( columns[ i ] ) ) {
			var sp = columns[ i ].match( /(\d{1,2})(\d{1,2})/ );
			numbers_sum = _.reduce( sp.slice( 1 ), function ( memo, num ) {
				return memo + parseInt( num, 10 );
			}, numbers_sum ); // TODO: jshint
		}
	}
	return columns_count + '' + numbers_sum;
}

/**
 * Create Unique id for records in storage.
 * Generate a pseudo-GUID by concatenating random hexadecimal.
 * @return {String}
 */
function vc_guid() {
	return (VCS4() + VCS4() + "-" + VCS4());
}

// Generate four random hex digits.
function VCS4() {
	return (((1 + Math.random()) * 0x10000) | 0).toString( 16 ).substring( 1 );
}

function vc_button_param_target_callback() {
	var $ = jQuery,
		$link_target = this.$content.find( '[name=target]' ).parents( '[data-vc-ui-element="panel-shortcode-param"]:first' ),
		$link_field = $( '.wpb-edit-form [name=href]' );
	var key_up_callback = _.debounce( function () {
		var val = $( this ).val();
		if ( 0 < val.length && 'http://' !== val && 'https://' !== val ) {
			$link_target.show();
		} else {
			$link_target.hide();
		}
	}, 300 );
	$link_field.keyup( key_up_callback ).trigger( 'keyup' );
}

function vc_cta_button_param_target_callback() {
	var $ = jQuery,
		$link_target = this.$content.find( '[name=target]' ).parents( '[data-vc-ui-element="panel-shortcode-param"]:first' ),
		$link_field = $( '.wpb-edit-form [name=href]' );
	var key_up_callback = _.debounce( function () {
		var val = $( this ).val();
		if ( 0 < val.length && 'http://' !== val && 'https://' !== val ) {
			$link_target.show();
		} else {
			$link_target.hide();
		}
	}, 300 );
	$link_field.keyup( key_up_callback ).trigger( 'keyup' );
}

function vc_grid_exclude_dependency_callback() {
	var $ = jQuery;
	var exclude_el = $( '.wpb_vc_param_value[name=exclude]', this.$content );
	var exclude_obj = exclude_el.data( 'object' );
	var post_type_object = $( 'select.wpb_vc_param_value[name="post_type"]', this.$content );
	var val = post_type_object.val();
	exclude_obj.source_data = function ( request, response ) {
		return { query: { query: val, term: request.term } };
	};
	exclude_obj.source_data_val = val;
	post_type_object.change( function () {
		val = $( this ).val();
		if ( exclude_obj.source_data_val != val ) {
			exclude_obj.source_data = function ( request, response ) {
				return { query: { query: val, term: request.term } };
			};
			exclude_obj.$el.data( 'uiAutocomplete' ).destroy();
			exclude_obj.$sortable_wrapper.find( '.vc_data' ).remove(); // remove all appended items
			exclude_obj.render(); // re-render data
			exclude_obj.source_data_val = val;
		}
	} );
}

function vcGridFilterExcludeCallBack() {
	var $ = jQuery, $filterBy, $exclude, autocomplete, defaultValue;
	$filterBy = $( '.wpb_vc_param_value[name=filter_source]', this.$content );
	defaultValue = $filterBy.val();
	$exclude = $( '.wpb_vc_param_value[name=exclude_filter]', this.$content );
	autocomplete = $exclude.data( 'object' );
	$filterBy.change( function () {
		var $this = $( this );
		defaultValue !== $this.val() && autocomplete.clearValue();
		autocomplete.source_data = function () {
			return { vc_filter_by: $this.val() };
		};
	} ).trigger( 'change' );
}

function vcChartCustomColorDependency() {
	var $, $masterEl, $content;
	$ = jQuery;
	$masterEl = $( '.wpb_vc_param_value[name=style]', this.$content );
	$content = this.$content;
	$masterEl.on( 'change', function () {
		var masterValue;
		masterValue = $( this ).val();
		$content.toggleClass( 'vc_chart-edit-form-custom-color', 'custom' === masterValue );
	} );
	$masterEl.trigger( 'change' );
}

function vc_wpnop( content ) {
	var blocklist1, blocklist2, preserve_linebreaks = false, preserve_br = false;

	// Protect pre|script tags
	if ( content.indexOf( '<pre' ) != - 1 || content.indexOf( '<script' ) != - 1 ) {
		preserve_linebreaks = true;
		content = content.replace( /<(pre|script)[^>]*>[\s\S]+?<\/\1>/g, function ( a ) {
			a = a.replace( /<br ?\/?>(\r\n|\n)?/g, '<wp-temp-lb>' );
			return a.replace( /<\/?p( [^>]*)?>(\r\n|\n)?/g, '<wp-temp-lb>' );
		} );
	}

	// keep <br> tags inside captions and remove line breaks
	if ( content.indexOf( '[caption' ) != - 1 ) {
		preserve_br = true;
		content = content.replace( /\[caption[\s\S]+?\[\/caption\]/g, function ( a ) {
			return a.replace( /<br([^>]*)>/g, '<wp-temp-br$1>' ).replace( /[\r\n\t]+/, '' );
		} );
	}

	// Pretty it up for the source editor
	blocklist1 = 'blockquote|ul|ol|li|table|thead|tbody|tfoot|tr|th|td|div|h[1-6]|p|fieldset';
	content = content.replace( new RegExp( '\\s*</(' + blocklist1 + ')>\\s*', 'g' ), '</$1>\n' );
	content = content.replace( new RegExp( '\\s*<((?:' + blocklist1 + ')(?: [^>]*)?)>', 'g' ), '\n<$1>' );

	// Mark </p> if it has any attributes.
	content = content.replace( /(<p [^>]+>.*?)<\/p>/g, '$1</p#>' );

	// Sepatate <div> containing <p>
	content = content.replace( /<div( [^>]*)?>\s*<p>/gi, '<div$1>\n\n' );

	// Remove <p> and <br />
	content = content.replace( /\s*<p>/gi, '' );
	content = content.replace( /\s*<\/p>\s*/gi, '\n\n' );
	content = content.replace( /\n[\s\u00a0]+\n/g, '\n\n' );
	content = content.replace( /\s*<br ?\/?>\s*/gi, '\n' );

	// Fix some block element newline issues
	content = content.replace( /\s*<div/g, '\n<div' );
	content = content.replace( /<\/div>\s*/g, '</div>\n' );
	content = content.replace( /\s*\[caption([^\[]+)\[\/caption\]\s*/gi, '\n\n[caption$1[/caption]\n\n' );
	content = content.replace( /caption\]\n\n+\[caption/g, 'caption]\n\n[caption' );

	blocklist2 = 'blockquote|ul|ol|li|table|thead|tbody|tfoot|tr|th|td|h[1-6]|pre|fieldset';
	content = content.replace( new RegExp( '\\s*<((?:' + blocklist2 + ')(?: [^>]*)?)\\s*>', 'g' ), '\n<$1>' );
	content = content.replace( new RegExp( '\\s*</(' + blocklist2 + ')>\\s*', 'g' ), '</$1>\n' );
	content = content.replace( /<li([^>]*)>/g, '\t<li$1>' );

	if ( content.indexOf( '<hr' ) != - 1 ) {
		content = content.replace( /\s*<hr( [^>]*)?>\s*/g, '\n\n<hr$1>\n\n' );
	}

	if ( content.indexOf( '<object' ) != - 1 ) {
		content = content.replace( /<object[\s\S]+?<\/object>/g, function ( a ) {
			return a.replace( /[\r\n]+/g, '' );
		} );
	}

	// Unmark special paragraph closing tags
	content = content.replace( /<\/p#>/g, '</p>\n' );
	content = content.replace( /\s*(<p [^>]+>[\s\S]*?<\/p>)/g, '\n$1' );

	// Trim whitespace
	content = content.replace( /^\s+/, '' );
	content = content.replace( /[\s\u00a0]+$/, '' );

	// put back the line breaks in pre|script
	if ( preserve_linebreaks ) {
		content = content.replace( /<wp-temp-lb>/g, '\n' );
	}

	// and the <br> tags in captions
	if ( preserve_br ) {
		content = content.replace( /<wp-temp-br([^>]*)>/g, '<br$1>' );
	}

	return content;
}

function vc_wpautop( pee ) {
	var preserve_linebreaks = false, preserve_br = false,
		blocklist = 'table|thead|tfoot|caption|col|colgroup|tbody|tr|td|th|div|dl|dd|dt|ul|ol|li|pre|select|option|form|map|area|blockquote|address|math|style|p|h[1-6]|hr|fieldset|noscript|legend|section|article|aside|hgroup|header|footer|nav|figure|figcaption|details|menu|summary';

	if ( pee.indexOf( '<object' ) != - 1 ) {
		pee = pee.replace( /<object[\s\S]+?<\/object>/g, function ( a ) {
			return a.replace( /[\r\n]+/g, '' );
		} );
	}

	pee = pee.replace( /<[^<>]+>/g, function ( a ) {
		return a.replace( /[\r\n]+/g, ' ' );
	} );

	// Protect pre|script tags
	if ( pee.indexOf( '<pre' ) != - 1 || pee.indexOf( '<script' ) != - 1 ) {
		preserve_linebreaks = true;
		pee = pee.replace( /<(pre|script)[^>]*>[\s\S]+?<\/\1>/g, function ( a ) {
			return a.replace( /(\r\n|\n)/g, '<wp-temp-lb>' );
		} );
	}

	// keep <br> tags inside captions and convert line breaks
	if ( pee.indexOf( '[caption' ) != - 1 ) {
		preserve_br = true;
		pee = pee.replace( /\[caption[\s\S]+?\[\/caption\]/g, function ( a ) {
			// keep existing <br>
			a = a.replace( /<br([^>]*)>/g, '<wp-temp-br$1>' );
			// no line breaks inside HTML tags
			a = a.replace( /<[a-zA-Z0-9]+( [^<>]+)?>/g, function ( b ) {
				return b.replace( /[\r\n\t]+/, ' ' );
			} );
			// convert remaining line breaks to <br>
			return a.replace( /\s*\n\s*/g, '<wp-temp-br />' );
		} );
	}

	pee = pee + '\n\n';
	pee = pee.replace( /<br \/>\s*<br \/>/gi, '\n\n' );
	pee = pee.replace( new RegExp( '(<(?:' + blocklist + ')(?: [^>]*)?>)', 'gi' ), '\n$1' );
	pee = pee.replace( new RegExp( '(</(?:' + blocklist + ')>)', 'gi' ), '$1\n\n' );
	pee = pee.replace( /<hr( [^>]*)?>/gi, '<hr$1>\n\n' ); // hr is self closing block element
	pee = pee.replace( /\r\n|\r/g, '\n' );
	pee = pee.replace( /\n\s*\n+/g, '\n\n' );
	pee = pee.replace( /([\s\S]+?)\n\n/g, '<p>$1</p>\n' );
	pee = pee.replace( /<p>\s*?<\/p>/gi, '' );
	pee = pee.replace( new RegExp( '<p>\\s*(</?(?:' + blocklist + ')(?: [^>]*)?>)\\s*</p>', 'gi' ), "$1" );
	pee = pee.replace( /<p>(<li.+?)<\/p>/gi, '$1' );
	pee = pee.replace( /<p>\s*<blockquote([^>]*)>/gi, '<blockquote$1><p>' );
	pee = pee.replace( /<\/blockquote>\s*<\/p>/gi, '</p></blockquote>' );
	pee = pee.replace( new RegExp( '<p>\\s*(</?(?:' + blocklist + ')(?: [^>]*)?>)', 'gi' ), "$1" );
	pee = pee.replace( new RegExp( '(</?(?:' + blocklist + ')(?: [^>]*)?>)\\s*</p>', 'gi' ), "$1" );
	pee = pee.replace( /\s*\n/gi, '<br />\n' );
	pee = pee.replace( new RegExp( '(</?(?:' + blocklist + ')[^>]*>)\\s*<br />', 'gi' ), "$1" );
	pee = pee.replace( /<br \/>(\s*<\/?(?:p|li|div|dl|dd|dt|th|pre|td|ul|ol)>)/gi, '$1' );
	pee = pee.replace( /(?:<p>|<br ?\/?>)*\s*\[caption([^\[]+)\[\/caption\]\s*(?:<\/p>|<br ?\/?>)*/gi,
		'[caption$1[/caption]' );

	pee = pee.replace( /(<(?:div|th|td|form|fieldset|dd)[^>]*>)(.*?)<\/p>/g, function ( a, b, c ) {
		if ( c.match( /<p( [^>]*)?>/ ) ) {
			return a;
		}

		return b + '<p>' + c + '</p>';
	} );

	// put back the line breaks in pre|script
	if ( preserve_linebreaks ) {
		pee = pee.replace( /<wp-temp-lb>/g, '\n' );
	}

	if ( preserve_br ) {
		pee = pee.replace( /<wp-temp-br([^>]*)>/g, '<br$1>' );
	}
	return pee;
}

var vc_regexp_shortcode = _.memoize( function () {
	return RegExp( '\\[(\\[?)(\\w+\\b)(?![\\w-])([^\\]\\/]*(?:\\/(?!\\])[^\\]\\/]*)*?)(?:(\\/)\\]|\\](?:([^\\[]*(?:\\[(?!\\/\\2\\])[^\\[]*)*)(\\[\\/\\2\\]))?)(\\]?)' );
} );

/**
 * Add default values for params on shortcode creation.
 *
 * @since 4.5
 * @param model
 */
function vcAddShortcodeDefaultParams( model ) {
	var params = model.get( 'params' );
	params = _.extend( {}, vc.getDefaults( model.get( 'shortcode' ) ), params );
	model.set( { params: params }, { silent: true } );
}

/**
 * Simple (non-secure) hash function
 *
 * @since 4.5
 *
 * @param {object|string} obj Thing to hash
 * @return {number} Can be negative
 */
function vc_globalHashCode( obj ) {
	if ( 'string' !== typeof(obj) ) {
		obj = JSON.stringify( obj );
	}

	if ( ! obj.length ) {
		return 0;
	}

	return obj.split( '' ).reduce( function ( a, b ) {
		a = ((a << 5) - a) + b.charCodeAt( 0 );
		return a & a;
	}, 0 );
}

// underscore object memoize can cause overriding problems
vc.memoizeWrapper = function ( func, resolver ) {
	var cache = {};
	return function () {
		var key = resolver ? resolver.apply( this, arguments ) : arguments[ 0 ];
		if ( ! _.hasOwnProperty.call( cache, key ) ) {
			cache[ key ] = func.apply( this, arguments );
		}
		return _.isObject( cache[ key ] ) ? jQuery.fn.extend( true, {}, cache[ key ] ) : cache[ key ]; // perform DEEP extend
	};
};

/**
 * Select random color when new param is added.
 *
 * @param $elem
 * @param action
 */
function vcChartParamAfterAddCallback( $elem, action ) {
	if ( 'new' === action || 'clone' === action ) {
		$elem.find( '.vc_control.column_toggle' ).click();
	}

	if ( 'new' !== action ) {
		return;
	}

	var i, $select, $options, random, exclude, colors;

	exclude = [
		'white',
		'black'
	];

	$select = $elem.find( '[name=values_color]' );
	$options = $select.find( 'option' );

	i = 0;
	while ( true ) {
		if ( 100 < i ++ ) {
			break;
		}

		random = Math.floor( (Math.random() * $options.length) );

		if ( jQuery.inArray( $options.eq( random ).val(), exclude ) === - 1 ) {
			$options.eq( random ).prop( 'selected', true );
			$select.change();
			break;
		}
	}

	colors = [
		'#5472d2',
		'#00c1cf',
		'#fe6c61',
		'#8d6dc4',
		'#4cadc9',
		'#cec2ab',
		'#50485b',
		'#75d69c',
		'#f7be68',
		'#5aa1e3',
		'#6dab3c',
		'#f4524d',
		'#f79468',
		'#b97ebb',
		'#ebebeb',
		'#f7f7f7',
		'#0088cc',
		'#58b9da',
		'#6ab165',
		'#ff9900',
		'#ff675b',
		'#555555'
	];

	random = Math.floor( (Math.random() * colors.length) );

	$elem.find( '[name=values_custom_color]' )
		.val( colors[ random ] )
		.change();
}

/**
 * @since 4.5
 */
vc.events.on( 'shortcodes:vc_row:add:param:name:parallax shortcodes:vc_row:update:param:name:parallax',
	function ( model, value ) {
		if ( value ) {
			var params = model.get( 'params' );
			if ( params && params.css ) {
				params.css = params.css.replace( /(background(\-position)?\s*\:\s*[\S]+(\s*[^\!\s]+)?)[\s*\!important]*/g,
					'$1' );
				model.set( 'params', params, { silent: true } );
			}
		}
	} );

/**
 * BC for single image
 *
 * If we have 'link' attribute, but 'onclick' is empty, set 'onclick' to 'custom_link'
 *
 * @since 4.8
 */
vc.events.on( 'shortcodes:vc_single_image:sync shortcodes:vc_single_image:add', function ( model ) {
	var params = model.get( 'params' );

	if ( params.link && ! params.onclick ) {
		params.onclick = 'custom_link';
		model.save( { params: params } );
	}
} );

/**
 * console.log for every browser
 *
 * @see http://paulirish.com/2009/log-a-lightweight-wrapper-for-consolelog/
 */
window.vcConsoleLog = function () {
	vcConsoleLog.history = vcConsoleLog.history || [];   // store logs to an array for reference
	vcConsoleLog.history.push( arguments );
	if ( this.console ) {
		console.log( Array.prototype.slice.call( arguments ) );
	}
};

/**
 * Escape html string
 *
 * @param {string} text
 * @return {string}
 */
function vcEscapeHtml( text ) {
	var map = {
		'&': '&amp;',
		'<': '&lt;',
		'>': '&gt;',
		'"': '&quot;',
		"'": '&#039;'
	};

	if ( null === text || 'undefined' === typeof(text) ) {
		return '';
	}

	return text.replace( /[&<>"']/g, function ( m ) {
		return map[ m ];
	} );
};