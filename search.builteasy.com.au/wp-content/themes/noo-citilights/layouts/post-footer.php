<?php global $post;?>
<?php if(is_singular()):?>
	<?php if((noo_get_option('noo_blog_post_show_post_tag', true)) || (noo_get_option('noo_blog_post_author_bio', true))):?>
	<footer class="content-footer">
		<?php if((noo_get_option('noo_blog_post_show_post_tag', true)) && has_tag($post->ID)):?>
		<div class="content-tags">
			<?php echo get_the_tag_list()?>
		</div>
		<?php endif;?>
		<?php if(noo_get_option('noo_blog_post_author_bio', true)):?>
		<div id="author-bio">
			<div class="author-avatar">
				<?php echo get_avatar( get_the_author_meta( 'user_email' ),170); ?>
			</div>
			<div class="author-info">
			<span><?php _e('About Author', 'noo'); ?></span>
				<h4>
					<a title="<?php printf( __( 'Post by %s','noo'), get_the_author() ); ?>" href="<?php echo esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ); ?>" rel="author">
						<?php echo get_the_author() ?>
					</a>
				</h4>
				<p>
					<?php the_author_meta( 'description' ) ?>
				</p>
			</div>
		</div>
		<?php endif;?>
	</footer>
	<?php endif;?>
<?php else:?>
	<?php if((noo_get_option('noo_blog_show_post_tag', true)) && has_tag($post->ID)):?>
	<footer class="content-footer">
		<div class="content-tags">
			<?php echo get_the_tag_list('','','',$post->ID)?>
		</div>
	</footer>
	<?php endif;?>
<?php endif;?>
