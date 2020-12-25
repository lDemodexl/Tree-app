<?php
class Tree{
	private $db;
	
	public function __construct(){
		$this->db = new DataBase;
	}
	
	public function getTree(){
		$this->db->query('SELECT *
						FROM tree
						ORDER BY tree.id ASC
						');
		
		return $this->db->resultSetAll();
	}

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
}
?>