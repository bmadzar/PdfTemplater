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
    private ?float $top = null;

    /**
     * @var float|null
     */
    private ?float $left = null;

    /**
     * @var float|null
     */
    private ?float $bottom = null;

    /**
     * @var float|null
     */
    private ?float $right = null;

    /**
     * @var float|null
     */
    private ?float $width = null;

    /**
     * @var float|null
     */
    private ?float $height = null;

    /**
     * @var float|null
     */
    private ?float $widthPercentage = null;

    /**
     * @var float|null
     */
    private ?float $heightPercentage = null;

    /**
     * @var string|null
     */
    private ?string $topRelative = null;

    /**
     * @var string|null
     */
    private ?string $leftRelative = null;

    /**
     * @var string|null
     */
    private ?string $bottomRelative = null;

    /**
     * @var string|null
     */
    private ?string $rightRelative = null;

    /**
     * @var string|null
     */
    private ?string $widthRelative = null;

    /**
     * @var string|null
     */
    private ?string $heightRelative = null;

    /**
     * @var string
     */
    private string $id;

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
        if (
            $this->top === null &&
            $this->bottom !== null &&
            $this->height !== null &&
            $this->bottomRelative === null &&
            $this->heightRelative === null
        ) {
            $this->top = $this->bottom - $this->height;
            $this->topRelative = null;
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
        if (
            $this->left === null &&
            $this->right !== null &&
            $this->width !== null &&
            $this->rightRelative === null &&
            $this->widthRelative === null
        ) {
            $this->left = $this->right - $this->width;
            $this->leftRelative = null;
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
        if (
            $this->width === null &&
            $this->right !== null &&
            $this->left !== null &&
            $this->rightRelative === null &&
            $this->leftRelative === null
        ) {
            $this->width = $this->right - $this->left;
            $this->widthPercentage = null;
            $this->widthRelative = null;
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
        if (
            $this->height === null &&
            $this->top !== null &&
            $this->bottom !== null &&
            $this->topRelative === null &&
            $this->bottomRelative === null
        ) {
            $this->height = $this->bottom - $this->top;
            $this->heightPercentage = null;
            $this->heightRelative = null;
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
        if (
            $this->bottom === null &&
            $this->height !== null &&
            $this->top !== null &&
            $this->heightRelative === null &&
            $this->topRelative === null
        ) {
            $this->bottom = $this->top + $this->height;
            $this->bottomRelative = null;
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
        if (
            $this->right === null &&
            $this->width !== null &&
            $this->left !== null &&
            $this->widthRelative === null &&
            $this->leftRelative === null
        ) {
            $this->right = $this->left + $this->width;
            $this->rightRelative = null;
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
            if (!\abs($this->top + $this->height - $this->bottom) < \PHP_FLOAT_EPSILON) {
                return false;
            }
        } elseif (\count(\array_filter([$this->top, $this->height, $this->bottom], '\\is_null')) >= 2) {
            return false;
        } elseif ($this->top !== null && $this->bottom !== null && $this->top <= $this->bottom) {
            return false;
        }

        // At least two of left, right and width must be set
        // If all three are set, they must add up
        // If right and left are set, right cannot be to the left of left
        if ($this->width !== null && $this->left !== null && $this->right !== null) {
            if (!\abs($this->left + $this->width - $this->right) < \PHP_FLOAT_EPSILON) {
                return false;
            }
        } elseif (\count(\array_filter([$this->left, $this->width, $this->right], '\\is_null')) >= 2) {
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
            return \array_filter(
                \array_unique([
                    $this->widthRelative,
                    $this->heightRelative,
                    $this->leftRelative,
                    $this->rightRelative,
                    $this->topRelative,
                    $this->bottomRelative,
                ])
            );
        }
    }

    /**
     * Resolves the dependencies on $box. If $box is not in the set of dependencies, nothing happens.
     *
     * @param Box $box
     */
    public function resolve(Box $box): void
    {
        if ($box === $this) {
            throw new ConstraintException('Cycle encountered!');
        }

        if ($this->isResolved()) {
            return;
        } else {
            $this->resolveInternal();
        }

        /// WIDTH ///

        if ($box->id === $this->widthRelative) {
            if ($box->widthRelative === $this->id) {
                throw new ConstraintException('Cycle encountered on width');
            } elseif ($box->widthRelative) {
                // Cascade -- since width is a percentage, we multiply
                $this->widthRelative = $box->widthRelative;
                $this->widthPercentage *= $box->widthPercentage;
            } else {
                // $box has an absolute width, so we can assign the dimension directly
                $this->width = $this->widthPercentage * $box->width;
                $this->widthPercentage = null;
                $this->widthRelative = null;
            }
        }

        /// HEIGHT ///

        if ($box->id === $this->heightRelative) {
            if ($box->heightRelative === $this->id) {
                throw new ConstraintException('Cycle encountered on height');
            } elseif ($box->heightRelative) {
                // Cascade -- since height is a percentage, we multiply
                $this->heightRelative = $box->heightRelative;
                $this->heightPercentage *= $box->heightPercentage;
            } else {
                // $box has an absolute height, so we can assign the dimension directly
                $this->height = $this->heightPercentage * $box->height;
                $this->heightPercentage = null;
                $this->heightRelative = null;
            }
        }

        /// LEFT ///

        if ($box->id === $this->leftRelative) {
            if ($box->leftRelative === $this->id) {
                throw new ConstraintException('Cycle encountered on left');
            } else {
                // This will conveniently handle both the cascade and the absolute case!
                $this->left += $box->left;
                $this->leftRelative = $box->leftRelative;
            }
        }

        /// RIGHT ///

        if ($box->id === $this->rightRelative) {
            if ($box->rightRelative === $this->id) {
                throw new ConstraintException('Cycle encountered on right');
            } else {
                // This will conveniently handle both the cascade and the absolute case!
                $this->right += $box->right;
                $this->rightRelative = $box->rightRelative;
            }
        }

        /// TOP ///

        if ($box->id === $this->topRelative) {
            if ($box->topRelative === $this->id) {
                throw new ConstraintException('Cycle encountered on top');
            } else {
                // This will conveniently handle both the cascade and the absolute case!
                $this->top += $box->top;
                $this->topRelative = $box->topRelative;
            }
        }

        /// BOTTOM ///

        if ($box->id === $this->bottomRelative) {
            if ($box->bottomRelative === $this->id) {
                throw new ConstraintException('Cycle encountered on bottom');
            } else {
                // This will conveniently handle both the cascade and the absolute case!
                $this->bottom += $box->bottom;
                $this->bottomRelative = $box->bottomRelative;
            }
        }

        if ($this->isResolved()) {
            return;
        } else {
            $this->resolveInternal();
        }
    }

    /**
     * Eliminates dependencies that can be calculated (e.g. if left and width are known, right is left + width)
     */
    private function resolveInternal(): void
    {
        if ($this->widthRelative === null) {
            if ($this->rightRelative === null && $this->leftRelative !== null) {
                $this->left = $this->right - $this->width;
                $this->leftRelative = null;
            } elseif ($this->rightRelative !== null && $this->leftRelative === null) {
                $this->right = $this->left + $this->width;
                $this->rightRelative = null;
            }
        } elseif ($this->rightRelative !== null && $this->leftRelative !== null) {
            $this->width = $this->right - $this->left;
            $this->widthPercentage = null;
            $this->widthRelative = null;
        }

        if ($this->heightRelative === null) {
            if ($this->bottomRelative === null && $this->topRelative !== null) {
                $this->top = $this->bottom - $this->height;
                $this->topRelative = null;
            } elseif ($this->bottomRelative !== null && $this->topRelative === null) {
                $this->bottom = $this->top + $this->height;
                $this->bottomRelative = null;
            }
        } elseif ($this->topRelative !== null && $this->bottomRelative !== null) {
            $this->height = $this->bottom - $this->top;
            $this->heightPercentage = null;
            $this->heightRelative = null;
        }
    }
}