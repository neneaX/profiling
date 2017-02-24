<?php

namespace Profiling;

/**
 * Class TraversalProfilerNode
 * @package Profiling
 */
class TraversalProfilerNode
{

    /**
     * @var string
     */
    private $label;

    /**
     * @var mixed
     */
    private $data;

    /**
     * @var \DateTime
     */
    private $start;

    /**
     * @var \DateTime
     */
    private $end;

    /**
     * TraversalProfilerNode constructor.
     *
     * @param string $label
     * @param mixed  $data
     */
    public function __construct($label, $data = null)
    {
        $this->label = (string)$label;
        $this->data = $data;
        $this->start = getCurrentDateTime();
    }

    /**
     * @return string
     */
    public function getLabel()
    {
        return $this->label;
    }

    /**
     * @return mixed|null
     */
    public function getData()
    {
        return $this->data;
    }

    /**
     *
     */
    public function end()
    {
        $this->end = getCurrentDateTime();
    }

    /**
     * @return \DateTime
     */
    public function getStart()
    {
        return $this->start;
    }

    /**
     * @return \DateTime
     */
    public function getEnd()
    {
        return $this->end;
    }

    /**
     * @return string
     */
    public function getStartDateTime()
    {
        return (null !== $this->start) ? $this->start->format('Y-m-d H:i:s.u') : '';
    }

    /**
     * @return string
     */
    public function getEndDateTime()
    {
        return (null !== $this->end) ? $this->end->format('Y-m-d H:i:s.u') : '';
    }

    /**
     * @return int
     */
    public function getDuration()
    {
        $startTime = 0;
        $endTime = 0;

        if (null !== $this->start) {
            $startSeconds = $this->start->getTimestamp();
            $startMicroseconds = (int)$this->start->format('u');
            $startTime = $startSeconds * 1000000 + $startMicroseconds;
        }
        if (null !== $this->end) {
            $endSeconds = $this->end->getTimestamp();
            $endMicroseconds = (int)$this->end->format('u');
            $endTime = $endSeconds * 1000000 + $endMicroseconds;
        }

        return number_format(
            floatval(($endTime - $startTime) / 1000000),
            6
        );
    }

}