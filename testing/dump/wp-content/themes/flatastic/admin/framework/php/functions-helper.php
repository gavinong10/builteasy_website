<?php

/*  Write To File
/* ---------------------------------------------------------------------- */

if (!function_exists('mad_write_to_file')) {
	function mad_write_to_file($file, $content = '', $verify = true) {
		$handle = @fopen( $file, 'w' );

		if ($handle) {
			$create = fwrite( $handle, $content );
			fclose( $handle );

			if ($verify === true) {
				$handle = fopen($file, "r");
				$filecontent = fread($handle, filesize($file));
				$create = ($filecontent == $content) ? true : false;
				fclose( $handle );
			}
		} else {
			$create  = false;
		}

		if ($create !== false) {
			$create = true;
		}
		return $create;
	}

}

/*  Create folder
/* ---------------------------------------------------------------------- */

if (!function_exists('mad_backend_create_folder')) {
	function mad_backend_create_folder(&$folder, $addindex = true) {
		if (is_dir($folder) && $addindex == false) {
			return true;
		}
		$created = wp_mkdir_p(trailingslashit($folder));
		@chmod($folder, 0777);

		if ($addindex == false) return $created;

		$index_file = trailingslashit($folder) . 'index.php';
		if (file_exists($index_file)) {
			return $created;
		}

		$handle = @fopen($index_file, 'w');
		if ($handle) {
			fwrite( $handle, "<?php\r\necho 'Browsing the directory is not allowed!';\r\n?>" );
			fclose( $handle );
		}
		return $created;
	}
}

/*  Elements decode
/* ---------------------------------------------------------------------- */

if (!function_exists('mad_deep_decode')) {

	function mad_deep_decode($elements) {
		if (is_array($elements) || is_object($elements)) {
			foreach ($elements as $key=>$element) {
				$elements[$key] = mad_deep_decode($element);
			}
		} else {
			$elements = html_entity_decode($elements, ENT_COMPAT, get_bloginfo('charset'));
		}
		return $elements;
	}

}

/*  Get Option
/* ---------------------------------------------------------------------- */

if (!function_exists('mad_custom_get_option')) {
	function mad_custom_get_option($key = false, $default = "", $decode = false) {
		global $mad_global_data;

		$result = $mad_global_data->options;

		if (is_array($key)) {
			$result = $result[$key[0]];
		} else {
			$result = $result['mad'];
		}

		if ($key === false) {
		} else if(isset($result[$key])) {
			$result = $result[$key];
		} else {
			$result = $default;
		}

		if ($decode) { $result = mad_deep_decode($result); }
		if ($result == "") { $result = $default; }
		return $result;
	}
}