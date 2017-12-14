<?php


class View {
	public static function render( $filePath, $viewData = array() ) {
	return Template::getInstance()->render( $filePath, $viewData );
	}
}