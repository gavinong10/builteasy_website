<?php
/** A simple text block **/
class Homeland_Text_Block extends AQ_Block {
	
	//set and create block
	function __construct() {
		$block_options = array(
			'name' => __('Text', 'aqpb-l10n'),
			'size' => 'span12',
		);
		
		//create the block
		parent::__construct('homeland_text_block', $block_options);
	}
	
	function form($instance) {
		
		$defaults = array(
			'homeland_text'   => '',
		);
		$instance = wp_parse_args($instance, $defaults);
		extract($instance);
		
		?>
		
		<p class="description">
			<label for="<?php echo $this->get_field_id('homeland_text'); ?>">
				<?php 
					_e( 'Content', 'aqpb-l10n' ); 
					echo aq_field_textarea('homeland_text', $block_id, $homeland_text, $size = 'full'); 
				?>
				<small><?php _e('Add your content text here', 'aqpb-l10n'); ?></small>
			</label>
		</p>
		
		<?php
	}
	
	function block($instance) {
		extract($instance);

		?>
			<div class="welcome-block-top">
				<div class="inside">
					<label><?php echo wpautop(do_shortcode(htmlspecialchars_decode($homeland_text))); ?></label>
				</div>
			</div>
		<?php
		
	}
	
}
