<?php

namespace Anax\Comment;

/**
 * Model for Comment.
 *
 */
class Comment extends \Anax\MVC\CDatabaseModel {

	/**
	 * Find and return entry matching user id.
	 *
	 * @param int $id. Comment user id.
	 *
	 * @return this
	 */
	public function findByUser($id) {
	    $this->db->select('*')
	             ->from($this->getSource())
	             ->where("userId = ?");
	 
	    $this->db->execute([$id]);
	    return $this->db->fetchAll();
	}

}