<?php
/* 
	*
	*Base Controller
	*Load models and views
	*
*/

class Controller{
	
	//Load model
	public function model($model){
		//Require modal file
		require_once '../app/models/' . $model . '.php';
		
		//Instatiate model
		return new $model();
	}
	
	//Load views
	public function view( $view, $data = [] ){
		//Check view file
		if( file_exists( '../app/views/' . $view . '.php' ) ){
			require_once '../app/views/' . $view . '.php';
		}else{
			die('View is not exist');
		}
	}
}