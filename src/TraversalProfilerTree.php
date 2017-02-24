<?php

namespace Profiling;

/**
 * Class TraversalProfilerTree
 * @package Profiling
 */
class TraversalProfilerTree
{

    /**
     * The parent node of the current subtree
     *
     * @var TraversalProfilerTree
     */
    private $parent;

    /**
     * The current node
     *
     * @var TraversalProfilerNode
     */
    private $current;

    /**
     * @var TraversalProfilerTree[]
     */
    private $children = [];

    /**
     * TraversalProfilerTree constructor.
     *
     * @param TraversalProfilerNode $current
     */
    public function __construct(TraversalProfilerNode &$current)
    {
        $this->current = $current;
    }

    /**
     * @return TraversalProfilerNode
     */
    public function getCurrent()
    {
        return $this->current;
    }

    /**
     * @return TraversalProfilerTree
     */
    public function getParent()
    {
        return $this->parent;
    }

    /**
     * @param TraversalProfilerTree $parent
     */
    public function setParent(TraversalProfilerTree &$parent)
    {
        $this->parent = &$parent;
    }

    /**
     * @param TraversalProfilerNode $node
     *
     * @return TraversalProfilerTree
     */
    public function addChild(TraversalProfilerNode &$node)
    {
        $subTree = new self($node);
        $subTree->setParent($this);

        $this->children[] = $subTree;

        return $subTree;
    }

    /**
     * Apply a callback function on the current subtree and on all the children
     *
     * @param callable $callback
     * @param array    $log
     */
    public function traverse(callable $callback, array &$log)
    {
        $callback($this, $log);

        $log[$this->getCurrent()->getLabel()]['children'] = (count($this->children) > 0 ? [] : null);

        foreach ($this->children as $child) {
            $child->traverse($callback, $log[$this->getCurrent()->getLabel()]['children']);
        }
    }

}
