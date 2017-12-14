<?php
/** Video block **/
class Homeland_Video_Block extends AQ_Block {
	
	//set and create block
	function __construct() {
		$block_options = array(
			'name' => __('Video', 'aqpb-l10n'),
			'size' => 'span12',
		);
		
		//create the block
		parent::__construct('homeland_video_block', $block_options);
	}
	
	function form($instance) {
		
		$defaults = array(
			'homeland_video' => '',
		);
		$instance = wp_parse_args($instance, $defaults);
		extract($instance);
		
		?>
		
		<p class="description">
			<label for="<?php echo $this->get_field_id('homeland_video'); ?>">
				<?php 
					_e( 'Video Url', 'aqpb-l10n' ); 
					echo aq_field_input('homeland_video', $block_id, $homeland_video); 
				?>
				<small><?php _e('Add your video embedded code here..', 'aqpb-l10n') ?></small>
			</label>
		</p>
		
		<?php
	}
	
	function block($instance) {
		extract($instance);

		?>
			<section class="home-video-block">
				<iframe width="770" height="433" src="<?php echo $homeland_video; ?>" frameborder="0" allowfullscreen class="sframe"></iframe>
			</section>
		<?php
		
	}
	
}