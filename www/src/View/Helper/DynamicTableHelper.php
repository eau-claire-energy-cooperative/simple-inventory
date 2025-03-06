<?php
namespace App\View\Helper;

use Cake\View\Helper;

class DynamicTableHelper extends Helper
{
  /*
  Generate a list of column indexes for the DataTables plugin based on columns that should be visible
  */
  public function listVisibleColumns($allColumns, $displayColumns, $alwaysShow=[], $offset=0){
    $result = $alwaysShow;
    $i = $offset;

    foreach($allColumns as $attribute)
    {
      if(in_array($attribute, $displayColumns))
      {
        // set as visible
        $result[] = $i;
      }
      $i ++;
    }

    return implode(",", $result);
  }
}
?>
