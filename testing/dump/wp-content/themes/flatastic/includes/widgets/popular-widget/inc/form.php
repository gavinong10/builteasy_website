<?php extract(wp_parse_args($instance, $this->defaults)); ?>

	<p>
		<label for="<?php $this->field_id('title'); ?>"><?php _e('Title', MAD_BASE_TEXTDOMAIN); ?>
			<input class="widefat" id="<?php $this->field_id('title'); ?>" name="<?php $this->field_name('title') ?>" type="text" value="<?php echo esc_attr( $title ) ?>" />
		</label>
	</p>

	<div class="popw-tabs">

		<h4 class="popw-collapse"><?php _e( 'Type:', MAD_BASE_TEXTDOMAIN); ?><span></span></h4>
		<div style="display: block" class="popw-inner sort-type">
			<p>
				<label for="<?php $this->field_id('type-popular'); ?>">
					<input id="<?php $this->field_id('type-popular'); ?>" name="<?php $this->field_name('type'); ?>" value="popular" type="radio" <?php checked( $type, 'popular' ) ?> />
					<abbr title="Display the most viewed posts"><?php _e('Popular', MAD_BASE_TEXTDOMAIN)?></abbr>
				</label> <br /><small><?php _e( 'Display the most viewed posts', MAD_BASE_TEXTDOMAIN) ?></small><br />

				<label for="<?php $this->field_id( 'type-latest' )?>">
					<input id="<?php $this->field_id( 'type-latest' )?>" name="<?php $this->field_name('type'); ?>" value="latest" type="radio" <?php checked( $type, 'latest' ) ?> />
					<abbr title="Display the latest posts"><?php _e( 'Latest', MAD_BASE_TEXTDOMAIN )?></abbr>
				</label><br /><small><?php _e( 'Display the latest posts', MAD_BASE_TEXTDOMAIN ) ?></small>
			</p>
		</div>

	</div>

	<div class="popw-tabs <?php echo ($type == 'latest') ? 'disabled' : '' ?>" data-tab="calculate">
		<h4 class="popw-collapse"><?php _e('Calculate:', MAD_BASE_TEXTDOMAIN)?><span></span></h4>
		<div class="popw-inner">
			<p>
				<label for="<?php $this->field_id('calculate-views'); ?>">
					<input id="<?php $this->field_id('calculate-views'); ?>" name="<?php $this->field_name('calculate'); ?>" value="views" type="radio" <?php checked($calculate, 'views') ?> />
					<abbr title="Every time the user views the page"><?php _e('Views', MAD_BASE_TEXTDOMAIN); ?></abbr>
				</label><br /><small><?php _e('Every time user views the post.', MAD_BASE_TEXTDOMAIN); ?></small><br />

				<label for="<?php $this->field_id('calculate-visits'); ?>">
					<input id="<?php $this->field_id('calculate-visits'); ?>" name="<?php $this->field_name('calculate'); ?>" value="visits" type="radio" <?php checked($calculate, 'visits') ?> />
					<abbr title="Every time the user visits the site"><?php _e('Visits', MAD_BASE_TEXTDOMAIN); ?></abbr>
				</label><br /><small><?php _e('Calculate only once per visit.', MAD_BASE_TEXTDOMAIN); ?></small>
			</p>
		</div>
	</div>

	<div class="popw-tabs">
		<h4 class="popw-collapse"><?php _e('Display:', MAD_BASE_TEXTDOMAIN); ?><span></span></h4>
		<div class="popw-inner">
			<p>
				<label for="<?php $this->field_id('counter'); ?>">
					<input id="<?php $this->field_id('counter'); ?>" name="<?php $this->field_name('counter'); ?>" type="checkbox" <?php checked('on', $counter) ?> />
					<?php _e('Display count', MAD_BASE_TEXTDOMAIN); ?>
				</label><br />

				<label for="<?php $this->field_id('thumb'); ?>">
					<input id="<?php $this->field_id('thumb'); ?>" name="<?php $this->field_name('thumb'); ?>" type="checkbox" <?php checked('on', $thumb); ?> />
					<?php _e('Display thumbnail', MAD_BASE_TEXTDOMAIN); ?>
				</label><br />

				<label for="<?php $this->field_id('excerpt'); ?>">
					<input id="<?php $this->field_id('excerpt'); ?>" name="<?php $this->field_name('excerpt'); ?>" type="checkbox" <?php checked('on', $excerpt); ?> />
					<?php _e('Display post excerpt', MAD_BASE_TEXTDOMAIN); ?>
				</label>
			</p>
			<p>
				<label for="<?php $this->field_id('limit'); ?>"><?php _e('Show how many posts?', MAD_BASE_TEXTDOMAIN); ?>
					<input id="<?php $this->field_id('limit'); ?>" name="<?php $this->field_name('limit'); ?>" size="5" type="text" value="<?php echo esc_attr($limit); ?>" />
				</label>
			</p>
			<p>
				<label for="<?php $this->field_id('excerptlength'); ?>"><?php _e('Excerpt length', MAD_BASE_TEXTDOMAIN); ?>
					<input id="<?php $this->field_id('excerptlength'); ?>" name="<?php $this->field_name('excerptlength'); ?>" size="5" type="text"
						   value="<?php echo esc_attr($excerptlength); ?>"/> <?php _e('Words', MAD_BASE_TEXTDOMAIN); ?>
				</label>
			</p>
		</div>

	</div>

	<?php do_action( 'pop_admin_form' ) ?>