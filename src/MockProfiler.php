<?php

namespace Profiling;

/**
 * Class MockProfiler
 * @package Profiling
 */
class MockProfiler implements Profiler
{

    /**
     * The singleton instance
     *
     * @var Profiler
     */
    private static $instance;

    /**
     * Enable the profiler
     */
    public static function on()
    {
        // nothing to do here
    }

    /**
     * Disable the profiler
     */
    public static function off()
    {
        // nothing to do here
    }

    /**
     * Check if the profiler is enabled
     *
     * @return bool
     */
    public static function isOn()
    {
        // nothing to do here
        return false;
    }

    /**
     * Get the singleton instance
     *
     * @return Profiler
     */
    public static function getInstance()
    {
        if (!self::$instance instanceof self) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    /**
     * @param string $label
     * @param mixed  $data
     */
    public function start($label, $data = null)
    {
        // nothing to do here
    }

    /**
     *
     */
    public function end()
    {
        // nothing to do here
    }

    /**
     * Print debug messages
     *
     * @param string $message
     * @param bool   $force
     */
    public function log($message, $force = false)
    {
        // nothing to do here
    }

    /**
     *
     */
    public function flush()
    {
        // nothing to do here
    }

}