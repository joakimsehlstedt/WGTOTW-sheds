<div class='comment-form'>
    <form method=post>
        <input type=hidden name="redirect" value="<?= $this->url->create($key) ?>">
        <input type=hidden name="postId" value="<?= $id ?>">
        <input type=hidden name="pageKey" value="<?= $key ?>">
        <fieldset>
            <legend>Edit your comment</legend>
            <label>Comment:<br><textarea name='content'><?= $content ?></textarea></label><br>
            <label>Name:<br><input type='text' name='name' value='<?= $name ?>'/></label><br>
            <label>Homepage:<br><input type='text' name='web' value='<?= $web ?>'/></label><br>
            <label>Email:<br><input type='text' name='mail' value='<?= $mail ?>'/></label>
            <div class=buttons>
                <input type='submit' name='doSave' value='Save' onClick="this.form.action = '<?= $this->url->create('comment/save-changes') ?>'"/>
                <input type='reset' value='Reset'/>
                <input type='submit' name='cancel' value='Cancel' onClick="this.form.action = '<?= $this->url->create($key) ?>'"/>
            </div>
        </fieldset>
    </form>
</div>
