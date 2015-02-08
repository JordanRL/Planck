<?php
namespace Samsara\Planck\Operators;

class ThreeDimensionLocation
{
    /** @var  int */
    protected $x;
    /** @var  int */
    protected $y;
    /** @var  int */
    protected $z;
    /** @var ThreeDimensionLocation  */
    protected $child;

    public function __construct($x, $y, $z, $child = null)
    {
        $this->x = $x;
        $this->y = $y;
        $this->z = $z;

        if ($child instanceof ThreeDimensionLocation) {
            $this->child = $child;
        }
    }

    public function getLocation()
    {
        return array('x' => $this->x, 'y' => $this->y, 'z' => $this->z);
    }

    public function setChild(ThreeDimensionLocation $child)
    {
        $this->child = $child;
    }

    public function hasChild()
    {
        return ($this->child instanceof ThreeDimensionLocation);
    }

    public function child()
    {
        return $this->child;
    }
}