<?php
require_once(ROOT . DS . '/vendors/Parsedown/Parsedown.php');
/**
 * Markdown Helper
 *
 * @package app.View.Helper
 */
class MarkdownHelper extends AppHelper {

/**
 * Convert markdown to html
 *
 * @param  string $text Text in markdown format
 * @return string
 */
	public function transform($text) {
		if (!isset($this->parser)) {
			if (!class_exists('Parsedown')) {
				App::import('Vendor', 'Parsedown' . DS . 'Parsedown');
			}
			$this->parser = new Parsedown();
		}
		return $this->parser->text($text);
	}

}
?>
