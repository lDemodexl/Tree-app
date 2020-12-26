<?php
require_once 'Trees.php';

class Pages extends Controller{
	public function __construct(){
		$this->treeModel = $this->model('Tree');
		$this->treeClass = new Trees;
	}
	
	public function index(){

		if( $_SERVER['REQUEST_METHOD'] == 'POST' ){
            //Sanitize Post
			$_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);
			if( !empty($_POST) ){
				$activeTree = $_POST['treeID'];
				$_SESSION['active_tree'] = $activeTree;

				$tree = $this->treeClass->getTreeFromRoot($_SESSION['active_tree']);
				$tree = $this->treeClass->parseTree($tree);
				return $this->treeClass->createTree($_SESSION['active_tree']);	
			}
		}

		$roots = $this->treeModel->getTreeRoots();
		if(!empty($roots)){
			//check if has selected tree in session
			if( empty($_SESSION['active_tree']) || !isset($_SESSION['active_tree']) ){
				$activeTree = $roots[0]->id;
				$_SESSION['active_tree'] = $activeTree;
			}

			//check if has saved tree in database
			$has = false;
			foreach($roots as $root){
				if($_SESSION['active_tree'] == $root->id){
					$has = true;
				}
			}
			if(!$has){
				$activeTree = $roots[0]->id;
				$_SESSION['active_tree'] = $activeTree;
			}
			
			$tree = $this->treeClass->getTreeFromRoot($_SESSION['active_tree']);
			$tree = $this->treeClass->parseTree($tree);
		}else{
			$tree = array();
		}

		$data = [
			'title' => 'Welcome',
			'roots' => $roots, 
			'tree' => $tree,
		];
		
		$this->view('pages/index', $data);
	}
}
?>