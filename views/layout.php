<?php global $app ?>
<?php $manifestVersion = $app->settings->manifestVersion; ?>

<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="utf-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no"/>

    <title><?= $app->env->variables['TITLE'] ?> | <?= $app->page->info['title'] ?></title>

    <meta content="<?= $app->env->variables['METADESCRIPTION'] ?>" name="description">
    <meta content="<?= $app->env->variables['METAKEYWORDS'] ?>" name="keywords">
    <meta content="<?= $app->env->variables['METAAUTHOR'] ?>" name="author">
    <meta name="theme-color" content="<?= $app->env->variables['METACOLOR'] ?>"/>
    <meta name="publisher" content="<?= $app->env->variables['METAPUBLISHER'] ?>"/>
    <meta name="copyright" content="<?= $app->env->variables['METACOPYRIGHT'] ?>"/>
    <meta http-equiv="Reply-to" content="<?= $app->env->variables['EMAIL'] ?>"/>
    <meta http-equiv="content-Language" content="<?= $app->env->variables['LANG'] ?>"/>
    <meta name="page-topic" content="<?= $app->env->variables['TYPE'] ?>"/>
    <meta name="page-type" content="<?= $app->env->variables['TYPE'] ?>"/>

    <meta http-equiv="content-Type" content="text/html; utf-8"/>
    <meta http-equiv="Pragma" content="cache"/>
    <meta name="robots" content="noindex"/>
    <meta name="audience" content="All"/>
    <meta name="expires" content=""/>
    <meta name="revisit-after" content="2 days"/>

    <link rel="icon" type="image/x-icon" href="assets/manifest/icons/icon-48x48.webp">
    <link rel="shortcut icon" type="image/x-icon" href="assets/manifest/icons/icon-48x48.webp">

    <!-- CSS Files -->
    <?php if (!empty($app->page->cssFiles)) : ?>
        <?php foreach ($app->page->cssFiles as $cssFile) : ?>
            <link rel="stylesheet" href="<?= $cssFile ?>?v=<?= $app->settings->cssVersion ?>"/>
        <?php endforeach; ?>
    <?php endif ?>

    <link rel="apple-touch-icon" href="assets/manifest/icons/icon-512x512.webp?v=<?= $manifestVersion ?>"/>
    <link rel="shortcut icon" href="assets/manifest/icons/icon-512x512.webp?v=<?= $manifestVersion ?>" type="image/webp"/>
    <link rel="shortcut icon" href="assets/manifest/icons/icon-48x48.webp?v=<?= $manifestVersion ?>" type="image/x-icon"/>
    <link rel="shortcut icon" href="assets/manifest/icons/icon-48x48.webp?v=<?= $manifestVersion ?>" type="image/png"/>
    <link rel="manifest" href="assets/manifest/manifest.webmanifest?v=<?= $manifestVersion ?>">
</head>
<body>
<div class="loading-line"></div>

<!-- Header-->
<?php include('_partials/header.php'); ?>

<!-- Page Preloder -->
<div id="preloder" style="display: <?= ($app->page->info['showPreloader'] == 'true') ? 'block' : 'none' ?>;">
    <div class="loader"></div>
</div>

<div id="pagecontent">

    <!-- site functions -->
    <?php if (file_exists('../app/modules/site/' . $app->page->info['name'] . '.php')) : ?>
        <?php include('../app/modules/site/' . $app->page->info['name'] . '.php') ?>
    <?php endif ?>

    <!-- content-->
    <?php include('../views/content/' . $app->page->info['name'] . '/index.view.php'); ?>
</div>

<a class="click page-btn-reload"
   style="opacity: 0;"
   data-page="<?= $app->page->info['name'] ?>"
   data-isreload="true"
   data-param="<?php foreach ($_GET as $key => $value) : echo '&' . $key . '=' . $value; endforeach; ?>">
</a>

<!-- Footer-->
<?php include('_partials/footer.php'); ?>

<script>
    const siteUrl = '<?= $app->settings->url ?>';
    const siteTitle = '<?= $app->env->variables['TITLE'] ?> | <?= $app->page->info['title'] ?>';
    <?php foreach ($app->settings->siteJsSettings as $key => $value) : ?>
    const <?= $key ?> = '<?= $value ?>';
    <?php endforeach; ?>
</script>

<!-- scripts -->
<?php if (!empty($app->page->jsFiles)) : ?>
    <?php foreach ($app->page->jsFiles as $jsFile) : ?>
        <script type="<?= $jsFile['type'] ?>" src="<?= $jsFile['url'] ?>?v=<?= $app->settings->jsVersion ?>"></script>
    <?php endforeach; ?>
<?php endif ?>

</body>
</html>
