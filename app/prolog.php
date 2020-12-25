<?php
/*
	*
	*Connection of all files you need
	*
*/
session_start();

//Config file
require_once 'config/config.php';
require_once 'helpers/functions.php';

//Autoload core libraries
spl_autoload_register( function( $className ){
	require_once 'libraries/' . $className . '.php';
} );



//Init Core Librarie
$init = new Core;
?>