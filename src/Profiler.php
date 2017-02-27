<?php

namespace Profiling;

use Psr\Log\LoggerInterface;

/**
 * Interface Profiler
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
     * Set a logger instance
     *
     * @param LoggerInterface $logger
     */
    public static function setLogger(LoggerInterface $logger);

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
     * Log messages with the set logger
     *
     * @param string $message
     */
    public function log($message);

    /**
     * Flush the profiling data and log it
     */
    public function flush();

    /**
     * Get the profiling log as an array
     *
     * @return array
     */
    public function toArray();

}
