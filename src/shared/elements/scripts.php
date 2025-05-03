<?php

$appUrlPath = $_SERVER['REQUEST_URI'] ?? null;
$appDirName = explode('/', trim($appUrlPath, '/'))[2] ?? '';

// All scripts needed by the application
$scripts = [
    // Regular scripts
    ['src' => 'lib/jquery/jquery.min.js', 'module' => false, 'utility' => false],
    ['src' => 'vendor/fomantic-ui/dist/semantic.min.js', 'module' => false, 'utility' => false],

    // Bootstrap modules (loaded as ES modules)
    ['src' => 'vendor/bootstrap/js/src/base-component.js', 'module' => true, 'utility' => false],
    ['src' => 'vendor/bootstrap/js/src/button.js', 'module' => true, 'utility' => false],
    ['src' => 'vendor/bootstrap/js/src/collapse.js', 'module' => true, 'utility' => false],
    ['src' => 'vendor/bootstrap/js/src/tab.js', 'module' => true, 'utility' => false],

    // Regular scripts
    ['src' => 'lib/lodash/lodash.min.js', 'module' => false, 'utility' => false],
    ['src' => 'js/loader/window.js', 'module' => false, 'utility' => false],
    ['src' => 'js/darkmode.js', 'module' => false, 'utility' => false],
    ['src' => 'js/' . $appDirName . '/script.js', 'module' => false, 'utility' => false],
    ['src' => 'js/' . $appDirName . '/prefetch.js', 'module' => false, 'utility' => false],
    ['src' => 'js/scripts.js', 'module' => false, 'utility' => false],

    // Utils
    ['src' => 'js/validateHandler.js', 'module' => false, 'utility' => true],
    ['src' => 'js/middleware.js', 'module' => false, 'utility' => true],
];

foreach ($scripts as $script) {
    $moduleAttr = $script['module'] ? ' type="module"' : '';
    $srcRef = $script['utility'] ? utils($script['src'], true) : asset($script['src']);
    echo '<script' . $moduleAttr . ' src="' . $srcRef . '"></script>';
}

?>

<script>
    $(function () {
        // Custom js here...
    });
</script>