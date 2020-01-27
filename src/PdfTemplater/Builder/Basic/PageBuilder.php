<?php
declare(strict_types=1);


namespace PdfTemplater\Builder\Basic;

use PdfTemplater\Builder\BuildArgumentException;
use PdfTemplater\Builder\BuildException;
use PdfTemplater\Layout\Basic\Layer;
use PdfTemplater\Layout\Basic\Page;
use PdfTemplater\Node\Node;

/**
 * Class PageBuilder
 *
 * Helper class to build Pages.
 *
 * @package PdfTemplater\Builder\Basic
 */
class PageBuilder
{
    private const PAGE_BOX_ID = "\0";

    /**
     * Verifies that the provided attribute is a valid page number.
     *
     * @param null|string $attribute
     * @return bool
     */
    private function checkPageNumber(?string $attribute): bool
    {
        return $attribute !== null &&
            \is_numeric($attribute) &&
            ((float)(int)$attribute === (float)$attribute) &&
            (int)$attribute >= 0;
    }

    /**
     * Verifies that the provided attribute is a valid layer number.
     *
     * @param null|string $attribute
     * @return bool
     */
    private function checkLayerNumber(?string $attribute): bool
    {
        return $attribute !== null &&
            \is_numeric($attribute) &&
            ((float)(int)$attribute === (float)$attribute);
    }

    /**
     * Verifies that the provided attribute is a valid dimension.
     *
     * @param null|string $attribute
     * @return bool
     */
    private function checkDimension(?string $attribute): bool
    {
        return $attribute !== null &&
            \is_numeric($attribute) &&
            (float)$attribute >= 0.00;
    }

    /**
     * Verifies that the provided attribute is a valid relative dimension.
     *
     * @param null|string $attribute
     * @return bool
     */
    private function checkRelativeDimension(?string $attribute): bool
    {
        return $attribute !== null &&
            \preg_match('/^\s*[0-9]+(?:\.[0-9]+)?\s*%$/', $attribute);
    }

    /**
     * Verifies that the provided attribute is a valid offset.
     *
     * @param null|string $attribute
     * @return bool
     */
    private function checkOffset(?string $attribute): bool
    {
        return $attribute !== null &&
            \is_numeric($attribute);
    }

    /**
     * Builds a page from a Node tree.
     *
     * @param Node $pageNode
     * @return Page
     */
    public function buildPage(Node $pageNode): Page
    {
        if (\strtolower($pageNode->getType()) !== 'page') {
            throw new BuildException('Incorrect node type!');
        }

        $number = $pageNode->getAttribute('number');
        $width = $pageNode->getAttribute('width');
        $height = $pageNode->getAttribute('height');

        if (!$this->checkPageNumber($number)) {
            throw new BuildArgumentException('Page number must be an integer 0 or greater.');
        }

        if (!$this->checkDimension($width)) {
            throw new BuildArgumentException('Page width must be a float 0 or greater.');
        }

        if (!$this->checkDimension($height)) {
            throw new BuildArgumentException('Page height must be a float 0 or greater.');
        }

        $page = new Page((int)$number, (float)$width, (float)$height);

        $this->placeElements(\array_filter($pageNode->getChildren(), function (Node $elementNode) {
            return !\in_array($elementNode->getType(), ['document', 'page'], true);
        }), $page);

        return $page;
    }

    /**
     * Creates, arranges and lays out the elements for a page.
     *
     * @param Node[] $elementNodes
     * @param Page   $page
     */
    private function placeElements(array $elementNodes, Page $page): void
    {
        $boxes = $this->createBoxes($elementNodes);

        $pageBox = new Box(self::PAGE_BOX_ID);
        $pageBox->setLeft(0.00);
        $pageBox->setTop(0.00);
        $pageBox->setWidth($page->getWidth());
        $pageBox->setHeight($page->getHeight());

        $boxes[$pageBox->getId()] = $pageBox;

        $this->doLayout($boxes);
        $this->applyLayout($elementNodes, $boxes);

        $layers = $this->separateIntoLayers($elementNodes);

        foreach ($layers as $num => $layerElements) {
            $layer = $this->buildLayer($num, $layerElements);

            $page->addLayer($layer);
        }
        unset($layer, $layerElements);
    }

    /**
     * Separates a set of element nodes into layers.
     *
     * @param Node[] $elementNodes
     * @return Node[][] The element nodes, separated into layers.
     */
    private function separateIntoLayers(array $elementNodes): array
    {
        $layers = [];

        foreach ($elementNodes as $elementNode) {
            if (!$this->checkLayerNumber($elementNode->getAttribute('layer'))) {
                $layer = 0;
            } else {
                $layer = (int)$elementNode->getAttribute('layer');
            }

            if (!isset($layers[$layer])) {
                $layers[$layer] = [];
            }

            $layers[$layer][] = $elementNode;
        }
        unset($elementNode, $layer);

        if (!\ksort($layers)) {
            throw new BuildException('Could not sort layers!');
        }

        return $layers;
    }

    /**
     * @param Node[] $elementNodes
     * @return Box[]
     */
    private function createBoxes(array $elementNodes): array
    {
        $boxes = [];

        foreach ($elementNodes as $elementNode) {
            $box = new Box($elementNode->getId());

            $this->assignBoxRelative($box, 'width', $elementNode);
            $this->assignBoxRelative($box, 'height', $elementNode);
            $this->assignBoxRelative($box, 'top', $elementNode);
            $this->assignBoxRelative($box, 'left', $elementNode);
            $this->assignBoxRelative($box, 'bottom', $elementNode);
            $this->assignBoxRelative($box, 'right', $elementNode);

            $this->assignBoxDimension($box, 'width', $elementNode);
            $this->assignBoxDimension($box, 'height', $elementNode);

            $this->assignBoxOffset($box, 'top', $elementNode);
            $this->assignBoxOffset($box, 'left', $elementNode);
            $this->assignBoxOffset($box, 'bottom', $elementNode);
            $this->assignBoxOffset($box, 'right', $elementNode);

            $boxes[$box->getId()] = $box;
        }

        return $boxes;
    }

    /**
     * Assigns the relative box ID for the specified offset or dimension.
     *
     * @param Box    $box
     * @param string $measurement
     * @param Node   $elementNode
     */
    private function assignBoxRelative(Box $box, string $measurement, Node $elementNode): void
    {
        $val = $elementNode->getAttribute($measurement . '-rel');

        if ($val) {
            $box->{'set' . \ucfirst($measurement) . 'Relative'}($val);
        }
    }

    /**
     * Assigns the specified relative or absolute dimension of the box.
     *
     * @param Box    $box
     * @param string $dimension
     * @param Node   $elementNode
     */
    private function assignBoxDimension(Box $box, string $dimension, Node $elementNode): void
    {
        $val = $elementNode->getAttribute($dimension);

        if ($this->checkRelativeDimension($val)) {
            $box->{'set' . \ucfirst($dimension) . 'Percentage'}((float)\trim($val, " \t\n\r\0\x0B%") / 100);

            // A percentage dimension must be relative to something
            // If no rel attribute is provided, they are relative to the page
            if ($box->{'get' . \ucfirst($dimension) . 'Relative'} === null) {
                $box->{'set' . \ucfirst($dimension) . 'Relative'} = self::PAGE_BOX_ID;
            }
        } elseif ($this->checkDimension($val)) {
            $box->{'set' . \ucfirst($dimension)}((float)$val);
        } elseif ($val !== null) {
            throw new BuildArgumentException('Invalid ' . $dimension . ' supplied for Element.');
        }
    }

    /**
     * Assigns the specified offset of the box.
     *
     * @param Box    $box
     * @param string $offset
     * @param Node   $elementNode
     */
    private function assignBoxOffset(Box $box, string $offset, Node $elementNode): void
    {
        $val = $elementNode->getAttribute($offset);

        if ($this->checkOffset($val)) {
            $box->{'set' . \ucfirst($offset)}((float)$val);
        } elseif ($val !== null) {
            throw new BuildArgumentException('Invalid ' . $offset . ' supplied for Element.');
        }
    }

    /**
     * Lays out the Box array. This means resolving all relative references.
     *
     * @param Box[] $boxes
     */
    private function doLayout(array $boxes): void
    {
        $resolved = [];
        $unresolved = $boxes;

        while ($unresolved) {
            foreach ($unresolved as $box) {
                if (!$box->isResolved()) {
                    foreach ($box->getDependencies() as $dependency) {
                        if (!isset($boxes[$dependency])) {
                            throw new BuildException('Could not find dependency during resolution phase!');
                        }

                        $box->resolve($boxes[$dependency]);
                    }
                    unset($dependency);
                }

                // Not using else here as the loop above may have resolved all dependencies
                if ($box->isResolved()) {
                    if (!$box->isValid()) {
                        throw new BuildArgumentException('Invalid box values!');
                    }

                    unset($unresolved[$box->getId()]);
                    $resolved[$box->getId()] = $box;
                }
            }
            unset($box);
        }
    }

    /**
     * Builds a Layer from a set of Elements.
     *
     * @param int    $num
     * @param Node[] $elementNodes
     * @return Layer
     */
    protected function buildLayer(int $num, array $elementNodes): Layer
    {
        $layer = new Layer($num);

        $elementBuilder = new ElementBuilder();

        foreach ($elementNodes as $elementNode) {
            $layer->addElement($elementBuilder->buildElement($elementNode));
        }
        unset($elementNode);

        return $layer;
    }

    /**
     * Applies the dimensions of a set of Boxes to the corresponding Nodes.
     *
     * @param Node[] $nodes
     * @param Box[]  $boxes
     */
    private function applyLayout(array $nodes, array $boxes): void
    {
        foreach ($nodes as $node) {
            if (isset($boxes[$node->getId()])) {
                $box = $boxes[$node->getId()];

                if (!$box->isResolved() || !$box->isValid()) {
                    throw new BuildException('Attempted to apply an unresolved or invalid Box!');
                }

                $node->setAttribute('left', (string)$box->getLeft());
                $node->setAttribute('top', (string)$box->getTop());
                $node->setAttribute('height', (string)$box->getHeight());
                $node->setAttribute('width', (string)$box->getWidth());

                $node->removeAttribute('right');
                $node->removeAttribute('bottom');

                $node->removeAttribute('left-rel');
                $node->removeAttribute('top-rel');
                $node->removeAttribute('right-rel');
                $node->removeAttribute('bottom-rel');
                $node->removeAttribute('height-rel');
                $node->removeAttribute('width-rel');
            }
        }
        unset($node);
    }
}