# Planck

**This project is currently unit tested against 5.5.X, 5.6.X, and 7.X, and merges are not accepted unless the tests pass on all.**

## Installation

To install, simply require the package using composer:

    composer require samsara/planck "~0.1"
    
Or include it in your `composer.json` file:

```json
{
    "require": {
        "samsara/planck": "~0.1"
    }
}
```

The project namespace is `Samsara\Planck\*`. You can view the project on [Packagist](https://packagist.org/packages/samsara/planck).

## Concepts

### Current Concepts

#### Grids

> The concept of a grid in this project is an object which extends AbstractGrid and implements the GridInterface. A grid has a number of axis depending on the dimension of space the grid is intended to represent. So a three dimensional grid will have three axis.

> Each axis has an independently defined range (maximum and minimum valid values). The grid itself is not explicitly and exhaustively defined at every possible node. (For a 100x100x100 3D grid, this would be an array of size 1 million). Instead, the grid provides you with the tools to define things that are occurring at specific places, or things which are occurring across areas (using Node Events).

> Grids can be nested within each other. You can assign a grid object to occupy a node within another grid, and when you do that the sub-grid represents subdivisions within that specific node. This allows you to define arbitrarily fine position within the grid, thus naming the project after Planck.

> All locations have an address. The format is:

> > AXIS1.AXIS2.AXISN:AXIS1.AXIS2.AXISN:...

> The period (or full stop) separates the different axis within a grid, and the colon separates the sub-grids. The left-most address in such a chain represents the parent grid, and as the grids can nest indefinitely, the address may have as many sub-grid sections as you want separated with colons.

> This is an example of an address for a three dimensional grid, nested three times:

> > 24.13.76:12.12.10:9.0.20

> However, in the same grid, the following address is just as valid:

> > 24.13.76

> Just because you have a grid with precision defined down to two or three sub-grids, you do not have to use addresses that specific. You may query the grid at any specificity that you find useful.

#### Nodes

> Each address within a grid may contain a sub-grid. But it also contains a Node object which represents the properties of that location. The Node object is a simple key/value instance that can be used to store information about that address for later.

> A Node by itself will only store information about that specific address however, and any subaddresses will not have the same information. To handle that use case, we must look at Node Events.

#### Node Events

> A Node Event represents a key/value instance for a specific address that will be inherited by all subaddresses. It functions much like a Node does, and they both extend the AbstractNode class. However, when an event is attached to a Node, it will automatically be looked at when you query the properties of an address directly from a Grid, and **it will override any identically named properties of that Node**.

> Events and their values are inherited indefinitely, and this overriding behavior is also inherited.

### Future Concepts

#### [Operators](https://github.com/JordanRL/Planck/issues/13)

> [GitHub Issue](https://github.com/JordanRL/Planck/issues/13)

> One of the planned features is to add a new type of object referred to as an Operator. These will perform actions to Grids. For instance, one planned Operator is the EuclideanMathOperator, which will be able to do things such as calculate distance between addresses for a Grid that represents a Euclidean space of some kind.

#### [Projectors](https://github.com/JordanRL/Planck/issues/8)

> [GitHub Issue](https://github.com/JordanRL/Planck/issues/8)

> Another planned feature is Projectors, which will take a Grid in one dimension and project it into a lower dimension. This is primarily for representing things like the surface of a sphere, or something of that nature. The initial list of planned projections may be found in the associated GitHub issue (#8), and include:

> - Mercator
> - Miller cylindrical
> - Mollweide

> These projections may be approximate at first.

## Usage

New Grids may be instantiated directly, or may be created using the included GridFactory. As this project matures, the GridFactory will provide more useful ways to simplify Grid definition and instantiation, particularly when it comes to defining non-Euclidean Grids (which are not explicitly supported at the moment).

```php
$subGridPrototype = Samsara\Planck\Factory\GridFactory(
    GridFactory::THREE_DIMENSION,
    [
        'maxVals' => [
            ThreeDimensionGrid:AXIS_ONE   => 10,
            ThreeDimensionGrid:AXIS_TWO   => 10,
            ThreeDimensionGrid:AXIS_THREE => 10
        ]
    ]
);

$grid = Samsara\Planck\Factory\GridFactory(
    GridFactory::THREE_DIMENSION,
    [
        'name' => 'My Game World'
        'subGrid' => $subGridPrototype
    ]
);
```

Once you have a grid, you can add addresses without specifically assigning it a subgrid. If you do so, the Grid will automatically create all the necessary subgrids using the supplied prototype grid, and if a prototype grid isn't defined, it will clone the parent grid in question.

### Examples

```php
$grid->addAddress('100.20.20:2.4.3');

// This returns the Grid object which represents this address.
$subGrid = $grid->getLocation('100.20.20:2.4.3');
```

You can also start attaching Nodes.

```php
$node = new Samsara\Planck\Node\Node();
$node->set('traversible', true);

$grid->attachNode($node, '100.20.20:2.4.3');
```

You can then ask about that address later.

```php
$properties = $grid->getNodeProperties('100.20.20:2.4.3');

echo $properties['traversible']; // true
```

Or you can edit the node after attaching.

```php
$grid->getNode('100.20.20:2.4.3')
    ->set('traversible', false)
    ->set('conditions', 'calm');
```

## Extending

All Grids must extend AbstractGrid and implement GridInterface:

```php
namespace MyNamespace;

use Samsara\Planck\Core\AbstractGrid;
use Samsara\Planck\Core\GridInterface;

class FourDimensionGrid extends AbstractGrid implements GridInterface
{
    const AXIS_ONE      = 'x';
    const AXIS_TWO      = 'y';
    const AXIS_THREE    = 'z';
    const AXIS_FOUR     = 'a';

    protected $maxVals = [
        self::AXIS_ONE      => 100,
        self::AXIS_TWO      => 10,
        self::AXIS_THREE    => 10
    ];

    protected $minVals = [
        self::AXIS_ONE      => 0,
        self::AXIS_TWO      => 0,
        self::AXIS_THREE    => 0
    ];

    public function __construct($name, $address = '', GridInterface $protoSubGrid = null, array $maxVals = null, array $minVals = null)
    {
        if (!is_null($maxVals) && count($maxVals)) {
            foreach ($maxVals as $key => $val) {
                switch ($key) {
                    case self::AXIS_ONE:
                    case self::AXIS_TWO:
                    case self::AXIS_THREE:
                    case self::AXIS_FOUR:
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
                    case self::AXIS_FOUR:
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
            if (count($parts) == 4) {
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

                        case 3:
                            if ($val > $this->maxVals[self::AXIS_FOUR] || $val < $this->minVals[self::AXIS_FOUR]) {
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
```

## Contributing

Please ensure that pull requests meet the following guidelines:

- New files created in the pull request must have a corresponding unit test file, or must be covered within an existing test file.
- Your merge may not drop the project's test coverage below 85%.
- Your merge may not drop the project's test coverage by MORE than 5%.
- Your merge must pass Tracis-CI build tests for PHP 5.5.X, 5.6.X, and PHP 7.X.

For more information, please see the section on [Contributing](CONTRIBUTING.md)