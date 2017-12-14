<?php
/**
 * Shortcode attributes
 * @var $atts
 * @var $content - shortcode content
 * Shortcode class
 * @var $this WPBakeryShortCode_VC_Cta
 */

$atts = vc_map_get_attributes( $this->getShortcode(), $atts );
$this->buildTemplate( $atts, $content );
$containerClass = 'vc_cta3-container ' . esc_attr( implode( ' ', $this->getTemplateVariable( 'container-class' ) ) );
$containerClass = trim( $containerClass );
?>
<section
	class="<?php echo $containerClass; ?>">
	<div class="vc_general <?php echo esc_attr( implode( ' ', $this->getTemplateVariable( 'css-class' ) ) ); ?>"<?php
	if ( $this->getTemplateVariable( 'inline-css' ) ) {
		echo ' style="' . esc_attr( implode( ' ', $this->getTemplateVariable( 'inline-css' ) ) ) . '"';
	}
	?>>
		<?php echo $this->getTemplateVariable( 'icons-top' ); ?>
		<?php echo $this->getTemplateVariable( 'icons-left' ); ?>
		<div class="vc_cta3_content-container">
			<?php echo $this->getTemplateVariable( 'actions-top' ); ?>
			<?php echo $this->getTemplateVariable( 'actions-left' ); ?>
			<div class="vc_cta3-content">
				<header class="vc_cta3-content-header">
					<?php echo $this->getTemplateVariable( 'heading1' ); ?>
					<?php echo $this->getTemplateVariable( 'heading2' ); ?>
				</header>
				<?php echo $this->getTemplateVariable( 'content' ); ?>
			</div>
			<?php echo $this->getTemplateVariable( 'actions-bottom' ); ?>
			<?php echo $this->getTemplateVariable( 'actions-right' ); ?>
		</div>
		<?php echo $this->getTemplateVariable( 'icons-bottom' ); ?>
		<?php echo $this->getTemplateVariable( 'icons-right' ); ?>
	</div>
</section>

