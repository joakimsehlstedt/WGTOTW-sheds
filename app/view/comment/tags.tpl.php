<div class="tag-cloud">

<h2><?=$title?></h2>

<?php foreach ($tags as $tag) : ?>

	<span class="tag-button-large"><a href="<?=$this->url->create('comment/tag-comments/' . $tag->name) ?>"><?= $tag->name ?></a></span>

<?php endforeach; ?>

</div>