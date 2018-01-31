/* =========================================================
 * jquery.vc_chart.js v1.0
 * =========================================================
 * Copyright 2013 Wpbakery
 *
 * Jquery chart plugin for the Visual Composer.
 * ========================================================= */
(function($){
    /**
     * Pie chart animated.
     * @param element - DOM element
     * @param options - settings object.
     * @constructor
     */
    var VcChart = function(element, options) {
        this.el = element;
        this.$el = $(this.el);
        this.options = $.extend({
            color: '#f7f7f7',
            units: '',
            label_selector: '.noo-pie-chart-value',
            back_selector: '.noo-pie-chart-back',
            responsive: true
        }, options);
        this.init();
    };
    VcChart.prototype = {
        constructor: VcChart,
        _progress_v: 0,
        animated: false,
        colors: {
            'wpb_button': 'rgba(247, 247, 247, 1)',
            'btn-primary': 'rgba(0, 136, 204, 1)',
            'btn-info': 'rgba(88, 185, 218, 1)',
            'btn-success': 'rgba(106, 177, 101, 1)',
            'btn-warning': 'rgba(255, 153, 0, 1)',
            'btn-danger': 'rgba(255, 103, 91, 1)',
            'btn-inverse': 'rgba(85, 85, 85, 1)'
        },
        init: function() {
            this.setupColor();
            this.value = this.$el.data('pie-value')/100;
            this.label_value = this.$el.data('pie-label-value') || this.$el.data('pie-value');
            this.$wrapper = $('.noo-pie-chart-wrapper', this.$el);
            this.$label = $(this.options.label_selector, this.$el);
            this.$back = $(this.options.back_selector, this.$el);
            this.$canvas = this.$el.find('canvas');
            this.draw();
            this.setWayPoint();
            if(this.options.responsive === true) this.setResponsive();

        },
        setupColor: function() {
                this.color = this.options.color;
        },
        setResponsive: function() {
            var that = this;
            $(window).resize(function(){
                if(that.animated === true) that.circle.stop();
                that.draw(true);
            });
        },
        draw: function(redraw) {
            var w = this.$el.width()/100*80,
                border_w = this.$el.data('pie-with'),
                radius = w/2 - border_w - 1,
                w2 = w+8,
                h2 = w+8;
            	
            this.$wrapper.css({"width" : w2 + "px", "height" : h2 + "px","padding":"4px"});
          
            this.$label.css({"width" : w2, "height" : w2, "line-height" : w2+"px"});
            this.$back.css({"width" : w, "height" : w,'border-width':(border_w + 2 )+'px','border-color':this.color});
            this.$canvas.attr({"width" : w + "px", "height" : w + "px"});
            if(radius < 0) return false;
            this.circle = new ProgressCircle({
                canvas: this.$canvas.get(0),
                minRadius: radius,
                arcWidth: border_w
            });
            
            var fillColor = this.color;
            
            if(this.$el.hasClass('noo-pie-chart-filled')){
            	this.$wrapper.css({"background-color" : this.color});
            	fillColor = '#ffffff';
            	this.$back.css({'border-color':'rgba(0, 0, 0, 0)'});
            }
            
           
            if(redraw === true && this.animated === true) {
                this._progress_v = this.value;
                this.circle.addEntry({
                    fillColor: fillColor,
                    progressListener: $.proxy(this.setProgress, this)
                }).start();
            }
        },
        setProgress: function() {
            if (this._progress_v >= this.value) {
                this.circle.stop();
                this.$label.text(this.label_value + this.options.units);
                return this._progress_v;
            }
            this._progress_v += 0.005;
            var label_value = this._progress_v/this.value*this.label_value;
            var val = Math.round(label_value) + this.options.units;
            this.$label.text(val);
            return this._progress_v;
        },
        animate: function() {
        	var fillColor = this.color;
            
            if(this.$el.hasClass('noo-pie-chart-filled')){
            	fillColor = '#ffffff';
            }
            if(this.animated !== true) {
                this.animated = true;
                this.circle.addEntry({
                    fillColor: fillColor,
                    progressListener: $.proxy(this.setProgress, this)
                }).start(5);
            }
        },
        setWayPoint: function() {
            if (typeof $.fn.waypoint !== 'undefined') {
                this.$el.waypoint($.proxy(this.animate, this), { offset: '85%' });
            } else {
                this.animate();
            }
        }
    };
    /**
     * jQuery plugin
     * @param option - object with settings
     * @return {*}
     */
    $.fn.vcChat = function(option) {
        return this.each(function () {
            var $this = $(this),
                data = $this.data('vc_chart'),
                options = typeof option === 'object' ? option : {
                    color: $this.data('pie-color'),
                    units: $this.data('pie-units')
                };
            if (typeof option == 'undefined') $this.data('vc_chart', (data = new VcChart(this, options)));
            if (typeof option == 'string') data[option]();
        });
    };
    /**
     * Allows users to rewrite function inside theme.
     */
    if ( typeof window['vc_pieChart'] !== 'function' ) {
        window.vc_pieChart = function() {
            $('.noo-pie-chart').appear(function(){
            	$(this).vcChat();
            })
        }
    }
    $(document).ready(function(){
        !window.vc_iframe && vc_pieChart();
    });

})(window.jQuery);