<?php global $variation;
if ( empty( $is_ajax_render ) ) : /** if AJAX-rendering the contents, we need to only output the html part, and do not include any of the custom css / fonts etc needed - used in the state manager */ ?>
	<?php do_action( 'get_header' ) ?><!DOCTYPE html>
	<!--[if IE 7]>
	<html class="ie ie7" <?php language_attributes(); ?>>
	<![endif]-->
	<!--[if IE 8]>
	<html class="ie ie8" <?php language_attributes(); ?>>
	<![endif]-->
	<!--[if !(IE 7) | !(IE 8)  ]><!-->
	<html <?php language_attributes(); ?>>
	<!--<![endif]-->
	<head>
		<meta charset="<?php bloginfo( 'charset' ); ?>"/>
		<meta name="robots" content="noindex, nofollow"/>
		<title>
			<?php /* Genesis wraps the meta title into another <title> tag using this hook: genesis_doctitle_wrap. the following line makes sure this isn't called */ ?>
			<?php /* What if they change the priority at which this hook is registered ? :D */ ?>
			<?php remove_filter( 'wp_title', 'genesis_doctitle_wrap', 20 ) ?>
			<?php wp_title( '' ); ?>
		</title>
		<meta name="viewport" content="width=device-width, initial-scale=1.0"/>
		<?php

		do_action( 'tcb_content_custom_css', $variation, false );
		$form_template = empty( $variation[ TVE_LEADS_FIELD_TEMPLATE ] ) ? '' : $variation[ TVE_LEADS_FIELD_TEMPLATE ];

		$state_manager_collapsed = ! empty( $_COOKIE['tve_leads_state_collapse'] );

		wp_head(); ?>
	</head>
	<body <?php body_class( ( $state_manager_collapsed ? 'tl-state-collapse ' : '' ) . 'tve_gray_bg ' . preg_replace( '#(.+?)\|(.+)#', '$2', $form_template ) ) ?>>
	<div style="display: none" class="bSe"></div>
<?php endif ?>
<?php $hide_form = tve_leads_check_variation_visibility( $variation ); ?>
	<div id="tve-leads-editor-replace">
		<?php include dirname( __FILE__ ) . '/_no_form.php'; ?>
		<div class="tve-leads-template-description" style="opacity: .6; margin: 0 auto;text-align: center; max-width: 80%;padding: 10px 0;<?php if ( $hide_form ): ?>display: none;<?php endif; ?>">
			<h4><?php echo __( 'This is a Form Design type called "Slide In".', 'thrive-leads' ) ?></h4>
			<h4><?php echo __( 'It is simply a form that slides in from the edge of the content and sits on the edge of the screen, usually in the bottom right, but can be anywhere.', 'thrive-leads' ) ?></h4>
		</div>
		<?php
		$key = '';
		if ( ! empty( $variation[ TVE_LEADS_FIELD_TEMPLATE ] ) ) {
			list( $type, $key ) = explode( '|', $variation[ TVE_LEADS_FIELD_TEMPLATE ] );
			$key = preg_replace( '#_v(.+)$#', '', $key );
		}
		$parent = $variation;
		if ( ! empty( $variation['parent_id'] ) ) {
			$parent = tve_leads_get_form_variation( null, $variation['parent_id'] );
		}
		?>
		<div class="tve-leads-slide-in tl_<?php echo $parent['position']; ?>" <?php if ( $hide_form ): ?>style="display: none;"<?php endif; ?>>
			<div class="tl-style" id="tve_<?php echo $key ?>" data-state="<?php echo $variation['key'] ?>">
				<?php echo apply_filters( 'tve_editor_custom_content', null ) ?>
			</div>
			<?php echo apply_filters( 'tve_leads_variation_append_states', '', $variation ); ?>
		</div>
	</div>

<?php if ( empty( $is_ajax_render ) ) : ?>
	<div id="tve_page_loader" class="tve_page_loader">
		<div class="tve_loader_inner"><img src="<?php echo tve_editor_css() ?>/images/loader.gif" alt=""/></div>
	</div>

	<?php include dirname( __FILE__ ) . '/_form_states.php' ?>
	<?php do_action( 'get_footer' ) ?>
	<?php wp_footer() ?>
	</body>
	</html>
<?php endif ?>