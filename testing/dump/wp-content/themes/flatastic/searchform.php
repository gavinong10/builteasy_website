<form method="get" id="searchform" action="<?php echo esc_url( home_url( '/' ) ); ?>">
	<p>
		<label for="s"><?php esc_html_e('Search for:', MAD_BASE_TEXTDOMAIN); ?></label>
		<input type="text" autocomplete="off" name="s" id="s" placeholder="<?php esc_attr_e( 'Type text and hit enter', MAD_BASE_TEXTDOMAIN ) ?>"  value="<?php echo(isset($_GET['s']) ? $_GET['s'] : ''); ?>" />
		<button type="submit" class="submit-search" id="searchsubmit"><?php esc_attr_e( 'Search', MAD_BASE_TEXTDOMAIN ); ?></button>
	</p>
</form>