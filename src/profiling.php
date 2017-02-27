<?php
/**
 * Profiling bootstrap file for using the Profiling tool before autoload
 */

use \Profiling\TraversalProfiler;

defined('DEFAULT_PROFILER') || define('DEFAULT_PROFILER', TraversalProfiler::class);


switch (DEFAULT_PROFILER) {
    case TraversalProfiler::class:
    default:
        loadCommonFiles();
        loadMockFiles();
        loadTraversalFiles();
        break;
}

function loadCommonFiles()
{
    define('COMMON_DIR', __DIR__);

    require COMMON_DIR . DIRECTORY_SEPARATOR . 'Profiler.php';
    require COMMON_DIR . DIRECTORY_SEPARATOR . 'functions.php';
}

function loadMockFiles()
{
    define('MOCK_DIR', __DIR__);

    require COMMON_DIR . DIRECTORY_SEPARATOR . 'MockProfiler.php';
}

/**
 * Load the files needed for the Traversal Profiler
 */
function loadTraversalFiles()
{
    define('TRAVERSAL_PROFILER_DIR', __DIR__);

    require TRAVERSAL_PROFILER_DIR . DIRECTORY_SEPARATOR . 'TraversalProfiler.php';
    require TRAVERSAL_PROFILER_DIR . DIRECTORY_SEPARATOR . 'TraversalProfilerTree.php';
    require TRAVERSAL_PROFILER_DIR . DIRECTORY_SEPARATOR . 'TraversalProfilerNode.php';
}