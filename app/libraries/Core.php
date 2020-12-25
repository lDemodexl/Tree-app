<?php
/*
	*
	*App Core Class
	*Creates URL & loads core controller
	*URL Format - /controller/metod/params
	*
*/

class Core {
	protected $currentController = 'Pages';
	protected $currentMethod = 'index';
	protected $currentParams = [];
	
	public function __construct(){
		$url = $this->getUrl();
		
		//Look in if controller exist
		if( file_exists('../app/controller/'. ucwords($url[0]) .'.php' ) ){
			//Set the controller
			$this->currentController = ucwords($url[0]);
			unset( $url[0] );
		}
		
		//Require Controllers
		require_once '../app/controller/'. $this->currentController .'.php';
		
		$this->currentController = new $this->currentController;

		//Check for second part of url
		if( isset(url[1]) ){
			//Check if method exist
			if( method_exists($this->currentController, $url[1]) ){
				$this->currentMethod = $url[1];
				unset($url[1]);
			}
		}
		
		//Get params
		$this->params = $url ? array_values( $url ) : [];
		
		//Call a callback with array and params
		call_user_func_array( [$this->currentController, $this->currentMethod], $this->params );
		
	}
	
	public function getUrl(){
		if( isset($_GET['url']) ){
			$url = rtrim( $_GET['url'], '/' );
			$url = filter_var( $url, FILTER_SANITIZE_URL);
			$url = explode( '/', $url );
			return $url;
		}
	}
}
?>