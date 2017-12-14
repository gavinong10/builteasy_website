<?php

class WPBakeryShortCode_VC_mad_team_members extends WPBakeryShortCode {

	public $atts = array();
	public $entries = '';

	protected function content($atts, $content = null) {

		$this->atts = shortcode_atts(array(
			'title' => '',
			'items' => 4,
			'categories' => array(),
			'orderby' => 'date',
			'order' => 'DESC'
		), $atts, 'vc_mad_team_members');

		$this->query_entries();
		$html = $this->html();

		return $html;
	}

	public function query_entries() {
		$params = $this->atts;

		$tax_query = array();

		if (!empty($params['categories'])) {
			$categories = explode(',', $params['categories']);
			$tax_query = array(
				'relation' => 'AND',
				array(
					'taxonomy' => 'team_category',
					'field' => 'id',
					'terms' => $categories
				)
			);
		}

		$query = array(
			'orderby' => $params['orderby'],
			'order' => $params['order'],
			'post_type' => 'team-members',
			'posts_per_page' => $params['items'],
			'tax_query' => $tax_query
		);

		$this->entries = new WP_Query($query);
	}

	protected function entry_title($title) {
		return "<h2 class='tt_uppercase m_bottom_25'>". $title ."</h2>";
	}

	public function html() {

		if (empty($this->entries) || empty($this->entries->posts)) return;

		extract($this->atts);

		ob_start() ?>

		<?php !empty($title) ? $this->entry_title($title) : "" ?>

		<div class="team-members clearfix">

			<?php foreach ($this->entries->posts as $entry): ?>

				<?php
					$id = $entry->ID;
					$name = get_the_title($id);
					$link  = get_permalink($id);
					$position = rwmb_meta('mad_tm_position', '', $id);

					$facebook = rwmb_meta('mad_tm_facebook', '', $id);
					$twitter = rwmb_meta('mad_tm_twitter', '', $id);
					$gplus = rwmb_meta('mad_tm_gplus', '', $id);
					$pinterest = rwmb_meta('mad_tm_pinterest', '', $id);
					$instagram = rwmb_meta('mad_tm_instagram', '', $id);

					$not_empty_social = $facebook . $twitter . $gplus . $pinterest . $instagram;
					$content = $entry->post_content;

					$alt = trim(strip_tags(get_post_meta($id, '_wp_attachment_image_alt', true)));
					if (empty($alt)) {
						$attachment = get_post($id);
						$alt = trim(strip_tags($attachment->post_title));
					}

					$thumbnail_atts = array(
						'title'	=> trim(strip_tags($entry->post_title)),
						'alt' => $alt
					);
				?>

				<div class="team-item">

					<?php if (has_post_thumbnail($id)): ?>

						<div class="team-photo">
							<?php echo MAD_HELPER::get_the_post_thumbnail($id, '200*200', $thumbnail_atts) ?>
						</div><!--/ .team-photo-->

						<div class="members-text-holder">

							<h4 class="entry-title">
								<a href="<?php echo esc_url($link); ?>"><?php echo esc_html($name); ?></a>
							</h4>
							<span class="team-member-position"><?php echo esc_html($position) ?></span>

							<p><?php echo $content; ?></p>

						</div><!--/ .members-text-holder-->

						<?php if ($not_empty_social != ''): ?>

							<ul class="social-links">

								<?php if (!empty($facebook)): ?>
									<li class="facebook">
										<a target="_blank" href="<?php echo esc_url($facebook) ?>"></a>
									</li>
								<?php endif; ?>

								<?php if (!empty($twitter)): ?>
									<li class="twitter">
										<a target="_blank" href="<?php echo esc_url($twitter) ?>"></a>
									</li>
								<?php endif; ?>

								<?php if (!empty($gplus)): ?>
									<li class="gplus">
										<a target="_blank" href="<?php echo esc_url($gplus) ?>"></a>
									</li>
								<?php endif; ?>

								<?php if (!empty($pinterest)): ?>
									<li class="pinterest">
										<a target="_blank" href="<?php echo esc_url($pinterest) ?>"></a>
									</li>
								<?php endif; ?>

								<?php if (!empty($instagram)): ?>
									<li class="instagram">
										<a target="_blank" href="<?php echo esc_url($instagram) ?>"></a>
									</li>
								<?php endif; ?>

							</ul><!--/ .social-links-->

						<?php endif; ?>

					<?php endif; ?>

				</div><!--/ .team-item-->

			<?php endforeach; ?>

		</div><!--/ .team-members-->

		<?php return ob_get_clean();
	}

}