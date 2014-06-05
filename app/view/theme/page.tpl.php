<article>

    <?php
    if (isset($content)) {
        echo $content;
    }
    ?>

    <?php if (isset($byline)) : ?>
        <hr>
        <footer class="byline">
            <?= $byline ?>
        </footer>
    <?php endif; ?>

</article>