module.exports = function(grunt) {
	grunt.initConfig({
		uglify: {
			js: {
				files: [
				{ // modal - .js to modal-all.min.js
					src: [
							'assets/js/modernizr-custom.js',
							'assets/js/classie.js',
							'assets/js/froogaloop2.min.js',
							'assets/js/snap-svg.js',
							'assets/js/modal.js'
						],
					dest: 'assets/min-js/modal-all.min.js',
				}, 
				{ // all .js to min.js
					expand: true,
					src: [
							'**.js',
							'!classie.js',
							'!snap-svg.js',
							'!froogaloop2.min.js',
							'!modal.js',
							'!vc-fronteditor.js',
							'!vc-inline-editor.js',
							'!vc_column.js',
							'!bootstrap-datetimepicker.js',
							'!masonry.js',
							'!jquery.js'
						],
					dest: 'assets/min-js/',
					cwd: 'assets/js/',
					ext: '.min.js'
				}, 
				{ // all .js to .ultimate.min.js
					src: [
							'assets/js/ultimate_bg.js',
							'assets/js/*.js',
							'!assets/js/modernizr-custom.js',
							'!assets/js/classie.js',
							'!assets/js/snap-svg.js',
							'!assets/js/froogaloop2.min.js',
							'!assets/js/modal.js',
							'!assets/js/modal-all.js',
							'!assets/js/jquery-ui-effect.js',
							'!assets/js/vc-fronteditor.js',
							'!assets/js/vc-inline-editor.js',
							'!assets/js/jquery-ui.js',
							'!assets/js/vc_column.js',
							'!assets/js/bootstrap-datetimepicker.js',
							'!assets/js/masonry.js',
							'!assets/js/jparallax.js',
							'!assets/js/vhparallax.js',
							'!assets/js/mb-YTPlayer.js',
							'!assets/js/jquery.vhparallax.js',
							'!assets/js/SmoothScroll.js',
							'!assets/js/jquery.js'
						],
					dest: 'assets/min-js/ultimate.min.js'
				},
				{
					src: [
							'woocomposer/assets/js/unveil.js',
							'woocomposer/assets/js/slick.js',
							'woocomposer/assets/js/custom.js',
						],
					dest: 'woocomposer/assets/js/woocomposer.min.js'
				},
				{ // minification of admin -> vc_extend
					src: [
							'admin/vc_extend/js/chosen.js',
							'admin/js/dualbtnbackend.js',
							'admin/js/jquery-colorpicker.js',
							'admin/js/jquery-classygradient-min.js',
							'admin/vc_extend/js/admin_enqueue_js.js',
						],
					dest: 'admin/js/ultimate-vc-backend.min.js'
				}, { // minification of wocomposer
					src: [
							'woocomposer/admin/js/unveil.js',
							'woocomposer/admin/js/select2.js',
							'woocomposer/admin/js/slick.js',
							'woocomposer/admin/js/custom.js',
						],
					dest: 'woocomposer/admin/js/ultimate-woocomposer-backend.min.js'
				}]
			}
		},
		cssmin: {
			css: {
				files: [{ //.css to min.css
					expand: true,
					src: [
							'**/*.css',
							'!icon.css',
							'!advacne_carosal_front.css',
							'!sub-tab.css',
							'!vc-fronteditor.css',
							'!bootstrap-datetimepicker.css'
						],
					dest: 'assets/min-css/',
					cwd: 'assets/css/',
					ext: '.min.css'
				}, { // .css to ultimate.min.css
					src: [
							'assets/css/*.css',
							'!assets/css/icon.css',
							'!assets/css/advacne_carosal_front.css',
							'!assets/css/sub-tab.css',
							'!assets/css/vc-fronteditor.css',
							'!assets/css/bootstrap-datetimepicker.css'
						],
					dest: 'assets/min-css/ultimate.min.css'
				}, { //woocomposer .css to woocomposer.min.css
					src: [
							'woocomposer/assets/css/style.css',
							'woocomposer/assets/css/slick.css',
							'woocomposer/assets/css/wooicon.css',
							'woocomposer/assets/css/animate.min.css',
						],
					dest: 'woocomposer/assets/css/woocomposer.min.css'
				}, { //backend css optimization
					src: [
							'admin/css/style.css',
							'admin/css/icon-manager.css',
							'assets/css/animate.css',
							'admin/vc_extend/css/ultimate_border.css',
							'admin/vc_extend/css/chosen.css',
							'admin/vc_extend/css/vc_param_boxshadow.css',
							'admin/vc_extend/css/ultimate_image_single.css',
							'admin/vc_extend/css/ultimate_responsive.min.css',
							'admin/vc_extend/css/ultimate_spacing.css',
							'admin/css/bootstrap-datetimepicker-admin.css',
							'admin/css/jquery-colorpicker.css',
							'admin/css/jquery-classygradient-min.css',
							'assets/css/advanced-buttons.css',
						],
					dest: 'admin/css/ultimate-vc-backend.min.css'
				}, { //woocomposer backend css optimization
					src: [
							'woocomposer/admin/css/admin.css',
							'woocomposer/admin/css/select2-bootstrap.css',
							'woocomposer/admin/css/select2.css'
						],
					dest: 'woocomposer/admin/css/ultimate-woocomposer-backend.min.css'
				}]
			}
		}
	});
	grunt.loadNpmTasks('grunt-contrib-cssmin');
	grunt.loadNpmTasks('grunt-contrib-uglify');
	grunt.registerTask('default', ['uglify:js','cssmin:css']);
};