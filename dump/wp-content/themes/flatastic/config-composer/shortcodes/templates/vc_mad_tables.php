<?php

class WPBakeryShortCode_VC_mad_tables extends WPBakeryShortCode {

	public $atts = array();

	protected function content($atts, $content = null) {

		$this->atts = shortcode_atts(array(
			'title' => '',
			'columns' => '',
			'rows' => '',
			'data' => 'none',
		), $atts, 'vc_mad_tables');

		return $this->html();
	}

	public function stringData($colsVal, $rowsVal, $data) {
		$stringData = array();
		$counter = 0;

		for ($i = 0; $i < $rowsVal; $i++) {
			$stringData[$i] = array();
			for ($j = 0; $j < $colsVal; $j++) {
				$stringData[$i][$j] = $data[$counter];
				$counter ++;
			}
		}
		return $stringData;
	}

	public function html() {

		$output = $title = $data = $rows = $columns = '';

		extract($this->atts);

		$data = explode('||', $data);

		$output .= wpb_widget_title( array( 'title' => $title, 'extraclass' => 'wpb_tables_heading' ) );

		$output .= '<div class="table-responsive">';

			$output .= '<div class="mad-table">';

				for ($i = 0; $i < $rows; $i++) {
					$output .= '<div class="mad-table-row">';

						for ($j = 0; $j < $columns; $j++) {
							$stringData = $this->stringData($columns, $rows, $data);
							if (isset($stringData) && is_array($stringData) && $stringData != '') {
								$value = $stringData[$i][$j];
							}
							$output .= '<div class="mad-table-cell">'. do_shortcode($value) .'</div>';
						}

					$output .= '</div>';
				}

			$output .= '</div>';

		$output .= '</div>';

		return $output;
	}
}