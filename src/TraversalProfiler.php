<?php

namespace Profiling;

/**
 * Class TraversalProfiler
 * @package Profiling
 */
class TraversalProfiler implements Profiler
{

    const START_LABEL = 'Started Profiling';

    /**
     * The enabled status
     *
     * Default: false (disabled)
     *
     * @var bool
     */
    private static $on = false;

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
        self::$on = true;
    }

    /**
     * Disable the profiler
     */
    public static function off()
    {
        self::$on = false;
    }

    /**
     * Check if the profiler is enabled
     *
     * @return bool
     */
    public static function isOn()
    {
        return (true === self::$on);
    }

    /**
     * Get the singleton instance
     *
     * @return Profiler
     */
    public static function getInstance()
    {
        /*
         * If profiling is off, return a mock instance to prevent memory usage
         */
        if (true !== self::isOn()) {
            return MockProfiler::getInstance();
        }

        if (!self::$instance instanceof self) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    /**
     * Current profiling Id
     *
     * @var string $ID
     */
    private $ID;

    /**
     * The profiling tree
     *
     * @var TraversalProfilerTree
     */
    private $tree;

    /**
     * Profiling constructor.
     *
     * Disabled for public use
     */
    private function __construct()
    {
        $this->ID = uniqid('Profiling_');
        $this->tree = new TraversalProfilerTree(
            new TraversalProfilerNode(self::START_LABEL)
        );
    }

    /**
     * @return TraversalProfilerTree
     */
    private function getTree()
    {
        return $this->tree;
    }

    /**
     * @param TraversalProfilerTree $subTree
     */
    private function setTree(TraversalProfilerTree &$subTree)
    {
        $this->tree = $subTree;
    }

    /**
     *
     */
    private function goUpTheTree()
    {
        $parent = $this->getTree()->getParent();

        if (null !== $parent) {
            $this->setTree($parent);
        }
    }

    /**
     * Add a child node in the current tree and return the newly created subtree
     *
     * @param string $label
     * @param mixed  $data
     *
     * @return TraversalProfilerTree
     */
    private function addChildInTree($label, $data = null)
    {
        $newNode = new TraversalProfilerNode($label, $data);

        return $this->getTree()->addChild($newNode);
    }

    /**
     * @param string $label
     * @param mixed  $data
     */
    public function start($label, $data = null)
    {
        $child = $this->addChildInTree($label, $data);

        if (null !== $child) {
            $this->setTree($child);
        }
    }

    /**
     *
     */
    public function end()
    {
        $this->getTree()->getCurrent()->end();

        $this->goUpTheTree();
    }

    /**
     * Log the profiling data
     *
     * @param string $message
     */
    public function log($message)
    {
        $formattedDateTime = getCurrentDateTime()->format('D M d H:i:s.u Y');

        error_log(sprintf('[%s] [%s] %s', $formattedDateTime, $this->ID, $message . PHP_EOL));
    }

    /**
     *
     */
    private function goToRoot()
    {
        while(null !== $this->getTree()->getParent()) {
            $this->goUpTheTree();
        }
    }

    /**
     * @param callable $callback
     * @param array    $log
     */
    private function traverseTree(callable $callback, array &$log)
    {
        $this->goToRoot();
        $this->getTree()->traverse($callback, $log);
    }

    /**
     * Flush the gathered information
     */
    public function flush()
    {
        $log = $this->logToArray();

        $this->log($this->logToString($log[self::START_LABEL]));
    }

    /**
     * Get the profiling log as an array
     *
     * @return array
     */
    public function logToArray()
    {
        $this->end();

        $profilingLog = [];
        $this->traverseTree(function (TraversalProfilerTree $tree, &$log) {
            $log[$tree->getCurrent()->getLabel()] = [
                'start' => $tree->getCurrent()->getStartDateTime(),
                'end' => $tree->getCurrent()->getEndDateTime(),
                'total' => $tree->getCurrent()->getDuration()
            ];
        }, $profilingLog);

        return $profilingLog;
    }

    /**
     * @param array $log
     * @param int   $level
     *
     * @return string
     */
    private function logToString(array $log, $level = 0)
    {
        $message = PHP_EOL;
        $message .= str_repeat('   ', $level) . sprintf('--- %s ---', $log['label']) . PHP_EOL;
        $message .= str_repeat('   ', $level) . sprintf('    %s - start time', $log['start']) . PHP_EOL;
        $message .= str_repeat('   ', $level) . sprintf('    %s - end time', $log['end']) . PHP_EOL;
        $message .= str_repeat('   ', $level) . sprintf('    %s seconds - total duration', $log['total']) . PHP_EOL;

        if (!empty($log['children'])) {
            $level++;
            foreach ($log['children'] as $childLog) {
                $message .= $this->logToString($childLog, $level);
            }
        }

        return $message;
    }

}
