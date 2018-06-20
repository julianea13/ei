<?php
/**
* 
*/
class Posts
{
	public function check($vars = array())
	{		
		$err = 0;
		if (count($vars)>0) {
			foreach ($vars as $key => $value) {
				if (!isset($_POST[$value])){
					$err++;
				}
			}
		}		
		return $err>0? false : true;
	}
}