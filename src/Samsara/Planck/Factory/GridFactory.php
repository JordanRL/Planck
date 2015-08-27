<?php

namespace Samsara\Planck\Factory;

use Samsara\Planck\Core\AbstractGrid;

class GridFactory
{
    const TWO_DIMENSION = 'Samsara\\Planck\\Grid\\TwoDimensionGrid';
    const THREE_DIMENSION = 'Samsara\\Planck\\Grid\\ThreeDimensionGrid';

    /**
     * @param   string|AbstractGrid     $type
     * @param   array                   $options
     * @return  AbstractGrid
     * @throws  \Exception
     */
    public static function makeGrid($type, $options = [])
    {
        $reflector = new \ReflectionClass($type);

        $name = (isset($options['name']) ? $options['name'] : '');
        $address = (isset($options['address']) ? $options['address'] : '');
        $subGrid = (isset($options['subGrid']) ? $options['subGrid'] : null);
        $maxVals = (isset($options['maxVals']) ? $options['maxVals'] : null);
        $minVals = (isset($options['minVals']) ? $options['minVals'] : null);

        if (!$reflector->implementsInterface('Samsara\\Planck\\Core\\GridInterface')) {
            throw new \Exception('Cannot instantiate class which does not implement GridInterface.');
        }

        if (!$reflector->isSubclassOf('Samsara\\Planck\\Core\\AbstractGrid')) {
            throw new \Exception('Cannot instantiate class which does not extend AbstractGrid.');
        }

        return $reflector->newInstance($name, $address, $subGrid, $maxVals, $minVals);
    }

}