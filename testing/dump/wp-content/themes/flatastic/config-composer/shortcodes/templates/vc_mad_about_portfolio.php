<?php

class WPBakeryShortCode_VC_mad_about_portfolio extends WPBakeryShortCode {

	public $atts = array();

	protected function content($atts, $content = null) {

		$this->atts = shortcode_atts(array(
			'output_date'   => "no",
			'output_client' => "",
			'output_services' => "",
			'output_skills' => "no",
			'output_category' => "no",
			'output_tags' => "no",
			'display_share' => "no"
		), $atts, 'vc_mad_about_portfolio');

		return $this->html();
	}

	public function html() {

		global $post;

		$output_skills = $output_client = $output_services = $output_date = $output_category = $output_tags = $display_share = '';

		extract($this->atts);

		ob_start(); ?>

		<table class="about_project">

			<tbody>

			<?php if ($output_date == 'yes'): ?>
				<tr>
					<td><?php _e('Date:', MAD_BASE_TEXTDOMAIN) ?></td>
					<td><?php echo get_the_date(); ?></td>
				</tr>
			<?php endif; ?>

			<?php if (!empty($output_client)): ?>
				<tr>
					<td><?php _e('Client:', MAD_BASE_TEXTDOMAIN) ?></td>
					<td><?php echo esc_html($output_client); ?></td>
				</tr>
			<?php endif; ?>

			<?php if (!empty($output_services)): ?>
				<tr>
					<td><?php _e('Services:', MAD_BASE_TEXTDOMAIN) ?></td>
					<td><?php echo esc_html($output_services); ?></td>
				</tr>
			<?php endif; ?>

			<?php $skills = get_the_term_list($post->ID, 'skills', '', ', ',''); ?>
			<?php if ($output_skills == 'yes' && $skills != ''): ?>
				<tr>
					<td><?php _e('Skills:', MAD_BASE_TEXTDOMAIN) ?></td>
					<td><?php echo $skills; ?></td>
				</tr>
			<?php endif; ?>

			<?php $categories = get_the_term_list($post->ID, 'portfolio_categories', '', ', ',''); ?>
			<?php if ($output_category == 'yes' && $categories != ''): ?>
				<tr>
					<td><?php _e('Category:', MAD_BASE_TEXTDOMAIN) ?></td>
					<td><?php echo $categories; ?></td>
				</tr>
			<?php endif; ?>

			<?php $tags = get_the_tag_list('', ', ',''); ?>
			<?php if ($output_tags == 'yes' && $tags != ''): ?>
				<tr>
					<td><?php _e('Tags:', MAD_BASE_TEXTDOMAIN) ?></td>
					<td><?php echo $tags; ?></td>
				</tr>
			<?php endif; ?>

			</tbody>

		</table><!--/ .about_project-->

		<?php if ($display_share == 'yes'): ?>
			<?php mad_share_portfolio_this(); ?>
		<?php endif; ?>

		<?php return ob_get_clean();
	}

}