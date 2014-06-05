<h2><?=$title?></h2>

<div class="user-blob-wrapper">

<?php foreach ($users as $user) : ?>
<?php $url = $this->url->create('comment/view-by-user/' . $user->id); ?>


<div class="user-blob">
<a href="<?= $this->url->create('comment/view-by-user/' . $user->id) ?>">

	<div class="avatar">
		<img src="<?=$user->gravatar?>">
	</div>

	<div class="user-text">
		<span class="user-name"><?=$user->name?></span><br>
		<span class="user-email"><?=$user->email?></span>
	</div>

</a>
</div>

<?php endforeach; ?>

</div>