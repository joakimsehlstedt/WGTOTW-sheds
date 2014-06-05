<?php

namespace Anax\Comment;

/**
 * Model for Comment.
 *
 */
class Comment2Tags extends \Anax\MVC\CDatabaseModel {

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
	        'idComment = ?'
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
	 * Find and return all tag id connected to comment id.
	 *
	 * @param integer $id to find.
	 *
	 * @return array
	 */
	public function find($id) {
	    $this->db->select('idTag')
	             ->from($this->getSource())
	             ->where('idComment = ?');
	 
	    $this->db->execute([$id]);
	    $this->db->setFetchModeClass(__CLASS__);
	    return $this->db->fetchAll();
	}

	/** @OVERRIDE
	 * Return tag name.
	 *
	 * @return string
	 */
	public function getTagName($id) {

	    $this->db->select('name')
            ->from('tags')
            ->where('id = ?')
        ;
	 
	    $this->db->execute([$id]);
	    return $this->db->fetchOne();
	}

}