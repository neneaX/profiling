<?php

namespace Profiling;

/**
 * Get the current date time with microseconds
 *
 * @return bool|\DateTime
 */
function getCurrentDateTime()
{
    $microTime = microtime();
    $microTimeArray = explode(' ', $microTime);
    $microseconds = substr($microTimeArray[0], 2, 6);
    $timestamp = $microTimeArray[1];
    $dateTime = \DateTime::createFromFormat('U.u', $timestamp . '.' . $microseconds);

    return $dateTime;
}

/**
 * Return a traversal Profiler instance
 *
 * @return Profiler
 */
function traverse()
{
    return TraversalProfiler::getInstance();
}