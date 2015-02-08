<?php
namespace Samsara\Planck\Grid;

class ThreeDimensional extends Grid
{
    /**
     * @param int $rangeX
     * @param int $rangeY
     * @param int $rangeZ
     */
    public function __construct($rangeX, $rangeY, $rangeZ)
    {
        $this->range = array('x' => $rangeX, 'y' => $rangeY, 'z' => $rangeZ);

        $this->nodeDefinitions = array("x", "y", "z");
    }
}