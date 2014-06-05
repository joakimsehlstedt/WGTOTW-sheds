<div class='pure-form pure-form-stacked'>
    <form method='post'>
        <input type='hidden' name="redirect" value="<?= $this->url->create($key) ?>">
        <input type='hidden' name="pageKey" value="<?= $key ?>">
        <fieldset>
            <legend>Leave a comment</legend>
            <label for='content'>Comment:</label><textarea name='content'><?= $content ?></textarea>
            <label for='name'>Name:</label><input type='text' name='name' value='<?= $name ?>'/>
            <label for='web'>Homepage:</label><input type='text' name='web' value='<?= $web ?>'/>
            <label for='mail'>Email:</label><input type='text' name='mail' value='<?= $mail ?>'/>
            <input class="pure-button pure-button-primary" type='submit' name='doCreate' value='Comment' onClick="this.form.action = '<?= $this->url->create('comment/add') ?>'"/>
            <input class="pure-button" type='reset' value='Reset'/>
            <input class="pure-button" type='submit' name='doRemoveAll' value='Remove all' onClick="this.form.action = '<?= $this->url->create('comment/remove-all') ?>'"/>
            <output><?= $output ?></output>
        </fieldset>
    </form>
</div>
