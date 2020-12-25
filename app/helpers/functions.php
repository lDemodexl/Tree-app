<?php
//Redirect function
function redirect($page){
	Header( 'Location: ' . URLROOT . '/' . $page , true, 302);
	exit;
}
?>