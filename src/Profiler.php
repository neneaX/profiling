<?php

namespace Profiling;

/**
 * Interface Profiling
 * @package Profiling
 */
interface Profiler
{

    /**
     * Enable the profiler
     */
    public static function on();

    /**
     * Disable the profiler
     */
    public static function off();

    /**
     * Check if the profiler is enabled
     *
     * @return bool
     */
    public static function isOn();

    /**
     * Get the singleton instance
     *
     * @return Profiler
     */
    public static function getInstance();

    /**
     * Start the timer for the current action
     *
     * @param string $label
     * @param mixed  $data
     */
    public function start($label, $data = null);

    /**
     * End the timer previously started for the current action
     */
    public function end();

    /**
     * Log the profiling data
     *
     * @param string $message
     */
    public function log($message);

    /**
     * Flush the gathered information
     */
    public function flush();

    /**
     * Get the profiling log as an array
     *
     * @return array
     */
    public function logToArray();

}
