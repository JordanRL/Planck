<?php
namespace Samsara\Planck\GridContainers;

use Samsara\Planck\Grid\Grid;

class BasicGridContainer
{
    /** @var Grid|null  */
    protected $grid;
    /** @var  array */
    protected $members;

    /**
     * @param Grid|null $grid
     */
    public function __construct($grid = null)
    {
        if (!is_null($grid)) {
            $this->grid = $grid;
        }
    }

    /**
     * Adds an item to $this->members at the parent grid location
     *
     * @param $key
     * @param $member
     *
     * @return self
     */
    public function addMember($key, $member)
    {
        $this->members[$key] = $member;

        return $this;
    }

    /**
     * Adds items to $this->members at the parent grid location
     *
     * @param array $members
     *
     * @return self
     */
    public function addMembers($members)
    {
        foreach ($members as $key => $member) {
            $this->members[$key] = $member;
        }

        return $this;
    }

    /**
     * Returns the items at the parent grid location indexed by $key in $this->members
     *
     * @throws \InvalidArgumentException if there is no member indexed by $key
     *
     * @param $key
     *
     * @return mixed
     */
    public function getMember($key)
    {
        if (isset($this->members[$key])) {
            return $this->members[$key];
        }

        throw new \InvalidArgumentException('There is no member in this grid location with key \''.$key.'\'');
    }

    /**
     * Returns all items at the parent grid location
     *
     * @return array
     */
    public function getMembers()
    {
        return $this->members;
    }

    /**
     * Returns the child grid if present
     *
     * @throws \BadMethodCallException if there is no child grid for the container
     *
     * @return Grid|null
     */
    public function grid()
    {
        if ($this->hasGrid()) {
            return $this->grid;
        }

        throw new \BadMethodCallException('Cannot access Grid object in terminal container.');
    }

    /**
     * A safe, non-exception throwing check for a child grid
     *
     * @return bool
     */
    public function hasGrid()
    {
        return !(is_null($this->grid));
    }
}