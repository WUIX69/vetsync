<?php $pageName = uriPagePath(); ?>
<button class="ui fluid large teal submit button" type="submit">
    <?= $pageName === 'register' ? 'Register' : 'Continue' ?>
</button>