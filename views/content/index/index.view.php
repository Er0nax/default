<?php if (!class_exists('index')) : ?>
    <?php include('../app/modules/site/index.php'); ?>
<?php endif ?>

<?php $index = new index(); ?>

<?php include('../views/content/index/content.view.php'); ?>