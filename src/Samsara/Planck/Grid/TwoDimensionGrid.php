<?php

namespace Samsara\Planck\Grid;

use Samsara\Planck\Core\AbstractGrid;
use Samsara\Planck\Core\GridInterface;

class TwoDimensionGrid extends AbstractGrid implements GridInterface
{

    const AXIS_ONE      = 'x';
    const AXIS_TWO      = 'y';

    public function __construct($name, $address = '', GridInterface $protoSubGrid = null, array $maxVals = null, array $minVals = null)
    {
        if (!is_null($maxVals) && count($maxVals)) {
            foreach ($maxVals as $key => $val) {
                switch ($key) {
                    case self::AXIS_ONE:
                    case self::AXIS_TWO:
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

    protected function isValidAddress($address)
    {
        if (is_string($address)) {
            $parts = explode('.', $address);
            if (count($parts) == 2) {
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
                    }
                }

                return true;
            }
        }

        return false;
    }

}