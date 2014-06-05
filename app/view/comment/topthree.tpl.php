<div id="stats-wrapper">

<table>
<tr>
	<th><h2>recent posts</h2></th>
	<th><h2>top tags</h2></th>
	<th><h2>top contributors</h2></th>
</tr>
<tr>
	<td class='stats-col'>
	<?php foreach ($new as $value) : ?>

		<span class="post-button-large">
			<a href="<?=$this->url->create('comment/answers/' . $value->id) ?>">
			<?= (strlen($value->title) > 35) ? substr($value->title, 0, 32) . '...' : $value->title ?></a>
		</span><br>

	<?php endforeach; ?>
	</td>

	<td class='stats-col'>
	<?php foreach ($tags as $value) : ?>

		<span class="tag-button-large">
			<a href="<?=$this->url->create('comment/tag-comments/' . $value->tag) ?>">
			<?= $value->tag ?> <small>(<?= $value->tc ?>)</small></a>
		</span><br>

	<?php endforeach; ?>
	</td>

	<td class='stats-col'>
	<?php foreach ($users as $value) : ?>

		<span class="post-button-large">
			<a href="<?=$this->url->create('comment/view-by-user/' . $value->userId) ?>">
			<?= $value->name ?> <small>(<?= $value->uc ?>)</small></a>
		</span><br>

	<?php endforeach; ?>
	</td>
</tr>
</table>
<br><hr>
</div>