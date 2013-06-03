<?php

function display($value)
{
	echo "<pre>";
	if( is_array($value) ) {
		print_r($value);
	} else {
		echo $value;
	}
	echo "</pre>";	
}