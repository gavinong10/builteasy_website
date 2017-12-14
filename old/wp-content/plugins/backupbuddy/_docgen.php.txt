<html>
<head>
<style type="text/css">
	.comment_block {
		border: 1px solid #CCCCCC;
		-webkit-border-radius: 3px;
		-moz-border-radius: 3px;
		border-radius: 3px;
		
		padding: 5px;
		background: #EEEEEE;
	}
</style>
</head>

<?php
$path = dirname( __FILE__ ) . '/*.php';
//$path = dirname( __FILE__ ) . '/_pluginbuddy.php';



// Does not support flag GLOB_BRACE
function glob_recursive($pattern, $flags = 0) {
	$files = glob($pattern, $flags);

	foreach (glob(dirname($pattern).'/*', GLOB_ONLYDIR|GLOB_NOSORT) as $dir) {
		$files = array_merge($files, glob_recursive($dir.'/'.basename($pattern), $flags));
	}

	return $files;
}



echo 'Looking for PHP files with pattern: ' . $path .'<br>';
$files = glob_recursive( $path );
echo 'Found ' . count( $files ) . ' files matching pattern. All files listed relative to path of this file.';
//echo '<pre>' . print_r( $files, true ) . '</pre>';

foreach( $files as $file ) {
	echo '<hr>';
	echo '<h1>File: ' . str_replace( dirname( __FILE__ ), '', $file ) . '</h1>';
	//if ( strstr( 
	$contents = file( $file );
	
	$in_comment_block = false; // True when inside a comment block /* ... */ (can be multiple lines).
	$in_class = ''; // Blank when not in a class; class name when in a class.
	$comment_block_contents = '';
	foreach( $contents as $content ) {
		if ( false !== strpos( $content, '/*' ) ) {
			$in_comment_block = true;
			$comment_block_contents = '';
		} // end checking for start of comment block.
		
		
		// Parse things NOT in comment blocks:
		if ( false === $in_comment_block ) {
			if ( ( false !== strpos( $content, 'class ' ) ) && ( false !== strpos( $content, ' {' ) ) ) { // todo: needs more specificity to avoid false positives.
				$in_class = substr( $content, strpos( $content, 'class ' ) + 6, -2 );
				echo '<h2>Class: ' . $in_class . '</h2>';
			}
		}
		
		
		if ( true === $in_comment_block ) {
			$comment_block_contents .= $content;
		} // end if in comment block.
		
		if ( false !== strpos( $content, '*/' ) ) {
			echo '<pre class="comment_block">' . print_r( $comment_block_contents, true ) . '</pre>';
			$in_comment_block = false;
		} // end checking for end of comment block.
	}
}


?>
