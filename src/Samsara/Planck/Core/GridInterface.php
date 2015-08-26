<?php

namespace Samsara\Planck\Core;

use Samsara\Planck\Node\Node;

interface GridInterface
{

    public function __construct($name, $address = '', GridInterface $protoSubGrid = null, array $maxVals = null, array $minVals = null);

    public function attachParentGrid(GridInterface $grid);

    public function attachNode(Node $node, $address = null);

    public function addAddress($address, GridInterface $grid = null);

    public function getLocation($address = null);

    public function getNode($address = null);

    public function getNodeProperties($address = null);

    public function getNodeEvents();

    public function resetGrid();

}