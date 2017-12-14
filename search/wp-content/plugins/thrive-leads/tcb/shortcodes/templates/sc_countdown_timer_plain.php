<div class="thrv_wrapper thrv_countdown_timer tve_cd_timer_plain tve_clearfix init_done tve_red"
     data-date="<?php echo gmdate( 'Y-m-d', time() + 3600 * $timezone_offset + ( 3600 * 24 ) ) ?>"
     data-hour="<?php echo gmdate( 'H', time() + 3600 * $timezone_offset ) ?>"
     data-min="<?php echo gmdate( 'i', time() + 3600 * $timezone_offset ) ?>"
     data-timezone="<?php echo $tzd ?>">
	<div class="sc_timer_content tve_clearfix">
		<div class="tve_t_day tve_t_part">
			<div class="t-digits"></div>
			<div class="t-caption">Days</div>
		</div>
		<div class="tve_t_hour tve_t_part">
			<div class="t-digits"></div>
			<div class="t-caption">Hours</div>
		</div>
		<div class="tve_t_min tve_t_part">
			<div class="t-digits"></div>
			<div class="t-caption">Minutes</div>
		</div>
		<div class="tve_t_sec tve_t_part">
			<div class="t-digits"></div>
			<div class="t-caption">Seconds</div>
		</div>
		<div class="tve_t_text"></div>
	</div>
</div>