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
                                return $this->createTree();	
                            }
							break;
						case 'createChild':
							if( $root = $this->treeModel->AddTreeChild($val) ){
                                return $this->createTree();	
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
	
	public function edit(){
		if( $_SERVER['REQUEST_METHOD'] == 'POST' ){
            //Sanitize Post
			$_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);
			if( !empty($_POST) ){
				if( $root = $this->treeModel->EditTreeElement($_POST) ){
					return $this->createTree();	
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
				return $this->createTree();		
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
    function createTree(){
        $new_tree = $this->treeModel->getTree();
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
}
?>