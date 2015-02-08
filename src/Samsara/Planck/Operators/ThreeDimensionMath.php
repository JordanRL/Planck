<?php
namespace Samsara\Planck\Operators;

use Samsara\Planck\GridContainers\BasicGridContainer;

class ThreeDimensionMath
{
    /** @var BasicGridContainer  */
    protected $container;
    /** @var  ThreeDimensionLocation */
    protected $home;

    /**
     * @param BasicGridContainer $container
     */
    public function __construct(BasicGridContainer $container)
    {
        $this->container = $container;
    }

    /**
     * Sets the base location to calculate from.
     *
     * @param ThreeDimensionLocation $location
     *
     * @return self
     */
    public function setHome(ThreeDimensionLocation $location)
    {
        $this->home = $location;

        return $this;
    }

    /**
     * Gets the distance between the home location and argument location.
     *
     * @param ThreeDimensionLocation $location
     *
     * @return float
     */
    public function getDistanceTo(ThreeDimensionLocation $location)
    {
        $start = $this->getExactPosition($this->home);
        $end = $this->getExactPosition($location);

        return sqrt( ($start['x']-$end['x'])**2 + ($start['y']-$end['y'])**2 + ($start['z']-$end['z'])**2 );
    }

    /**
     * Recursively gets the exact decimal grid location.
     *
     * @param ThreeDimensionLocation $location
     * @param array|null             $range
     *
     * @return array
     */
    protected function getExactPosition(ThreeDimensionLocation $location, $range = null)
    {
        $position = $location->getLocation();

        if ($location->hasChild()) {
            if (is_null($range)) {
                $childRange = $this->container->grid()->getChild()->grid()->getRange();
            } else {
                $childRange = $range;
            }
            $childLocation = $this->getExactPosition($location->child(), $childRange);

            $position['x'] += $childLocation['x'];
            $position['y'] += $childLocation['y'];
            $position['z'] += $childLocation['z'];
        }

        if (!is_null($range)) {
            $position['x'] /= ($range['x']*2);
            $position['y'] /= ($range['y']*2);
            $position['z'] /= ($range['z']*2);
        }

        return $position;
    }
}