<h2>my profile</h2>

<ul style="list-style: none;">
	<li><img src="<?=getGravatar($user->email, 80)?>"></li>
	<li>Name: <strong><?=$user->name?></strong></li>
	<li>Acronym: <strong><?=$user->acronym?></strong></li>
	<li>Email: <strong><?=$user->email?></strong></li>
	<li>Created: <strong><?=$user->created?></strong></li>
</ul>

<p>
<a href="<?=$this->url->create('users/update/' . $user->id) ?>"><i class='fa fa-edit'></i> Update your profile</a>
</p>