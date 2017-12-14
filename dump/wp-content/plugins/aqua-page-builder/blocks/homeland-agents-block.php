<?php
/** Agents block **/
class Homeland_Agents_Block extends AQ_Block {
	
	//set and create block
	function __construct() {
		$block_options = array(
			'name' => __('Agents', 'aqpb-l10n'),
			'size' => 'span4',
		);
		
		//create the block
		parent::__construct('homeland_agents_block', $block_options);
	}
	
	function form($instance) {
		
		$defaults = array(
			'homeland_limit' => '',
			'homeland_order' => '',
			'homeland_sort' => '',
		);
		
		$instance = wp_parse_args($instance, $defaults);
		extract($instance);

		$homeland_order_options = array(
			'DESC' => 'Descending',
			'ASC' => 'Ascending',
		);

		$homeland_sort_options = array(
			'ID' => 'ID',
			'display_name' => 'Display Name',
			'name' => 'Name',
			'login' => 'Login',
			'nicename' => 'Nicename',
			'email' => 'Email',
			'url' => 'Url',
			'registered' => 'Registered',
			'post_count' => 'Post Count',
			'meta_value' => 'Meta Value',
		);
		
		?>

		<p class="description">
			<label for="<?php echo $this->get_field_id('title'); ?>">
				<?php 
					_e( 'Header', 'aqpb-l10n' );
					echo aq_field_input('title', $block_id, $title); 
				?>
				<small><?php _e(' Enter your agents header title', 'aqpb-l10n'); ?></small>
			</label>
		</p>
		<p class="description">
			<label for="<?php echo $this->get_field_id('homeland_limit'); ?>">
				<?php 
					_e( 'Limit', 'aqpb-l10n' );
					echo aq_field_input('homeland_limit', $block_id, $homeland_limit); 
				?>
				<small><?php _e(' Enter your number of agents to be display', 'aqpb-l10n'); ?></small>
			</label>
		</p>
		<p class="description half">
			<label for="<?php echo $this->get_field_id('homeland_order'); ?>">
				<?php 
					_e( 'Order', 'aqpb-l10n' ); 
					echo aq_field_select('homeland_order', $block_id, $homeland_order_options, $homeland_order);
				?>
				<small><?php _e( 'Select your agents order type', 'aqpb-l10n' ); ?></small>
			</label>
		</p>
		<p class="description half last">
			<label for="<?php echo $this->get_field_id('homeland_sort'); ?>">
				<?php 
					_e( 'Sort', 'aqpb-l10n' );
					echo aq_field_select('homeland_sort', $block_id, $homeland_sort_options, $homeland_sort); 
				?>
				<small><?php _e( 'Select your sort by parameter for agents', 'aqpb-l10n' ); ?></small>
			</label>
		</p>
		
		<?php
	}
	
	function block($instance) {
		extract($instance);

		?>
		<div class="agent-block">
			<h3><span><?php echo $title; ?></span></h3>
			<ul>
				<?php
					$args = array( 
						'role' => 'contributor', 
						'order' => $homeland_order, 
						'orderby' => $homeland_sort, 
						'number' => $homeland_limit 
					);

				   $homeland_agents = new WP_User_Query( $args );

				   if (!empty( $homeland_agents->results )) :
						foreach ($homeland_agents->results as $homeland_user) :
							global $wpdb;
							$homeland_post_author = $homeland_user->ID;
							$homeland_count = (int) $wpdb->get_var( $wpdb->prepare( "SELECT COUNT(*) FROM $wpdb->posts WHERE post_type = 'homeland_properties' AND post_status = 'publish' AND post_author = %d", $homeland_post_author ) );
							?>
								<li class="clear">
									<a href="<?php echo esc_url( get_author_posts_url( $homeland_user->ID ) ); ?>">
										<?php echo get_avatar( $homeland_user->ID, 70 ); ?>
									</a>
									<h4><a href="<?php echo esc_url( get_author_posts_url( $homeland_user->ID ) ); ?>">
										<?php echo $homeland_user->display_name; ?></a></h4>
									<label>
										<i class="fa fa-home fa-lg"></i> <?php esc_attr( _e( 'Listed:', 'aqpb-l10n' ) ); ?>
										<span>
											<?php 
												echo intval($homeland_count); echo "&nbsp;"; esc_attr( _e( 'Properties', 'aqpb-l10n' ) ); 
											?>
										</span>
									</label>
								</li>	
							<?php
						endforeach;
					else : _e( 'No Agents found!', 'aqpb-l10n' );
					endif;
				?>
			</ul>
		</div><?php	
	}
	
}