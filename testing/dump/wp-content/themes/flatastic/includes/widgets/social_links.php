
<?php echo $before_widget; ?>

	<?php if ($instance['title'] != ''): ?>
		<?php echo $before_title; ?><?php echo esc_html($instance['title']); ?><?php echo $after_title ?>
	<?php endif; ?>

	<div class="social_icons_holder">

		<ul class="social_icons clearfix">

			<?php if ($instance['facebook_links'] != '') : ?>
				<li class="facebook">
					<span class="tool-tip">Facebook</span>
					<a target="_blank" href="<?php echo esc_url($instance['facebook_links']); ?>">
						<i class="fa fa-facebook"></i>
					</a>
				</li>
			<?php endif; ?>

			<?php if ($instance['twitter_links'] != '') : ?>
				<li class="twitter">
					<span class="tool-tip">Twitter</span>
					<a target="_blank" href="<?php echo esc_url($instance['twitter_links']); ?>">
						<i class="fa fa-twitter"></i>
					</a>
				</li>
			<?php endif; ?>

			<?php if ($instance['gplus_links'] != '') : ?>
				<li class="google_plus">
					<span class="tool-tip">Google Plus</span>
					<a target="_blank" href="<?php echo esc_url($instance['gplus_links']); ?>">
						<i class="fa fa-google-plus"></i>
					</a>
				</li>
			<?php endif; ?>

			<?php if ($instance['rss_links'] == 'true') : ?>
				<li class="rss">
					<span class="tool-tip">Rss</span>
					<a href="<?php bloginfo('rss2_url'); ?>">
						<i class="fa fa-rss"></i>
					</a>
				</li>
			<?php endif; ?>

			<?php if (@$instance['pinterest_links'] != '') : ?>
				<li class="pinterest">
					<span class="tool-tip">Pinterest</span>
					<a target="_blank" href="<?php echo esc_url($instance['pinterest_links']); ?>">
						<i class="fa fa-pinterest"></i>
					</a>
				</li>
			<?php endif; ?>

			<?php if ($instance['instagram_links'] != '') : ?>
				<li class="instagram">
					<span class="tool-tip">Instagram</span>
					<a target="_blank" href="<?php echo esc_url($instance['instagram_links']); ?>">
						<i class="fa fa-instagram"></i>
					</a>
				</li>
			<?php endif; ?>

			<?php if (@$instance['linkedin_links'] != '') : ?>
				<li class="linkedin">
					<span class="tool-tip">LinkedIn</span>
					<a target="_blank" href="<?php echo esc_url($instance['linkedin_links']); ?>">
						<i class="fa fa-linkedin"></i>
					</a>
				</li>
			<?php endif; ?>

			<?php if ($instance['vimeo_links'] != '') : ?>
				<li class="vimeo">
					<span class="tool-tip">Vimeo</span>
					<a target="_blank" href="<?php echo esc_url($instance['vimeo_links']); ?>">
						<i class="fa fa-vimeo-square"></i>
					</a>
				</li>
			<?php endif; ?>

			<?php if ($instance['youtube_links'] != '') : ?>
				<li class="youtube">
					<span class="tool-tip">Youtube</span>
					<a target="_blank" href="<?php echo esc_url($instance['youtube_links']); ?>">
						<i class="fa fa-youtube-play"></i>
					</a>
				</li>
			<?php endif; ?>

			<?php if ($instance['flickr_links'] != '') : ?>
				<li class="flickr">
					<span class="tool-tip">Flickr</span>
					<a target="_blank" href="<?php echo esc_url($instance['flickr_links']); ?>">
						<i class="fa fa-flickr"></i>
					</a>
				</li>
			<?php endif; ?>

			<?php if (@$instance['vk_links'] != '') : ?>
				<li class="vk">
					<span class="tool-tip">Vkontakte</span>
					<a target="_blank" href="<?php echo esc_url($instance['vk_links']); ?>">
						<i class="fa fa-vk"></i>
					</a>
				</li>
			<?php endif; ?>

			<?php if ($instance['contact_us'] != '') : ?>
				<li class="envelope">
					<span class="tool-tip">Contact Us</span>
					<a target="_blank" href="mailto:<?php echo esc_attr($instance['contact_us']); ?>">
						<i class="fa fa-envelope-o"></i>
					</a>
				</li>
			<?php endif; ?>

		</ul><!--/ .social-icons-->

	</div><!--/ .social_icons_holder-->

<?php echo $after_widget; ?>