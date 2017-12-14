<!-- - - - - - - - - - - - Slider for Portfolio Single - - - - - - - - - - - -->

<?php
	if (is_singular(get_post_type()) == 'portfolio') {
		if (class_exists('MAD_PORTFOLIO')) {
			$mad_portfolio = new MAD_PORTFOLIO();
			$mad_portfolio->fullwidthSlider();
		}
	}
?>

<!-- - - - - - - - - - - - / Slider for Portfolio Single - - - - - - - - - - - -->