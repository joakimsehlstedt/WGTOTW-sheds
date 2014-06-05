<h1><?=$title?></h1>

<table style='width: 100%; text-align: left;'>

<tr>
	<th><?='Id'?></th>
	<th><?='Gravatar'?></th>
	<th><?='Acronym'?></th>
	<th><?='Name'?></th>
	<th><?='Email'?></th>
	<th><?='Edit'?></th>
	<th><?='Delete'?></th>
	<th><?='Active'?></th>
	<th><?='Remove'?></th>
</tr> 

<?php foreach ($users as $user) : ?>
<tr>
	<td><?=$user->id?></td>
	<td>
		<a href="<?=$this->url->create('comment/view-by-user/' . $user->id) ?>">
		<img src="<?=$user->gravatar?>" style="width: 30px;"></a>
	</td>
	<td><?=$user->acronym?></td>
	<td><?=$user->name?></td>
	<td><?=$user->email?></td>
<!--	
	<td><?=isset($user->active) ? 'Y' : 'N'?></td>
	<td><?=isset($user->deleted) ? 'Y' : 'N'?></td>
-->	

	<td><a href="<?=$this->url->create('users/update/' . $user->id) ?>"><i class='fa fa-edit'></i></a></td>

	<?php if (isset($user->deleted)) : ?>
	<td><a href="<?=$this->url->create('users/soft-undelete/' . $user->id) ?>"><i class='fa fa-undo'></i></a></td>
	<?php else : ?>
	<td><a href="<?=$this->url->create('users/soft-delete/' . $user->id) ?>"><i class='fa fa-trash-o'></i></a></td>
	<?php endif; ?>

	<?php if (isset($user->active)) : ?>
	<td><a href="<?=$this->url->create('users/deactivate/' . $user->id) ?>"><i class='fa fa-check-square-o'></i></a></td>
	<?php else : ?>
	<td><a href="<?=$this->url->create('users/activate/' . $user->id) ?>"><i class='fa fa-square-o'></i></a></td>
	<?php endif; ?>

	<td><a href="<?=$this->url->create('users/delete/' . $user->id) ?>"><i class='fa fa-times'></i></a></td>

</tr> 
<?php endforeach; ?>

</table>
 
<p><a href='<?=$this->url->create('users/list')?>'>Full list...</a></p>