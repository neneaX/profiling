<?php

namespace Profiling;

use Psr\Log\LoggerInterface;

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
     * The logger instance
     *
     * @var LoggerInterface
     */
    private static $logger;

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
     * Set a logger instance
     *
     * @param LoggerInterface $logger
     */
    public static function setLogger(LoggerInterface $logger)
    {
        self::$logger = $logger;
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
     * Return the current tree
     *
     * @return TraversalProfilerTree
     */
    private function getTree()
    {
        return $this->tree;
    }

    /**
     * Set the current tree pointer
     *
     * @param TraversalProfilerTree $subTree
     */
    private function setTree(TraversalProfilerTree &$subTree)
    {
        $this->tree = $subTree;
    }

    /**
     * Move the pointer from the current tree node to the parent tree node
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
     * Start the timer for the current action
     *
     * Profile a current action by creating a node with the given label and data in a subtree
     *
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
     * End the timer previously started for the current action
     *
     * Finish profiling the current action and move the pointer to the parent node
     */
    public function end()
    {
        $this->getTree()->getCurrent()->end();

        $this->goUpTheTree();
    }

    /**
     * Log messages with error_log
     *
     * @param string $message
     */
    private function errorLog($message)
    {
        if (is_string($message)) {
            error_log($message);
        } else {
            error_log(sprintf('Could not log error message - string expected, [%s] given', gettype($message)));
        }
    }

    /**
     * Log messages with the set logger
     *
     * @param string $message
     * @param array $context
     *
     * @throws \RuntimeException
     */
    private function loggerLog($message, array $context = [])
    {
        if (self::$logger instanceof LoggerInterface) {
            self::$logger->debug($message);
        } else {
            throw new \RuntimeException('Logger not set');
        }
    }

    /**
     * Log messages with the set logger
     *
     * If no logger is set, error_log is used as a fallback
     *
     * @param string $message
     */
    public function log($message)
    {
        $formattedDateTime = getCurrentDateTime()->format('D M d H:i:s.u Y');

        $message = sprintf('[%s] [%s] %s', $formattedDateTime, $this->ID, $message . PHP_EOL);

        try {
            $this->loggerLog($message);
        } catch (\RuntimeException $e) {
            $this->errorLog($message);
        }
    }

    /**
     * Reset the pointer to the tree root
     */
    private function goToRoot()
    {
        while(null !== $this->getTree()->getParent()) {
            $this->goUpTheTree();
        }
    }

    /**
     * Traverse the tree and apply a callback method
     *
     * @param callable $callback
     * @param array    $log
     */
    private function traverseTree(callable $callback, array &$log)
    {
        $this->goToRoot();
        $this->getTree()->traverse($callback, $log);
    }

    /**
     * Flush the profiling data and log it
     */
    public function flush()
    {
        /*
         * End the profiling (action started on constructor)
         */
        $this->end();

        $log = $this->toArray();

        $this->log(
            $this->logToString($log[self::START_LABEL])
        );
    }

    /**
     * Get the profiling log as an array
     *
     * @return array
     */
    public function toArray()
    {
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
     * Transform a given log array to a formatted string message
     *
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
