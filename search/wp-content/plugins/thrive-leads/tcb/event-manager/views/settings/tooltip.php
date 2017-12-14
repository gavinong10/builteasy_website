<?php $animation_classes            = ''; /* render specific settings for Thrive Tooltip actions */
if(isset($this->config['event_tooltip_text'])){
	$this->config['event_tooltip_text'] = stripslashes( $this->config['event_tooltip_text'] );
}
if ( $this->success_message ) : ?>
	<br>
	<div class="tve_message tve_success" id="tve_landing_page_msg"><?php echo $this->success_message ?></div>
<?php endif ?>
<br>
<h5><?php echo __( "Thrive Tooltip Settings", "thrive-cb" ) ?></h5>

<table>
	<tbody>
	<tr>
		<td width="40%">
			<?php echo __( "What text should be displayed ?", "thrive-cb" ) ?>
		</td>
		<td width="60%">
			<div class="tve_lightbox_text_holder">
				<textarea name="event_tooltip_text" id="tve_event_tooltip_text" rows="1"
				          class="tve_ctrl_validate tve_lightbox_input"
				          data-validators="required;"><?php echo isset( $this->config['event_tooltip_text'] ) ? stripslashes( $this->config['event_tooltip_text'] ) : ''; ?></textarea>
			</div>
		</td>
	</tr>
	<tr>
		<td><?php echo __( "Style", "thrive-cb" ) ?></td>
		<td>
			<div class="tve_lightbox_select_holder">
				<select name="event_tooltip_style" id="tve-tooltip-style-select">
					<?php foreach ( $this->_styles as $value => $label ): ?>
						<option value="<?php echo $value ?>"<?php
						echo ! empty( $this->config['event_tooltip_style'] ) && $this->config['event_tooltip_style'] == $value ? ' selected="selected"' : '' ?>><?php echo $label ?></option>
					<?php endforeach ?>
				</select>
			</div>
		</td>
	</tr>
	<tr>
		<td><?php echo __( "Tooltip direction", "thrive-cb" ) ?></td>
		<td>
			<div class="tve_lightbox_select_holder">
				<select name="event_tooltip_position" id="tve-tooltip-position-select">
					<?php foreach ( $this->_positions as $value => $label ): ?>
						<option value="<?php echo $value ?>"<?php
						echo ! empty( $this->config['event_tooltip_position'] ) && $this->config['event_tooltip_position'] == $value ? ' selected="selected"' : '' ?>><?php echo $label ?></option>
					<?php endforeach ?>
				</select>
			</div>
		</td>
	</tr>
	<tr>
		<td><?php echo __( "Tooltip preview", "thrive-cb" ) ?></td>
		<td>
			<div class="tve_lightbox_preview_holder">
				<a href="javascript:void(0)"
				   class="tve_preview_hover tve_tooltip_anchor tve_ea_underline_<?php echo isset( $this->config['event_tooltip_decoration'] ) ? $this->config['event_tooltip_decoration'] : 'solid'; ?>"
				   data-tooltip-position="<?php echo isset( $this->config['event_tooltip_position'] ) ? $this->config['event_tooltip_position'] : 'top'; ?>"
				   data-tooltip-text="<?php echo isset( $this->config['event_tooltip_text'] ) ? htmlentities( stripslashes( $this->config['event_tooltip_text'] ) ) : ''; ?>"
				   data-tooltip-style="<?php echo isset( $this->config['event_tooltip_style'] ) ? $this->config['event_tooltip_style'] : 'light'; ?>"
				><?php echo __( "Hover for preview", "thrive-cb" ) ?></a>
			</div>
		</td>
	</tr>
	</tbody>
</table>

<script type="text/javascript">
	jQuery( document ).ready( function ( $ ) {

		$( '#tve_event_tooltip_text' ).on( 'keyup', function () {
			$( '.tve_preview_hover' ).attr( 'data-tooltip-text', this.value );
		} );

		$( '#tve-tooltip-position-select' ).on( 'change', function () {
			$( '.tve_preview_hover' ).attr( 'data-tooltip-position', this.value );
		} );

		$( '#tve-tooltip-style-select' ).on( 'change', function () {
			$( '.tve_preview_hover' ).attr( 'data-tooltip-style', this.value );
		} );

		/**
		 * the tooltip implementation
		 */
		(function () {
			var a = document.getElementsByClassName( 'tve_tooltip_anchor' ),
				tip, text, position,
				base = document.createElement( 'div' ); //Defining object
			for ( var x = 0; x < a.length; x ++ ) { //get all tooltip anchors.
				a[x].onmouseover = function () {
					base.setAttribute( "class", "tve_ui_tooltip tve_tooltip_style_" + this.getAttribute( 'data-tooltip-style' ) + " tve_tooltip_position_" + this.getAttribute( 'data-tooltip-position' ) );
					text = this.getAttribute( 'data-tooltip-text' );
					tip = document.createTextNode( text );
					//create tooltip
					if ( text != '' ) {// Checking if tooltip is empty or not.
						base.innerHTML = '';
						base.appendChild( tip );
						if ( document.getElementsByClassName( 'tve_ui_tooltip' )[0] ) {// Checking for any "tooltip" element
							document.getElementsByClassName( 'tve_ui_tooltip' )[0].remove();// Removing old tooltip
						}

						document.body.appendChild( base );
					}
					var tooltip_width = base.offsetWidth,
						tooltip_height = base.offsetHeight,
						offset = 20, // set custom offset ( for aesthetic purposes only)
						top = 0,
						left = 0;
					// handle position
					position = this.getAttribute( 'data-tooltip-position' );
					var rect = this.getBoundingClientRect();
					switch ( position ) {
						case 'top':
							left = ( rect.right - rect.left - tooltip_width ) / 2 + rect.left;
							top = rect.top - tooltip_height - offset;
							break;
						case 'top_right':
							left = rect.right + offset;
							top = rect.top - tooltip_height - offset;
							break;
						case 'right':
							left = rect.right + offset;
							top = ( rect.bottom - rect.top - tooltip_height ) / 2 + rect.top;
							break;
						case 'bottom_right':
							left = rect.right + offset;
							top = rect.bottom + offset;
							break;
						case 'bottom':
							left = ( rect.right - rect.left - tooltip_width ) / 2 + rect.left;
							top = rect.bottom + offset;
							break;
						case 'bottom_left':
							left = rect.left - tooltip_width - offset;
							top = rect.bottom + offset;
							break;
						case 'left':
							left = rect.left - tooltip_width - offset;
							top = ( rect.bottom - rect.top - tooltip_height ) / 2 + rect.top;
							break;
						case 'top_left':
							left = rect.left - tooltip_width - offset;
							top = rect.top - tooltip_height - offset;
							break;
						default:
							left = 1;
							top = 1;
					}
					base.style.top = ( top ) + 'px';
					base.style.left = ( left ) + 'px';

				};
				a[x].onmouseout = function () {
					if ( document.getElementsByClassName( 'tve_ui_tooltip' )[0] ) {
						document.getElementsByClassName( 'tve_ui_tooltip' )[0].remove();// Remove last tooltip
					}
				};
			}
		})();
	} );
</script>