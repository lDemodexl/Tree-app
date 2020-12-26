<?php
class Trees extends Controller{
	public function __construct(){
		$this->treeModel = $this->model('Tree');
	}
	
	//add element to tree
	public function add(){
		
		if( $_SERVER['REQUEST_METHOD'] == 'POST' ){
			if( !empty($_POST) ){
				//Sanitize Post
				$_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);
				
				foreach( $_POST as $key => $val ){
					switch($key){
						case 'createRoot':
							if( $root = $this->treeModel->AddTreeRoot() ){
                                return $this->createTree($root);	
                            }
							break;
						case 'createChild':
							if( $root = $this->treeModel->AddTreeChild($val) ){
                                return $this->createTree($_SESSION['active_tree']);	
                            }
							break;
					}
				}
			}else{
                redirect('');
            }		
		}else{
			redirect('');
		}
	}

	public function addTree(){
		if( $_SERVER['REQUEST_METHOD'] == 'POST' ){
			if( !empty($_POST) ){
				//Sanitize Post
				$_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);
				if( $root = $this->treeModel->AddTree($_POST) ){
					$_SESSION['active_tree'] = $root;
					return $this->createTree($_SESSION['active_tree']);	
				}
			}else{
                redirect('');
            }		
		}else{
			redirect('');
		}
				
	}

	public function getTreeSelect(){
		$roots = $this->treeModel->getTreeRoots();
		$data = [
            'roots' => $roots,
        ];
        return $this->view('pages/partials/treeSelect', $data);
	}
	
	public function edit(){
		if( $_SERVER['REQUEST_METHOD'] == 'POST' ){
            //Sanitize Post
			$_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);
			if( !empty($_POST) ){
				if( $root = $this->treeModel->EditTreeElement($_POST) ){
					return $this->createTree($_SESSION['active_tree']);	
				}
			}else{
				redirect('');
			}
		}else{
			redirect('');
		}
	}
	
	//Delete the element
	public function delete(){
		if( $_SERVER['REQUEST_METHOD'] == 'POST' ){
            //Sanitize Post
			$_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);
			if( $this->treeModel->isParent($_POST['id']) ){
				$this->deleteRecursive($_POST['id']);
			}
			if( $this->treeModel->deleteElement($_POST['id']) ){
				if( $this->treeModel->getTreeElementById($_SESSION['active_tree']) ){
					return $this->createTree($_SESSION['active_tree']);	
				}else{
					return '';
				}
			}else{
				return 'Error';
			}
		}else{
			redirect('');
		}
	}
	
	//Delete element childs
	function deleteRecursive($id){
		$childs = $this->treeModel->getChilds($id);
		foreach( $childs as $child ){
			if( $this->treeModel->isParent($child->id) ){
				$this->deleteRecursive($child->id);
			}
			$this->treeModel->deleteElement($child->id);
		}
	}
	
	//build tree and return new html
    function createTree($rootID){
        $new_tree = $this->getTreeFromRoot($rootID);
        $new_tree = $this->parseTree($new_tree);

        $data = [
            'tree' => $new_tree,
        ];
        return $this->view('pages/partials/tree', $data);
    }

	//collect all records to tree structure
    function parseTree($tree, $parentID = '' ){
		$parsed_tree = [];
		
        foreach( $tree as $key=>$tree_element ){
            if ( empty($parentID) ){
				
                if( empty($tree_element->parentID) || !isset($tree_element->parentID) ){
                    $parsed_tree[$tree_element->id] = array('id'=>$tree_element->id, 'name'=>$tree_element->name);
                    $parsed_tree[$tree_element->id]['childs'] = $this->parseTree($tree, $tree_element->id);
                }
            }else if($tree_element->parentID == $parentID){
                $parsed_tree[$tree_element->id] = array('id'=>$tree_element->id, 'name'=>$tree_element->name,'parentID'=>$tree_element->parentID);
                $parsed_tree[$tree_element->id]['childs'] = $this->parseTree($tree, $tree_element->id);
            }
        }
        
        return $parsed_tree;
	}
	
	function getTreeFromRoot($root){
		$tree = $this->treeModel->getTreeElementById($root);
		$childs = $this->treeModel->getChilds($root);
		
		foreach( $childs as $child ){
			if( $this->treeModel->isParent($child->id) ){
				$hasChilds = $this->getTreeFromRoot($child->id);
				$tree = (object)array_merge( (array)$tree, (array)$hasChilds );
			}else{
				
				$tree = (object)array_merge( (array)$tree, [$child]);
			}
		}
		
		$tree = (object)array_merge( (array)$tree, (array)$childs);
		
		return $tree;
	}
}
?>