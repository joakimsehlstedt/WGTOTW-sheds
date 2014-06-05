<?php

namespace Anax\Comment;

/**
 * Model for Comment.
 *
 */
class Tags extends \Anax\MVC\CDatabaseModel {


    /**
	 * Find and return active tags.
	 *
	 * @return array
	 */
	public function findTags() {

	    $this->db->select('DISTINCT T.name')
            ->from('tags AS T')
            ->join('comment2tags AS C2T', 'T.id = C2T.idTag')
        ;
	 
	    $this->db->execute();
	    $this->db->setFetchModeClass(__CLASS__);
	    return $this->db->fetchAll();
	}

	/**
	 * Find and return all tags.
	 *
	 * @return array
	 */
	public function findAllTags() {

	    $this->db->select('id, name')
            ->from('tags')
        ;
	 
	    $this->db->execute();
	    $this->db->setFetchModeClass(__CLASS__);
	    return $this->db->fetchAll();
	}

}