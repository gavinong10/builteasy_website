<h3><?php echo __( "Your Custom Icons", TVE_DASH_TRANSLATE_DOMAIN ) ?></h3>
<p><?php echo __( "These icons are available for use on your site:", TVE_DASH_TRANSLATE_DOMAIN ) ?></p>
<div class="icomoon-admin-icons">
	<?php foreach ( $this->icons as $class ) : ?>
		<span class="icomoon-icon" title="<?php echo array_key_exists($class, $this->variations) ? $this->variations[$class] : $class ?>">
            <span class="<?php echo $class ?>"></span>
        </span>
	<?php endforeach ?>
</div>