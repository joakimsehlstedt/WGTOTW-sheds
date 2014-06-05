<?php

namespace Anax\Comment;

/**
 * To attach comments-flow to a page or some content.
 *
 */
class CommentDbController implements \Anax\DI\IInjectionAware {

    use \Anax\DI\TInjectable;

    /**
     * Initialize the controller.
     *
     * @return void
     */
    public function initialize() {
        $this->comments = new \Anax\Comment\Comment();
        $this->comments->setDI($this->di);
        $this->comments->setTablePrefix('WGTOTW_');

        $this->tags = new \Anax\Comment\Tags();
        $this->tags->setDI($this->di);
        $this->tags->setTablePrefix('WGTOTW_');

        $this->comments2tags = new \Anax\Comment\Comment2Tags();
        $this->comments2tags->setDI($this->di);
        $this->comments2tags->setTablePrefix('WGTOTW_');

        $this->comm2comm = new \Anax\Comment\Comm2Comm();
        $this->comm2comm->setDI($this->di);
        $this->comm2comm->setTablePrefix('WGTOTW_');

        $this->users = new \Anax\Users\User();
        $this->users->setDI($this->di);
        $this->users->setTablePrefix('WGTOTW_');
    }

    /**
     * View all comments.
     *
     * @return void
     */
    public function viewAction() {
        $all = $this->comments->findAll();

        $all = $this->addTagsString($all);
        $all = $this->addUserData($all);
        $all = $this->addNumberAnswers($all);
        $all = $this->filterMarkdown($all);

        $this->views->add('comment/commentsdb', [
            'comments' => $all,
            'title' => $this->theme->getVariable('title'),
        ]);
    }

    /**
     * View 1st level questions.
     *
     * @return void
     */
    public function viewQuestionsAction() {
        $all = $this->comments->findAll();

        $res = [];
        foreach ($all as $value) {
            if ($this->comm2comm->isAnswer($value->id) == null) {
                $res[] = $value;
            }
        }

        $res = $this->addTagsString($res);
        $res = $this->addUserData($res);
        $res = $this->addNumberAnswers($res);
        $res = $this->filterMarkdown($res);

        $this->views->add('comment/commentsdb', [
            'comments' => $res,
            'title' => 'all questions',
        ]);
    }

    /**
     * View top 3 newest questions, tags and users.
     *
     * @return void
     */
    public function viewTopThreeAction() {

        $this->db->select('*')
            ->from('comment ORDER BY timestamp DESC LIMIT 3');
        $top_new = $this->db->executeFetchAll();

        $this->db->select('idTag, COUNT(idTag) as tc')
            ->from('comment2tags GROUP BY idTag ORDER BY tc DESC LIMIT 3');
        $top_tags = $this->db->executeFetchAll();
        foreach ($top_tags as $value) {
            $value->tag = $this->comments2tags->getTagName($value->idTag)->name;
        }

        $this->db->select('userId, COUNT(userId) as uc')
            ->from('comment GROUP BY userId ORDER BY uc DESC LIMIT 3');
        $top_users = $this->db->executeFetchAll();
        foreach ($top_users as $value) {
            $value->name = $this->users->find($value->userId)->name;
        }

        $this->views->add('comment/topthree', [
            'new' => $top_new,
            'tags' => $top_tags,
            'users' => $top_users,
            'title' => 'stats',
        ]);

    }

    /**
     * View comments by userId.
     *
     * @param int $id, user id.
     *
     * @return void
     */
    public function viewByUserAction($userId) {
        $res = $this->comments->findByUser($userId);

        $res = $this->addTagsString($res);
        $res = $this->addUserData($res);
        $res = $this->addNumberAnswers($res);
        $res = $this->filterMarkdown($res);

        $user = isset($res[0]->name) ? $res[0]->name : null;

        $this->views->add('comment/commentsdb', [
            'comments' => $res,
            'title' => 'all entries from ' . $user,
        ]);
    }

    /**
     * View answers tied to comment id.
     *
     * @param int $id, comment id.
     *
     * @return void
     */
    public function answersAction($id) {
        $all_id = $this->comm2comm->find($id);

        $all = [];
        foreach ($all_id as $key => $value) {
            $all[] = $this->comments->find($value->idAnswer);
        }

        $all = $this->addTagsString($all);
        $all = $this->addUserData($all);
        $all = $this->addNumberAnswers($all);
        $all = $this->filterMarkdown($all);

        $question[0] = $this->comments->find($id);
        $question = $this->addTagsString($question);
        $question = $this->addUserData($question);
        $question = $this->filterMarkdown($question);

        $this->views->add('comment/commentsdb', [
            'question' => $question,
            'comments' => $all,
            'title' => 'post',
        ]);
    }

    /**
     * Filter markdown in comment object.
     *
     * @param array $all, comment objects.
     *
     * @return array $all.
     */
    private function filterMarkdown($all) {
        foreach ($all as $value) {

            $value->comment = $this->textFilter->doFilter($value->comment, 'shortcode, markdown');

        }
        return $all;
    }

    /**
     * Add Tag string array to a comment object.
     *
     * @param array $all, comment objects.
     *
     * @return array $all.
     */
    private function addTagsString($all) {
        foreach ($all as $value) {

            $tag_names = [];

            foreach ($this->comments2tags->find($value->id) as $inner_value) {
                $tag_names[] = $this->comments2tags->getTagName($inner_value->idTag)->name;
            }

            $value->tags = $tag_names;

        }
        return $all;
    }

    /**
     * Add user data to comment objects.
     *
     * @param array $all, comment objects.
     *
     * @return array $all.
     */
    private function addUserData($all) {

        foreach ($all as $value) {

            $user_data = $this->users->find($value->userId);
            $value->name = $user_data->name;
            $value->mail = $user_data->email;
            $value->gravatar = $user_data->gravatar;

        }
        return $all;
    }

    /**
     * Add number of answers to comment objects.
     *
     * @param array $all, comment objects.
     *
     * @return array $all.
     */
    private function addNumberAnswers($all) {

        foreach ($all as $value) {
            $num_answers = $this->comm2comm->numberAnswers($value->id);
            $value->answers = $num_answers[0]->answers;
        }
        return $all;
    }

    /**
     * View active tags.
     *
     * @return void
     */
    public function tagsAction() {
        $res = $this->tags->findTags();

        $this->views->add('comment/tags', [
            'tags' => $res,
            'title' => 'all active tags',
        ]);
    }

    /**
     * View all tags.
     *
     * @return void
     */
    public function allTagsAction() {
        $res = $this->tags->findAllTags();

        $this->views->add('comment/tags', [
            'tags' => $res,
            'title' => $this->theme->getVariable('title'),
        ]);
    }

    /**
     * View all comments with tag
     *
     * @param string $name of tag to list.
     *
     * @return void
     */
    public function tagCommentsAction($name = null) {

        $this->db->select('C.*, T.name AS tag')
            ->from('comment AS C')
            ->leftOuterJoin('comment2tags AS C2T', 'C.id = C2T.idComment')
            ->leftOuterJoin('tags AS T', 'C2T.idTag = T.id')
            ->where('T.name = "' . $name . '"')
        ;

        $res = $this->db->executeFetchAll();

        $res = $this->addTagsString($res);
        $res = $this->addUserData($res);
        $res = $this->addNumberAnswers($res);
        $res = $this->filterMarkdown($res);

        $this->views->add('comment/commentsdb', [
            'comments' => $res,
            'title' => $this->theme->getVariable('title'),
        ]);
    }

    /**
     * Reset and setup database table.
     *
     * @return void
     */
/*    public function setupAction() {
 
        $this->theme->setTitle("Reset and setup database.");

        $table_comment = [
            'id' => ['integer', 'primary key', 'not null', 'auto_increment'],
            'comment' => ['tinytext'],
            'name' => ['varchar(80)'],
            'web' => ['varchar(80)'],
            'mail' => ['varchar(80)'],
            'gravatar' => ['varchar(80)'],
            'timestamp' => ['datetime'],
            'ip' => ['varchar(20)'],
        ];
        $res = $this->comments->setupTable($table_comment);

        $table_tags = [
            'id' => ['integer', 'primary key', 'not null', 'auto_increment'],
            'name' => ['varchar(20)'],
        ];
        $res = $this->tags->setupTable($table_tags);

        $table_comment2tags = [
            'idComment' => ['integer', 'not null'],
            'idTag' => ['integer', 'not null'],
        ];
        $res = $this->comments2tags->setupTable($table_comment2tags);

        // Add a comment
        $this->comments->create([
            'comment' => 'Your database connection is all ok if you see this message!',
            'name' => 'No-name',
            'web' => 'www.default.se',
            'mail' => 'default@default.se',
            'gravatar' => getGravatar('default@default.se', 60),
            'timestamp' => time(),
            'ip' => $this->request->getServer('REMOTE_ADDR'),
        ]);

        // Add default tags
        $this->tags->create([
            'name' => 'base',
        ]);
        $this->tags->create([
            'name' => 'wall',
        ]);
        $this->tags->create([
            'name' => 'roof',
        ]);

        // Add default comment to tag
        $this->comments2tags->create([
            'idComment' => 1,
            'idTag' =>  1,
        ]);
        $this->comments2tags->create([
            'idComment' => 1,
            'idTag' =>  2,
        ]);
        $this->comments2tags->create([
            'idComment' => 1,
            'idTag' =>  3,
        ]);
 
        $url = $this->url->create('comment/view');
        $this->response->redirect($url);
    }
*/
    
    /**
     * Add a comment.
     *
     * @return void
     */
    public function addAction($id = null, $idFromQuestion = null) {

        // If 'null' is sent as string from url instead of id.
        if ($id == 'null') { $id = null; }

        // Edit comment object default values
        $edit_comment = (object) [
            'comment' => '',
            'tags' => '',
            'title' => '',
        ];

        // If in edit mode, get relevant data from database.
        if ($id) {
            $edit_comment = $this->comments->find($id);

            // Authenticate edit
            if ($edit_comment->userId != $_SESSION['authenticated']['user']->id) {
                die("You can only edit your own posts.");
            }

            foreach ($this->comments2tags->find($id) as $key => $value) {
                $edit_comment->tags[] = $this->comments2tags->getTagName($value->idTag)->name;
            }
        }

        // Convert tags db result to simple array
        $tags_array = [];
        foreach ($this->tags->findAllTags() as $key => $value) {
            $tags_array[$value->id] = $value->name;
        }

        $form_setup = [
            'comment' => [
              'type'        => 'textarea',
              'label'       => 'Comment: ',
              'required'    => true,
              'validation'  => ['not_empty'],
              'value'       => $edit_comment->comment,
            ],
            'submit' => [
                'type'      => 'submit',
                'callback'  => function($form) {
                    $form->saveInSession = true;
                    return true;
                }
            ],
        ];

        $is_answer = $this->comm2comm->isAnswer($id);

        if( !isset($idFromQuestion) && ($is_answer == null) ) {
            $form_setup_add['tags'] = [
              'type'        => 'checkbox-multiple',
              'values'      => $tags_array,
              'label'       => 'Tags: ',
              'required'    => true,
              'checked'     => $edit_comment->tags,
            ];
            $form_setup_add['title'] = [
              'type'        => 'text',
              'label'       => 'Title: ',
              'required'    => true,
              'validation'  => ['not_empty'],
              'value'       => $edit_comment->title,
            ];

            $form_setup = $form_setup_add + $form_setup;
        }

        // Create form
        $form = $this->form->create([], $form_setup );

        // Check the status of the form
        $status = $form->check();

        if(!isset($idFromQuestion) && !isset($_SESSION['form-save']['tags']['values'])) {
            $status = false;
            $form->AddOutput("<p><i>At least one tag is required.</i></p>");
        }
         
        if ($status === true) {

            // Get data from and and unset the session variable
            $comment['id']          = isset($id) ? $id : null;
            $comment['comment']     = $_SESSION['form-save']['comment']['value'];
            $comment['userId']      = $_SESSION['authenticated']['user']->id;
            $comment['timestamp']   = time();
            $comment['ip']          = $this->request->getServer('REMOTE_ADDR');
            $comment['title']       = !isset($idFromQuestion) ? $_SESSION['form-save']['title']['value'] : 'Re: ' . $this->comments->find($idFromQuestion)->title;
            $tags                   = !isset($idFromQuestion) ? $_SESSION['form-save']['tags']['values'] : null;

            unset($_SESSION['form-save']);

            // Update or save comment.
            $this->comments->save($comment);
            $row['idComment'] = isset($id) ? $id : $this->comments->findLastInsert();

            // Update or save tags
            if (!isset($idFromQuestion)) {
                if(isset($id)) {
                    $this->comments2tags->delete($id);
                }
                foreach ($tags as $key => $value) {
                    $row['idTag'] = array_search($value, $tags_array);
                    $this->comments2tags->save($row);
                }
            }

            // Update or save questions to answers
            if ($idFromQuestion) {
                $data = [
                    'idQuestion' => $idFromQuestion,
                    'idAnswer' => $row['idComment'],
                ];
                $this->comm2comm->save($data);

                $url = $this->url->create('comment/answers/' . $idFromQuestion);

            } else {

                $url = $this->url->create('comment/view-questions');

            }

            // Route to prefered controller function
            $this->response->redirect($url);
         
        } else if ($status === false) {  

            // What to do when form could not be processed?
            $form->AddOutput("<p><i>Form submitted but did not checkout.</i></p>");
        }

        // Prepare the page content
        $this->views->add('comment/view-default', [
            'title' => "Add a comment",
            'main' => $form->getHTML(),
        ]);
        $this->theme->setVariable('title', "Add a user");

    }


    public function removeIdAction($id = null) {
        if (!isset($id)) {
            die("Missing id");
        }

        // Authenticate deletion
        $comment = $this->comments->find($id);
        if ($comment->userId != $_SESSION['authenticated']['user']->id) {
            die("You can only edit your own posts.");
        }

        $this->comments->delete($id);
        $this->comments2tags->delete($id);
        $this->comm2comm->delete($id);

        $url = $this->url->create('comment/view-questions');
        $this->response->redirect($url);
    }

}
