<?php

namespace Anax\Comment;

/**
 * To attach comments-flow to a page or some content.
 *
 */
class CommentsInSession implements \Anax\DI\IInjectionAware {

    use \Anax\DI\TInjectable;

    /**
     * Add a new comment.
     *
     * @param array $comment with all details.
     * 
     * @return void
     */
    public function add($comment, $key = null) {
        $comments = $this->session->get('comments', []);
        $comments[$key][] = $comment;
        $this->session->set('comments', $comments);
    }

    /**
     * Save edited comment.
     *
     * @param array $comment with all details.
     * 
     * @return void
     */
    public function saveId($comment, $id = null, $key = null) {
        $comments = $this->session->get('comments', []);
        $comments[$key][$id] = $comment;
        $this->session->set('comments', $comments);
    }

    /**
     * Find and return all comments.
     *
     * @return array with all comments.
     */
    public function findAll($key = null) {
        $comments = $this->session->get('comments', []);
        if (isset($comments[$key]))
            return $comments[$key];
    }

    /**
     * Find and return one comment.
     *
     * @return array with comment.
     */
    public function findId($id = null, $key = null) {
        $comments = $this->session->get('comments', []);
        $comment = $comments[$key][$id];
        return $comment;
    }

    /**
     * Delete all comments in session.
     *
     * @return void
     */
    public function deleteAllSession() {
        $this->session->set('comments', []);
    }

    /**
     * Delete one comment.
     *
     * @return void
     */
    public function deleteAll($key = null) {
        $comments = $this->session->get('comments', []);
        $comments[$key] = [];
        $this->session->set('comments', $comments);
    }

    /**
     * Delete one comment.
     *
     * @return void
     */
    public function deleteId($id = null, $key = null) {
        $comments = $this->session->get('comments', []);
        unset($comments[$key][$id]);
        $this->session->set('comments', $comments);
    }

}
