<?php

$appUrlPath = $_SERVER['REQUEST_URI'] ?? null;
$appDirName = explode('/', trim($appUrlPath, '/'))[2] ?? '';

rcsScripts();
function rcsScripts()
{
    // All scripts needed by the application
    $scripts = [
        // Regular scripts
        ['src' => 'lib/jquery/jquery.min.js', 'module' => false],
        ['src' => 'vendor/fomantic-ui/dist/semantic.min.js', 'module' => false],

        // Bootstrap modules (loaded as ES modules)
        ['src' => 'vendor/bootstrap/js/src/base-component.js', 'module' => true],
        ['src' => 'vendor/bootstrap/js/src/button.js', 'module' => true],
        ['src' => 'vendor/bootstrap/js/src/collapse.js', 'module' => true],
        ['src' => 'vendor/bootstrap/js/src/tab.js', 'module' => true],

        // Regular scripts
        ['src' => 'lib/lodash/lodash.min.js', 'module' => false],
        ['src' => 'js/loader/window.js', 'module' => false],
        ['src' => 'js/darkmode.js', 'module' => false],
        // ['src' => 'js/' . $appDirName . '/script.js', 'module' => false],
        // ['src' => 'js/' . $appDirName . '/prefetch.js', 'module' => false],
        ['src' => 'js/scripts.js', 'module' => false],
    ];

    foreach ($scripts as $script) {
        $moduleAttr = $script['module'] ? ' type="module"' : '';
        echo '<script' . $moduleAttr . ' src="' . asset($script['src']) . '"></script>';
    }
}


utilScripts();
function utilScripts()
{
    // Utility scripts
    $utilityScripts = [
        // 'js/validateHandler.js',
        'js/middleware.js',
    ];

    foreach ($utilityScripts as $script) {
        echo '<script src="' . utils($script, true) . '"></script>';
    }
}

?>

<script>
    // API URL Helper
    function apiUrl(feature = null) {
        return `<?= baseUrl() ?>src/features/${feature}/api/`;
    }
</script>