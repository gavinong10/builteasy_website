<?php include TVE_DASH_PATH .'/templates/header.phtml';?>
<div class="tvd-v-spacer vs-2"></div>
<div class="font-manager-settings">
	<h3><?php echo __( "Custom Font Manager", TVE_DASH_TRANSLATE_DOMAIN ); ?></h3>

	<p>
		<?php echo __( "By default, Thrive Themes integrates with Google Fonts. This allows you to choose from 600+ fonts for use in your content. However, you can also use the blue import font button below to import your own fonts files using a service called Font Squirrel" ); ?>
		<a href="https://thrivethemes.com/tkb_item/how-to-use-the-font-import-manager/" target="_blank"><?php echo __( "Learn more about how to import your own fonts", 'thrive' ) ?></a>
	</p>
	<div class="tvd-row">
		<a class="tvd-waves-effect tvd-waves-light tvd-btn-small tvd-btn-blue" href="<?php echo admin_url( "admin.php?page=tve_dash_font_import_manager" ) ?>">
			<?php echo __( "Import custom font manager", TVE_DASH_TRANSLATE_DOMAIN ) ?>
		</a>
	</div>
	<div>
		<div class="tvd-row">
			<h3 class="tvd-col tvd-m2 tvd-s1">
				<?php echo __( "Font name", TVE_DASH_TRANSLATE_DOMAIN ) ?>
			</h3>
			<h3 class="tvd-col tvd-m2 tvd-s1">
				<?php echo __( "Size", TVE_DASH_TRANSLATE_DOMAIN ) ?>
			</h3>
			<h3 class="tvd-col tvd-m2 tvd-s1">
				<?php echo __( "Color", TVE_DASH_TRANSLATE_DOMAIN ) ?>
			</h3>
			<h3 class="tvd-col tvd-m2 tvd-s1">
				<?php echo __( "CSS Class Name", TVE_DASH_TRANSLATE_DOMAIN ) ?>
			</h3>
			<h3 class="tvd-col tvd-m4 tvd-s1">
				<?php echo __( "Actions", TVE_DASH_TRANSLATE_DOMAIN ) ?>
			</h3>
		</div>

		<?php foreach ( $font_options as $font ): ?>
			<div class="tvd-row">
				<div class="tvd-col tvd-m2 tvd-s1"><?php echo $font['font_name']; ?></div>
				<div class="tvd-col tvd-m2 tvd-s1"><?php echo $font['font_size']; ?></div>
				<div class="tvd-col tvd-m2 tvd-s1">
					<span class="tvd-fm-color" style="background-color: <?php echo $font['font_color']; ?>;">&nbsp;</span>
					<?php echo empty( $font['font_color'] ) ? __( 'white', TVE_DASH_TRANSLATE_DOMAIN ) : $font['font_color']; ?>
				</div>
				<div class="tvd-col tvd-m2 tvd-s1">
					<input type="text" readonly value="<?php echo $font['font_class']; ?>">
				</div>
				<div class="tvd-col tvd-m4 tvd-s1">
					<a class='tvd-waves-effect tvd-waves-light tvd-btn-small tvd-btn-blue edit-font'><i class="tvd-icon-edit"></i> <?php echo __( "Edit", TVE_DASH_TRANSLATE_DOMAIN ) ?></a>
					<a class='tvd-waves-effect tvd-waves-light tvd-btn-small tvd-btn-gray duplicate-font'><i class="tvd-icon-clone"></i> <?php echo __( "Duplicate", TVE_DASH_TRANSLATE_DOMAIN ) ?></a>
					<a class="tvd-waves-effect tvd-waves-light tvd-btn-small tvd-btn-red delete-font"><i class="tvd-icon-delete"></i> <?php echo __( "Delete", TVE_DASH_TRANSLATE_DOMAIN ) ?></a>
					<input type="hidden" class="font-id" value="<?php echo $font['font_id']; ?>">
				</div>
			</div>
		<?php endforeach; ?>
	</div>
	<div class="tvd-row" style="margin-top: 5px;">
		<div class="tvd-col tvd-m6">
			<a href="<?php echo admin_url( 'admin.php?page=tve_dash_section' ); ?>" class="tvd-waves-effect tvd-waves-light tvd-btn-small tvd-btn-gray">
				<?php echo __( "Back To Dashboard", TVE_DASH_TRANSLATE_DOMAIN ); ?>
			</a>
		</div>
		<div class="tvd-col tvd-m6">
			<a class="tvd-waves-effect tvd-waves-light tvd-btn-small tvd-btn-green tvd-right" id="thrive-add-font" href="javascript:void(0)">
				<i class="tvd-icon-add"></i> <?php echo __( "Add Custom Font", TVE_DASH_TRANSLATE_DOMAIN ); ?>
			</a>
			<input type="hidden" value="<?php echo $font_id; ?>" id='new-font-id'/>

			<a style="margin-right: 5px;" class="tvd-waves-effect tvd-waves-light tvd-btn-small tvd-btn-blue tvd-right" id="thrive-update-posts" href="javascript:void(0)">
				<i style="display: none;" class="tvd-icon-spinner mdi-pulse"></i> <?php echo __( "Update Posts", TVE_DASH_TRANSLATE_DOMAIN ); ?>
			</a>
		</div>
	</div>
</div>
<script type="text/javascript">
	jQuery( document ).ready( function () {
		var tb_height = 600,
			tb_width = 600;
		jQuery( '#thrive-add-font' ).click( function () {
			var font_id = jQuery( '#new-font-id' ).val();
			tb_show( 'Edit shortcode options', 'admin-ajax.php?action=tve_dash_display_font_manager&height=' + tb_height + '&width=' + tb_width + '&font_id=' + font_id );
		} );

		jQuery( '#thrive-update-posts' ).click( function () {
			var loading = jQuery( this ).find( 'i' );
			loading.show();
			jQuery.post( 'admin-ajax.php?action=tve_dash_font_manager_update_posts_fonts', function ( response ) {
				loading.hide();
			} );
		} );

		jQuery( 'a.edit-font' ).click( function () {
			var font_id = jQuery( this ).siblings( '.font-id' ).val();
			tb_show( 'Edit shortcode options', 'admin-ajax.php?action=tve_dash_display_font_manager&height=' + tb_height + '&width=' + tb_width + '&font_action=update&font_id=' + font_id );
		} );

		jQuery( 'a.delete-font' ).click( function () {
			var font_id = jQuery( this ).siblings( '.font-id' ).val();
			var postData = {
				font_id: font_id
			};
			jQuery.post( 'admin-ajax.php?action=tve_dash_font_manager_delete', postData, function ( response ) {
				location.reload();
			} );
		} );
		jQuery( 'a.duplicate-font' ).click( function () {
			var font_id = jQuery( this ).siblings( '.font-id' ).val();
			var postData = {
				font_action: 'duplicate',
				font_id: font_id
			};
			jQuery.post( 'admin-ajax.php?action=tve_dash_font_manager_duplicate', postData, function ( response ) {
				location.reload();
			} );
		} );
	} );
</script>