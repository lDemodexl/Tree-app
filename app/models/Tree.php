<?php
class Tree{
	private $db;
	
	public function __construct(){
		$this->db = new DataBase;
	}
	
	//get all records from  tree table
	public function getTree(){
		$this->db->query('SELECT *
						FROM tree
						ORDER BY tree.id ASC
						');
		
		return $this->db->resultSetAll();
	}

	//add root to tree
	public function AddTreeRoot(){
		$this->db->query('INSERT INTO tree (name, parentID) VALUES (:name, :parentID)');
		$this->db->bind(':name', 'root');
		$this->db->bind(':parentID', NULL);

		//Execute
		if( $this->db->execute() ){
			return true;
		}else{
			return false;
		}

	}

	//add element to tree
	public function AddTreeChild($id){
		$this->db->query('INSERT INTO tree (name, parentID) VALUES (:name, :parentID)');
		$this->db->bind(':name', 'new branch');
		$this->db->bind(':parentID', $id);

		//Execute
		if( $this->db->execute() ){
			return true;
		}else{
			return false;
		}

	}

	//Change tree element name
	public function EditTreeElement($data){
		$this->db->query('UPDATE tree SET name = :name WHERE id = :id');
		$this->db->bind(':name', $data['name']);
		$this->db->bind(':id', $data['id']);

		//Execute
		if( $this->db->execute() ){
			return true;
		}else{
			return false;
		}
	}

	//delete element from tree
	public function deleteElement($id){
		$this->db->query('DELETE FROM tree WHERE id = :id');
		$this->db->bind(':id', $id);
		
		//Execute
		if( $this->db->execute() ){
			return true;
		}else{
			return false;
		}
	}

	//Check if element has childs
	public function isParent($id){
		$this->db->query('SELECT * FROM tree WHERE parentID = :id');
		$this->db->bind(':id', $id);
		//Execute
		$this->db->execute();

		if( $this->db->rowCount() > 0 ){
			return true;
		}else{
			return false;
		}
	}

	//Select all childs of element
	public function getChilds($id){
		$this->db->query('SELECT * FROM tree WHERE parentID = :id');
		$this->db->bind(':id', $id);

		return $this->db->resultSetAll();
	}
}
?>