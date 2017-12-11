<?php
declare(strict_types=1);

namespace PdfTemplater\Builder\Basic;

/**
 * Class Box
 *
 * The basic unit of a page layout is a Box. Used to resolve relative constraints.
 *
 * @package PdfTemplater\Builder\Basic
 */
class Box
{
    /**
     * @var float|null
     */
    private $top;

    /**
     * @var float|null
     */
    private $left;

    /**
     * @var float|null
     */
    private $bottom;

    /**
     * @var float|null
     */
    private $right;

    /**
     * @var float|null
     */
    private $width;

    /**
     * @var float|null
     */
    private $height;

    /**
     * @var float|null
     */
    private $widthPercentage;

    /**
     * @var float|null
     */
    private $heightPercentage;

    /**
     * @var string|null
     */
    private $topRelative;

    /**
     * @var string|null
     */
    private $leftRelative;

    /**
     * @var string|null
     */
    private $bottomRelative;

    /**
     * @var string|null
     */
    private $rightRelative;

    /**
     * @var string|null
     */
    private $widthRelative;

    /**
     * @var string|null
     */
    private $heightRelative;

    /**
     * @var string
     */
    private $id;

    /**
     * Box constructor.
     *
     * @param string $id
     */
    public function __construct(string $id)
    {
        $this->id = $id;
    }

    /**
     * @return float
     */
    public function getTop(): ?float
    {
        if ($this->top === null && $this->bottom !== null && $this->height !== null && $this->isResolved()) {
            return $this->bottom - $this->height;
        }

        return $this->top;
    }

    /**
     * @param float $top
     */
    public function setTop(?float $top): void
    {
        $this->top = $top;
    }

    /**
     * @return float
     */
    public function getLeft(): ?float
    {
        if ($this->left === null && $this->right !== null && $this->width !== null && $this->isResolved()) {
            return $this->right - $this->width;
        }

        return $this->left;
    }

    /**
     * @param float $left
     */
    public function setLeft(?float $left): void
    {
        $this->left = $left;
    }

    /**
     * @return float
     */
    public function getWidth(): ?float
    {
        if ($this->width === null && $this->right !== null && $this->left !== null && $this->isResolved()) {
            return $this->right - $this->left;
        }

        return $this->width;
    }

    /**
     * @param float $width
     */
    public function setWidth(?float $width): void
    {
        if ($width < 0.00) {
            throw new BoxArgumentException('Width must be 0 or greater.');
        }

        $this->width = $width;
    }

    /**
     * @return float
     */
    public function getHeight(): ?float
    {
        if ($this->height === null && $this->top !== null && $this->bottom !== null && $this->isResolved()) {
            return $this->bottom - $this->top;
        }

        return $this->height;
    }

    /**
     * @param float $height
     */
    public function setHeight(?float $height): void
    {
        if ($height < 0.00) {
            throw new BoxArgumentException('Height must be 0 or greater.');
        }

        $this->height = $height;
    }

    /**
     * @return float
     */
    public function getWidthPercentage(): ?float
    {
        return $this->widthPercentage;
    }

    /**
     * @param float $widthPercentage
     */
    public function setWidthPercentage(?float $widthPercentage): void
    {
        if ($widthPercentage < 0.00) {
            throw new BoxArgumentException('Width percentage must be 0 or greater.');
        }

        $this->widthPercentage = $widthPercentage;
    }

    /**
     * @return float
     */
    public function getHeightPercentage(): ?float
    {
        return $this->heightPercentage;
    }

    /**
     * @param float $heightPercentage
     */
    public function setHeightPercentage(?float $heightPercentage): void
    {
        if ($heightPercentage < 0.00) {
            throw new BoxArgumentException('Height percentage must be 0 or greater.');
        }

        $this->heightPercentage = $heightPercentage;
    }

    /**
     * @return string
     */
    public function getTopRelative(): ?string
    {
        return $this->topRelative;
    }

    /**
     * @param string $topRelative
     */
    public function setTopRelative(?string $topRelative): void
    {
        if ($topRelative === $this->id) {
            throw new ConstraintException('Attempting to set a box relative to itself!');
        }

        $this->topRelative = $topRelative;
    }

    /**
     * @return string
     */
    public function getLeftRelative(): ?string
    {
        return $this->leftRelative;
    }

    /**
     * @param string $leftRelative
     */
    public function setLeftRelative(?string $leftRelative): void
    {
        if ($leftRelative === $this->id) {
            throw new ConstraintException('Attempting to set a box relative to itself!');
        }

        $this->leftRelative = $leftRelative;
    }

    /**
     * @return string
     */
    public function getBottomRelative(): ?string
    {
        return $this->bottomRelative;
    }

    /**
     * @param string $bottomRelative
     */
    public function setBottomRelative(?string $bottomRelative): void
    {
        if ($bottomRelative === $this->id) {
            throw new ConstraintException('Attempting to set a box relative to itself!');
        }

        $this->bottomRelative = $bottomRelative;
    }

    /**
     * @return string
     */
    public function getRightRelative(): ?string
    {
        return $this->rightRelative;
    }

    /**
     * @param string $rightRelative
     */
    public function setRightRelative(?string $rightRelative): void
    {
        if ($rightRelative === $this->id) {
            throw new ConstraintException('Attempting to set a box relative to itself!');
        }

        $this->rightRelative = $rightRelative;
    }

    /**
     * @return string
     */
    public function getWidthRelative(): ?string
    {
        return $this->widthRelative;
    }

    /**
     * @param string $widthRelative
     */
    public function setWidthRelative(?string $widthRelative): void
    {
        if ($widthRelative === $this->id) {
            throw new ConstraintException('Attempting to set a box relative to itself!');
        }

        $this->widthRelative = $widthRelative;
    }

    /**
     * @return string
     */
    public function getHeightRelative(): ?string
    {
        return $this->heightRelative;
    }

    /**
     * @param string $heightRelative
     */
    public function setHeightRelative(?string $heightRelative): void
    {
        if ($heightRelative === $this->id) {
            throw new ConstraintException('Attempting to set a box relative to itself!');
        }

        $this->heightRelative = $heightRelative;
    }

    /**
     * @return string
     */
    public function getId(): string
    {
        return $this->id;
    }

    /**
     * @param string $id
     */
    public function setId(string $id): void
    {
        $this->id = $id;
    }

    /**
     * @return float|null
     */
    public function getBottom(): ?float
    {
        if ($this->bottom === null && $this->height !== null && $this->top !== null && $this->isResolved()) {
            return $this->top + $this->height;
        }

        return $this->bottom;
    }

    /**
     * @param float|null $bottom
     */
    public function setBottom(?float $bottom): void
    {
        $this->bottom = $bottom;
    }

    /**
     * @return float|null
     */
    public function getRight(): ?float
    {
        if ($this->right === null && $this->width !== null && $this->left !== null && $this->isResolved()) {
            return $this->left + $this->width;
        }

        return $this->right;
    }

    /**
     * @param float|null $right
     */
    public function setRight(?float $right): void
    {
        $this->right = $right;
    }

    /**
     * Validates that all dimensions are set correctly.
     *
     * @return bool|null
     */
    public function isValid(): ?bool
    {
        if (!$this->isResolved()) {
            return null;
        }

        // At least two of top, bottom and height must be set
        // If all three are set, they must add up
        // If bottom and top are set, bottom cannot be above top
        if ($this->height !== null && $this->top !== null && $this->bottom !== null) {
            if (!\abs($this->top + $this->height - $this->bottom) < 0.001) {
                return false;
            }
        } elseif (\count(\array_filter([$this->top, $this->height, $this->bottom], '\\is_null')) !== 2) {
            return false;
        } elseif ($this->top !== null && $this->bottom !== null && $this->top <= $this->bottom) {
            return false;
        }

        // At least two of left, right and width must be set
        // If all three are set, they must add up
        // If right and left are set, right cannot be to the left of left
        if ($this->width !== null && $this->left !== null && $this->right !== null) {
            if (!\abs($this->left + $this->width - $this->right) < 0.001) {
                return false;
            }
        } elseif (\count(\array_filter([$this->left, $this->width, $this->right], '\\is_null')) !== 2) {
            return false;
        } elseif ($this->left !== null && $this->right !== null && $this->right <= $this->left) {
            return false;
        }

        return true;
    }

    /**
     * Checks that all relative dimensions have been resolved.
     *
     * @return bool
     */
    public function isResolved(): bool
    {
        return !(
            $this->widthRelative ||
            $this->heightRelative ||
            $this->leftRelative ||
            $this->rightRelative ||
            $this->topRelative ||
            $this->bottomRelative
        );
    }

    /**
     * Gets all the IDs of the dependencies.
     *
     * @return string[]
     */
    public function getDependencies(): array
    {
        if ($this->isResolved()) {
            return [];
        } else {
            return \array_unique([
                $this->widthRelative,
                $this->heightRelative,
                $this->leftRelative,
                $this->rightRelative,
                $this->topRelative,
                $this->bottomRelative,
            ]);
        }
    }

    /**
     * Resolves the dependencies on $box. If $box is not in the set of dependencies, nothing happens.
     *
     * @param Box $box
     */
    public function resolve(Box $box): void
    {
        if (!\in_array($box->id, $this->getDependencies(), true)) {
            return;
        }

        if ($box === $this) {
            throw new ConstraintException('Cycle encountered!');
        }

        // Using dynamic features results in less code BUT may be too slow...
        foreach (['width', 'height'] as $dim) {
            if ($box->id === $this->{$dim . 'Relative'}) {
                if ($box->{$dim . 'Relative'} === $this->id) {
                    throw new ConstraintException('Cycle encountered on ' . $dim);
                } elseif ($box->{$dim . 'Relative'}) {
                    // Cascade -- since width and height are percentages, we multiply
                    $this->{$dim . 'Relative'} = $box->{$dim . 'Relative'};
                    $this->{$dim . 'Percentage'} *= $box->{$dim . 'Percentage'};
                } else {
                    // $box has an absolute width/height, so we can assign the dimension directly
                    $this->$dim = $this->{$dim . 'Percentage'} * $box->$dim;
                    $this->{$dim . 'Percentage'} = null;
                    $this->{$dim . 'Relative'} = null;
                }
            }
        }
        unset($dim);

        foreach (['left', 'right', 'top', 'bottom'] as $dim) {
            if ($box->id === $this->{$dim . 'Relative'}) {
                if ($box->{$dim . 'Relative'} === $this->id) {
                    throw new ConstraintException('Cycle encountered on ' . $dim);
                } else {
                    // This will conveniently handle both the cascade and the absolute case!
                    $this->$dim += $box->$dim;
                    $this->{$dim . 'Relative'} = $box->{$dim . 'Relative'};
                }
            }
        }
        unset($dim);
    }
}