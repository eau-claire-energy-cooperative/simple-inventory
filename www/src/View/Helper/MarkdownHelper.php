<?php
namespace App\View\Helper;

use Cake\View\Helper;
use League\CommonMark\CommonMarkConverter;

class MarkdownHelper extends Helper
{
  protected $parser;

  function transform($text){
    return $this->_getParser()->convertToHtml($text);
  }

  protected function _getParser()
  {
    if ($this->parser !== null) {
      return $this->parser;
    }

    $this->parser = new CommonMarkConverter();
    return $this->parser;
  }
}
?>
