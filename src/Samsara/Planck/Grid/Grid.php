<?php
namespace Samsara\Planck\Grid;

use Samsara\Planck\GridContainers\BasicGridContainer;

abstract class Grid
{
    /** @var  BasicGridContainer[] */
    protected $nodes;
    /** @var  string[] */
    protected $nodeDefinitions;
    /** @var  int[] */
    protected $range;
    /** @var  BasicGridContainer */
    protected $child;

    /**
     * Build the correct string key for a particular grid location
     *
     * @throws \InvalidArgumentException if the $loc argument's keys to not match $this->nodeDefinitions
     * @throws \InvalidArgumentException if the $loc argument's values are not integers
     * @throws \InvalidArgumentException if the $loc argument's values are out of range as defined in $this->range
     *
     * @param int[] $loc
     *
     * @return string
     */
    protected function buildNodeIndex($loc)
    {
        $indexes = array_keys($loc);

        // Make sure that the keys of the $loc variable and the allowed indexes match exactly
        if (array_diff($indexes, $this->nodeDefinitions) !== array()) {
            if (count($loc) == count($this->nodeDefinitions)) {
                foreach ($this->nodeDefinitions as $index) {
                    $grid[$index] = array_shift($loc);
                }
            } else {
                throw new \InvalidArgumentException('Tried to set location on unknown index.');
            }
        } else {
            $grid = $loc;
        }

        $key = "";

        // Build the location index string
        foreach ($this->nodeDefinitions as $index) {
            if (!is_int($grid[$index])) {
                throw new \InvalidArgumentException('Tried to use non-integer location.');
            }
            if (abs($grid[$index]) > $this->range[$index]) {
                throw new \InvalidArgumentException('Tried to set location out of range of grid.');
            }
            $key .= $grid[$index]."_";
        }

        // Remove the final underscore from the location index string
        $key = trim($key, "_");

        return $key;
    }

    /**
     * Adds a node at a particular location in the grid which is represented by an instance of BasicGridContainer
     *
     * @param int[]|string $loc
     * @param array|null  $members
     *
     * @return self
     */
    public function addNode($loc, $members = null)
    {
        if (is_array($loc)) {
            $key = $this->buildNodeIndex($loc);
        } elseif (is_string($loc)) {
            $key = $loc;
        }

        // Create a grid container with the child grid
        $this->nodes[$key] = clone $this->child;

        // Add any members that were passed
        if (!is_null($members)) {
            $this->nodes[$key]->addMembers($members);
        }

        return $this;
    }

    /**
     * Gets the child grid for this grid.
     *
     * @param int[] $loc
     *
     * @return Grid|null
     */
    public function child($loc)
    {
        $key = $this->buildNodeIndex($loc);

        if (!isset($this->nodes[$key])) {
            $this->addNode($key);
        }

        return $this->nodes[$key]->grid();
    }

    public function getChild()
    {
        return $this->child;
    }

    public function getRange()
    {
        return $this->range;
    }
}