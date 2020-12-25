<?php
require_once 'Trees.php';

class Pages extends Controller{
	public function __construct(){
		$this->treeModel = $this->model('Tree');
		$this->treeClass = new Trees;
	}
	
	public function index(){
		$tree = $this->treeModel->getTree();
		$tree = $this->treeClass->parseTree($tree);
		$data = [
			'title' => 'Welcome',
			'tree' => $tree,
		];
		
		$this->view('pages/index', $data);
	}
}
?>