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
     * Using PHP_FLOAT_EPSILON won't work, as the error adds up over many operations.
     * So we pick a larger value that is still insignificant given the problem domain.
     *
     * 10e-X, where X is the value of RESOLUTION
     */
    private const RESOLUTION = 5;
    private const RESOLUTION_EPSILON = 10 ** -self::RESOLUTION;

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
        return $this->top === null ? null : \round($this->top, self::RESOLUTION);
    }

    /**
     * @param float|null  $top
     * @param string|null $topRelative
     */
    public function setTop(?float $top, ?string $topRelative): void
    {
        if ($topRelative !== null && $top === null) {
            throw new ConstraintException('Cannot set relative dimension to null.');
        }

        if ($topRelative === $this->id) {
            throw new ConstraintException('Cannot set a box relative to itself.');
        }

        $this->top         = $top;
        $this->topRelative = $topRelative;

        $this->resolveInternal();
    }

    /**
     * @return float
     */
    public function getLeft(): ?float
    {
        return $this->left === null ? null : \round($this->left, self::RESOLUTION);
    }

    /**
     * @param float|null  $left
     * @param string|null $leftRelative
     */
    public function setLeft(?float $left, ?string $leftRelative): void
    {
        if ($leftRelative !== null && $left === null) {
            throw new ConstraintException('Cannot set relative dimension to null.');
        }

        if ($leftRelative === $this->id) {
            throw new ConstraintException('Cannot set a box relative to itself.');
        }

        $this->left         = $left;
        $this->leftRelative = $leftRelative;

        $this->resolveInternal();
    }

    /**
     * @return float
     */
    public function getWidth(): ?float
    {
        return $this->width === null ? null : \round($this->width, self::RESOLUTION);
    }

    /**
     * @param float|null $width
     */
    public function setWidth(?float $width): void
    {
        if ($width !== null) {
            if ($width < 0.00) {
                throw new BoxArgumentException('Width must be 0 or greater.');
            }

            $this->widthPercentage = null;
            $this->widthRelative   = null;
        }

        $this->width = $width;

        $this->resolveInternal();
    }

    /**
     * @return float
     */
    public function getHeight(): ?float
    {
        return $this->height === null ? null : \round($this->height, self::RESOLUTION);
    }

    /**
     * @param float|null $height
     */
    public function setHeight(?float $height): void
    {
        if ($height !== null) {
            if ($height < 0.00) {
                throw new BoxArgumentException('Width must be 0 or greater.');
            }

            $this->heightPercentage = null;
            $this->heightRelative   = null;
        }

        $this->height = $height;

        $this->resolveInternal();
    }

    /**
     * @return float
     */
    public function getWidthPercentage(): ?float
    {
        return $this->widthPercentage === null ? null : \round($this->widthPercentage, self::RESOLUTION + 2);
    }

    /**
     * @param float|null  $widthPercentage
     * @param string|null $widthRelative
     */
    public function setWidthPercentage(?float $widthPercentage, ?string $widthRelative): void
    {
        if ($widthRelative !== null && $widthPercentage === null) {
            throw new ConstraintException('Cannot set relative dimension to null.');
        }

        if ($widthRelative === $this->id) {
            throw new ConstraintException('Cannot set a box relative to itself.');
        }

        if ($widthPercentage !== null) {
            if ($widthPercentage < 0.00) {
                throw new BoxArgumentException('Width percentage must be 0 or greater.');
            }

            $this->width = null;
        }

        $this->widthPercentage = $widthPercentage;
        $this->widthRelative   = $widthRelative;

        $this->resolveInternal();
    }

    /**
     * @return float
     */
    public function getHeightPercentage(): ?float
    {
        return $this->heightPercentage === null ? null : \round($this->heightPercentage, self::RESOLUTION + 2);
    }

    /**
     * @param float|null  $heightPercentage
     * @param string|null $heightRelative
     */
    public function setHeightPercentage(?float $heightPercentage, ?string $heightRelative): void
    {
        if ($heightRelative !== null && $heightPercentage === null) {
            throw new ConstraintException('Cannot set relative dimension to null.');
        }

        if ($heightRelative === $this->id) {
            throw new ConstraintException('Cannot set a box relative to itself.');
        }

        if ($heightPercentage !== null) {
            if ($heightPercentage < 0.00) {
                throw new BoxArgumentException('Height percentage must be 0 or greater.');
            }

            $this->height = null;
        }

        $this->heightPercentage = $heightPercentage;
        $this->heightRelative   = $heightRelative;

        $this->resolveInternal();
    }

    /**
     * @return string
     */
    public function getTopRelative(): ?string
    {
        return $this->topRelative;
    }

    /**
     * @return string
     */
    public function getLeftRelative(): ?string
    {
        return $this->leftRelative;
    }

    /**
     * @return string
     */
    public function getBottomRelative(): ?string
    {
        return $this->bottomRelative;
    }

    /**
     * @return string
     */
    public function getRightRelative(): ?string
    {
        return $this->rightRelative;
    }

    /**
     * @return string
     */
    public function getWidthRelative(): ?string
    {
        return $this->widthRelative;
    }

    /**
     * @return string
     */
    public function getHeightRelative(): ?string
    {
        return $this->heightRelative;
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
        return $this->bottom === null ? null : \round($this->bottom, self::RESOLUTION);
    }

    /**
     * @param float|null  $bottom
     * @param string|null $bottomRelative
     */
    public function setBottom(?float $bottom, ?string $bottomRelative): void
    {
        if ($bottomRelative !== null && $bottom === null) {
            throw new ConstraintException('Cannot set relative dimension to null.');
        }

        if ($bottomRelative === $this->id) {
            throw new ConstraintException('Cannot set a box relative to itself.');
        }

        $this->bottom         = $bottom;
        $this->bottomRelative = $bottomRelative;

        $this->resolveInternal();
    }

    /**
     * @return float|null
     */
    public function getRight(): ?float
    {
        return $this->right === null ? null : \round($this->right, self::RESOLUTION);
    }

    /**
     * @param float|null  $right
     * @param string|null $rightRelative
     */
    public function setRight(?float $right, ?string $rightRelative): void
    {
        if ($rightRelative !== null && $right === null) {
            throw new ConstraintException('Cannot set relative dimension to null.');
        }

        if ($rightRelative === $this->id) {
            throw new ConstraintException('Cannot set a box relative to itself.');
        }

        $this->right         = $right;
        $this->rightRelative = $rightRelative;

        $this->resolveInternal();
    }

    /**
     * Validates that all dimensions are set correctly.
     *
     * @return bool
     */
    public function isValid(): bool
    {
        if ($this->isResolved()) {
            return $this->isValidResolved();
        } else {
            return $this->isValidUnresolved();
        }
    }

    /**
     * Validates a resolved Box.
     *
     * @return bool
     */
    private function isValidResolved(): bool
    {
        // All values must be set
        if (
            $this->top === null ||
            $this->right === null ||
            $this->bottom === null ||
            $this->left === null ||
            $this->width === null ||
            $this->height === null
        ) {
            return false;
        }

        // Values must be sane, we cannot have an inside-out or degenerate Box
        if (
            $this->top >= $this->bottom ||
            $this->left >= $this->right
        ) {
            return false;
        }

        // Values must be consistent: height must be bottom - top, width must be right - left
        $hdelta = $this->right - $this->left - $this->width;
        $vdelta = $this->bottom - $this->top - $this->height;

        if (
            $hdelta > self::RESOLUTION_EPSILON ||
            $hdelta < -self::RESOLUTION_EPSILON ||
            $vdelta > self::RESOLUTION_EPSILON ||
            $vdelta < -self::RESOLUTION_EPSILON
        ) {
            return false;
        }

        return true;
    }

    /**
     * Validates an unresolved Box.
     *
     * @return bool
     */
    private function isValidUnresolved(): bool
    {
        // At least two values from each axis must be set

        $horizontal = ($this->left === null ? 0 : 1) + ($this->right === null ? 0 : 1) + (($this->widthPercentage === null && $this->width === null) ? 0 : 1);
        $vertical   = ($this->top === null ? 0 : 1) + ($this->bottom === null ? 0 : 1) + (($this->heightPercentage === null && $this->height === null) ? 0 : 1);

        if ($horizontal < 2 || $vertical < 2) {
            return false;
        }

        // If they are resolved, values must be sane

        if (
            $this->rightRelative === null &&
            $this->leftRelative === null &&
            $this->widthRelative === null &&
            $this->left !== null &&
            $this->right !== null &&
            $this->width !== null &&
            $this->left >= $this->right
        ) {
            return false;
        }

        if (
            $this->bottomRelative === null &&
            $this->topRelative === null &&
            $this->heightRelative === null &&
            $this->bottom !== null &&
            $this->top !== null &&
            $this->height !== null &&
            $this->top >= $this->bottom
        ) {
            return false;
        }

        // If they are resolved, then right - left must equal width, bottom - top must equal height

        if (
            $this->rightRelative === null &&
            $this->leftRelative === null &&
            $this->widthRelative === null &&
            $this->left !== null &&
            $this->right !== null &&
            $this->width !== null &&
            (
                ($this->right - $this->left - $this->width) > self::RESOLUTION_EPSILON ||
                ($this->right - $this->left - $this->width) < -self::RESOLUTION_EPSILON
            )
        ) {
            return false;
        }

        if (
            $this->bottomRelative === null &&
            $this->topRelative === null &&
            $this->heightRelative === null &&
            $this->bottom !== null &&
            $this->top !== null &&
            $this->height !== null &&
            (
                ($this->bottom - $this->top - $this->height) > self::RESOLUTION_EPSILON ||
                ($this->bottom - $this->top - $this->height) < -self::RESOLUTION_EPSILON
            )
        ) {
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
        }

        /// WIDTH ///

        if ($box->id === $this->widthRelative) {
            if ($box->widthRelative === $this->id) {
                throw new ConstraintException('Cycle encountered on width');
            } elseif ($box->widthRelative !== null) {
                // Cascade -- since width is a percentage, we multiply
                $this->widthRelative   = $box->widthRelative;
                $this->widthPercentage *= $box->widthPercentage;
            } elseif ($box->width !== null) {
                $this->width           = $this->widthPercentage * $box->width;
                $this->widthPercentage = null;
                $this->widthRelative   = null;
            }
        }

        /// HEIGHT ///

        if ($box->id === $this->heightRelative) {
            if ($box->heightRelative === $this->id) {
                throw new ConstraintException('Cycle encountered on height');
            } elseif ($box->heightRelative !== null) {
                // Cascade -- since height is a percentage, we multiply
                $this->heightRelative   = $box->heightRelative;
                $this->heightPercentage *= $box->heightPercentage;
            } elseif ($box->width !== null) {
                // $box has an absolute height, so we can assign the dimension directly
                $this->height           = $this->heightPercentage * $box->height;
                $this->heightPercentage = null;
                $this->heightRelative   = null;
            }
        }

        /// LEFT ///

        if ($box->id === $this->leftRelative) {
            if ($box->leftRelative === $this->id) {
                throw new ConstraintException('Cycle encountered on left');
            } elseif ($box->left !== null) {
                $this->left         += $box->left;
                $this->leftRelative = $box->leftRelative;
            }
        }

        /// RIGHT ///

        if ($box->id === $this->rightRelative) {
            if ($box->rightRelative === $this->id) {
                throw new ConstraintException('Cycle encountered on right');
            } elseif ($box->right !== null) {
                $this->right         += $box->right;
                $this->rightRelative = $box->rightRelative;
            }
        }

        /// TOP ///

        if ($box->id === $this->topRelative) {
            if ($box->topRelative === $this->id) {
                throw new ConstraintException('Cycle encountered on top');
            } elseif ($box->top !== null) {
                $this->top         += $box->top;
                $this->topRelative = $box->topRelative;
            }
        }

        /// BOTTOM ///

        if ($box->id === $this->bottomRelative) {
            if ($box->bottomRelative === $this->id) {
                throw new ConstraintException('Cycle encountered on bottom');
            } elseif ($box->bottom !== null) {
                $this->bottom         += $box->bottom;
                $this->bottomRelative = $box->bottomRelative;
            }
        }

        $this->resolveInternal();
    }

    /**
     * For each axis, if two of the three dimensions are known,
     * the third can be calculated.
     */
    private function resolveInternal()
    {
        if (
            $this->leftRelative === null &&
            $this->left !== null &&
            $this->rightRelative === null &&
            $this->right !== null
        ) {
            $this->width           = $this->right - $this->left;
            $this->widthRelative   = null;
            $this->widthPercentage = null;
        } elseif (
            $this->leftRelative === null &&
            $this->left !== null &&
            $this->widthRelative === null &&
            $this->width !== null
        ) {
            $this->right         = $this->left + $this->width;
            $this->rightRelative = null;
        } elseif (
            $this->rightRelative === null &&
            $this->right !== null &&
            $this->widthRelative === null &&
            $this->width !== null
        ) {
            $this->left         = $this->right - $this->width;
            $this->leftRelative = null;
        }

        if (
            $this->topRelative === null &&
            $this->top !== null &&
            $this->bottomRelative === null &&
            $this->bottom !== null
        ) {
            $this->height           = $this->bottom - $this->top;
            $this->heightRelative   = null;
            $this->heightPercentage = null;
        } elseif (
            $this->topRelative === null &&
            $this->top !== null &&
            $this->heightRelative === null &&
            $this->height !== null
        ) {
            $this->bottom         = $this->top + $this->height;
            $this->bottomRelative = null;
        } elseif (
            $this->bottomRelative === null &&
            $this->bottom !== null &&
            $this->heightRelative === null &&
            $this->height !== null
        ) {
            $this->top         = $this->bottom - $this->height;
            $this->topRelative = null;
        }
    }
}