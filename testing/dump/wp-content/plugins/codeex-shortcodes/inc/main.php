<?php
	/****************************
	NOTIFICATIONS
	****************************/
	
	function typo_notification( $atts, $content = null ) {
		extract(shortcode_atts(array(	
			'style' => '',
			'type' => ''
		), $atts));
		$out = "<label class='$style $type'>$content<span class='close'></span></label>";
		return $out;
	}
	add_shortcode('typo_alert', 'typo_notification');
	
	
	/****************************
	BLOCKQUOTES
	****************************/
	
	function typo_quote( $atts, $content = null ) {
		extract(shortcode_atts(array(	
			'style' => '',
			'type' => ''
		), $atts));
		$out = "<blockquote class='$style $type'><p>" . do_shortcode($content) . "</p></blockquote>";
		return $out;
	}
	add_shortcode('typo_blockquote', 'typo_quote');
	
	
	/****************************
	BUTTONS
	****************************/
	
	function typo_button( $atts, $content = null ) {
		extract(shortcode_atts(array(	
			'href' => '',
			'style' => '',
			'target' => '',
			'color' => '',
			'type' => ''
		), $atts));
		$out = "<a href='$href' class='$style $color $type' target='$target'>" . do_shortcode($content) . "</a>";
		return $out;
	}
	add_shortcode('typo_buttons', 'typo_button');
	
	
	/****************************
	UNORDERED LIST
	****************************/
	
	function typo_ulist($atts, $content=null) {
	    extract(shortcode_atts(array(
	    	'style' => ''
	    ),$atts));
	    $ret = "<ul class='$style'>" . do_shortcode($content);
	    $ret .= "</ul>";
	    return $ret;
	}
	add_shortcode('typo_ul', 'typo_ulist');

	function typo_li( $atts, $content = null ) {
	    return "<li><span class='icon'></span>" . do_shortcode($content) . '</li>';
	}
	add_shortcode('typo_li', 'typo_li');
	
	
	/****************************
	DROPCAPS
	****************************/
	
	function typo_dcap( $atts, $content = null ) {
		extract(shortcode_atts(array(				
			'style' => '',			
		), $atts));
		$out = "<span class='$style'>" . do_shortcode($content) . "</span>";
		return $out;
	}
	add_shortcode('typo_dropcap', 'typo_dcap');
	
	
	/****************************
	COLUMNS
	****************************/

	function typo_clear($atts, $content=null) {
	    extract(shortcode_atts(array(
	    	'style' => 'clear'
	    ),$atts));
	    $ret = "<div class='$style'>" . do_shortcode($content);
	    $ret .= "</div>";
	    return $ret;
	}
	add_shortcode('typo_clear', 'typo_clear');
	
	
	function typo_column( $atts, $content = null ) {
		extract(shortcode_atts(array(				
			'style' => '',		
			'type' => ''	
		), $atts));

		$out = "<div class='$style $type'>" . do_shortcode($content) . '</div>';
		return $out;
	}
	add_shortcode('typo_columns', 'typo_column');
	
	
	/****************************
	IMAGES
	****************************/	
	
	function typo_singleimage($atts) {
		extract(shortcode_atts(array(
			'src' => '',
			'width' => '',
			'height' => '',
			'title' => '',
			'style' => ''
		), $atts));
		if($width == '' && $height == '')
			return "<img src='$src' alt='$title' class='$style' />";

		$theme = get_template_directory_uri();
		$out = "<a href='$src' rel='gallery'><img src='$src' height='$height' width='$width' title='$title' alt='$title' class='$style' /></a>";

		return $out;
	}
	add_shortcode('typo_image', 'typo_singleimage');


	/****************************
	TABS
	****************************/

	function typo_jtab_group( $atts, $content ){	
		$GLOBALS['tab_count'] = 0;
		do_shortcode( $content );

		if( is_array( $GLOBALS['tabs'] ) ){
			foreach( $GLOBALS['tabs'] as $tabCount => $tab ){
				$tabs[] = '<li><a href="#'.$tab['anchor'].'">'.$tab['title'].'</a></li>';
				$panes[] = '<div id="'.$tab['anchor'].'" class="tab-content">'.$tab['content'].'</div>';
			}
			$return = "\n".'<ul class="tab-menu clear">'.implode( "\n", $tabs ).'</ul>'."\n".'<div class="tab-container">'.implode( "\n", $panes ).'</div>'."\n";
		}
		return $return;
	}
	
	function typo_jtabs( $atts, $content ){
		extract(shortcode_atts(array(
			'anchor' => '%d',	
			'title' => 'Tab %d'
		), $atts));

		$curTab = $GLOBALS['tab_count'];
		$GLOBALS['tabs'][$curTab] = array( 'title' => sprintf( $title, $curTab), 'anchor' => sprintf( $anchor, $curTab ), 'content' => do_shortcode($content) );

		$GLOBALS['tab_count']++;
	}
	add_shortcode( 'typo_tabgroup', 'typo_jtab_group' );	
	add_shortcode( 'typo_tab', 'typo_jtabs' );


	/****************************
	TOGGLE
	****************************/

	function typo_toggle_shortcode( $atts, $content = null ) {
		extract( shortcode_atts(array(
			'title' => ''
		), $atts));
		$toggle = '<h4 class="toggle"><a href="#">'. $title .'</a></h4><div class="toggle_container">' . do_shortcode($content) . '</div>';
		return $toggle;
	}
	add_shortcode('typo_toggle', 'typo_toggle_shortcode');


	/****************************
	VIDEO
	****************************/

	function typo_video_shortcode( $atts, $content = null ) {
		 extract(  
	        shortcode_atts(array(  
	            'site' => 'youtube',  
	            'id' => '',  
	            'w' => '551',  
	            'h' => '350'  
	        ), $atts)  
	    );  
	    if ( $site == "youtube" ) { $src = 'http://www.youtube-nocookie.com/embed/'.$id; }  
	    else if ( $site == "vimeo" ) { $src = 'http://player.vimeo.com/video/'.$id; }  
	    else if ( $site == "dailymotion" ) { $src = 'http://www.dailymotion.com/embed/video/'.$id; }  
	    if ( $id != '' ) {  return '<iframe width="'.$w.'" height="'.$h.'" src="'.$src.'" class="video iframe-'.$site.'"></iframe>';  }  
	}
	add_shortcode('typo_video', 'typo_video_shortcode');


	/****************************
	GOOGLE MAP
	****************************/

	function typo_googleMap( $atts, $content = null ) {
	   	extract(shortcode_atts(array(
			"width" => '653',
			"height" => '284',
			"src" => ''
	    ), $atts));
	    
	    return '<iframe width="'.$width.'" height="'.$height.'" frameborder="0" scrolling="no" marginheight="0" marginwidth="0" src="'.$src.'&amp;output=embed"></iframe>';
	}
	add_shortcode( 'typo_googlemap', 'typo_googleMap' );


	/****************************
	SOCIAL ICONS
	****************************/
	
	function typo_social_icons( $atts, $content = null ) {
		extract(shortcode_atts(array(	
			'href' => '',
			'style' => '',
			'target' => '',
			'type' => ''
		), $atts));
		$out = "<a href='$href' class='$style $type' target='$target'>" . do_shortcode($content) . "</a>";
		return $out;
	}
	add_shortcode('typo_social', 'typo_social_icons');


	/****************************
	CALLOUTS
	****************************/
	
	function typo_callout( $atts, $content = null ) {
		extract(shortcode_atts(array(	
			'style' => 'typo_default',
			'type' => '',
		), $atts));
		$out = "<div class='$style $type'>"  . do_shortcode($content) . "</div>";
		return $out;
	}
	add_shortcode('typo_callouts', 'typo_callout');

	function typo_label($atts, $content=null) {
	    extract(shortcode_atts(array(
	    	'style' => ''
	    ),$atts));
	    $ret = "<label>" . do_shortcode($content);
	    $ret .= "</label>";
	    return $ret;
	}
	add_shortcode('typo_label', 'typo_label');


	/****************************
	Skills Bar
	****************************/
	
	function typo_skills_bar( $atts, $content = null ) {
		extract(shortcode_atts(array(	
			'percent' => '',
			'color' => ''
		), $atts));
		$out = "<div class='skillbar clear' data-percent='". $percent ."%'><div class='skillbar-title'><span>". do_shortcode($content) ."</span></div><div class='skillbar-bar ". $color ."'></div><div class='skill-bar-percent'>". $percent ."%</div></div>";
		return $out;
	}
	add_shortcode('typo_skills_bar', 'typo_skills_bar');

?>
