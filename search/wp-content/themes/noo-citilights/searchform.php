<form method="GET" id="searchform" class="form-horizontal" action="<?php echo esc_url( home_url( '/' ) ); ?>" role="form">
<label for="s" class="sr-only"><?php __( 'Search', 'noo' ); ?></label>
	<input type="search" id="s" name="s" class="form-control" value="<?php echo get_search_query(); ?>" placeholder="<?php esc_attr_e( 'Search', 'noo' ); ?>" />
	<input type="submit" id="searchsubmit" class="hidden" value="<?php esc_attr_e( 'Search', 'noo' ); ?>" />
</form>