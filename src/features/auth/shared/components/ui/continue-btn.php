<?php

$urlPath = $_SERVER['REQUEST_URI'] ?? null;
$dirName = explode('/', trim($urlPath, '/'))[3] ?? '';
$pageName = str_replace('.php', '', $dirName);

?>
<button class="ui fluid large teal submit button" type="submit">
    <?= $pageName === 'register' ? 'Register' : 'Continue' ?>
</button>