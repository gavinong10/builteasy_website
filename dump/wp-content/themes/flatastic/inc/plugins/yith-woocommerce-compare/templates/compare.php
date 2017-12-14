<?php
/**
 * Woocommerce Compare page
 *
 * @author Your Inspiration Themes
 * @package YITH Woocommerce Compare
 * @version 1.1.4
 */

// remove the style of woocommerce
if( defined('WOOCOMMERCE_USE_CSS') && WOOCOMMERCE_USE_CSS ) wp_dequeue_style('woocommerce_frontend_styles');

$is_iframe = (bool)( isset( $_REQUEST['iframe'] ) && $_REQUEST['iframe'] );

wp_enqueue_script( 'jquery-fixedheadertable', YITH_WOOCOMPARE_URL . 'assets/js/jquery.dataTables.min.js', array('jquery'), '1.3', true );
wp_enqueue_script( 'jquery-fixedcolumns', YITH_WOOCOMPARE_URL . 'assets/js/FixedColumns.min.js', array('jquery', 'jquery-fixedheadertable'), '1.3', true );

$widths = array();
foreach( $products as $product ) $widths[] = '{ "sWidth": "205px", resizeable:true }';

/** FIX WOO 2.1 */
$wc_get_template = function_exists('wc_get_template') ? 'wc_get_template' : 'woocommerce_get_template';

$table_text = get_option( 'yith_woocompare_table_text' );
$localized_table_text = function_exists( 'icl_translate' ) ? icl_translate( 'Plugins', 'plugin_yit_compare_table_text', $table_text ) : $table_text;

?><!DOCTYPE html>
<!--[if IE 8]>
<html id="ie8" class="ie"<?php language_attributes() ?>>
<![endif]-->
<!--[if IE 9]>
<html id="ie9" class="ie"<?php language_attributes() ?>>
<![endif]-->
<!--[if gt IE 9]>
<html class="ie"<?php language_attributes() ?>>
<![endif]-->
<!--[if !IE]>
<html <?php language_attributes() ?>>
<![endif]-->

<!-- START HEAD -->
<head>
    <meta charset="<?php bloginfo( 'charset' ); ?>" />
    <meta name="viewport" content="width=device-width" />
    <title><?php _e( 'Product Comparison', MAD_BASE_TEXTDOMAIN ) ?></title>
    <link rel="profile" href="http://gmpg.org/xfn/11" />

    <link rel="stylesheet" href="http://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700,800" />
    <link rel="stylesheet" href="<?php echo $this->stylesheet_url() ?>" type="text/css" />

	<?php if (is_rtl()): ?>
		<link rel="stylesheet" href="<?php echo YITH_WOOCOMPARE_URL ?>assets/css/rtl.css" type="text/css" />
	<?php endif; ?>

    <link rel="stylesheet" href="<?php echo YITH_WOOCOMPARE_URL ?>assets/css/colorbox.css"/>
    <link rel="stylesheet" href="<?php echo YITH_WOOCOMPARE_URL ?>assets/css/jquery.dataTables.css"/>

    <?php wp_head() ?>

    <style type="text/css">
        body.loading {
            background: url("<?php echo YITH_WOOCOMPARE_URL ?>assets/images/colorbox/loading.gif") no-repeat scroll center center transparent;
        }
    </style>

</head>
<!-- END HEAD -->

<?php global $product; ?>

<!-- START BODY -->
<body <?php body_class('woocommerce') ?>>

<h2>
    <?php echo $localized_table_text ?>
    <?php if ( ! $is_iframe ) : ?><a class="close" href="#"><?php _e( 'Close window [X]', MAD_BASE_TEXTDOMAIN ) ?></a><?php endif; ?>
</h2>

<table class="compare-list" cellpadding="0" cellspacing="0"<?php if ( empty( $products ) ) echo ' style="width:100%"' ?>>
    <thead>
		<tr>
			<th>&nbsp;</th>
			<?php foreach( $products as $i => $product ) : ?>
				<td></td>
			<?php endforeach; ?>
		</tr>
    </thead>
    <tfoot>
		<tr>
			<th>&nbsp;</th>
			<?php foreach( $products as $i => $product ) : ?>
				<td></td>
			<?php endforeach; ?>
		</tr>
    </tfoot>

    <tbody>

    <?php if ( empty( $products ) ) : ?>

        <tr class="no-products">
            <td><?php _e( 'No products added in the compare table.', MAD_BASE_TEXTDOMAIN ) ?></td>
        </tr>

    <?php else : ?>

        <?php foreach ( $fields as $field => $name ) : ?>

            <tr class="<?php echo $field ?>">

                <th><?php echo $name ?></th>

                <?php foreach( $products as $i => $product ) : $product_class = ( $i % 2 == 0 ? 'odd' : 'even' ) . ' product_' . $product->id ?>
                    <td class="<?php echo $product_class; ?>"><?php

                        switch( $field ) {

                            case 'image':
                                echo '<div class="image-wrap">' . wp_get_attachment_image( $product->fields[$field], array(110, 110) ) . '</div>';
                                break;

							case 'attributes':

								$attributes = $product->fields[$field];

								?>

								<?php if (!empty($attributes)): ?>

								<?php ob_start(); ?>

								<ul class="attr-list">

									<?php foreach ($attributes as $key => $value): ?>
										<li>
											<?php if (!empty($key)): ?>
												<span><?php echo ucfirst(substr($key, 3)); ?>:</span>
											<?php endif; ?>

											<?php if ($value['name'] !== ''): ?>
												<span>
													<?php $attribute = $product->get_attribute($value['name']); ?>

													<?php if (strpos($attribute, ",") !== false): ?>
														<?php $values = explode(',', $attribute); ?>
														<?php echo implode(', ', $values); ?>
													<?php else: ?>
														<?php echo $attribute; ?>
													<?php endif; ?>

												</span>
											<?php endif; ?>

										</li>
									<?php endforeach; ?>

								</ul><!--/ .attr-list-->

								<?php echo ob_get_clean();

							endif;

								break;
                            case 'actions':
                                $wc_get_template( 'loop/add-to-cart.php' ); ?>
									<div class="clear"></div>
									<a class="remove" href="<?php echo add_query_arg( 'redirect', 'view', $this->remove_product_url( $product->id ) ) ?>" data-product_id="<?php echo $product->id; ?>"><?php _e( 'Remove', MAD_BASE_TEXTDOMAIN ) ?></a>
								<?php
                                break;

                            default:
                                echo empty( $product->fields[$field] ) ? '&nbsp;' : $product->fields[$field];
                                break;
                        }

                        ?>
                    </td>
                <?php endforeach ?>

            </tr>

        <?php endforeach; ?>

    <?php endif; ?>

    </tbody>
</table>

<?php if( wp_script_is( 'responsive-theme', 'enqueued' ) ) wp_dequeue_script( 'responsive-theme' ) ?>

<?php do_action('wp_print_footer_scripts'); ?>

<script type="text/javascript">

    jQuery(document).ready(function ($) {

        <?php if ( $is_iframe ) : ?>
			$('a').attr('target', '_parent');
		<?php endif; ?>

        var oTable;

        $('body').on( 'yith_woocompare_render_table', function () {
            if( $( window ).width() > 767 ) {
                oTable = $('table.compare-list').dataTable( {
                    "sScrollX": "100%",
                    //"sScrollXInner": "150%",
                    "bScrollInfinite": true,
                    "bScrollCollapse": true,
                    "bPaginate": false,
                    "bSort": false,
                    "bInfo": false,
                    "bFilter": false,
                    "bAutoWidth": false
                } );

                new FixedColumns( oTable );

                $('<table class="compare-list" />').insertAfter( $('h2') ).hide();
            }
        }).trigger('yith_woocompare_render_table');

        // add to cart
        var button_clicked;
        $(document).on('click', 'a.add_to_cart_button', function(){
            button_clicked = $(this);
            button_clicked.block({
				message: null, overlayCSS: {background: '#fff url(' + woocommerce_params.ajax_loader_url + ') no-repeat center',
					backgroundSize: '16px 16px', opacity: 0.6}
			});
        });

        // remove add to cart button after added
        $('body').on('added_to_cart', function () {

            button_clicked.hide();

            <?php if ( $is_iframe ) : ?>

				$('a').attr('target', '_parent');

				// Replace fragments
				if ( fragments ) {
					$.each(fragments, function(key, value) {console.log( key, window.parent.document );
						$(key, window.parent.document).replaceWith(value);
					});
				}
            <?php endif; ?>
        });

        // close window
        $(document).on( 'click', 'a.close', function (e) {
            e.preventDefault();
            window.close();
        });

        $(window).on( 'yith_woocompare_product_removed', function () {
            if( $( window ).width() > 767 ) {
                oTable.fnDestroy(true);
            }
            $('body').trigger('yith_woocompare_render_table');
        });

    });

</script>

</body>
</html>