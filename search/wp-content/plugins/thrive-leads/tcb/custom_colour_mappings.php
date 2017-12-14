<?php
/* custom color mappings file */
return array(
	'typefocus'                => array(
		'undefined' => array(
			'all' => array(
				array(
					"label"              => "Highlight color",
					"selector"           => "",
					'selector_suffix'    => ' .tve_selected_typist',
					'force_non_existent' => 1,
					'opacity'            => 1,
					"property"           => "background-color",
					"value"              => "[color]",
				),
			),
		)
	),
	'post_grid'                => array(
		"undefined" => array(
			"Flat"    => array(
				array(
					"label"    => "Background color",
					'opacity'  => 1,
					"selector" => ".tve_pg_container",
					"property" => "background-color",
					"value"    => "[color]",
					'save_key' => 'item_container'
				),
				array(
					"label"    => "Border color",
					'opacity'  => 1,
					"selector" => ".tve_pg_container",
					"property" => "border-color",
					"value"    => "[color]",
					'save_key' => 'item_container'
				),
				array(
					"label"    => "Shadow color",
					'opacity'  => 1,
					"selector" => ".tve_pg_container",
					"property" => "box-shadow",
					"value"    => "0 3px 2px -3px [color]",
					'save_key' => 'item_container'
				),
				array(
					"label"    => "Main text color",
					'opacity'  => 1,
					"selector" => ".tve_pg_container .tve-post-grid-text",
					"property" => "color",
					"value"    => "[color]",
					'save_key' => 'item_text'
				),
				array(
					"label"    => "Headline text color",
					'opacity'  => 1,
					"selector" => ".tve_pg_container .tve-post-grid-title a",
					"property" => "color",
					"value"    => "[color]",
					'save_key' => 'item_headline'
				),
				array(
					"label"    => "\"Read more\" link color",
					'opacity'  => 1,
					"selector" => ".tve_pg_container .tve_pg_more a, .tve_pg_more",
					"property" => "color",
					"value"    => "[color]",
					'save_key' => 'read_more_color'
				),
			),
			"Classy"  => array(
				array(
					"label"    => "Background color",
					'opacity'  => 1,
					"selector" => ".tve_pg_container",
					"property" => "background-color",
					"value"    => "[color]",
					'save_key' => 'item_container'
				),
				array(
					"label"    => "Border color",
					'opacity'  => 1,
					"selector" => ".tve_pg_container",
					"property" => "border-color",
					"value"    => "[color]",
					'save_key' => 'item_container'
				),
				array(
					"label"    => "Shadow color",
					'opacity'  => 1,
					"selector" => ".tve_pg_container",
					"property" => "box-shadow",
					"value"    => "0 3px 2px -3px [color]",
					'save_key' => 'item_container'
				),
				array(
					"label"    => "Main text color",
					'opacity'  => 1,
					"selector" => ".tve_pg_container .tve-post-grid-text",
					"property" => "color",
					"value"    => "[color]",
					'save_key' => 'item_text'
				),
				array(
					"label"    => "Headline text color",
					'opacity'  => 1,
					"selector" => ".tve_pg_container h2 a",
					"property" => "color",
					"value"    => "[color]",
					'save_key' => 'item_headline'
				),
				array(
					"label"    => "\"Read more\" link color",
					'opacity'  => 1,
					"selector" => ".tve_pg_container .tve_pg_more a, .tve_pg_more",
					"property" => "color",
					"value"    => "[color]",
					'save_key' => 'read_more_color'
				),
			),
			"Minimal" => array(
				array(
					"label"    => "Background color",
					'opacity'  => 1,
					"selector" => ".tve_pg_container",
					"property" => "background-color",
					"value"    => "[color]",
					'save_key' => 'item_container'
				),
				array(
					"label"    => "Border color",
					'opacity'  => 1,
					"selector" => ".tve_pg_container",
					"property" => "border-color",
					"value"    => "[color]",
					'save_key' => 'item_container'
				),
				array(
					"label"    => "Shadow color",
					'opacity'  => 1,
					"selector" => ".tve_pg_container",
					"property" => "box-shadow",
					"value"    => "0 3px 2px -3px [color]",
					'save_key' => 'item_container'
				),
				array(
					"label"    => "Main text color",
					'opacity'  => 1,
					"selector" => ".tve_pg_container .tve-post-grid-text",
					"property" => "color",
					"value"    => "[color]",
					'save_key' => 'item_text'
				),
				array(
					"label"    => "Headline text color",
					'opacity'  => 1,
					"selector" => ".tve_pg_container h2 a",
					"property" => "color",
					"value"    => "[color]",
					'save_key' => 'item_headline'
				),
				array(
					"label"    => "\"Read more\" link color",
					'opacity'  => 1,
					"selector" => ".tve_pg_container .tve_pg_more a, .tve_pg_more",
					"property" => "color",
					"value"    => "[color]",
					'save_key' => 'read_more_color'
				),
			)
		)
	),
	"contentbox"               => array(
		"1s"     => array(
			"Flat"    => array(
				array(
					"label"    => "Headline Background",
					'opacity'  => 1,
					"selector" => ".tve_hd",
					"property" => "background-color",
					"value"    => "[color]"
				),
				array(
					"label"    => "Border",
					"selector" => ".tve_cb1",
					"property" => "border",
					"value"    => "2px solid [color]"
				),
				array(
					"label"    => "Headline text shadow",
					"selector" => ".tve_hd h1, .tve_hd h2, .tve_hd h3, .tve_hd h4, .tve_hd h5, .tve_hd h6",
					"property" => "text-shadow",
					"value"    => "0 1px 0 [color]"
				),
				array(
					"label"    => "Background",
					'opacity'  => 1,
					"selector" => ".tve_cb1",
					"property" => "background-color",
					"value"    => "[color]"
				),
				array(
					"label"    => "Internal shadow color",
					'opacity'  => 1,
					'inset'    => 1,
					"selector" => ".tve_cb1",
					"property" => "box-shadow",
					"value"    => "box-shadow-all"
				),
				array(
					"label"    => "External shadow color",
					'opacity'  => 1,
					"selector" => ".tve_cb1",
					"property" => "box-shadow",
					"value"    => "box-shadow-all"
				),
			),
			"Classy"  => array(
				array(
					"label"    => "Headline Gradient",
					"selector" => ".tve_hd",
					"property" => "background-image",
					"value"    => "gradient"
				),
				array(
					'label'    => 'Content gradient',
					'selector' => '.tve_cb1',
					'property' => 'background-image',
					'value'    => 'gradient'
				),
				array(
					"label"    => "Headline Shadow",
					"selector" => ".tve_hd h1, .tve_hd h2, .tve_hd h3, .tve_hd h4, .tve_hd h5, .tve_hd h6",
					"property" => "text-shadow",
					"value"    => "0 1px 0 [color]"
				),
				array(
					"label"    => "Internal shadow color",
					'opacity'  => 1,
					'inset'    => 1,
					"selector" => ".tve_cb1",
					"property" => "box-shadow",
					"value"    => "box-shadow-all"
				),
				array(
					"label"    => "External shadow color",
					'opacity'  => 1,
					"selector" => ".tve_cb1",
					"property" => "box-shadow",
					"value"    => "box-shadow-all"
				),
			),
			"Minimal" => array(
				array(
					"label"    => "Background",
					'opacity'  => 1,
					"selector" => ".tve_cb1",
					"property" => "background-color",
					"value"    => "[color]"
				),
				array(
					"label"    => "Internal shadow color",
					'opacity'  => 1,
					'inset'    => 1,
					"selector" => ".tve_cb1",
					"property" => "box-shadow",
					"value"    => "box-shadow-all"
				),
				array(
					"label"    => "External shadow color",
					'opacity'  => 1,
					"selector" => ".tve_cb1",
					"property" => "box-shadow",
					"value"    => "box-shadow-all"
				),
			)
		),
		"2s"     => array(
			"Flat"    => array(
				array(
					"label"    => "Background",
					'opacity'  => 1,
					"selector" => ".tve_cb2",
					"property" => "background-color",
					"value"    => "[color]"
				),
				array(
					"label"    => "Horizontal Line",
					"selector" => ".tve_cb2 hr",
					"property" => "background-color",
					"value"    => "[color]"
				),
				array(
					"label"    => "Internal shadow color",
					'opacity'  => 1,
					'inset'    => 1,
					"selector" => ".tve_cb2",
					"property" => "box-shadow",
					"value"    => "box-shadow-all"
				),
				array(
					"label"    => "External shadow color",
					'opacity'  => 1,
					"selector" => ".tve_cb2",
					"property" => "box-shadow",
					"value"    => "box-shadow-all"
				),
			),
			"Classy"  => array(
				array(
					"label"    => "Background Gradient",
					"selector" => ".tve_cb2",
					"property" => "background-image",
					"value"    => "gradient"
				),
				array(
					"label"    => "Headline Shadow",
					"selector" => ".tve_cb2 h1, .tve_cb2 h2, .tve_cb2 h3, .tve_cb2 h4, .tve_cb2 h5, .tve_cb2 h6",
					"property" => "text-shadow",
					"value"    => "0 1px 0 [color]"
				),
				array(
					"label"    => "Horizontal Line",
					"selector" => ".tve_cb2 hr",
					"property" => "background-color",
					"value"    => "[color]"
				),
				array(
					"label"    => "Internal shadow color",
					'opacity'  => 1,
					'inset'    => 1,
					"selector" => ".tve_cb2",
					"property" => "box-shadow",
					"value"    => "box-shadow-all"
				),
				array(
					"label"    => "External shadow color",
					'opacity'  => 1,
					"selector" => ".tve_cb2",
					"property" => "box-shadow",
					"value"    => "box-shadow-all"
				),
			),
			"Minimal" => array(
				array(
					"label"    => "Border",
					"selector" => ".tve_cb_cnt:last",
					"property" => "border",
					"value"    => "2px solid [color]"
				),
				array(
					"label"    => "Internal shadow color",
					'opacity'  => 1,
					'inset'    => 1,
					"selector" => ".tve_cb_cnt:last",
					"property" => "box-shadow",
					"value"    => "box-shadow-all"
				),
				array(
					"label"    => "External shadow color",
					'opacity'  => 1,
					"selector" => ".tve_cb_cnt:last",
					"property" => "box-shadow",
					"value"    => "box-shadow-all"
				),
			),
		),
		"3s"     => array(
			"Flat"    => array(
				array(
					"label"    => "Headline Background",
					'opacity'  => 1,
					"selector" => ".tve_hd",
					"property" => "background-color",
					"value"    => "[color]"
				),
				array(
					"label"    => "Box Background",
					'opacity'  => 1,
					"selector" => ".tve_cb3",
					"property" => "background-color",
					"value"    => "[color]"
				),
				array(
					"label"    => "Border Colour",
					"selector" => ".tve_cb3",
					"property" => "border",
					"value"    => "2px solid [color]"
				),
				array(
					"label"    => "Internal shadow color",
					'opacity'  => 1,
					'inset'    => 1,
					"selector" => ".tve_cb3",
					"property" => "box-shadow",
					"value"    => "box-shadow-all"
				),
				array(
					"label"    => "External shadow color",
					'opacity'  => 1,
					"selector" => ".tve_cb3",
					"property" => "box-shadow",
					"value"    => "box-shadow-all"
				),
			),
			"Classy"  => array(
				0 => array(
					"label"    => "Background Gradient",
					"selector" => ".tve_cb3",
					"property" => "background-image",
					"value"    => "gradient"
				),
				1 => array(
					"label"    => "Divider",
					"selector" => "hr",
					"property" => "background-color",
					"value"    => "[color]"
				),
				array(
					"label"    => "Internal shadow color",
					'opacity'  => 1,
					'inset'    => 1,
					"selector" => ".tve_cb3",
					"property" => "box-shadow",
					"value"    => "box-shadow-all"
				),
				array(
					"label"    => "External shadow color",
					'opacity'  => 1,
					"selector" => ".tve_cb3",
					"property" => "box-shadow",
					"value"    => "box-shadow-all"
				),
			),
			"Minimal" => array(
				array(
					"label"    => "Headline Background",
					'opacity'  => 1,
					"selector" => ".tve_hd",
					"property" => "background-color",
					"value"    => "[color]"
				),
				array(
					"label"    => "Border Colour",
					"selector" => ".tve_cb3",
					"property" => "border",
					"value"    => "2px solid [color]"
				),
				array(
					"label"    => "Background Colour",
					'opacity'  => 1,
					"selector" => ".tve_cb3",
					"property" => "background-color",
					"value"    => "[color]"
				),
				array(
					"label"    => "Internal shadow color",
					'opacity'  => 1,
					'inset'    => 1,
					"selector" => ".tve_cb3",
					"property" => "box-shadow",
					"value"    => "box-shadow-all"
				),
				array(
					"label"    => "External shadow color",
					'opacity'  => 1,
					"selector" => ".tve_cb3",
					"property" => "box-shadow",
					"value"    => "box-shadow-all"
				),
			),
		),
		"4s"     => array(
			"Flat"    => array(
				array(
					"label"    => "Border",
					"selector" => ".tve_cb4",
					"property" => "border",
					"value"    => "2px solid [color]"
				),
				array(
					"label"    => "Internal shadow color",
					'opacity'  => 1,
					'inset'    => 1,
					"selector" => ".tve_cb4",
					"property" => "box-shadow",
					"value"    => "box-shadow-all"
				),
				array(
					"label"    => "External shadow color",
					'opacity'  => 1,
					"selector" => ".tve_cb4",
					"property" => "box-shadow",
					"value"    => "box-shadow-all"
				),
			),
			"Classy"  => array(
				array(
					"label"    => "Headline Gradient",
					"selector" => ".tve_hd",
					"property" => "background-image",
					"value"    => "gradient"
				),
				array(
					"label"    => "Background Gradient",
					"selector" => ".tve_cb4",
					"property" => "background-image",
					"value"    => "gradient"
				),
				array(
					"label"    => "Internal shadow color",
					'opacity'  => 1,
					'inset'    => 1,
					"selector" => ".tve_cb4",
					"property" => "box-shadow",
					"value"    => "box-shadow-all"
				),
				array(
					"label"    => "External shadow color",
					'opacity'  => 1,
					"selector" => ".tve_cb4",
					"property" => "box-shadow",
					"value"    => "box-shadow-all"
				),
			),
			"Minimal" => array(
				array(
					"label"    => "Background Colour",
					'opacity'  => 1,
					"selector" => ".tve_cb4",
					"property" => "background-color",
					"value"    => "[color]"
				),
				array(
					"label"    => "Border Colour",
					"selector" => ".tve_cb4",
					"property" => "border",
					"value"    => "1px solid [color]"
				),
				array(
					"label"    => "Internal shadow color",
					'opacity'  => 1,
					'inset'    => 1,
					"selector" => ".tve_cb4",
					"property" => "box-shadow",
					"value"    => "box-shadow-all"
				),
				array(
					"label"    => "External shadow color",
					'opacity'  => 1,
					"selector" => ".tve_cb4",
					"property" => "box-shadow",
					"value"    => "box-shadow-all"
				),
			),
		),
		"5s"     => array(
			"Flat"    => array(
				array(
					"label"    => "Background",
					'opacity'  => 1,
					"selector" => ".tve_cb5",
					"property" => "background-color",
					"value"    => "[color]"
				),
				array(
					"label"    => "Internal shadow color",
					'opacity'  => 1,
					'inset'    => 1,
					"selector" => ".tve_cb5",
					"property" => "box-shadow",
					"value"    => "box-shadow-all"
				),
				array(
					"label"    => "External shadow color",
					'opacity'  => 1,
					"selector" => ".tve_cb5",
					"property" => "box-shadow",
					"value"    => "box-shadow-all"
				),
			),
			"Classy"  => array(
				array(
					"label"    => "Background",
					"selector" => ".tve_cb5",
					"property" => "background-image",
					"value"    => "gradient"
				),
				array(
					"label"    => "Border",
					"selector" => ".tve_cb5",
					"property" => "border",
					"value"    => "2px solid [color]"
				),
				array(
					"label"    => "Internal shadow color",
					'opacity'  => 1,
					'inset'    => 1,
					"selector" => ".tve_cb5",
					"property" => "box-shadow",
					"value"    => "box-shadow-all"
				),
				array(
					"label"    => "External shadow color",
					'opacity'  => 1,
					"selector" => ".tve_cb5",
					"property" => "box-shadow",
					"value"    => "box-shadow-all"
				),
			),
			"Minimal" => array(
				array(
					"label"    => "Border",
					"selector" => ".tve_cb5",
					"property" => "border",
					"value"    => "1px solid [color]"
				),
				array(
					"label"    => "Internal shadow color",
					'opacity'  => 1,
					'inset'    => 1,
					"selector" => ".tve_cb5",
					"property" => "box-shadow",
					"value"    => "box-shadow-all"
				),
				array(
					"label"    => "External shadow color",
					'opacity'  => 1,
					"selector" => ".tve_cb5",
					"property" => "box-shadow",
					"value"    => "box-shadow-all"
				),
			)
		),
		"6s"     => array(
			"Flat"    => array(
				array(
					"label"    => "Background",
					'opacity'  => 1,
					"selector" => ".tve_cb6",
					"property" => "background-color",
					"value"    => "[color]"
				),
				array(
					"label"    => "Border",
					"selector" => ".tve_cb6",
					"property" => "border",
					"value"    => "2px solid [color]"
				),
				array(
					"label"    => "Internal shadow color",
					'opacity'  => 1,
					'inset'    => 1,
					"selector" => ".tve_cb6",
					"property" => "box-shadow",
					"value"    => "box-shadow-all"
				),
				array(
					"label"    => "External shadow color",
					'opacity'  => 1,
					"selector" => ".tve_cb6",
					"property" => "box-shadow",
					"value"    => "box-shadow-all"
				),
			),
			"Classy"  => array(
				array(
					"label"    => "Background",
					"selector" => ".tve_cb6",
					"property" => "background-image",
					"value"    => "gradient"
				),
				array(
					"label"    => "Internal shadow color",
					'opacity'  => 1,
					'inset'    => 1,
					"selector" => ".tve_cb6",
					"property" => "box-shadow",
					"value"    => "box-shadow-all"
				),
				array(
					"label"    => "External shadow color",
					'opacity'  => 1,
					"selector" => ".tve_cb6",
					"property" => "box-shadow",
					"value"    => "box-shadow-all"
				),
			),
			"Minimal" => array(
				array(
					"label"    => "Background",
					'opacity'  => 1,
					"selector" => ".tve_cb6",
					"property" => "background-color",
					"value"    => "[color]"
				),
				array(
					"label"    => "Border",
					"selector" => ".tve_cb6",
					"property" => "border-color",
					"value"    => "[color]"
				),
				array(
					"label"    => "Internal shadow color",
					'opacity'  => 1,
					'inset'    => 1,
					"selector" => ".tve_cb6",
					"property" => "box-shadow",
					"value"    => "box-shadow-all"
				),
				array(
					"label"    => "External shadow color",
					'opacity'  => 1,
					"selector" => ".tve_cb6",
					"property" => "box-shadow",
					"value"    => "box-shadow-all"
				),
			)
		),
		'symbol' => array(
			'Flat'    => array(
				array(
					"label"    => "Background",
					'opacity'  => 1,
					"selector" => ".tve_cb_symbol",
					"property" => "background-color",
					"value"    => "[color]"
				),
				array(
					"label"    => "Internal shadow color",
					'opacity'  => 1,
					'inset'    => 1,
					"selector" => ".tve_cb_symbol",
					"property" => "box-shadow",
					"value"    => "box-shadow-all"
				),
				array(
					"label"    => "External shadow color",
					'opacity'  => 1,
					"selector" => ".tve_cb_symbol",
					"property" => "box-shadow",
					"value"    => "box-shadow-all"
				),
			),
			'Classy'  => array(
				array(
					"label"    => "Background",
					"selector" => ".tve_cb_symbol",
					"property" => "background-image",
					"value"    => "gradient"
				),
				array(
					"label"    => "Internal shadow color",
					'opacity'  => 1,
					'inset'    => 1,
					"selector" => ".tve_cb_symbol",
					"property" => "box-shadow",
					"value"    => "box-shadow-all"
				),
				array(
					"label"    => "External shadow color",
					'opacity'  => 1,
					"selector" => ".tve_cb_symbol",
					"property" => "box-shadow",
					"value"    => "box-shadow-all"
				),
			),
			'Minimal' => array(
				array(
					"label"    => "Background",
					'opacity'  => 1,
					"selector" => ".tve_cb_symbol",
					"property" => "background-color",
					"value"    => "[color]"
				),
				array(
					"label"    => "Internal shadow color",
					'opacity'  => 1,
					'inset'    => 1,
					"selector" => ".tve_cb_symbol",
					"property" => "box-shadow",
					"value"    => "box-shadow-all"
				),
				array(
					"label"    => "External shadow color",
					'opacity'  => 1,
					"selector" => ".tve_cb_symbol",
					"property" => "box-shadow",
					"value"    => "box-shadow-all"
				),
			)
		)
	),
	"testimonial"              => array(
		"1" => array(
			"Flat"    => array(
				0 => array(
					"label"    => "Border",
					"selector" => ".tve_ts1",
					"property" => "border",
					"value"    => "2px solid [color]"
				),
				1 => array(
					"label"    => "Background",
					'opacity'  => 1,
					"selector" => ".tve_ts1",
					"property" => "background-color",
					"value"    => "[color]"
				),
				2 => array(
					"label"    => "Picture Background",
					'opacity'  => 1,
					"selector" => ".tve_ts_o",
					"property" => "background-color",
					"value"    => "[color]"
				),
				3 => array(
					"label"    => "Picture Border",
					"selector" => ".tve_ts_o img",
					"property" => "border",
					"value"    => "2px solid [color]"
				),
			),
			"Classy"  => array(
				0 => array(
					"label"    => "Main Background",
					"selector" => ".tve_ts.tve_ts1",
					"property" => "background-image",
					"value"    => "gradient"
				),
				1 => array(
					"label"    => "Button Background",
					"selector" => ".tve_ts_o:first",
					"property" => "background-image",
					"value"    => "gradient"
				)
			),
			"Minimal" => array(
				0 => array(
					"label"    => "Box Border",
					"selector" => ".tve_ts.tve_ts1",
					"property" => "border",
					"value"    => "3px solid [color]"
				),
				1 => array(
					"label"    => "Button Background",
					"selector" => ".tve_ts.tve_red .tve_ts_o img",
					"property" => "border",
					"value"    => "3px solid [color]"
				)
			)
		),
		"2" => array(
			"Flat"    => array(
				0 => array(
					"label"    => "Background",
					'opacity'  => 1,
					"selector" => ".tve_ts_cn",
					"property" => "background-color",
					"value"    => "[color]"
				),
				1 => array(
					"label"    => "Image Border",
					"selector" => ".tve_ts_imc",
					"property" => "border",
					"value"    => "2px solid [color]"
				),
				2 => array(
					"label"    => "Divider",
					"selector" => ".tve_ts_o",
					"property" => "border-bottom-color",
					"value"    => "[color]"
				),
			),
			"Classy"  => array(
				0 => array(
					"label"    => "Background",
					"selector" => ".tve_ts2",
					"property" => "background-image",
					"value"    => "gradient"
				),
				1 => array(
					"label"    => "Name Background",
					'opacity'  => 1,
					"selector" => ".tve_ts_o > span",
					"property" => "background-color",
					"value"    => "[color]"
				),
				2 => array(
					"label"    => "Picture Border",
					"selector" => ".tve_ts_o img",
					"property" => "border",
					"value"    => "2px solid [color]"
				),
			),
			"Minimal" => array(
				0 => array(
					"label"    => "Box Border",
					"selector" => ".tve_ts_t",
					"property" => "border",
					"value"    => "3px solid [color]"
				),
				1 => array(
					"label"    => "Picture Border",
					"selector" => ".tve_ts_imc",
					"property" => "border",
					"value"    => "3px solid [color]"
				),
				2 => array(
					"label"    => "Background",
					'opacity'  => 1,
					"selector" => ".tve_ts_t",
					"property" => "background-color",
					"value"    => "[color]"
				),
			)
		),
		"3" => array(
			"Flat"    => array(
				0 => array(
					"label"    => "Top Background",
					'opacity'  => 1,
					"selector" => ".tve_ts_o",
					"property" => "background-color",
					"value"    => "[color]"
				),
				1 => array(
					"label"    => "Bottom Background",
					'opacity'  => 1,
					"selector" => ".tve_ts_cn",
					"property" => "background-color",
					"value"    => "[color]"
				),
				2 => array(
					"label"    => "Divider",
					"selector" => ".tve_ts_o",
					"property" => "border-bottom-color",
					"value"    => "[color]"
				),
				3 => array(
					"label"    => "Image Border",
					"selector" => ".tve_ts_o img",
					"property" => "border",
					"value"    => "2px solid [color]"
				),
			),
			"Classy"  => array(
				0 => array(
					"label"    => "Background",
					"selector" => ".tve_ts3",
					"property" => "background-image",
					"value"    => "gradient"
				),
				1 => array(
					"label"    => "Name Background",
					'opacity'  => 1,
					"selector" => ".tve_ts_o > span",
					"property" => "background-color",
					"value"    => "[color]"
				),
				2 => array(
					"label"    => "Picture Border",
					"selector" => ".tve_ts_o img",
					"property" => "border",
					"value"    => "2px solid [color]"
				),
			),
			"Minimal" => array(
				0 => array(
					"label"    => "Border",
					"selector" => ".tve_ts3",
					"property" => "border",
					"value"    => "3px solid [color]"
				),
				1 => array(
					"label"    => "Picture Border",
					"selector" => ".tve_ts_o img",
					"property" => "border",
					"value"    => "3px solid [color]"
				),
				2 => array(
					"label"    => "Background",
					'opacity'  => 1,
					"selector" => ".tve_ts_o, .tve_ts_cn",
					"property" => "background-color",
					"value"    => "[color]"
				),
			)
		),
		"4" => array(
			"Flat"    => array(
				0 => array(
					"label"    => "Border",
					"selector" => ".tve_ts_cn",
					"property" => "border",
					"value"    => "3px solid [color]"
				),
				1 => array(
					"label"    => "Background",
					'opacity'  => 1,
					"selector" => ".tve_ts_cn",
					"property" => "background-color",
					"value"    => "[color]"
				),
				2 => array(
					"label"    => "Image Border",
					"selector" => ".tve_ts_imc",
					"property" => "border",
					"value"    => "2px solid [color]"
				),
			),
			"Classy"  => array(
				0 => array(
					"label"    => "Background",
					'opacity'  => 1,
					"selector" => ".tve_ts_cn",
					"property" => "background-color",
					"value"    => "[color]"
				),
				1 => array(
					"label"    => "Border",
					"selector" => ".tve_ts_cn",
					"property" => "border",
					"value"    => "1px solid [color]"
				)
			),
			"Minimal" => array(
				0 => array(
					"label"    => "Border",
					"selector" => ".tve_ts_cn",
					"property" => "border",
					"value"    => "3px solid [color]"
				),
				1 => array(
					"label"    => "Background",
					'opacity'  => 1,
					"selector" => ".tve_ts_cn",
					"property" => "background-color",
					"value"    => "[color]"
				),
				2 => array(
					"label"    => "Picture Border",
					"selector" => ".tve_ts_imc",
					"property" => "border",
					"value"    => "3px solid [color]"
				),
			)
		),
		"5" => array(
			"Flat"    => array(
				0 => array(
					"label"    => "Border",
					"selector" => ".tve_ts_cn",
					"property" => "border",
					"value"    => "3px solid [color]"
				),
				1 => array(
					"label"    => "Background",
					'opacity'  => 1,
					"selector" => ".tve_ts_cn",
					"property" => "background-color",
					"value"    => "[color]"
				),
				2 => array(
					"label"    => "Image Border",
					"selector" => ".tve_ts_imc",
					"property" => "border",
					"value"    => "2px solid [color]"
				),
			),
			"Classy"  => array(
				0 => array(
					"label"    => "Background",
					'opacity'  => 1,
					"selector" => ".tve_ts_cn",
					"property" => "background-color",
					"value"    => "[color]"
				),
				1 => array(
					"label"    => "Border",
					"selector" => ".tve_ts_cn",
					"property" => "border",
					"value"    => "1px solid [color]"
				)
			),
			"Minimal" => array(
				0 => array(
					"label"    => "Border",
					"selector" => ".tve_ts_cn",
					"property" => "border",
					"value"    => "3px solid [color]"
				),
				1 => array(
					"label"    => "Background",
					'opacity'  => 1,
					"selector" => ".tve_ts_cn",
					"property" => "background-color",
					"value"    => "[color]"
				),
				2 => array(
					"label"    => "Picture Border",
					"selector" => ".tve_ts_imc",
					"property" => "border",
					"value"    => "3px solid [color]"
				),
			)
		),
		"6" => array(
			"Flat"    => array(
				0 => array(
					"label"    => "Border",
					"selector" => ".tve_ts1",
					"property" => "border",
					"value"    => "2px solid [color]"
				),
				1 => array(
					"label"    => "Background",
					'opacity'  => 1,
					"selector" => ".tve_ts1",
					"property" => "background-color",
					"value"    => "[color]"
				),
				2 => array(
					"label"    => "Name Background",
					'opacity'  => 1,
					"selector" => ".tve_ts_o",
					"property" => "background-color",
					"value"    => "[color]"
				),
			),
			"Classy"  => array(
				0 => array(
					"label"    => "Main Background",
					"selector" => ".tve_ts1",
					"property" => "background-image",
					"value"    => "gradient"
				),
				1 => array(
					"label"    => "Button Background",
					"selector" => ".tve_ts_o",
					"property" => "background-image",
					"value"    => "gradient"
				)
			),
			"Minimal" => array(
				0 => array(
					"label"    => "Border",
					"selector" => ".tve_ts1",
					"property" => "border",
					"value"    => "3px solid [color]"
				),
				1 => array(
					"label"    => "Background",
					'opacity'  => 1,
					"selector" => ".tve_ts1",
					"property" => "background-color",
					"value"    => "[color]"
				)
			)
		),
		"7" => array(
			"Flat"    => array(
				0 => array(
					"label"    => "Divider",
					"selector" => ".tve_ts_o",
					"property" => "border-bottom-color",
					"value"    => "[color]"
				),
				1 => array(
					"label"    => "Background",
					'opacity'  => 1,
					"selector" => ".tve_ts_cn",
					"property" => "background-color",
					"value"    => "[color]"
				),
			),
			"Classy"  => array(
				0 => array(
					"label"    => "Main Background",
					"selector" => ".tve_ts2",
					"property" => "background-image",
					"value"    => "gradient"
				),
				1 => array(
					"label"    => "Name Background",
					'opacity'  => 1,
					"selector" => ".tve_ts_o > span",
					"property" => "background-color",
					"value"    => "[color]"
				)
			),
			"Minimal" => array(
				0 => array(
					"label"    => "Border",
					"selector" => ".tve_ts_t",
					"property" => "border",
					"value"    => "3px solid [color]"
				),
				1 => array(
					"label"    => "Background",
					'opacity'  => 1,
					"selector" => ".tve_ts_t",
					"property" => "background-color",
					"value"    => "[color]"
				)
			)
		),
		"8" => array(
			"Flat"    => array(
				0 => array(
					"label"    => "Divider",
					"selector" => ".tve_ts_o",
					"property" => "border-bottom-color",
					"value"    => "[color]"
				),
				1 => array(
					"label"    => "Background Top",
					'opacity'  => 1,
					"selector" => ".tve_ts_o",
					"property" => "background-color",
					"value"    => "[color]"
				),
				2 => array(
					"label"    => "Background Bottom",
					'opacity'  => 1,
					"selector" => ".tve_ts_cn",
					"property" => "background-color",
					"value"    => "[color]"
				),
			),
			"Classy"  => array(
				0 => array(
					"label"    => "Main Background",
					"selector" => ".tve_ts3",
					"property" => "background-image",
					"value"    => "gradient"
				),
				1 => array(
					"label"    => "Name Background",
					"selector" => ".tve_ts_o > span",
					"property" => "background-image",
					"value"    => "gradient"
				)
			),
			"Minimal" => array(
				0 => array(
					"label"    => "Border",
					"selector" => ".tve_ts",
					"property" => "border",
					"value"    => "3px solid [color]"
				),
				1 => array(
					"label"    => "Background Top",
					'opacity'  => 1,
					"selector" => ".tve_ts_o",
					"property" => "background-color",
					"value"    => "[color]"
				),
				2 => array(
					"label"    => "Background Bottom",
					'opacity'  => 1,
					"selector" => ".tve_ts_cn",
					"property" => "background-color",
					"value"    => "[color]"
				)
			)
		),
		"9" => array(
			"Flat"    => array(
				0 => array(
					"label"    => "Background",
					'opacity'  => 1,
					"selector" => ".tve_ts_cn",
					"property" => "background-color",
					"value"    => "[color]"
				)
			),
			"Classy"  => array(
				0 => array(
					"label"    => "Main Background",
					'opacity'  => 1,
					"selector" => ".tve_ts_cn",
					"property" => "background-color",
					"value"    => "[color]"
				),
				1 => array(
					"label"    => "Border",
					"selector" => ".tve_ts_cn",
					"property" => "border",
					"value"    => "1px solid [color]"
				)
			),
			"Minimal" => array(
				0 => array(
					"label"    => "Border",
					"selector" => ".tve_ts_cn",
					"property" => "border",
					"value"    => "3px solid [color]"
				),
				1 => array(
					"label"    => "Background",
					'opacity'  => 1,
					"selector" => ".tve_ts_cn",
					"property" => "background-color",
					"value"    => "[color]"
				)
			)
		)
	),
	"calltoaction"             => array(
		"1" => array(
			"Flat"    => array(
				0 => array(
					"label"    => "Button Colour",
					'opacity'  => 1,
					"selector" => ".tve_btn",
					"property" => "background-color",
					"value"    => "[color]"
				),
			),
			"Classy"  => array(
				0 => array(
					"label"    => "Headline Background",
					'opacity'  => 1,
					"selector" => ".tve_line",
					"property" => "background-color",
					"value"    => "[color]"
				),
				1 => array(
					"label"    => "Headline Text Shadow",
					"selector" => ".tve_ca h1, tve_ca h2, tve_ca h3, tve_ca h4, .tve_ca h5, .tve_ca h6",
					"property" => "text-shadow",
					"value"    => "0 1px 1px [color]"
				),
				2 => array(
					"label"    => "Button Background",
					"selector" => ".tve_btn",
					"property" => "background-image",
					"value"    => "gradient"
				),
				3 => array(
					"label"    => "Button Text Shadow",
					"selector" => ".tve_btn a > span",
					"property" => "text-shadow",
					"value"    => "0 1px 0 [color]"
				),
				4 => array(
					"label"    => "Border",
					"selector" => ".tve_ca",
					"property" => "border",
					"value"    => "2px solid [color]"
				),
				5 => array(
					"label"    => "Main Background",
					'opacity'  => 1,
					"selector" => ".tve_ca",
					"property" => "background-color",
					"value"    => "[color]"
				),
			),
			"Minimal" => array(
				0 => array(
					"label"    => "Border",
					"selector" => ".tve_ca",
					"property" => "border",
					"value"    => "3px solid [color]"
				),
				1 => array(
					"label"    => "Button Background",
					'opacity'  => 1,
					"selector" => ".tve_btn",
					"property" => "background-color",
					"value"    => "[color]"
				),
				2 => array(
					"label"    => "Main Background",
					'opacity'  => 1,
					"selector" => ".tve_ca",
					"property" => "background-color",
					"value"    => "[color]"
				)
			)
		),
		"2" => array(
			"Flat"    => array(
				array(
					"label"    => "Border",
					"selector" => ".tve_ca",
					"property" => "border",
					"value"    => "2px solid [color]"
				),
				array(
					"label"    => "Main Background",
					'opacity'  => 1,
					"selector" => ".tve_ca",
					"property" => "background-color",
					"value"    => "[color]"
				),
			),
			"Classy"  => array(
				0 => array(
					"label"    => "Button Background",
					"selector" => ".tve_ca_t",
					"property" => "background-image",
					"value"    => "gradient"
				),
				1 => array(
					"label"    => "Main Background",
					'opacity'  => 1,
					"selector" => ".tve_ca",
					"property" => "background-color",
					"value"    => "[color]"
				),
				2 => array(
					"label"    => "Border",
					"selector" => ".tve_ca",
					"property" => "border",
					"value"    => "2px solid [color]"
				)
			),
			"Minimal" => array(
				0 => array(
					"label"    => "Border",
					"selector" => ".tve_ca",
					"property" => "border",
					"value"    => "3px solid [color]"
				),
				1 => array(
					"label"    => "Button Border",
					"selector" => ".tve_ca_t",
					"property" => "border",
					"value"    => "2px solid [color]"
				),
				2 => array(
					"label"    => "Button Background",
					'opacity'  => 1,
					"selector" => ".tve_ca_t",
					"property" => "background-color",
					"value"    => "[color]"
				),
				3 => array(
					"label"    => "Main Background",
					'opacity'  => 1,
					"selector" => ".tve_ca",
					"property" => "background-color",
					"value"    => "[color]"
				)
			)
		),
		"3" => array(
			"Flat"    => array(
				0 => array(
					"label"    => "Border",
					"selector" => ".tve_ca",
					"property" => "border",
					"value"    => "2px solid [color]"
				),
				1 => array(
					"label"    => "Main Background",
					'opacity'  => 1,
					"selector" => ".tve_ca",
					"property" => "background-color",
					"value"    => "[color]"
				),
				2 => array(
					"label"    => "Button Background",
					'opacity'  => 1,
					"selector" => ".tve_btn",
					"property" => "background-color",
					"value"    => "[color]"
				),
				3 => array(
					"label"    => "Button Border",
					"selector" => ".tve_btn",
					"property" => "border",
					"value"    => "2px solid [color]"
				),
			),
			"Classy"  => array(
				0 => array(
					"label"    => "Button Background",
					"selector" => ".tve_btn",
					"property" => "background-image",
					"value"    => "gradient"
				),
				1 => array(
					"label"    => "Main Background",
					'opacity'  => 1,
					"selector" => ".tve_ca",
					"property" => "background-color",
					"value"    => "[color]"
				),
				2 => array(
					"label"    => "Border",
					"selector" => ".tve_ca",
					"property" => "border",
					"value"    => "2px solid [color]"
				),
				3 => array(
					"label"    => "Button Text Shadow",
					"selector" => "a.tve_btnLink",
					"property" => "text-shadow",
					"value"    => "0 1px 0 [color]"
				)
			),
			"Minimal" => array(
				array(
					"label"    => "Border",
					"selector" => ".tve_ca",
					"property" => "border",
					"value"    => "3px solid [color]"
				),
				array(
					"label"    => "Button Background",
					'opacity'  => 1,
					"selector" => ".tve_btn",
					"property" => "background-color",
					"value"    => "[color]"
				),
				array(
					"label"    => "Main Background",
					'opacity'  => 1,
					"selector" => ".tve_ca",
					"property" => "background-color",
					"value"    => "[color]"
				)
			)
		),
		"4" => array(
			"Flat"    => array(
				0 => array(
					"label"    => "Border",
					"selector" => ".tve_ca",
					"property" => "border",
					"value"    => "2px solid [color]"
				),
				1 => array(
					"label"    => "Main Background",
					'opacity'  => 1,
					"selector" => ".tve_ca",
					"property" => "background-color",
					"value"    => "[color]"
				),
				2 => array(
					"label"    => "Button Background",
					'opacity'  => 1,
					"selector" => ".tve_btn",
					"property" => "background-color",
					"value"    => "[color]"
				),
				3 => array(
					"label"    => "Button Border",
					"selector" => ".tve_btn",
					"property" => "border",
					"value"    => "2px solid [color]"
				),
			),
			"Classy"  => array(
				0 => array(
					"label"    => "Button Background",
					"selector" => ".tve_btn",
					"property" => "background-image",
					"value"    => "gradient"
				),
				1 => array(
					"label"    => "Main Background",
					'opacity'  => 1,
					"selector" => ".tve_ca",
					"property" => "background-color",
					"value"    => "[color]"
				),
				2 => array(
					"label"    => "Border",
					"selector" => ".tve_ca",
					"property" => "border",
					"value"    => "2px solid [color]"
				),
				3 => array(
					"label"    => "Button Text Shadow",
					"selector" => "a.tve_btnLink",
					"property" => "text-shadow",
					"value"    => "0 1px 0 [color]"
				)
			),
			"Minimal" => array(
				0 => array(
					"label"    => "Button Background",
					'opacity'  => 1,
					"selector" => ".tve_btn",
					"property" => "background-color",
					"value"    => "[color]"
				),
				1 => array(
					"label"    => "Main Background",
					'opacity'  => 1,
					"selector" => ".tve_ca",
					"property" => "background-color",
					"value"    => "[color]"
				)
			)
		)
	),
	"button"                   => array(
		"1" => array(
			"Flat"    => array(
				array(
					"label"    => "Background Colour",
					"selector" => ".tve_btn",
					"property" => "background-color",
					"value"    => "[color]",
					'opacity'  => 1
				),
				array(
					"label"    => "Background (mouseover)",
					"selector" => ".tve_btn",
					"property" => "background-color",
					"value"    => "[color]",
					'opacity'  => 1,
					'hover'    => 1
				),
				array(
					"label"    => "Vertical Divider",
					"selector" => ".tve_btn a .tve_btn_divider",
					"property" => "background-color",
					"value"    => "[color]"
				),
				array(
					"label"          => "Vertical Divider (mouseover)",
					"selector"       => ".tve_btn",
					'selector_hover' => 'a .tve_btn_divider',
					"property"       => "background-color",
					"value"          => "[color]",
					'hover'          => 1
				),
				array(
					"label"    => "Box Shadow",
					"selector" => ".tve_btn",
					"property" => "box-shadow",
					"value"    => "0 5px 0 [color]"
				),
				array(
					"label"    => "Box Shadow (mouseover)",
					"selector" => ".tve_btn",
					"property" => "box-shadow",
					"value"    => "0 5px 0 [color]",
					'hover'    => 1,
				),
				array(
					"label"    => "Bottom Border",
					"selector" => ".tve_btn",
					"property" => "border-bottom-color",
					"value"    => "[color]"
				),
				array(
					"label"    => "Bottom Border (mouseover)",
					"selector" => ".tve_btn",
					"property" => "border-bottom-color",
					"value"    => "[color]",
					'hover'    => 1,
				),
				array(
					"label"    => "Text color",
					"selector" => ".tve_btn .tve_btnLink .tve_btn_txt",
					"property" => "color",
					"value"    => "[color]"
				),
				array(
					"label"          => "Text color (mouseover)",
					"selector"       => ".tve_btn",
					'selector_hover' => ".tve_btnLink .tve_btn_txt",
					"property"       => "color",
					"value"          => "[color]",
					'hover'          => 1
				),
				array(
					"label"    => "Text shadow color",
					"selector" => ".tve_btn .tve_btnLink .tve_btn_txt",
					"property" => "text-shadow",
					"value"    => "0 1px 0 [color]"
				),
				array(
					"label"          => "Text shadow (mouseover)",
					"selector"       => ".tve_btn",
					'selector_hover' => ".tve_btnLink .tve_btn_txt",
					"property"       => "text-shadow",
					"value"          => "0 1px 0 [color]",
					'hover'          => 1
				),
				array(
					'label'    => 'Icon color',
					'selector' => 'i',
					'property' => 'color',
					'value'    => '[color]'
				),
				array(
					'label'          => 'Icon color (mouseover)',
					'selector'       => '.tve_btn',
					'selector_hover' => 'i',
					'property'       => 'color',
					'value'          => '[color]',
					'hover'          => 1
				)
			),
			"Classy"  => array(
				array(
					"label"    => "Background Colour",
					"selector" => ".tve_btn",
					"property" => "background-image",
					"value"    => "gradient"
				),
				array(
					"label"    => "Background (mouseover)",
					"selector" => ".tve_btn",
					"property" => "background-image",
					"value"    => "gradient",
					'hover'    => 1,
				),
				array(
					"label"    => "Vertical Divider",
					"selector" => ".tve_btn a .tve_btn_divider",
					"property" => "background-color",
					"value"    => "[color]"
				),
				array(
					"label"          => "Vertical Divider (mouseover)",
					'selector'       => '.tve_btn', /* the selector which needs to have the data-tve-custom-colour attribute */
					"selector_hover" => "a .tve_btn_divider", /* the real selector (this will get appended to the CSS rule */
					"property"       => "background-color",
					"value"          => "[color]",
					'hover'          => 1
				),
				array(
					"label"    => "Box Shadow",
					"selector" => ".tve_btn",
					"property" => "box-shadow",
					"value"    => "0 2px 3px [color]",
				),
				array(
					"label"    => "Box Shadow (mouseover)",
					"selector" => ".tve_btn",
					"property" => "box-shadow",
					"value"    => "0 2px 3px [color]",
					'hover'    => 1,
				),
				array(
					"label"    => "Bottom Border",
					"selector" => ".tve_btn",
					"property" => "border-bottom-color",
					"value"    => "[color]"
				),
				array(
					"label"    => "Bottom Border (mouseover)",
					"selector" => ".tve_btn",
					"property" => "border-bottom-color",
					"value"    => "[color]",
					'hover'    => 1
				),
				array(
					"label"    => "Text color",
					"selector" => ".tve_btn .tve_btnLink .tve_btn_txt",
					"property" => "color",
					"value"    => "[color]"
				),
				array(
					"label"          => "Text color (mouseover)",
					"selector"       => '.tve_btn',
					'selector_hover' => '.tve_btnLink .tve_btn_txt',
					"property"       => "color",
					"value"          => "[color]",
					'hover'          => 1
				),
				array(
					"label"    => "Text shadow color",
					"selector" => ".tve_btn .tve_btnLink .tve_btn_txt",
					"property" => "text-shadow",
					"value"    => "0 1px 0 [color]"
				),
				array(
					"label"          => "Text shadow (mouseover)",
					"selector"       => ".tve_btn",
					'selector_hover' => ".tve_btnLink .tve_btn_txt",
					"property"       => "text-shadow",
					"value"          => "0 1px 0 [color]",
					'hover'          => 1
				),
				array(
					'label'    => 'Icon color',
					'selector' => 'i',
					'property' => 'color',
					'value'    => '[color]'
				),
				array(
					'label'          => 'Icon color (mouseover)',
					'selector'       => '.tve_btn',
					'selector_hover' => 'i',
					'property'       => 'color',
					'value'          => '[color]',
					'hover'          => 1
				)
			),
			"Minimal" => array(
				array(
					"label"    => "Background",
					"selector" => ".tve_btn",
					"property" => "background-color",
					"value"    => "[color]",
					'opacity'  => 1
				),
				array(
					"label"    => "Background (mouseover)",
					"selector" => ".tve_btn",
					"property" => "background-color",
					"value"    => "[color]",
					'opacity'  => 1,
					'hover'    => 1
				),
				array(
					"label"    => "Vertical Divider",
					"selector" => ".tve_btn a .tve_btn_divider",
					"property" => "background-color",
					"value"    => "[color]"
				),
				array(
					"label"    => "Vertical Divider",
					"selector" => ".tve_btn a .tve_btn_divider",
					"property" => "background-color",
					"value"    => "[color]"
				),
				array(
					"label"    => "Box Shadow",
					"selector" => ".tve_btn",
					"property" => "box-shadow",
					"value"    => "0 5px 0 [color]"
				),
				array(
					"label"    => "Box Shadow (mouseover)",
					"selector" => ".tve_btn",
					"property" => "box-shadow",
					"value"    => "0 5px 0 [color]",
					'hover'    => 1,
				),
				array(
					"label"    => "Bottom Border",
					"selector" => ".tve_btn",
					"property" => "border-bottom-color",
					"value"    => "[color]"
				),
				array(
					"label"    => "Bottom Border (mouseover)",
					"selector" => ".tve_btn",
					"property" => "border-bottom-color",
					"value"    => "[color]",
					'hover'    => 1
				),
				array(
					"label"    => "Text color",
					"selector" => ".tve_btn .tve_btnLink .tve_btn_txt",
					"property" => "color",
					"value"    => "[color]"
				),
				array(
					"label"          => "Text color (hover)",
					"selector"       => ".tve_btn",
					'selector_hover' => ".tve_btnLink .tve_btn_txt",
					"property"       => "color",
					"value"          => "[color]",
					'hover'          => 1
				),
				array(
					"label"    => "Text shadow color",
					"selector" => ".tve_btn .tve_btnLink .tve_btn_txt",
					"property" => "text-shadow",
					"value"    => "0 1px 0 [color]"
				),
				array(
					"label"          => "Text shadow (mouseover)",
					"selector"       => ".tve_btn",
					'selector_hover' => ".tve_btnLink .tve_btn_txt",
					"property"       => "text-shadow",
					"value"          => "0 1px 0 [color]",
					'hover'          => 1
				),
				array(
					'label'    => 'Icon color',
					'selector' => 'i',
					'property' => 'color',
					'value'    => '[color]'
				),
				array(
					'label'          => 'Icon color (mouseover)',
					'selector'       => '.tve_btn',
					'selector_hover' => 'i',
					'property'       => 'color',
					'value'          => '[color]',
					'hover'          => 1
				)
			)
		)
	),
	'tw_qs'                    => array(
		"1" => array(
			"Classy"  => array(
				0 => array(
					"label"    => "Background color",
					"selector" => ".thrv_tw_qs_container",
					"property" => "background-image",
					"value"    => "gradient"
				),
				1 => array(
					"label"    => "Button color",
					"selector" => ".thrv_tw_qs_button > span",
					"property" => "background-image",
					"value"    => "gradient"
				)
			),
			"Flat"    => array(
				0 => array(
					"label"    => "Background color",
					'opacity'  => 1,
					"selector" => ".thrv_tw_qs_container",
					"property" => "background-color",
					"value"    => "[color]"
				),
				1 => array(
					"label"    => "Button color",
					'opacity'  => 1,
					"selector" => ".thrv_tw_qs_button > span",
					"property" => "background-color",
					"value"    => "[color]"
				)
			),
			"Minimal" => array(
				0 => array(
					"label"    => "Background color",
					'opacity'  => 1,
					"selector" => ".thrv_tw_qs_container",
					"property" => "background-color",
					"value"    => "[color]"
				),
				1 => array(
					"label"    => "Border color",
					"selector" => ".thrv_tw_qs_container",
					"property" => "border-color",
					"value"    => "[color]"
				),
			)
		)
	),
	'lead_generation'          => array(
		"1" => array(
			"Flat"    => array(
				array(
					"label"    => "Inputs Text Color",
					"selector" => 'input[type="text"],textarea,select,input[type="email"]',
					"property" => 'color',
					"value"    => "[color]",
				),
				array(
					"label"    => "Inputs Border Color",
					"selector" => 'input[type="text"],textarea,select, input[type="email"]',
					"property" => 'border-color',
					"value"    => "[color]",
				),
				array(
					"label"    => "Inputs Border Color(hover)",
					"selector" => 'input[type="text"],textarea,select,input[type="email"]',
					"property" => 'border-color',
					"value"    => "[color]",
					'hover'    => 1
				),
				array(
					"label"    => "Label colour",
					"selector" => 'label',
					"property" => 'color',
					"value"    => "[color]",
				),
				array(
					"label"    => "Button Text Color",
					"selector" => 'button',
					"property" => 'color',
					"value"    => "[color]",
				),
				array(
					"label"    => "Button Text Shadow Color",
					"selector" => "button",
					"property" => "text-shadow",
					"value"    => "0 1px 0 [color]"
				),
				array(
					"label"    => "Button Shadow Color",
					'opacity'  => 1,
					"selector" => "button",
					"property" => "box-shadow",
					"value"    => "0 3px 3px 1px [color]"
				),
				array(
					"label"    => "Button BG Color",
					"selector" => 'button',
					"property" => 'background-color',
					"value"    => "[color]",
				),
				array(
					"label"    => "Button BG Color(hover)",
					"selector" => 'button',
					"property" => 'background-color',
					"value"    => "[color]",
					"hover"    => 1
				),
				array(
					"label"    => "Button Border Color",
					"selector" => 'button',
					"property" => 'border-color',
					"value"    => "[color]",
				),
				array(
					"label"    => "Button Border Color (hover)",
					"selector" => 'button',
					"hover"    => 1,
					"property" => 'border-color',
					"value"    => "[color]",
				),
			),
			"Classy"  => array(
				array(
					"label"    => "Inputs Text Color",
					"selector" => 'input[type="text"],textarea,select,input[type="email"]',
					"property" => 'color',
					"value"    => "[color]",
				),
				array(
					"label"    => "Inputs Border Color",
					"selector" => 'input[type="text"],textarea,select,input[type="email"]',
					"property" => 'border-color',
					"value"    => "[color]",
				),
				array(
					"label"    => "Inputs Border Color(hover)",
					"selector" => 'input[type="text"],textarea,select,input[type="email"]',
					"property" => 'border-color',
					"value"    => "[color]",
					'hover'    => 1
				),
				array(
					"label"    => "Label colour",
					"selector" => 'label',
					"property" => 'color',
					"value"    => "[color]",
				),
				array(
					"label"    => "Button Text Color",
					"selector" => 'button',
					"property" => 'color',
					"value"    => "[color]",
				),
				array(
					"label"    => "Button Text Shadow Color",
					"selector" => "button",
					"property" => "text-shadow",
					"value"    => "0 1px 0 [color]"
				),
				array(
					"label"    => "Button Shadow Color",
					'opacity'  => 1,
					"selector" => "button",
					"property" => "box-shadow",
					"value"    => "0 3px 3px 1px [color]"
				),
				array(
					"label"    => "Button BG Color",
					"selector" => 'button',
					"property" => 'background-color',
					"value"    => "[color]",
				),
				array(
					"label"    => "Button BG Color(hover)",
					"selector" => 'button',
					"property" => 'background-color',
					"value"    => "[color]",
					"hover"    => 1
				),
				array(
					"label"    => "Button Border Color",
					"selector" => 'button',
					"property" => 'border-color',
					"value"    => "[color]",
				),
				array(
					"label"    => "Button Border Color (hover)",
					"selector" => 'button',
					"hover"    => 1,
					"property" => 'border-color',
					"value"    => "[color]",
				),
			),
			"Minimal" => array(
				array(
					"label"    => "Inputs Text Color",
					"selector" => 'input[type="text"],textarea,select,input[type="email"]',
					"property" => 'color',
					"value"    => "[color]",
				),
				array(
					"label"    => "Inputs Border Color",
					"selector" => 'input[type="text"],textarea,select,input[type="email"]',
					"property" => 'border-color',
					"value"    => "[color]",
				),
				array(
					"label"    => "Inputs Border Color(hover)",
					"selector" => 'input[type="text"],textarea,select,input[type="email"]',
					"property" => 'border-color',
					"value"    => "[color]",
					'hover'    => 1
				),
				array(
					"label"    => "Label colour",
					"selector" => 'label',
					"property" => 'color',
					"value"    => "[color]",
				),
				array(
					"label"    => "Button Text Color",
					"selector" => 'button',
					"property" => 'color',
					"value"    => "[color]",
				),
				array(
					"label"    => "Button Text Shadow Color",
					"selector" => "button",
					"property" => "text-shadow",
					"value"    => "0 1px 0 [color]"
				),
				array(
					"label"    => "Button Shadow Color",
					'opacity'  => 1,
					"selector" => "button",
					"property" => "box-shadow",
					"value"    => "0 3px 3px 1px [color]"
				),
				array(
					"label"    => "Button BG Color",
					"selector" => 'button',
					"property" => 'background-color',
					"value"    => "[color]",
				),
				array(
					"label"    => "Button BG Color(hover)",
					"selector" => 'button',
					"property" => 'background-color',
					"value"    => "[color]",
					"hover"    => 1
				),
				array(
					"label"    => "Button Border Color",
					"selector" => 'button',
					"property" => 'border-color',
					"value"    => "[color]",
				),
				array(
					"label"    => "Button Border Color (hover)",
					"selector" => 'button',
					"hover"    => 1,
					"property" => 'border-color',
					"value"    => "[color]",
				),
			),
		)
	),
	"contents_table"           => array(
		"1" => array(
			"Flat"    => array(
				0 => array(
					"label"    => "Background color",
					'opacity'  => 1,
					"selector" => ".tve_contents_table",
					"property" => "background-color",
					"value"    => "[color]"
				),
				1 => array(
					"label"    => "Header background color",
					'opacity'  => 1,
					"selector" => ".tve_ct_title",
					"property" => "background-color",
					"value"    => "[color]"
				),
				2 => array(
					"label"    => "Border color",
					"selector" => ".tve_contents_table",
					"property" => "border",
					"value"    => "1px solid [color]"
				),
				3 => array(
					"label"           => "Link color",
					"selector"        => ".tve_contents_table",
					'selector_suffix' => ' a',
					"property"        => "color",
					"value"           => "[color]"
				),
			),
			"Classy"  => array(
				0 => array(
					"label"    => "Background color",
					"selector" => ".tve_contents_table",
					"property" => "background-image",
					"value"    => "gradient"
				),
				1 => array(
					"label"    => "Header background color",
					"selector" => ".tve_ct_title",
					"property" => "background-image",
					"value"    => "gradient"
				),
				2 => array(
					"label"    => "Border color",
					"selector" => ".tve_contents_table",
					"property" => "border",
					"value"    => "1px solid [color]"
				),
				3 => array(
					"label"    => "Link color",
					"selector" => ".tve_contents_table a",
					"property" => "color",
					"value"    => "[color]"
				)
			),
			"Minimal" => array(
				0 => array(
					"label"    => "Background color",
					'opacity'  => 1,
					"selector" => ".tve_ct_content",
					"property" => "background-color",
					"value"    => "[color]"
				),
				1 => array(
					"label"    => "Header background color",
					'opacity'  => 1,
					"selector" => ".tve_ct_title",
					"property" => "background-color",
					"value"    => "[color]"
				),
				2 => array(
					"label"    => "Border color",
					"selector" => ".tve_ct_content",
					"property" => "border",
					"value"    => "1px solid [color]"
				),
				3 => array(
					"label"    => "Link color",
					"selector" => ".tve_contents_table a",
					"property" => "color",
					"value"    => "[color]"
				)
			),
		)
	),
	"guarantee"                => array(
		"1" => array(
			"Flat"    => array(
				0 => array(
					"label"    => "Background",
					'opacity'  => 1,
					"selector" => ".tve_fg",
					"property" => "background-color",
					"value"    => "[color]"
				),
				1 => array(
					"label"    => "Border",
					"selector" => ".tve_fg",
					"property" => "border",
					"value"    => "1px solid [color]"
				),
				2 => array(
					"label"    => "Divider",
					'opacity'  => 1,
					"selector" => "hr",
					"property" => "background-color",
					"value"    => "[color]"
				)
			),
			"Classy"  => array(
				0 => array(
					"label"    => "Background",
					'opacity'  => 1,
					"selector" => ".tve_fg",
					"property" => "background-color",
					"value"    => "[color]"
				),
				1 => array(
					"label"    => "Border",
					"selector" => ".tve_fg",
					"property" => "border",
					"value"    => "1px solid [color]"
				),
				2 => array(
					"label"    => "Divider",
					"selector" => "hr",
					"property" => "background-color",
					"value"    => "[color]"
				)
			),
			"Minimal" => array(
				0 => array(
					"label"    => "Background",
					'opacity'  => 1,
					"selector" => ".tve_fg",
					"property" => "background-color",
					"value"    => "[color]"
				),
				1 => array(
					"label"    => "Border",
					"selector" => ".tve_fg",
					"property" => "border",
					"value"    => "1px solid [color]"
				),
			)
		),
		"2" => array(
			"Flat"    => array(
				0 => array(
					"label"    => "Background",
					'opacity'  => 1,
					"selector" => ".tve_fg",
					"property" => "background-color",
					"value"    => "[color]"
				),
				1 => array(
					"label"    => "Border",
					"selector" => ".tve_fg",
					"property" => "border",
					"value"    => "1px solid [color]"
				),
				2 => array(
					"label"    => "Divider",
					'opacity'  => 1,
					"selector" => "hr",
					"property" => "background-color",
					"value"    => "[color]"
				)
			),
			"Classy"  => array(
				0 => array(
					"label"    => "Background",
					'opacity'  => 1,
					"selector" => ".tve_fg",
					"property" => "background-color",
					"value"    => "[color]"
				),
				1 => array(
					"label"    => "Border",
					"selector" => ".tve_fg",
					"property" => "border",
					"value"    => "1px solid [color]"
				),
				2 => array(
					"label"    => "Divider",
					'opacity'  => 1,
					"selector" => "hr",
					"property" => "background-color",
					"value"    => "[color]"
				)
			),
			"Minimal" => array(
				0 => array(
					"label"    => "Background",
					'opacity'  => 1,
					"selector" => ".tve_fg",
					"property" => "background-color",
					"value"    => "[color]"
				),
				1 => array(
					"label"    => "Border",
					"selector" => ".tve_fg",
					"property" => "border",
					"value"    => "1px solid [color]"
				),
			)
		),
		"3" => array(
			"Flat"    => array(
				0 => array(
					"label"    => "Background",
					'opacity'  => 1,
					"selector" => ".tve_fg",
					"property" => "background-color",
					"value"    => "[color]"
				),
				1 => array(
					"label"    => "Border",
					"selector" => ".tve_fg",
					"property" => "border",
					"value"    => "1px solid [color]"
				),
				2 => array(
					"label"    => "Ribbon Background",
					'opacity'  => 1,
					"selector" => ".tve_line",
					"property" => "background-color",
					"value"    => "[color]"
				),
			),
			"Classy"  => array(
				0 => array(
					"label"    => "Background",
					"selector" => ".tve_fg",
					"property" => "background-image",
					"value"    => "gradient"
				),
				1 => array(
					"label"    => "Border",
					"selector" => ".tve_fg",
					"property" => "border",
					"value"    => "1px solid [color]"
				),
				2 => array(
					"label"    => "Ribbon Background",
					'opacity'  => 1,
					"selector" => ".tve_line",
					"property" => "background-color",
					"value"    => "[color]"
				),
				3 => array(
					"label"    => "Ribbon Border",
					"selector" => ".tve_line",
					"property" => "border-bottom-color",
					"value"    => "[color]"
				),
			),
			"Minimal" => array(
				0 => array(
					"label"    => "Background",
					'opacity'  => 1,
					"selector" => ".tve_fg",
					"property" => "background-color",
					"value"    => "[color]"
				),
				1 => array(
					"label"    => "Border",
					"selector" => ".tve_fg",
					"property" => "border",
					"value"    => "1px solid [color]"
				),
			)
		),
		"4" => array(
			"Flat"    => array(
				0 => array(
					"label"    => "Background",
					'opacity'  => 1,
					"selector" => ".tve_fg",
					"property" => "background-color",
					"value"    => "[color]"
				),
				1 => array(
					"label"    => "Border",
					"selector" => ".tve_fg",
					"property" => "border",
					"value"    => "1px solid [color]"
				),
				2 => array(
					"label"    => "Ribbon Background",
					'opacity'  => 1,
					"selector" => ".tve_line",
					"property" => "background-color",
					"value"    => "[color]"
				),
			),
			"Classy"  => array(
				0 => array(
					"label"    => "Background",
					"selector" => ".tve_fg",
					"property" => "background-image",
					"value"    => "gradient"
				),
				1 => array(
					"label"    => "Border",
					"selector" => ".tve_fg",
					"property" => "border",
					"value"    => "1px solid [color]"
				),
				2 => array(
					"label"    => "Ribbon Background",
					'opacity'  => 1,
					"selector" => ".tve_line",
					"property" => "background-color",
					"value"    => "[color]"
				),
				3 => array(
					"label"    => "Ribbon Border",
					"selector" => ".tve_line",
					"property" => "border-bottom-color",
					"value"    => "[color]"
				),
			),
			"Minimal" => array(
				0 => array(
					"label"    => "Background",
					'opacity'  => 1,
					"selector" => ".tve_fg",
					"property" => "background-color",
					"value"    => "[color]"
				),
				1 => array(
					"label"    => "Border",
					"selector" => ".tve_fg",
					"property" => "border",
					"value"    => "1px solid [color]"
				),
			)
		)
	),
	"pricing_table"            => array(
		"1" => array(
			"Flat"    => array(
				0 => array(
					"label"    => "Main Column Background",
					'opacity'  => 1,
					"selector" => ".tve_prt_in:not(.tve_prt_col.tve_hgh .tve_prt_in)",
					"property" => "background-color",
					"value"    => "[color]"
				),
				1 => array(
					"label"    => "Highlighted Column Background",
					'opacity'  => 1,
					"selector" => ".tve_prt_col.tve_hgh .tve_prt_in",
					"property" => "background-color",
					"value"    => "[color]"
				),
				2 => array(
					"label"    => "Main Column Divider",
					"selector" => ".tve_prt_in .tve_ftr:not(.tve_prt_col.tve_hgh .tve_prt_in .tve_ftr)",
					"property" => "border-color",
					"value"    => "[color]"
				),
				3 => array(
					"label"    => "Highlighted Column Divider",
					"selector" => ".tve_prt_col.tve_hgh .tve_prt_in .tve_ftr",
					"property" => "border-color",
					"value"    => "[color]"
				),
				4 => array(
					"label"    => "Title Shadow",
					"selector" => ".tve_prt_col:not(.tve_hgh) .tve_prt_in .tve_ctr h2",
					"property" => "text-shadow",
					"value"    => "0 1px 0 [color]"
				),
				5 => array(
					"label"    => "Highlighted Title Shadow",
					"selector" => ".tve_prt_col.tve_hgh .tve_prt_in .tve_ctr h2",
					"property" => "text-shadow",
					"value"    => "0 1px 0 [color]"
				)
			),
			"Classy"  => array(
				0 => array(
					"label"    => "Main Column Background",
					"selector" => ".tve_prt_in:not(.tve_hgh .tve_prt_in)",
					"property" => "background-image",
					"value"    => "gradient"
				),
				1 => array(
					"label"    => "Highlighted Column Background",
					"selector" => ".tve_hgh .tve_prt_in",
					"property" => "background-image",
					"value"    => "gradient"
				),
				2 => array(
					"label"    => "Main Column Divider",
					"selector" => ".tve_prt_in .tve_ftr:not(.tve_prt_col.tve_hgh .tve_prt_in .tve_ftr)",
					"property" => "border-color",
					"value"    => "[color]"
				),
				3 => array(
					"label"    => "Highlighted Column Divider",
					"selector" => ".tve_prt_col.tve_hgh .tve_prt_in .tve_ftr",
					"property" => "border-color",
					"value"    => "[color]"
				),
				4 => array(
					"label"    => "Main Column Text Shadow",
					"selector" => ".tve_prt_in h2:not(.tve hgh .tve_prt_in h2)",
					"property" => "text-shadow",
					"value"    => "0 1px 0 [color]"
				),
				5 => array(
					"label"    => "Highlighted Col. Text Shadow",
					"selector" => ".tve_prt_col.tve_hgh .tve_prt_in h2",
					"property" => "text-shadow",
					"value"    => "0 1px 0 [color]"
				),
			),
			"Minimal" => array(
				0 => array(
					"label"    => "Main Column Background",
					'opacity'  => 1,
					"selector" => ".tve_prt_in:not(.tve_hgh .tve_prt_in)",
					"property" => "background-color",
					"value"    => "[color]"
				),
				1 => array(
					"label"    => "Highlighted Column Background",
					'opacity'  => 1,
					"selector" => ".tve_hgh .tve_prt_in",
					"property" => "background-color",
					"value"    => "[color]"
				),
				2 => array(
					"label"    => "Main Column Border",
					"selector" => ".tve_prt_in:not(.tve_hgh .tve_prt_in)",
					"property" => "border-color",
					"value"    => "[color]"
				),
				3 => array(
					"label"    => "Highlighted Column Border",
					"selector" => ".tve_hgh .tve_prt_in",
					"property" => "border-color",
					"value"    => "[color]"
				),
				4 => array(
					"label"    => "Main Column Text Shadow",
					"selector" => ".tve_prt_in h2:not(.tve hgh .tve_prt_in h2)",
					"property" => "text-shadow",
					"value"    => "0 1px 0 [color]"
				),
				5 => array(
					"label"    => "Highlighted Column Text Shadow",
					"selector" => ".tve_prt_col.tve_hgh .tve_prt_in h2",
					"property" => "text-shadow",
					"value"    => "0 1px 0 [color]"
				),
			)
		)
	),
	"page_section"             => array(
		"1" => array(
			"Flat"    => array(
				array(
					"label"    => "Background colour",
					"selector" => ".out",
					'opacity'  => 1,
					"property" => "background-color",
					"value"    => "[color]",
				),
				array(
					"label"    => "Internal shadow color",
					'opacity'  => 1,
					'inset'    => 1,
					"selector" => ".out",
					"property" => "box-shadow",
					"value"    => "box-shadow-all"
				),
				array(
					"label"    => "External shadow color",
					'opacity'  => 1,
					"selector" => ".out",
					"property" => "box-shadow",
					"value"    => "box-shadow-all"
				),
				array(
					"label"    => "Border color",
					'opacity'  => 1,
					"selector" => ".out",
					"property" => "border-color",
					"value"    => "[color]"
				),
			),
			"Classy"  => array(
				array(
					"label"    => "Background colour",
					"selector" => ".out",
					"property" => "background-color",
					"value"    => "[color]",
					'opacity'  => 1
				),
				array(
					"label"    => "Internal shadow color",
					'opacity'  => 1,
					'inset'    => 1,
					"selector" => ".out",
					"property" => "box-shadow",
					"value"    => "box-shadow-all"
				),
				array(
					"label"    => "External shadow color",
					'opacity'  => 1,
					"selector" => ".out",
					"property" => "box-shadow",
					"value"    => "box-shadow-all"
				),
				array(
					"label"    => "Border color",
					'opacity'  => 1,
					"selector" => ".out",
					"property" => "border-color",
					"value"    => "[color]"
				),
			),
			"Minimal" => array(
				array(
					"label"    => "Background colour",
					"selector" => ".out",
					"property" => "background-color",
					"value"    => "[color]",
					'opacity'  => 1
				),
				array(
					"label"    => "Internal shadow color",
					'opacity'  => 1,
					'inset'    => 1,
					"selector" => ".out",
					"property" => "box-shadow",
					"value"    => "box-shadow-all"
				),
				array(
					"label"    => "External shadow color",
					'opacity'  => 1,
					"selector" => ".out",
					"property" => "box-shadow",
					"value"    => "box-shadow-all"
				),
				array(
					"label"    => "Border color",
					'opacity'  => 1,
					"selector" => ".out",
					"property" => "border-color",
					"value"    => "[color]"
				),
			)
		)
	),
	"tabs"                     => array(
		"undefined" => array(
			"Flat"    => array(
				0 => array(
					"label"    => "Active Tab Background",
					"selector" => "li.tve_tS",
					"property" => "background-color",
					"value"    => "[color]"
				),
				1 => array(
					"label"    => "Active Tab Border",
					"selector" => "li.tve_tS",
					"property" => "border-color",
					"value"    => "[color]"
				),
				2 => array(
					"label"    => "Tab Background Colour",
					"selector" => "ul.tve_clearfix > li:not(li.tve_tS)",
					"property" => "background-color",
					"value"    => "[color]"
				),
				3 => array(
					"label"    => "Content Background",
					"selector" => ".tve_scTC",
					"property" => "background-color",
					"value"    => "[color]"
				),
				4 => array(
					"label"    => "Content Border",
					"selector" => ".tve_scTC",
					"property" => "border-color",
					"value"    => "[color]"
				),
			),
			"Classy"  => array(
				0 => array(
					"label"    => "Active Tab Background",
					"selector" => "li.tve_tS",
					"property" => "background-image",
					"value"    => "gradient"
				),
				1 => array(
					"label"    => "Active Tab Border",
					"selector" => "li.tve_tS",
					"property" => "border-color",
					"value"    => "[color]"
				),
				2 => array(
					"label"    => "Tab Background",
					"selector" => "ul.tve_clearfix > li:not(li.tve_tS)",
					"property" => "background-color",
					"value"    => "[color]"
				),
				3 => array(
					"label"    => "Content Background",
					"selector" => ".tve_scTC",
					"property" => "background-color",
					"value"    => "[color]"
				),
				4 => array(
					"label"    => "Content Border",
					"selector" => ".tve_scTC",
					"property" => "border-color",
					"value"    => "[color]"
				),
			),
			"Minimal" => array(
				0 => array(
					"label"    => "Active Tab Background",
					"selector" => "li.tve_tS",
					"property" => "background-color",
					"value"    => "[color]"
				),
				1 => array(
					"label"    => "Active Tab Border",
					"selector" => "li.tve_tS",
					"property" => "border-color",
					"value"    => "[color]"
				),
				2 => array(
					"label"    => "Standard Tab Background",
					"selector" => "ul.tve_clearfix > li:not(li.tve_tS)",
					"property" => "background-color",
					"value"    => "[color]"
				),
				3 => array(
					"label"    => "Content Background",
					"selector" => ".tve_scTC",
					"property" => "background-color",
					"value"    => "[color]"
				),
				4 => array(
					"label"    => "Content Border",
					"selector" => ".tve_scTC",
					"property" => "border-color",
					"value"    => "[color]"
				),
			),
		)
	),
	"table"                    => array(
		"undefined" => array(
			"Flat"    => array(
				array(
					"label"    => "Table border color",
					"selector" => "> .tve_table",
					"property" => "border-color",
					"value"    => "[color]"
				),
				array(
					"label"    => "Heading background",
					"selector" => "> .tve_table thead tr th",
					"property" => "background-color",
					"value"    => "[color]"
				),
				array(
					"label"    => "Heading border",
					"selector" => "> .tve_table thead tr th",
					"property" => "border-color",
					"value"    => "[color]"
				),
				array(
					"label"    => "Cell background",
					"selector" => "> .tve_table tbody tr td",
					"property" => "background-color",
					"value"    => "[color]"
				),
				array(
					"label"           => "Cell border",
					"selector"        => "> .tve_table tbody tr td",
					"property"        => "border-color",
					'selector_prefix' => '#tve_editor ',
					"value"           => "[color]"
				),
				array(
					"label"           => "Odd rows color",
					"selector"        => "> .tve_table tbody",
					"selector_suffix" => " > tr:nth-child(2n+1) > td", /* the real selector (this will get appended to the CSS rule */
					'selector_prefix' => '#tve_editor ',
					'important'       => false,
					"property"        => "background-color",
					"value"           => "[color]",
				),
				array(
					"label"           => "Even rows color",
					"selector"        => "> .tve_table tbody",
					"selector_suffix" => " > tr:nth-child(2n) > td", /* the real selector (this will get appended to the CSS rule */
					'selector_prefix' => '#tve_editor ',
					'important'       => false,
					"property"        => "background-color",
					"value"           => "[color]",
				),
			),
			"Classy"  => array(
				array(
					"label"    => "Table border color",
					"selector" => "> .tve_table",
					"property" => "border-color",
					"value"    => "[color]"
				),
				array(
					"label"    => "Heading background",
					"selector" => "> .tve_table thead tr th",
					"property" => "background-color",
					"value"    => "[color]"
				),
				array(
					"label"    => "Heading border",
					"selector" => "> .tve_table thead tr th",
					"property" => "border-color",
					"value"    => "[color]"
				),
				array(
					"label"    => "Cell background",
					"selector" => "> .tve_table tbody tr td",
					"property" => "background-color",
					"value"    => "[color]"
				),
				array(
					"label"    => "Cell border",
					"selector" => "> .tve_table tbody tr td",
					"property" => "border-color",
					"value"    => "[color]"
				),
				array(
					"label"           => "Odd rows color",
					"selector"        => "> .tve_table tbody",
					"selector_suffix" => " > tr:nth-child(2n+1) > td", /* the real selector (this will get appended to the CSS rule */
					'selector_prefix' => '#tve_editor ',
					'important'       => false,
					"property"        => "background-color",
					"value"           => "[color]",
				),
				array(
					"label"           => "Even rows color",
					"selector"        => "> .tve_table tbody",
					"selector_suffix" => " > tr:nth-child(2n) > td", /* the real selector (this will get appended to the CSS rule */
					'selector_prefix' => '#tve_editor ',
					'important'       => false,
					"property"        => "background-color",
					"value"           => "[color]",
				),
			),
			"Minimal" => array(
				array(
					"label"    => "Table border color",
					"selector" => "> .tve_table",
					"property" => "border-color",
					"value"    => "[color]"
				),
				array(
					"label"    => "Heading background",
					"selector" => "> .tve_table thead tr th",
					"property" => "background-color",
					"value"    => "[color]"
				),
				array(
					"label"    => "Heading border",
					"selector" => "> .tve_table thead tr th",
					"property" => "border-color",
					"value"    => "[color]"
				),
				array(
					"label"    => "Cell background",
					"selector" => "> .tve_table tbody tr td",
					"property" => "background-color",
					"value"    => "[color]"
				),
				array(
					"label"    => "Cell border",
					"selector" => "> .tve_table tbody tr td",
					"property" => "border-color",
					"value"    => "[color]"
				),
				array(
					"label"           => "Odd rows color",
					"selector"        => "> .tve_table tbody",
					"selector_suffix" => " > tr:nth-child(2n+1) > td", /* the real selector (this will get appended to the CSS rule */
					'selector_prefix' => '#tve_editor ',
					'important'       => false,
					"property"        => "background-color",
					"value"           => "[color]",
				),
				array(
					"label"           => "Even rows color",
					"selector"        => "> .tve_table tbody",
					"selector_suffix" => " > tr:nth-child(2n) > td", /* the real selector (this will get appended to the CSS rule */
					'selector_prefix' => '#tve_editor ',
					'important'       => false,
					"property"        => "background-color",
					"value"           => "[color]",
				),
			)
		)
	),
	"table_cell"               => array(
		"undefined" => array(
			"Flat"    => array(
				array(
					"label"              => "Cell background",
					"selector"           => "",
					"property"           => "background-color",
					"value"              => "[color]",
					'generate_unique_id' => 1, // we must generate each time a new unique id for the cell background / border
				),
				array(
					"label"              => "Cell border",
					"selector"           => "",
					"property"           => "border-color",
					"value"              => "[color]",
					'generate_unique_id' => 1, // we must generate each time a new unique id for the cell background / border
				),
			),
			"Classy"  => array(
				array(
					"label"              => "Cell background",
					"selector"           => "",
					"property"           => "background-color",
					"value"              => "[color]",
					'generate_unique_id' => 1, // we must generate each time a new unique id for the cell background / border
				),
				array(
					"label"              => "Cell border",
					"selector"           => "",
					"property"           => "border-color",
					"value"              => "[color]",
					'generate_unique_id' => 1, // we must generate each time a new unique id for the cell background / border
				),
			),
			"Minimal" => array(
				array(
					"label"              => "Cell background",
					"selector"           => "",
					"property"           => "background-color",
					"value"              => "[color]",
					'generate_unique_id' => 1, // we must generate each time a new unique id for the cell background / border
				),
				array(
					"label"              => "Cell border",
					"selector"           => "",
					"property"           => "border-color",
					"value"              => "[color]",
					'generate_unique_id' => 1, // we must generate each time a new unique id for the cell background / border
				),
			)
		)
	),
	"img"                      => array(
		"undefined" => array(
			"Flat"    => array(
				array(
					"label"              => "Border color",
					"selector"           => "",
					"property"           => "border-color",
					"value"              => "[color]",
					'generate_unique_id' => 1,
				),
			),
			"Classy"  => array(
				array(
					"label"              => "Border color",
					"selector"           => "",
					"property"           => "border-color",
					"value"              => "[color]",
					'generate_unique_id' => 1,
				),
			),
			"Minimal" => array(
				array(
					"label"              => "Border color",
					"selector"           => "",
					"property"           => "border-color",
					"value"              => "[color]",
					'generate_unique_id' => 1,
				),
			)
		)
	),
	'lightbox'                 => array(
		'undefined' => array(
			'all' => array(
				array(
					'label'              => 'Background color',
					'selector'           => '.tve_p_lb_content',
					'search_outside'     => 1, // global selector, do not search inside edit_mode
					'property'           => 'background-color',
					'global_save_key'    => 'l_cb', // lightbox_content_background
					'value'              => '[color]',
					'opacity'            => 1,
					'generate_unique_id' => 1,
				),
				array(
					'label'              => 'Overlay color',
					'selector'           => '.tve_p_lb_overlay',
					'search_outside'     => 1,
					'global_save_key'    => 'l_ob', // lightbox_overlay_background
					'property'           => 'background-color',
					'value'              => '[color]',
					'generate_unique_id' => 1,
				),
				array(
					'label'              => 'Border color',
					'selector'           => '.tve_p_lb_content',
					'search_outside'     => 1,
					'property'           => 'border-color',
					'global_save_key'    => 'l_cb', // lightbox_content_border
					'value'              => '[color]',
					'generate_unique_id' => 1,
				),
				array(
					'label'           => 'Close icon color',
					'selector'        => '.tve_p_lb_close',
					'search_outside'  => 1,
					'property'        => 'color',
					'global_save_key' => 'l_ccc', // close custom color
					'value'           => '[color]',
				),
				array(
					'label'           => 'Close icon background',
					'selector'        => '.tve_p_lb_close',
					'search_outside'  => 1,
					'property'        => 'background-color',
					'opacity'         => 1,
					'global_save_key' => 'l_ccc', // close custom color
					'value'           => '[color]',
				),
				array(
					'label'           => 'Close icon border',
					'selector'        => '.tve_p_lb_close',
					'search_outside'  => 1,
					'property'        => 'border-color',
					'opacity'         => 1,
					'global_save_key' => 'l_ccc', // close custom color
					'value'           => '[color]',
				)
			),
		)
	),
	'landing_page'             => array(
		'undefined' => array(
			"Flat"    => array(
				array(
					"label"              => "Background color",
					"selector"           => "body",
					'search_outside'     => 1, // global selector, do not search inside edit_mode
					"property"           => "background-color",
					'global_save_key'    => 'lp_bg', // Landing page BG
					"value"              => "[color]",
					'generate_unique_id' => 1,
				),
			),
			"Classy"  => array(
				array(
					"label"              => "Background color",
					"selector"           => "body",
					'search_outside'     => 1, // global selector, do not search inside edit_mode
					"property"           => "background-color",
					'global_save_key'    => 'lp_bg', // Landing page BG
					"value"              => "[color]",
					'generate_unique_id' => 1,
				),
			),
			"Minimal" => array(
				array(
					"label"              => "Background color",
					"selector"           => "body",
					'search_outside'     => 1, // global selector, do not search inside edit_mode
					"property"           => "background-color",
					'global_save_key'    => 'lp_bg', // Landing page BG
					"value"              => "[color]",
					'generate_unique_id' => 1,
				),
			)
		)
	),
	'landing_page_content'     => array(
		'undefined' => array(
			"Flat"    => array(
				array(
					"label"              => "Background color",
					'opacity'            => 1,
					"selector"           => ".tve_lp_content",
					'search_outside'     => 1, // global selector, do not search inside edit_mode
					"property"           => "background-color",
					"value"              => "[color]",
					'generate_unique_id' => 1,
				),
			),
			"Classy"  => array(
				array(
					"label"              => "Background color",
					'opacity'            => 1,
					"selector"           => ".tve_lp_content",
					'search_outside'     => 1, // global selector, do not search inside edit_mode
					"property"           => "background-color",
					"value"              => "[color]",
					'generate_unique_id' => 1,
				),
			),
			"Minimal" => array(
				array(
					"label"              => "Background color",
					'opacity'            => 1,
					"selector"           => ".tve_lp_content",
					'search_outside'     => 1, // global selector, do not search inside edit_mode
					"property"           => "background-color",
					"value"              => "[color]",
					'generate_unique_id' => 1,
				),
			)
		)
	),
	'icon'                     => array(
		'undefined' => array(
			"Flat"    => array(
				array(
					"label"    => "Color",
					"selector" => "span.tve_sc_icon",
					"property" => "color",
					"value"    => "[color]",
				),
				array(
					"label"    => "Color (mouseover)",
					"selector" => "span.tve_sc_icon",
					"property" => "color",
					"value"    => "[color]",
					'hover'    => 1
				),
				array(
					"label"    => "Border Color",
					"selector" => "",
					"property" => "border-color",
					"value"    => "[color]",
					'opacity'  => 1,
				),
				array(
					'label'    => 'Background Color',
					'selector' => 'span.tve_sc_icon',
					'property' => 'background-color',
					'value'    => '[color]',
					'opacity'  => 1
				),
			),
			"Classy"  => array(
				array(
					"label"    => "Color",
					"selector" => "span.tve_sc_icon",
					"property" => "color",
					"value"    => "[color]",
				),
				array(
					"label"    => "Color (mouseover)",
					"selector" => "span.tve_sc_icon",
					"property" => "color",
					"value"    => "[color]",
					'hover'    => 1
				),
				array(
					"label"    => "Border Color",
					"selector" => "",
					"property" => "border-color",
					"value"    => "[color]",
					'opacity'  => 1,
				),
				array(
					'label'    => 'Background Color',
					'selector' => 'span.tve_sc_icon',
					'property' => 'background-image',
					'value'    => 'gradient',
				),
			),
			"Minimal" => array(
				array(
					"label"    => "Color",
					"selector" => "span.tve_sc_icon",
					"property" => "color",
					"value"    => "[color]",
				),
				array(
					"label"    => "Color (mouseover)",
					"selector" => "span.tve_sc_icon",
					"property" => "color",
					"value"    => "[color]",
					'hover'    => 1
				),
				array(
					"label"    => "Border Color",
					"selector" => "",
					"property" => "border-color",
					"value"    => "[color]",
					'opacity'  => 1,
				),
				array(
					'label'    => 'Background Color',
					'selector' => 'span.tve_sc_icon',
					'property' => 'background-color',
					'value'    => '[color]',
					'opacity'  => 1
				),
			)
		)
	),
	'landing_fonts'            => array(
		'undefined' => array(
			'Flat' => array(
				array(
					"label"          => "H1 Color",
					"selector"       => "",
					"force_selector" => '#tve_editor h1',
					"property"       => "color",
					"value"          => "[color]",
				),
				array(
					"label"          => "H2 Color",
					"selector"       => "",
					"force_selector" => '#tve_editor h2',
					"property"       => "color",
					"value"          => "[color]",
				),
				array(
					"label"          => "H3 Color",
					"selector"       => "",
					"force_selector" => '#tve_editor h3',
					"property"       => "color",
					"value"          => "[color]",
				),
				array(
					"label"          => "Paragraph Color",
					"selector"       => "",
					"force_selector" => '#tve_editor p',
					"property"       => "color",
					"value"          => "[color]",
				)
			),
		)
	),
	'widget_menu'              => array(
		'tve_vertical'   => array(
			'all' => array(
				array(
					"label"    => "Item color",
					"selector" => "ul.tve_w_menu li a",
					"property" => "color",
					"value"    => "[color]",
					'save_key' => 'link_attr',
				),
				array(
					"label"    => "Item color (mouseover)",
					"selector" => "ul.tve_w_menu li a", // element that receives the [tve-custom-colour] attribute
					"property" => "color",
					"value"    => "[color]",
					'hover'    => 1,
					'save_key' => 'link_attr',
				),
			),
		),
		'tve_horizontal' => array(
			'all' => array(
				array(
					"label"    => "Main Item color",
					"selector" => "ul.tve_w_menu > li > a",
					"property" => "color",
					"value"    => "[color]",
					'save_key' => 'top_link_attr',
				),
				array(
					"label"    => "Main Item color (mouseover)",
					"selector" => "ul.tve_w_menu > li > a", // element that receives the [tve-custom-colour] attribute
					"property" => "color",
					"value"    => "[color]",
					'hover'    => 1,
					'save_key' => 'top_link_attr',
				),
				array(
					"label"    => "Sub-item color",
					"selector" => "ul.tve_w_menu li li a",
					"property" => "color",
					"value"    => "[color]",
					'save_key' => 'link_attr',
				),
				array(
					"label"    => "Sub-item color (mouseover)",
					"selector" => "ul.tve_w_menu li li a", // element that receives the [tve-custom-colour] attribute
					"property" => "color",
					"value"    => "[color]",
					'hover'    => 1,
					'save_key' => 'link_attr',
				),
				array(
					'label'    => 'Sub-item background',
					'selector' => 'ul.tve_w_menu li li a',
					'property' => 'background-color',
					'value'    => '[color]',
					'opacity'  => 1,
					'save_key' => 'link_attr',
				),
				array(
					'label'    => 'Sub-item bg. (mouseover)',
					"selector" => "ul.tve_w_menu li li a", // element that receives the [tve-custom-colour] attribute
					'property' => 'background-color',
					'value'    => '[color]',
					'save_key' => 'link_attr',
					'hover'    => 1,
					'opacity'  => 1
				),
				array(
					'label'    => 'Trigger color (small screens)',
					"selector" => ".tve-m-trigger", // element that receives the [tve-custom-colour] attribute
					'property' => 'color',
					'value'    => '[color]',
					'save_key' => 'trigger_attr'
				),
			),
		)
	),
	"progress_bar"             => array(
		"1" => array(
			"Flat"    => array(
				array(
					"label"    => "Background Colour",
					"selector" => ".tve_progress_bar",
					"property" => "background-color",
					"value"    => "[color]",
					'opacity'  => 1
				),
				array(
					"label"    => "Fill Colour",
					"selector" => ".tve_progress_bar_fill",
					"property" => "background-color",
					"value"    => "[color]",
					'opacity'  => 1
				),
				array(
					"label"    => "Label Text Colour",
					"selector" => ".tve_data_element_label",
					"property" => "color",
					"value"    => "[color]"
				),
				array(
					"label"    => "Label Text Shadow",
					"selector" => ".tve_data_element_label",
					"property" => "text-shadow",
					"value"    => "0 1px 0 [color]"
				),
				array(
					"label"    => "Border",
					"selector" => "",
					"property" => "border-color",
					"value"    => "[color]",
					'opacity'  => 1,
				)
			),
			"Classy"  => array(
				array(
					"label"    => "Background Colour",
					"selector" => ".tve_progress_bar",
					"property" => "background-color",
					"value"    => "[color]",
					'opacity'  => 1
				),
				array(
					"label"    => "Fill Colour",
					"selector" => ".tve_progress_bar_fill",
					"property" => "background-color",
					"value"    => "[color]",
					'opacity'  => 1
				),
				array(
					"label"    => "Label Text Colour",
					"selector" => ".tve_data_element_label",
					"property" => "color",
					"value"    => "[color]"
				),
				array(
					"label"    => "Label Text Shadow",
					"selector" => ".tve_data_element_label",
					"property" => "text-shadow",
					"value"    => "0 1px 0 [color]"
				),
				array(
					"label"    => "Border",
					"selector" => "",
					"property" => "border-color",
					"value"    => "[color]",
					'opacity'  => 1,
				)
			),
			"Minimal" => array(
				array(
					"label"    => "Background Colour",
					"selector" => ".tve_progress_bar",
					"property" => "background-color",
					"value"    => "[color]",
					'opacity'  => 1
				),
				array(
					"label"    => "Fill Colour",
					"selector" => ".tve_progress_bar_fill",
					"property" => "background-color",
					"value"    => "[color]",
					'opacity'  => 1
				),
				array(
					"label"    => "Label Text Colour",
					"selector" => ".tve_data_element_label",
					"property" => "color",
					"value"    => "[color]"
				),
				array(
					"label"    => "Label Text Shadow",
					"selector" => ".tve_data_element_label",
					"property" => "text-shadow",
					"value"    => "0 1px 0 [color]"
				),
				array(
					"label"    => "Border",
					"selector" => "",
					"property" => "border-color",
					"value"    => "[color]",
					'opacity'  => 1,
				)
			)
		)
	),
	"fill_counter"             => array(
		"1" => array(
			"Flat"    => array(
				array(
					"label"    => "Outer Circle Color",
					"selector" => ".tve_fill_counter_circle circle",
					"property" => "stroke",
					"value"    => "[color]"
				),
				array(
					"label"    => "Inner Circle Background",
					"selector" => ".tve_fill_text_in",
					"property" => "background-color",
					"value"    => "[color]"
				),
				array(
					"label"    => "Unit Text Colour",
					"selector" => ".tve_fill_text_before, .tve_fill_text_after",
					"property" => "color",
					"value"    => "[color]"
				),
				array(
					"label"    => "Value Text Colour",
					"selector" => ".tve_fill_text",
					"property" => "color",
					"value"    => "[color]"
				),
				array(
					"label"    => "Label Text Colour",
					"selector" => ".tve_data_element_label",
					"property" => "color",
					"value"    => "[color]"
				),
				array(
					"label"    => "Border",
					"selector" => "",
					"property" => "border-color",
					"value"    => "[color]",
					'opacity'  => 1,
				)
			),
			"Classy"  => array(
				array(
					"label"    => "Outer Circle Color",
					"selector" => ".tve_fill_c_in",
					"property" => "background-color",
					"value"    => "[color]"
				),
				array(
					"label"    => "Inner Circle Background",
					"selector" => ".tve_fill_text_in",
					"property" => "background-color",
					"value"    => "[color]"
				),
				array(
					"label"    => "Unit Text Colour",
					"selector" => ".tve_fill_text_before, .tve_fill_text_after",
					"property" => "color",
					"value"    => "[color]"
				),
				array(
					"label"    => "Value Text Colour",
					"selector" => ".tve_fill_text",
					"property" => "color",
					"value"    => "[color]"
				),
				array(
					"label"    => "Label Text Colour",
					"selector" => ".tve_data_element_label",
					"property" => "color",
					"value"    => "[color]"
				),
				array(
					"label"    => "Border",
					"selector" => "",
					"property" => "border-color",
					"value"    => "[color]",
					'opacity'  => 1,
				)
			),
			"Minimal" => array(
				array(
					"label"    => "Outer Circle Color",
					"selector" => ".tve_fill_c_in",
					"property" => "background-color",
					"value"    => "[color]"
				),
				array(
					"label"    => "Inner Circle Background",
					"selector" => ".tve_fill_text_in",
					"property" => "background-color",
					"value"    => "[color]"
				),
				array(
					"label"    => "Unit Text Colour",
					"selector" => ".tve_fill_text_before, .tve_fill_text_after",
					"property" => "color",
					"value"    => "[color]"
				),
				array(
					"label"    => "Value Text Colour",
					"selector" => ".tve_fill_text",
					"property" => "color",
					"value"    => "[color]"
				),
				array(
					"label"    => "Label Text Colour",
					"selector" => ".tve_data_element_label",
					"property" => "color",
					"value"    => "[color]"
				),
				array(
					"label"    => "Border",
					"selector" => "",
					"property" => "border-color",
					"value"    => "[color]",
					'opacity'  => 1,
				)
			)
		)
	),
	"number_counter"           => array(
		"1" => array(
			"Flat"    => array(
				array(
					"label"    => "Value Text Colour",
					"selector" => ".tve_numberc_text",
					"property" => "color",
					"value"    => "[color]",
				),
				array(
					"label"    => "Label Text Colour",
					"selector" => ".tve_data_element_label",
					"property" => "color",
					"value"    => "[color]",
				),
				array(
					"label"    => "Unit Text Colour",
					"selector" => ".tve_numberc_before, .tve_numberc_after",
					"property" => "color",
					"value"    => "[color]",
				)
			),
			"Classy"  => array(
				array(
					"label"    => "Value Text Colour",
					"selector" => ".tve_numberc_text",
					"property" => "color",
					"value"    => "[color]",
				),
				array(
					"label"    => "Label Text Colour",
					"selector" => ".tve_data_element_label",
					"property" => "color",
					"value"    => "[color]",
				),
				array(
					"label"    => "Unit Text Colour",
					"selector" => ".tve_numberc_before, .tve_numberc_after",
					"property" => "color",
					"value"    => "[color]",
				)
			),
			"Minimal" => array(
				array(
					"label"    => "Value Text Colour",
					"selector" => ".tve_numberc_text",
					"property" => "color",
					"value"    => "[color]",
				),
				array(
					"label"    => "Label Text Colour",
					"selector" => ".tve_data_element_label",
					"property" => "color",
					"value"    => "[color]",
				),
				array(
					"label"    => "Unit Text Colour",
					"selector" => ".tve_numberc_before, .tve_numberc_after",
					"property" => "color",
					"value"    => "[color]",
				)
			)
		)
	),
	'responsive_video'         => array(
		"undefined" => array(
			"all" => array(
				array(
					"label"    => "Video color",
					"selector" => ".tve_responsive_video_container",
					'opacity'  => 1,
					"property" => "color",
					"value"    => "[color]",
					"type"     => "video-color" //used to know which color picker color is used in the url when importing the video
				),
				array(
					"label"    => "Play button",
					"selector" => ".overlay_play_button",
					'opacity'  => 1,
					"property" => "color",
					"value"    => "[color]",
					"type"     => "play-button"
				),
				array(
					"label"    => "Hover: Play button",
					"selector" => ".overlay_play_button",
					'opacity'  => 1,
					"property" => "color",
					"value"    => "[color]",
					'hover'    => 1,
					"type"     => "play-color"
				),
				array(
					"label"    => "Play button shadow",
					"selector" => ".overlay_play_button",
					'opacity'  => 1,
					"property" => "text-shadow",
					"value"    => "0 0 11px [color]",
					"type"     => "play-shadow-color"
				),
				array(
					"label"    => "Hover: play button shadow",
					"selector" => ".overlay_play_button",
					'opacity'  => 1,
					"property" => "text-shadow",
					"value"    => "0 0 11px [color]",
					'hover'    => 1,
					"type"     => "play-video-hover-color"
				),
			)
		)
	),
	'tvo_display_testimonials' => array(
		'undefined' => array(
			'all' => array(
				array(
					"label"     => "Background color",
					"selector"  => '.tvo-apply-background',
					'opacity'   => 1,
					"property"  => "background-color",
					"value"     => "[color]",
				),
				array(
					"label"     => "Border color",
					"selector"  => '.tvo-apply-background',
					'opacity'   => 1,
					"property"  => "border",
					"value"     => "1px solid [color]",
				),
				array(
					"label"     => "Title color",
					"selector"  => '.tvo-testimonials-display h4',
					'opacity'   => 1,
					"property"  => "color",
					"value"     => "[color]",
				),
				array(
					"label"     => "Text color",
					"selector"  => '.tvo-testimonials-display p',
					'opacity'   => 1,
					"property"  => "color",
					"value"     => "[color]",
				),
				array(
					"label"     => "Quote color",
					"selector"  => '.tvo-testimonial-quote',
					'opacity'   => 1,
					"property"  => "color",
					"value"     => "[color]",
				),
				array(
					"label"     => "Name color",
					"selector"  => '.tvo-testimonial-name',
					'opacity'   => 1,
					"property"  => "color",
					"value"     => "[color]",
				),
				array(
					"label"     => "Role color",
					"selector"  => '.tvo-testimonial-role',
					'opacity'   => 1,
					"property"  => "color",
					"value"     => "[color]",
				),
				array(
					"label"     => "Info background color",
					"selector"  => '.tvo-info-background',
					'opacity'   => 1,
					"property"  => "background-color",
					"value"     => "[color]",
				),
				array(
					"label"     => "Info border color",
					"selector"  => '.tvo-info-background',
					'opacity'   => 1,
					"property"  => "border-color",
					"value"     => "[color]",
				),
				array(
					"label"     => "Info border color",
					"selector"  => '.tvo-info-border',
					'opacity'   => 1,
					"property"  => "border-color",
					"value"     => "[color]",
				),
				array(
					"label"     => "Info border color",
					"selector"  => '.tvo-info-special-bg',
					'opacity'   => 1,
					"property"  => "background",
					"value"     => "[color]",
				),
				array(
					"label"     => "Quote background",
					"selector"  => '.tvo-quote-background',
					'opacity'   => 1,
					"property"  => "background-color",
					"value"     => "[color]",
				),
				array(
					"label"     => "Separator background",
					"selector"  => '.tvo-testimonial-separator-bg',
					'opacity'   => 1,
					"property"  => "border-color",
					"value"     => "[color]",
				),
				array(
					"label"     => "Separator background",
					"selector"  => '.tvo-separator-svg-stroke',
					'opacity'   => 1,
					"property"  => "stroke",
					"value"     => "[color]",
				),
				array(
					"label"     => "Image border color",
					"selector"  => '.tvo-testimonial-image-border',
					'opacity'   => 1,
					"property"  => "box-shadow",
					"value"     => "0 0 0 6px [color]",
				),
				array(
					"label"     => "Image border color",
					"selector"  => '.tvo-testimonial-real-border',
					'opacity'   => 1,
					"property"  => "border-color",
					"value"     => "[color]",
				),
			),
		)
	)
);