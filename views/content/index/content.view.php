<?php global $app; ?>

<p><?= t('Here you can find some cool information!') ?></p>

<?php if (file_exists('../README.md')): ?>
    <?php include('../README.md') ?>
<?php endif ?>
