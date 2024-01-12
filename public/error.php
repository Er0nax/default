<?php
// include all files
include('../app/config/init.php');

// set new page
global $app;
$app->page->setPage('error');

// include layout
include('../views/layout.php');