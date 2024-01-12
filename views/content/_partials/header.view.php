<?php global $app ?>

<nav class="navbar navbar-expand-lg navbar-light bg-light">
    <div class="container px-4 px-lg-5">
        <a class="navbar-brand click" data-page="index">Default</a>

        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false"
                aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0 ms-lg-4">

                <?php if (!empty($app->page->pages)) : ?>
                    <?php foreach ($app->page->pages as $page) : ?>

                        <?php $title = $page['title'] ?>
                        <?php $name = $page['name'] ?>
                        <?php $activeClass = ($app->page->info['name'] === $name) ? 'active' : '' ?>

                        <?php if ($app->page->followsRules($name, false, true)) : ?>
                            <?php if ($app->page->info['isRawPage'] == 'false'): ?>
                                <li class="nav-item">
                                    <a class="nav-link click <?= $activeClass ?>"
                                       data-page="<?= $name ?>">
                                        <?= $title ?>
                                    </a>
                                </li>
                            <?php else : ?>
                                <li class="nav-item">
                                    <a class="nav-link <?= $activeClass ?>"
                                       href="<?= $name ?>">
                                        <?= $title ?>
                                    </a>
                                </li>
                            <?php endif; ?>
                        <?php endif; ?>

                    <?php endforeach; ?>
                <?php endif ?>
            </ul>
        </div>
    </div>
</nav>