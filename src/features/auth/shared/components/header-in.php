<?php

$urlPath = $_SERVER['REQUEST_URI'] ?? null;
$dirName = explode('/', trim($urlPath, '/'))[3] ?? '';
$pageName = str_replace('.php', '', $dirName);

?>
<div class="ui teal header section-in-header">
    <?= $pageName === 'register' ? 'Sign up' : 'Sign in' ?>
</div>