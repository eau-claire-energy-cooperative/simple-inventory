<?php

class DiskSpaceHelper extends AppHelper {
	var $MegaByte = 1024;
	var $GigaByte = 1048576;
	var $TeraByte = 1073741824;
	
	function toString($kb){
		$result = '';
		
		//convert size in KB to nearest size block
		if($kb > $this->TeraByte)
		{
			$result = round($kb / $this->TeraByte,0) . " TB";
		}
		else if($kb > $this->GigaByte)
		{
			$result = round($kb / $this->GigaByte,0) . " GB";
		}
		else if($kb > $this->MegaByte)
		{
			$result = round($kb / $this->MegaByte,0) . " MB";
		}
		else
		{
			$result = $kb + " KB";
		}
		
		return $result;
	}
	
	function compare($total,$used){
		if($total != 0)
		{
			return number_format(($used/$total) * 100,0);
		}
		else
		{
			return 0;
		}
	}
}

?>
