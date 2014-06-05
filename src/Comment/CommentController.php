<?php

namespace Anax\Comment;

/**
 * To attach comments-flow to a page or some content.
 *
 */
class CommentController implements \Anax\DI\IInjectionAware {

    use \Anax\DI\TInjectable;

    /**
     * View all comments.
     *
     * @return void
     */
    public function viewAction($key = '_no-key') {
        $comments = new \Anax\Comment\CommentsInSession();
        $comments->setDI($this->di);

        $all = $comments->findAll($key);

        $this->views->add('comment/comments', [
            'comments' => $all,
            'key' => $key,
        ]);
    }

    /**
     * Add a comment.
     *
     * @return void
     */
    public function addAction() {
        $isPosted = $this->request->getPost('doCreate');

        if (!$isPosted) {
            $this->response->redirect($this->request->getPost('redirect'));
        }

        $comment = [
            'content' => $this->request->getPost('content'),
            'name' => $this->request->getPost('name'),
            'web' => $this->request->getPost('web'),
            'mail' => $this->request->getPost('mail'),
            'timestamp' => time(),
            'ip' => $this->request->getServer('REMOTE_ADDR'),
        ];

        $comments = new \Anax\Comment\CommentsInSession();
        $comments->setDI($this->di);

        $key = $this->request->getPost('pageKey');
        $comments->add($comment, $key);

        $this->response->redirect($this->request->getPost('redirect'));
    }

    public function editAction() {
        $isPosted = $this->request->getPost('doEditId');

        if (!$isPosted) {
            $this->response->redirect($this->request->getPost('redirect'));
        }

        $comments = new \Anax\Comment\CommentsInSession();
        $comments->setDI($this->di);

        $id = ($this->request->getPost('postId') - 1);
        $key = $this->request->getPost('pageKey');

        $comment = $comments->findId($id, $key);
        $comment['id'] = $id;
        $comment['key'] = $key;

        $this->views->add('comment/edit', $comment);
    }

    public function saveChangesAction() {
        $isPosted = $this->request->getPost('doSave');

        if (!$isPosted) {
            $this->response->redirect($this->request->getPost('redirect'));
        }

        $comment = [
            'content' => $this->request->getPost('content'),
            'name' => $this->request->getPost('name'),
            'web' => $this->request->getPost('web'),
            'mail' => $this->request->getPost('mail'),
            'timestamp' => time(),
            'ip' => $this->request->getServer('REMOTE_ADDR'),
        ];

        $comments = new \Anax\Comment\CommentsInSession();
        $comments->setDI($this->di);

        $id = $this->request->getPost('postId');
        $key = $this->request->getPost('pageKey');
        $comments->saveId($comment, $id, $key);

        $this->response->redirect($this->request->getPost('redirect'));
    }

    /**
     * Remove all comments.
     *
     * @return void
     */
    public function removeAllAction() {
        $isPosted = $this->request->getPost('doRemoveAll');

        if (!$isPosted) {
            $this->response->redirect($this->request->getPost('redirect'));
        }

        $comments = new \Anax\Comment\CommentsInSession();
        $comments->setDI($this->di);

        $key = $this->request->getPost('pageKey');
        $comments->deleteAll($key);

        $this->response->redirect($this->request->getPost('redirect'));
    }

    public function removeIdAction() {
        $isPosted = $this->request->getPost('doRemovePost');

        if (!$isPosted) {
            $this->response->redirect($this->request->getPost('redirect'));
        }

        $comments = new \Anax\Comment\CommentsInSession();
        $comments->setDI($this->di);

        $id = ($this->request->getPost('postId') - 1);
        $key = $this->request->getPost('pageKey');
        $comments->deleteId($id, $key);

        $this->response->redirect($this->request->getPost('redirect'));
    }

}
