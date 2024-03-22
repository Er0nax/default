<?php global $app; ?>
<?php if (!class_exists('error')) : ?>
    <?php include('../app/modules/error/error.php'); ?>
<?php endif ?>


<h1><?= $app->page->info['title'] ?? 'Error'; ?></h1>