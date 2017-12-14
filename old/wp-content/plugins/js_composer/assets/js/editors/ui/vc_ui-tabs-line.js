+ function ( $ ) {
	'use strict';

	var TabsLine, Plugin, old;

	/**
	 * Tabs Line object definition
	 * @param element
	 * @param options
	 * @constructor
	 */
	TabsLine = function ( element, options ) {
		var _this = this;
		this.options = options;
		this.$element = $( element );
		this.$dropdownContainer = this.$element.find( this.options.dropdownContainerSelector );
		this.$dropdown = this.$dropdownContainer.find( this.options.dropdownSelector );

		if ( this.options.delayInit ) {
			_this.$element.addClass( this.options.initializingClass );
			setTimeout( function () {
				if ( ! _this.options.autoRefresh ) {
					_this.refresh();
				}
				_this.moveTabs();
				_this.$element.removeClass( _this.options.initializingClass );
			}, _this.options.delayInitTime );

		} else {
			if ( ! this.options.autoRefresh ) {
				this.refresh();
			}
			this.moveTabs();
		}
		$( window ).on( 'resize', $.proxy( this.moveTabs, this ) );
		this.$dropdownContainer.on( 'click.vc.tabsLine', $.proxy( this.checkDropdownContainerActive, this ) );
	};

	/**
	 * Tabs line default values
	 */
	TabsLine.DEFAULTS = {
		initializingClass: "vc_initializing",
		delayInit: false,
		delayInitTime: 1000,
		activeClass: "vc_active",
		visibleClass: "vc_visible",
		dropdownContainerSelector: '[data-vc-ui-element="panel-tabs-line-toggle"]',
		dropdownSelector: '[data-vc-ui-element="panel-tabs-line-dropdown"]',
		tabSelector: '>li:not([data-vc-ui-element="panel-tabs-line-toggle"])',
		dropdownTabSelector: 'li',
		freeSpaceOffset: 5,
		autoRefresh: false,
		showDevInfo: false
	};

	/**
	 * Refresh plugin data
	 * @returns {TabsLine}
	 */
	TabsLine.prototype.refresh = function () {
		var _this = this;
		var addClick;
		_this.tabs = [];
		_this.dropdownTabs = [];

		_this.$element.find( _this.options.tabSelector ).each( function () {
			_this.tabs.push( {
				$tab: $( this ),
				width: $( this ).outerWidth()
			} );
		} );

		_this.$dropdown.find( _this.options.dropdownTabSelector ).each( function () {
			var $tempElement = $( this ).clone().css( {
				visibility: 'hidden',
				position: 'fixed'
			} );
			$tempElement.appendTo( _this.$element );
			_this.dropdownTabs.push( {
				$tab: $( this ),
				width: $tempElement.outerWidth()
			} );
			$tempElement.remove();
			$( this ).on( 'click', _this.options.onTabClick );
		} );

		if ( 'function' === typeof(this.options.onTabClick) ) {
			addClick = function ( el ) {
				if ( 'undefined' === typeof(el.$tab.data( 'tabClickSet' ) ) ) {
					el.$tab.on( 'click', $.proxy( _this.options.onTabClick, el.$tab ) );
					el.$tab.data( 'tabClickSet', true );
				}
			};

			_this.tabs.map( addClick );
			_this.dropdownTabs.map( addClick );
		}

		return this;
	};

	/**
	 * Move last tab from tabs line to dropdown
	 * @returns {TabsLine}
	 */
	TabsLine.prototype.moveLastToDropdown = function () {
		var $element;
		if ( this.tabs.length ) {
			$element = this.tabs.pop();
			$element.$tab.prependTo( this.$dropdown );
			this.dropdownTabs.unshift( $element );
		}
		this.checkDropdownContainer();
		return this;
	};

	/**
	 * Move first tab from dropdown to Tabs Line
	 * @returns {TabsLine}
	 */
	TabsLine.prototype.moveFirstToContainer = function () {
		var $element;
		if ( this.dropdownTabs.length ) {
			$element = this.dropdownTabs.shift();
			$element.$tab.appendTo( this.$element );
			this.tabs.push( $element );
		}
		this.checkDropdownContainer();
		return this;
	};

	/**
	 * Gec all Tabs Line tabs width
	 * @returns {number}
	 */
	TabsLine.prototype.getTabsWidth = function () {
		var tabsWidth = 0;
		this.tabs.forEach( function ( entry ) {
			tabsWidth += entry.width;
		} );
		return tabsWidth;
	};

	/**
	 * Check is dropdown visible
	 * @returns bool
	 */
	TabsLine.prototype.isDropdownContainerVisible = function () {
		return this.$dropdownContainer.hasClass( this.options.visibleClass );
	};

	/**
	 * Check Tabs Line for free space
	 * @returns {number}
	 */
	TabsLine.prototype.getFreeSpace = function () {
		var freeSpace = this.$element.width() - this.getTabsWidth() - this.options.freeSpaceOffset;
		if ( this.isDropdownContainerVisible() ) {
			freeSpace -= this.$dropdownContainer.outerWidth();
			if ( 1 === this.dropdownTabs.length && 0 <= freeSpace - this.dropdownTabs[ 0 ].width + this.$dropdownContainer.outerWidth() ) {
				freeSpace += this.$dropdownContainer.outerWidth();
			}
		}
		return freeSpace;
	};

	/**
	 * Move tabs from Tabs Line to dropdown based on free space
	 * @returns {TabsLine}
	 */
	TabsLine.prototype.moveTabsToDropdown = function () {
		var tabsCount = this.tabs.length;
		for ( var i = tabsCount - 1;
			  0 <= i;
			  i -- ) {
			if ( 0 > this.getFreeSpace() ) {
				this.moveLastToDropdown();
			} else {
				return this;
			}
		}
		return this;
	};

	/**
	 *  Move tabs from Dropdown to Tabs Line based on free space
	 * @returns {TabsLine}
	 */
	TabsLine.prototype.moveDropdownToTabs = function () {
		var dropdownTabsCount = this.dropdownTabs.length;
		for ( var i = 0;
			  i < dropdownTabsCount;
			  i ++ ) {
			if ( 0 <= this.getFreeSpace() - this.dropdownTabs[ 0 ].width ) {
				this.moveFirstToContainer();
			} else {
				return this;
			}
		}
		return this;
	};

	/**
	 * Show Dropdown container
	 * @returns {TabsLine}
	 */
	TabsLine.prototype.showDropdownContainer = function () {
		this.$dropdownContainer.addClass( this.options.visibleClass );
		return this;
	};

	/**
	 * Hide dropdown container;
	 * @returns {TabsLine}
	 */
	TabsLine.prototype.hideDropdownContainer = function () {
		this.$dropdownContainer.removeClass( this.options.visibleClass );
		return this;
	};

	/**
	 * Set active state to dropdown
	 * @returns {TabsLine}
	 */
	TabsLine.prototype.activateDropdownContainer = function () {
		this.$dropdownContainer.addClass( this.options.activeClass );
		return this;
	};

	/**
	 * Remove active state from dropdown
	 * @returns {TabsLine}
	 */
	TabsLine.prototype.deactivateDropdownContainer = function () {
		this.$dropdownContainer.removeClass( this.options.activeClass );
		return this;
	};

	/**
	 * Check if dropdown has active state
	 * @returns {TabsLine}
	 */
	TabsLine.prototype.checkDropdownContainerActive = function () {
		if ( this.$dropdown.find( '.' + this.options.activeClass + ":first" ).length ) {
			this.activateDropdownContainer();
		} else {
			this.deactivateDropdownContainer();
		}
		return this;
	};

	/**
	 * Check is dropdown container need to be shown
	 * @returns {TabsLine}
	 */
	TabsLine.prototype.checkDropdownContainer = function () {
		if ( this.dropdownTabs.length ) {
			this.showDropdownContainer();
		} else {
			this.hideDropdownContainer();
		}
		this.checkDropdownContainerActive();
		return this;
	};

	/**
	 * Synchronize tabs in dropdown and tabs line
	 * @returns {TabsLine}
	 */
	TabsLine.prototype.moveTabs = function () {
		if ( this.options.autoRefresh ) {
			this.refresh();
		}
		this.checkDropdownContainer();
		this.moveTabsToDropdown();
		this.moveDropdownToTabs();
		if ( this.options.showDevInfo ) {
			this.showDevInfo();
		}
		return this;
	};

	/**
	 * Show developer info
	 */
	TabsLine.prototype.showDevInfo = function () {
		var $devInfoBlock = $( '#vc-ui-tabs-line-dev-info' );
		if ( $devInfoBlock.length ) {
			this.$devBlock = $devInfoBlock;
		}

		if ( 'undefined' === typeof(this.$devBlock) ) {
			this.$devBlock = $( '<div id="vc-ui-tabs-line-dev-info" />' ).css( {
				position: 'fixed',
				right: '40px',
				top: '40px',
				padding: '7px 12px',
				border: '1px solid rgba(0, 0, 0, .2)',
				background: 'rgba(0, 0, 0, .7)',
				color: '#0a0',
				'border-radius': '5px',
				'font-family': 'tahoma',
				'font-size': '12px',
				'z-index': 1100
			} );
			this.$devBlock.appendTo( 'body' );
		}

		if ( 'undefined' === typeof(this.$devInfo) ) {
			this.$devInfo = $( '<div />' ).css( {
				'margin-bottom': '7px',
				'padding-bottom': '7px',
				'border-bottom': '1px dashed rgba(0, 200, 0, .35)'
			} );
			this.$devInfo.appendTo( this.$devBlock );
		}
		this.$devInfo.empty();
		this.$devInfo.append( $( '<div />' ).text( 'Tabs count: ' + this.tabs.length ) );
		this.$devInfo.append( $( '<div />' ).text( 'Dropdown count: ' + this.dropdownTabs.length ) );
		this.$devInfo.append( $( '<div />' ).text( 'El width: ' + this.$element.width() ) );
		this.$devInfo.append( $( '<div />' ).text( 'Tabs width: ' + this.getTabsWidth() ) );
		this.$devInfo.append( $( '<div />' ).text( 'Tabs width with dots: ' + (this.getTabsWidth() + this.$dropdownContainer.outerWidth()) ) );
		this.$devInfo.append( $( '<div />' ).text( 'Free space: ' + this.getFreeSpace() ) );
		if ( this.tabs.length ) {
			this.$devInfo.append( $( '<div />' ).text( 'Last tab width: ' + this.tabs[ this.tabs.length - 1 ].width ) );
		}
		if ( this.dropdownTabs.length ) {
			this.$devInfo.append( $( '<div />' ).text( 'First dropdown tab width: ' + this.dropdownTabs[ 0 ].width ) );
		}
	};

	/**
	 * Tabs line plugin definition
	 * @param option
	 */
	Plugin = function ( option ) {
		return this.each( function () {
			var $this = $( this );
			var optionsData = $this.data( 'vcUiTabsLine' );
			var data = $this.data( 'vc.tabsLine' );
			var options = $.extend( true,
				{},
				TabsLine.DEFAULTS,
				$this.data(),
				optionsData,
				'object' === typeof(option) && option );
			var action = 'string' === typeof(option) ? option : options.action;

			if ( ! data ) {
				$this.data( 'vc.tabsLine', (data = new TabsLine( this, options )) );
			}

			if ( action ) {
				data[ action ]();
			}
		} );
	};

	old = $.fn.vcTabsLine;
	$.fn.vcTabsLine = Plugin;
	$.fn.vcTabsLine.Constructor = TabsLine;

	// Accordion no conflict
	// ==========================
	$.fn.vcTabsLine.noConflict = function () {
		$.fn.vcTabsLine = old;
		return this;
	};

	$( window ).on( 'load', function () {
		$( '[data-vc-ui-tabs-line]' ).each( function () {
			var $vcTabsLine = $( this );
			Plugin.call( $vcTabsLine, $vcTabsLine.data() );
		} );
	} );

}( window.jQuery );