	<footer>	
		<?php
			$homeland_hide_widgets = esc_attr( get_option( 'homeland_hide_widgets' ) );
			$homeland_copyright_text = stripslashes( esc_attr( get_option( 'homeland_copyright_text' ) ) );
			$homeland_footer_layout = esc_attr( get_option( 'homeland_footer_layout' ) );

			if(empty($homeland_hide_widgets)) :
				?>
				<!--FOOTER WIDGETS-->
				<section class="footer-widgets">
					<div class="inside clear">
						<div class="widget-column">
							<?php
								if ( is_active_sidebar( 'homeland_footer_one' ) ) : dynamic_sidebar( 'homeland_footer_one' );
								else : _e( 'This is a widget area, so please add widgets here...', 'codeex_theme_name' );
								endif;
							?>
						</div>
						<div class="widget-column">
							<?php
								if ( is_active_sidebar( 'homeland_footer_two' ) ) : dynamic_sidebar( 'homeland_footer_two' );
								else : _e( 'This is a widget area, so please add widgets here...', 'codeex_theme_name' );
								endif;
							?>
						</div>
						<div class="widget-column">
							<?php
								if ( is_active_sidebar( 'homeland_footer_three' ) ) : dynamic_sidebar( 'homeland_footer_three' );
								else : _e( 'This is a widget area, so please add widgets here...', 'codeex_theme_name' );
								endif;
							?>
						</div>
						<div class="widget-column last">
							<?php
								if ( is_active_sidebar( 'homeland_footer_four' ) ) : dynamic_sidebar( 'homeland_footer_four' );
								else : _e( 'This is a widget area, so please add widgets here...', 'codeex_theme_name' );
								endif;
							?>
						</div>
					</div>
				</section>
				<?php						
			endif;


			if($homeland_footer_layout == "Layout 2") :
				$homeland_footer_layout_class = "footer-main footer-layout-two";
			elseif($homeland_footer_layout == "Layout 3") :
				$homeland_footer_layout_class = "footer-main footer-layout-three";
			elseif($homeland_footer_layout == "Layout 4") :
				$homeland_footer_layout_class = "footer-main footer-layout-four";
			elseif($homeland_footer_layout == "Layout 5") :
				$homeland_footer_layout_class = "footer-main footer-layout-five";
			elseif($homeland_footer_layout == "Layout 6") :
				$homeland_footer_layout_class = "footer-main footer-layout-six";
			else :
				$homeland_footer_layout_class = "footer-main";
			endif;
		?>
		<section class="<?php echo $homeland_footer_layout_class; ?>">
			<div class="inside clear">
				<div class="footer-inside clear">
					<label class="copyright">
						<?php 
							echo "&copy;&nbsp;" . date('Y') . "&nbsp;"; ?><a href="<?php echo esc_url( home_url() ); ?>"><?php esc_attr( bloginfo('name') ); ?></a><?php
							echo "&nbsp;&dash;&nbsp;"; echo $homeland_copyright_text; echo "&nbsp;"; 
						?>
					</label>
					<?php
						if($homeland_footer_layout == "Layout 2" || $homeland_footer_layout == "Layout 3" || $homeland_footer_layout == "Layout 4" || $homeland_footer_layout == "Layout 5") : 
							wp_nav_menu( array( 
								'theme_location' => 'footer-menu',
								'container_class' => 'footer-menu', 
								'fallback_cb' => 'homeland_footer_menu_fallback', 
								'container_id' => '', 
								'menu_id' => '', 
								'menu_class' => 'clear' 
							) );
						elseif($homeland_footer_layout == "Layout 6") :
							homeland_social_share_header();
						endif;
					?>	
					<a href="#" id="toTop"><i class="fa fa-angle-up"></i></a>
				</div>
			</div>
		</section>			
	</footer>

</div>

<?php wp_footer(); ?>

</body>
</html>