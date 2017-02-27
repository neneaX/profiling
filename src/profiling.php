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

    require COMMON_DIR . 'Profiler.php';
    require COMMON_DIR . 'functions.php';
}

function loadMockFiles()
{
    define('MOCK_DIR', __DIR__);

    require COMMON_DIR . 'MockProfiler.php';
}

/**
 * Load the files needed for the Traversal Profiler
 */
function loadTraversalFiles()
{
    define('TRAVERSAL_PROFILER_DIR', __DIR__);

    require TRAVERSAL_PROFILER_DIR . 'TraversalProfiler.php';
    require TRAVERSAL_PROFILER_DIR . 'TraversalProfilerTree.php';
    require TRAVERSAL_PROFILER_DIR . 'TraversalProfilerNode.php';
}