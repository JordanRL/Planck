<?php

namespace Samsara\Planck\Core;

use Samsara\Planck\Node\Node;

interface GridInterface
{

    /**
     * @param   string              $name
     * @param   string              $address
     * @param   GridInterface|null  $protoSubGrid
     * @param   array|null          $maxVals
     * @param   array|null          $minVals
     */
    public function __construct($name, $address = '', GridInterface $protoSubGrid = null, array $maxVals = null, array $minVals = null);

    /**
     * @param   GridInterface   $grid
     * @return  $this
     */
    public function attachParentGrid(GridInterface $grid);

    /**
     * @param   Node            $node
     * @param   null|string     $address
     * @return  $this
     */
    public function attachNode(Node $node, $address = null);

    /**
     * @param   string              $address
     * @param   GridInterface|null  $grid
     * @return  $this
     */
    public function addAddress($address, GridInterface $grid = null);

    /**
     * @param   string|null     $address
     * @return  $this
     */
    public function getLocation($address = null);

    /**
     * @param   string|null     $address
     * @return  Node
     */
    public function getNode($address = null);

    /**
     * @param   string|null     $address
     * @return  array
     */
    public function getNodeProperties($address = null);

    /**
     * @return  array
     */
    public function getNodeEvents();

    /**
     * @return  $this
     */
    public function resetGrid();

}