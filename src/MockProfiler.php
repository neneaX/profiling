<?php

namespace Profiling;

use Psr\Log\LoggerInterface;

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
     * @return false
     */
    public static function isOn()
    {
        // The Mock Profiler is never enabled
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
     * Set a logger instance
     *
     * @param LoggerInterface $logger
     */
    public static function setLogger(LoggerInterface $logger)
    {
        // nothing to do here
    }

    /**
     * Start the timer for the current action
     *
     * @param string $label
     * @param mixed  $data
     */
    public function start($label, $data = null)
    {
        // nothing to do here
    }

    /**
     * End the timer previously started for the current action
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
     * Flush the profiling data and log it
     */
    public function flush()
    {
        // nothing to do here
    }

    /**
     * Get the profiling log as an array
     *
     * @return array
     */
    public function toArray()
    {
        // nothing to do here
    }

}