<?php
/** Welcome block **/
class Homeland_Welcome_Block extends AQ_Block {
	
	//set and create block
	function __construct() {
		$block_options = array(
			'name' => __('Welcome', 'aqpb-l10n'),
			'size' => 'span12',
		);
		
		//create the block
		parent::__construct('homeland_welcome_block', $block_options);
	}
	
	function form($instance) {
		
		$defaults = array(
			'homeland_text' => '',
			'homeland_button_text' => '',
			'homeland_button_link' => '',
			'homeland_background' => ''
		);
		
		$instance = wp_parse_args($instance, $defaults);
		extract($instance);
		
		?>

		<p class="description">
			<label for="<?php echo $this->get_field_id('title'); ?>">
				<?php 
					_e( 'Header', 'aqpb-l10n' ); 
					echo aq_field_input('title', $block_id, $title); 
				?>
				<small><?php _e(' Enter your welcome header title', 'aqpb-l10n'); ?></small>
			</label>
		</p>
		<p class="description">
			<label for="<?php echo $this->get_field_id('homeland_text'); ?>">
				<?php 
					_e( 'Content', 'aqpb-l10n' );
					echo aq_field_textarea('homeland_text', $block_id, $homeland_text); 
				?>
				<small><?php _e(' Enter your welcome content text', 'aqpb-l10n') ?></small>
			</label>
		</p>
		<p class="description half">
			<label for="<?php echo $this->get_field_id('homeland_button_text'); ?>">
				<?php 
					_e( 'Button Label', 'aqpb-l10n' );
					echo aq_field_input('homeland_button_text', $block_id, $homeland_button_text, $size = 'full'); 
				?>  
				<small><?php _e( ' Add label of your welcome button', 'aqpb-l10n' ); ?></small>
			</label>
		</p>
		<p class="description half last">
			<label for="<?php echo $this->get_field_id('homeland_button_link'); ?>">
				<?php 
					_e( 'Button Link', 'aqpb-l10n' );
					echo aq_field_input('homeland_button_link', $block_id, $homeland_button_link, $size = 'full'); 
				?>  
				<small><?php _e( ' Add link of your welcome button', 'aqpb-l10n' ); ?></small>
			</label>
		</p>
		<p class="description">
			<label for="<?php echo $this->get_field_id('homeland_background'); ?>">
				<?php 
					_e( 'Background', 'aqpb-l10n' );
					echo aq_field_upload('homeland_background', $block_id, $homeland_background, $media_type = 'image'); 
				?>  
			</label>
		</p>
		
		<?php
	}
	
	function block($instance) {
		extract($instance);

		if(!empty($homeland_background)) :
			?><style>.welcome-pb-block { background: url('<?php echo $homeland_background; ?>') !important; }</style><?php
		endif;

		?>
			<section class="welcome-pb-block">
				<div class="inside">
					<h2><?php echo $title; ?></h2>
					<label><?php echo wpautop(do_shortcode(htmlspecialchars_decode($homeland_text))); ?></label>
					<a href="<?php echo $homeland_button_link; ?>" class="view-property"><?php echo $homeland_button_text; ?></a>
				</div>
			</section>
		<?php

	}
	
}