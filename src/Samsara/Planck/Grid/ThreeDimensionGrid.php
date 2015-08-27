<?php

namespace Samsara\Planck\Grid;

use Samsara\Planck\Core\AbstractGrid;
use Samsara\Planck\Core\GridInterface;

abstract class ThreeDimensionGrid extends AbstractGrid implements GridInterface
{
    const AXIS_ONE      = 'x';
    const AXIS_TWO      = 'y';
    const AXIS_THREE    = 'z';

    protected $maxVals = [
        self::AXIS_ONE      => 100,
        self::AXIS_TWO      => 100,
        self::AXIS_THREE    => 100
    ];

    protected $minVals = [
        self::AXIS_ONE      => 0,
        self::AXIS_TWO      => 0,
        self::AXIS_THREE    => 0
    ];

    /**
     * @param   string              $name
     * @param   string              $address
     * @param   GridInterface|null  $protoSubGrid
     * @param   array|null          $maxVals
     * @param   array|null          $minVals
     * @throws  \Exception
     */
    public function __construct($name, $address = '', GridInterface $protoSubGrid = null, array $maxVals = null, array $minVals = null)
    {
        if (!is_null($maxVals) && count($maxVals)) {
            foreach ($maxVals as $key => $val) {
                switch ($key) {
                    case self::AXIS_ONE:
                    case self::AXIS_TWO:
                    case self::AXIS_THREE:
                        if (is_numeric($val)) {
                            $this->maxVals[$key] = (int) $val;
                        } else {
                            throw new \Exception('Cannot use non-numeric values for maximum grid values.');
                        }
                        break;

                    default:
                        throw new \Exception('Cannot set maximum values for axis that doesn\'t exist');
                        break;
                }
            }
        }

        if (!is_null($minVals) && count($minVals)) {
            foreach ($minVals as $key => $val) {
                switch ($key) {
                    case self::AXIS_ONE:
                    case self::AXIS_TWO:
                    case self::AXIS_THREE:
                        if (is_numeric($val)) {
                            $this->maxVals[$key] = (int) $val;
                        } else {
                            throw new \Exception('Cannot use non-numeric values for minimum grid values.');
                        }
                        break;

                    default:
                        throw new \Exception('Cannot set minimum values for axis that doesn\'t exist');
                        break;
                }
            }
        }

        parent::__construct($name, $address, $protoSubGrid);
    }

    /**
     * @param   string  $address
     * @return  bool
     */
    protected function isValidAddress($address)
    {
        if (is_string($address)) {
            $parts = explode('.', $address);
            if (count($parts) == 3) {
                foreach ($parts as $key => $val) {
                    if (!is_numeric($val)) {
                        return false;
                    }

                    switch ($key) {
                        case 0:
                            if ($val > $this->maxVals[self::AXIS_ONE] || $val < $this->minVals[self::AXIS_ONE]) {
                                return false;
                            }
                            break;

                        case 1:
                            if ($val > $this->maxVals[self::AXIS_TWO] || $val < $this->minVals[self::AXIS_TWO]) {
                                return false;
                            }
                            break;

                        case 2:
                            if ($val > $this->maxVals[self::AXIS_THREE] || $val < $this->minVals[self::AXIS_THREE]) {
                                return false;
                            }
                            break;
                    }
                }

                return true;
            }
        }

        return false;
    }

}