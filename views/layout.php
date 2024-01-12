<?php global $app ?>

<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="utf-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no"/>

    <meta content="<?= $app->env->variables['METADESCRIPTION'] ?>" name="description">
    <meta content="<?= $app->env->variables['METAKEYWORDS'] ?>" name="keywords">
    <meta content="<?= $app->env->variables['METAAUTHOR'] ?>" name="author">

    <title><?= $app->env->variables['TITLE'] ?> | <?= $app->page->info['title'] ?></title>

    <link rel="icon" type="image/x-icon" href="assets/img/icons/favicon.ico"/>

    <!-- CSS Files -->
    <?php if (!empty($app->page->cssFiles)) : ?>
        <?php foreach ($app->page->cssFiles as $cssFile) : ?>
            <link rel="stylesheet" href="<?= $cssFile ?>?v=<?= $app->settings->cssVersion ?>"/>
        <?php endforeach; ?>
    <?php endif ?>
</head>
<body>
<div class="loading-line"></div>

<!-- Header-->
<?php include('_partials/header.php'); ?>

<div id="pagecontent">

    <!-- site functions -->
    <?php if (file_exists('../app/modules/site/' . $app->page->info['name'] . '.php')) : ?>
        <?php include('../app/modules/site/' . $app->page->info['name'] . '.php') ?>
    <?php endif ?>

    <!-- content-->
    <?php include('../views/content/' . $app->page->info['name'] . '/index.view.php'); ?>
</div>

<!-- Footer-->
<?php include('_partials/footer.php'); ?>

<script>
    const siteUrl = '<?= $app->settings->url ?>';
    const siteTitle = '<?= $app->env->variables['TITLE'] ?> | <?= $app->page->info['title'] ?>';
    const auto_scroll_top = true;
</script>

<!-- scripts -->
<?php if (!empty($app->page->jsFiles)) : ?>
    <?php foreach ($app->page->jsFiles as $jsFile) : ?>
        <script type="<?= $jsFile['type'] ?>" src="<?= $jsFile['url'] ?>?v=<?= $app->settings->jsVersion ?>"></script>
    <?php endforeach; ?>
<?php endif ?>

</body>
</html>
