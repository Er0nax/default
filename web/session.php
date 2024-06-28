<?php
session_start();

echo '<pre>';

if (!empty($_GET['key'])) {
    var_dump($_SESSION[$_GET['key']]);
} else {
    var_dump($_SESSION);
}
exit();