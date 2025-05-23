<?php
$isAuthIndex = uriAppPath('auth') && uriPagePath() === 'index';
$text = $isAuthIndex
    ? 'Terms of Service and Privacy Policy'
    : 'By creating an account, you agree to the <br> Terms of Service and Privacy Policy';
?>
<div class="section-out-footer <?= !$isAuthIndex ? 'mt-2' : '' ?>">
    <div class="ui text white"><?= $text ?></div>
</div>