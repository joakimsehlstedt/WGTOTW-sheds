<h3><?=$title?></h3>

<?php if (isset($question)) : ?>

    <div class="post-wrapper">    
        
        <div class="avatar">
            <a href="<?=$this->url->create('comment/view-by-user/' . $question[0]->userId) ?>">
            <img class="avatar" src="<?=$this->url->asset($question[0]->gravatar)?>" alt="Avatar"></a>
        </div>

        <div class="post-body">
            
            <div class="post-header">
                <span class="post-name"><?= (strlen($question[0]->title) > 35) ? substr($question[0]->title, 0, 32) . '...' : $question[0]->title ?></span>
                
                <?php if (!isset($question[0]->tags[0])) : ?>
                        <span class="post-id answer"> | Answer</span>
                    <?php else: ?>
                        <span class="post-id"> | Question</span>
                <?php endif; ?>

            </div>
            
            <div class="post-content">
            <?= $question[0]->comment ?>
            </div>

            <div class="post-tags">
                <?php foreach ($question[0]->tags as $tag) : ?>
                <span class="tag-button"><a href="<?=$this->url->create('comment/tag-comments/' . $tag) ?>"><?= $tag ?></a></span>
                <?php endforeach; ?>
            </div>
            
            <div class="post-footer">
            <?= $question[0]->name ?> | <?= $question[0]->mail ?>
            </div>

        </div>

    </div>
    <div class="divider">
        <br>
        <hr>
        <h3>Answers</h3>
    </div>
<?php endif; ?>


<div class="answers">
<?php if (is_array($comments)) : ?>

    <ul class=<?php if (isset($question)) { echo('post-list-answer'); } else { echo("post-list"); } ?> >
        <?php foreach ($comments as $comment) : ?>
            <li class="post">
                    
            <div class="post-wrapper">

                <div class="avatar">
                    <a href="<?=$this->url->create('comment/view-by-user/' . $comment->userId) ?>">
                    <img class="avatar" src="<?=$this->url->asset($comment->gravatar)?>" alt="Avatar"></a>
                </div>

                <div class="post-body">
                    
                    <div class="post-header">
                    <span class="post-name"><?= (strlen($comment->title) > 35) ? substr($comment->title, 0, 32) . '...' : $comment->title ?></span>
                    <?php if (!isset($comment->tags[0])) : ?>
                        <span class="post-id answer"> | Answer</span>
                    <?php else: ?>
                        <span class="post-id"> | Question</span>
                    <?php endif; ?>

                    <span class="post-menu">

                    <?php if ($comment->userId == $_SESSION['authenticated']['user']->id) : ?>
                        <a href="<?=$this->url->create('comment/remove-id/' . $comment->id) ?>"><i class="fa fa-trash-o"></i></a>
                        <a href="<?=$this->url->create('comment/add/' . $comment->id) ?>"><i class="fa fa-edit"></i></a>
                    <?php endif; ?>

                    <a href="<?=$this->url->create('comment/add/null/' . $comment->id) ?>"><i class="fa fa-reply"></i></a>
                    <a href="<?=$this->url->create('comment/answers/' . $comment->id) ?>">Read <?= $comment->answers ?> answers</i></a>
                    </span>

                    </div>
                    
                    <div class="post-content">
                    <?= $comment->comment ?>
                    </div>

                    <div class="post-tags">
                        <?php foreach ($comment->tags as $tag) : ?>
                        <span class="tag-button"><a href="<?=$this->url->create('comment/tag-comments/' . $tag) ?>"><?= $tag ?></a></span>
                        <?php endforeach; ?>
                    </div>
                    
                    <div class="post-footer">
                    <?= $comment->name ?> | <?= $comment->mail ?>

                    </div>

                </div>

            </div>

            </li>
        <?php endforeach; ?>
    </ul>

<?php endif; ?>
</div>

<div class="add-comment">
    <hr>
    <?php if (isset($question)) : ?>
        <a href="<?=$this->url->create('comment/add/null/' . $question[0]->id) ?>">add answer <i class="fa fa-reply"></i></a>
    <?php else: ?>
        <a href="<?=$this->url->create('comment/add') ?>">add question <i class="fa fa-reply"></i></a>
    <?php endif; ?>
</div>