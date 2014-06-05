<?php

namespace Anax\Comment;

/**
 * Model for Comment to Comment connection.
 *
 */
class Comm2Comm extends \Anax\MVC\CDatabaseModel {

	/** @OVERRIDE
	 * Delete row.
	 *
	 * @param integer $id to delete.
	 *
	 * @return boolean true or false if deleting went okey.
	 */
	public function delete($id) {
	    $this->db->delete(
	        $this->getSource(),
	        'idQuestion = ?'
	    );
	 
	    return $this->db->execute([$id]);
	}

	/** @OVERRIDE
	 * Save current object/row.
	 *
	 * @param array $values key/values to save or empty to use object properties.
	 *
	 * @return boolean true or false if saving went okey.
	 */
	public function save($values = []) {
	    return $this->create($values);
	}

	/** @OVERRIDE
	 * Find and return all Answer id connected to Question id.
	 *
	 * @param integer $id to find.
	 *
	 * @return array
	 */
	public function find($id) {
	    $this->db->select('idAnswer')
	             ->from($this->getSource())
	             ->where('idQuestion = ?');
	 
	    $this->db->execute([$id]);
	    $this->db->setFetchModeClass(__CLASS__);
	    return $this->db->fetchAll();
	}

	/**
	 * Check if id is an answer.
	 *
	 * @param integer $id to find.
	 *
	 * @return bool
	 */
	public function isAnswer($id) {
	    $this->db->select('*')
	             ->from($this->getSource())
	             ->where('idAnswer = ?')
	    ;
	 
	    $this->db->execute([$id]);
	    $this->db->setFetchModeClass(__CLASS__);
	    $res = $this->db->fetchAll();

    	if ($res != null) { 
    		return true; 
    	} else { 
    		return false;
    	}
	}

	/**
	 * Return number of answers connected to comment.
	 *
	 * @param integer $id to check.
	 *
	 * @return array
	 */
	public function numberAnswers($id) {
	    $this->db->select('idQuestion, count(*) AS answers')
	             ->from($this->getSource())
	             ->where('idQuestion = ?')
	    ;
	 
	    $this->db->execute([$id]);
	    $this->db->setFetchModeClass(__CLASS__);
	    return $this->db->fetchAll();
	}


}