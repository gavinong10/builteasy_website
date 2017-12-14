<?php
if($style==='grid'): ?>
	<div class="recent-properties">
		<?php NooProperty::display_content($q,$title,$show_control,'grid',$show_pagination); ?>
	</div>
<?php elseif ($style === 'list') : ?>
	<div class="recent-properties">
		<?php NooProperty::display_content($q,$title,$show_control,'list',$show_pagination); ?>
	</div>
<?php elseif($style === 'slider'): ?>
	<?php if($q->have_posts()):?>
		<div class="recent-properties recent-properties-slider" data-auto="<?php echo $show_auto_play; ?>" data-slider-time="<?php echo $slider_time; ?>" data-slider-speed="<?php echo $slider_speed; ?>">
			<?php if(!empty($title)):?>
				<div class="recent-properties-title"><h3><?php echo $title?></h3></div>
			<?php endif;?>
			<?php 
				$i = 0;
				$visible = 4;
				$r = 0;
			?>
			<div class="recent-properties-content">
				<div class="caroufredsel-wrap">
				<ul>
				<?php while ($q->have_posts()): $q->the_post();global $post;?>
					<?php if ($r++ % $visible == 0):?>
					<li>
					<?php endif;?>
					<?php if ($i++ % 2 == 0):?>
					<div class="property-row">
					<?php endif;?>
					<article <?php post_class(); ?>>
						<div class="property-featured">
					        <a class="content-thumb" href="<?php the_permalink() ?>">
								<?php echo get_the_post_thumbnail(get_the_ID(),'property-thumb') ?>
							</a>
							<span class="property-category"><?php echo get_the_term_list(get_the_ID(), 'property_category')?></span>
							<?php echo self::get_property_summary( array('container_class'=>'property-detail')); ?>
					    </div>
					    <div class="property-wrap">
							<h2 class="property-title">
								<a href="<?php the_permalink(); ?>" title="<?php the_title(); ?>"><?php the_title(); ?></a>
							</h2>
							<div class="property-excerpt">
								<?php if($excerpt = get_the_excerpt()):?>
									<?php 
									$num_word = 15;
									$excerpt = strip_shortcodes($excerpt);
									echo '<p>' . wp_trim_words($excerpt,$num_word,'...') . '</p>';
									?>
								<?php endif;?>
							</div>
						</div>
						<div class="property-summary">
							<div class="property-info">
								<div class="property-price">
									<span><?php echo self::get_price_html(get_the_ID(),true)?></span>
								</div>
								<div class="property-action">
									<a href="<?php the_permalink()?>"><?php echo __('More Details','noo')?></a>
								</div>
							</div>
						</div>
					</article>
					<?php if ($i % 2 == 0 || $i == $q->post_count):?>
					</div>
					<?php endif;?>
					<?php if ($r % $visible == 0 || $r == $q->post_count):?>
					</li>
					<?php endif;?>
				<?php endwhile;?>
				</ul>
				</div>
				<a class="caroufredsel-prev" href="#"></a>
		    	<a class="caroufredsel-next" href="#"></a>
			</div>
		</div>
	<?php endif;?>
	<?php
	wp_reset_query();
elseif ($style==='featured'): ?>
	<?php if($q->have_posts()):?>
		<div class="recent-properties recent-properties-featured" data-auto="<?php echo $show_auto_play; ?>" data-slider-time="<?php echo $slider_time; ?>" data-slider-speed="<?php echo $slider_speed; ?>">
			<?php if(!empty($title)):?>
			<div class="recent-properties-title"><h3><?php echo $title?></h3></div>
			<?php endif;?>
			<div class="recent-properties-content">
				<div class="caroufredsel-wrap">
				<ul>
				<?php while ($q->have_posts()): $q->the_post();global $post;?>
					<li>
					<article <?php post_class(); ?>>
						<div class="property-featured">
					        <a class="content-thumb" href="<?php the_permalink() ?>">
								<?php echo get_the_post_thumbnail(get_the_ID(),'property-image') ?>
							</a>
							<span class="property-category"><?php echo get_the_term_list(get_the_ID(), 'property_category')?></span>
					    </div>
					    <div class="property-wrap">
							<h2 class="property-title">
								<a href="<?php the_permalink(); ?>" title="<?php the_title(); ?>"><?php the_title(); ?></a>
							</h2>
							<div class="property-excerpt">
								<?php if($excerpt = get_the_excerpt()):?>
									<?php 
									$num_word = 30;
									$excerpt = strip_shortcodes($excerpt);
									echo '<p>' . wp_trim_words($excerpt,$num_word,'...') . '</p>';
									?>
								<?php endif;?>
							</div>
							<div class="property-summary">
								<?php echo self::get_property_summary( array('container_class'=>'property-detail')); ?>
								<div class="property-info">
									<div class="property-price">
										<span><?php echo self::get_price_html(get_the_ID(),true)?></span>
									</div>
									<div class="property-action">
										<a href="<?php the_permalink()?>"><?php echo __('More Details','noo')?> <i class="fa fa-arrow-circle-o-right"></i></a>
									</div>
								</div>
							</div>
						</div>
					</article>
					</li>
				<?php endwhile;?>
				</ul>
				</div>
				<a class="caroufredsel-prev" href="#"></a>
		    	<a class="caroufredsel-next" href="#"></a>
			</div>
		</div>
	<?php endif;?>
	<?php
	wp_reset_query();
endif;