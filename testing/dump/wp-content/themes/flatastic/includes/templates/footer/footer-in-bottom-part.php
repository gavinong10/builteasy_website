
<?php $is_align_copyright = mad_custom_get_option('copyright_center');

if ($is_align_copyright): ?>

	<div class="col-sm-12">

		<p class="copyright align-center">
			<?php echo html_entity_decode(mad_custom_get_option('copyright'), ENT_QUOTES, get_option('blog_charset')); ?>
		</p>

	</div>

<?php else: ?>

	<div class="col-sm-6">

		<p class="copyright">
			<?php echo html_entity_decode(mad_custom_get_option('copyright'), ENT_QUOTES, get_option('blog_charset')); ?>
		</p>

	</div>

	<div class="col-sm-6">

		<ul class="payment-images">
			<?php if (mad_custom_get_option('payment_1') != ''): ?>
				<li>
					<img src="<?php echo mad_custom_get_option('payment_1') ?>" alt=""/>
				</li>
			<?php endif; ?>
			<?php if (mad_custom_get_option('payment_2') != ''): ?>
				<li>
					<img src="<?php echo mad_custom_get_option('payment_2') ?>" alt=""/>
				</li>
			<?php endif; ?>
			<?php if (mad_custom_get_option('payment_3') != ''): ?>
				<li>
					<img src="<?php echo mad_custom_get_option('payment_3') ?>" alt=""/>
				</li>
			<?php endif; ?>
			<?php if (mad_custom_get_option('payment_4') != ''): ?>
				<li>
					<img src="<?php echo mad_custom_get_option('payment_4') ?>" alt=""/>
				</li>
			<?php endif; ?>
			<?php if (mad_custom_get_option('payment_5') != ''): ?>
				<li>
					<img src="<?php echo mad_custom_get_option('payment_5') ?>" alt=""/>
				</li>
			<?php endif; ?>
		</ul><!--/ .payment-images-->

	</div>

<?php endif; ?>
