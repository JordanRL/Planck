<?php

namespace Samsara\Planck\Core;

use Samsara\Planck\Node\Node;

abstract class AbstractGrid
{

    /**
     * @var AbstractGrid
     */
    protected $protoSubGrid;

    /**
     * @var AbstractGrid
     */
    protected $parentGrid;

    /**
     * @var Node
     */
    protected $node;

    /**
     * @var string
     */
    protected $name;

    /**
     * @var AbstractGrid[]
     */
    protected $addresses = [];

    protected $maxVals = [];

    protected $minVals = [];

    public function __construct($name, $address = '', GridInterface $protoSubGrid = null)
    {
        $this->name = $name;

        if ($protoSubGrid instanceof $this) {
            $this->protoSubGrid = $protoSubGrid;
        }

        if (!empty($address)) {
            $this->addAddress($address);
        }
    }

    public function attachParentGrid(GridInterface $grid)
    {
        $this->parentGrid = $grid;

        return $this;
    }

    public function attachNode(Node $node, $address = null)
    {
        if (is_null($address)) {
            $this->node = $node;
        }

        $this->resolve($address)->attachNode($node);

        return $this;
    }

    public function addAddress($address, GridInterface $grid = null)
    {
        $validAddress = $this->evalAddress($address);

        $subGrid = $this->makeSubGrid($grid);
        $subGrid->attachParentGrid($this);

        if (!empty($validAddress['remaining'])) {
            $subGrid->addAddress($validAddress['remaining'], $grid);
        }

        $this->addresses[$validAddress['current']] = $subGrid;

        return $this;
    }

    public function getLocation($address = null)
    {
        return $this->resolve($address);
    }

    public function getNode($address = null)
    {
        if (is_null($address)) {
            return $this->node;
        }

        return $this->resolve($address)->getNode();
    }

    public function getNodeProperties($address = null)
    {
        $location = $this->resolve($address);

        $properties = $location->node->getAllProperties();

        if ($location->parentGrid instanceof $this) {
            $events = $location->parentGrid->getNodeEvents();
            $properties = array_merge($events, $properties);
        }

        return $properties;
    }

    public function getNodeEvents()
    {
        $properties = $this->node->getAllEventsProperties();

        if ($this->parentGrid instanceof $this) {
            $properties = array_merge($properties, $this->parentGrid->getNodeEvents());
        }

        return $properties;
    }

    public function resetGrid()
    {
        $this->name = '';
        $this->addresses = [];
        unset($this->parentGrid);

        return $this;
    }

    protected function makeSubGrid(AbstractGrid $grid = null)
    {
        if (is_null($grid)) {
            if (is_null($this->protoSubGrid)) {
                $return = clone $this;
                $return->resetGrid();
            } else {
                $return = clone ($this->protoSubGrid);
            }
        } else {
            $return = clone $grid;
        }

        return $return;
    }

    protected function evalAddress($address)
    {
        $return = [];
        $return['current'] = '';
        $return['remaining'] = '';

        $firstColon = strpos($address, ':');

        if ($firstColon === false) {
            $return['current'] = $address;
        } else {
            $return['current'] = substr($address, 0, $firstColon);
            $return['remaining'] = substr($address, ($firstColon+1));
        }

        if (!$this->isValidAddress($return['current'])) {
            throw new \Exception('The address provided was invalid: '.$return['current']);
        }

        return $return;
    }

    protected function resolve($address = '')
    {
        if (empty($address)) {
            return $this;
        }

        $validAddress = $this->evalAddress($address);

        if (array_key_exists($validAddress['current'], $this->addresses)) {
            return $this->addresses[$validAddress['current']]->resolve($validAddress['remaining']);
        } else {
            throw new \Exception('There is no grid definition for the address requested.');
        }
    }

    abstract protected function isValidAddress($address);

}