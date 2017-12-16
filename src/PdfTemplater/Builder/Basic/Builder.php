<?php
declare(strict_types=1);

namespace PdfTemplater\Builder\Basic;


use PdfTemplater\Builder\Builder as BuilderInterface;
use PdfTemplater\Builder\BuildException;
use PdfTemplater\Layout\Basic\Document;
use PdfTemplater\Layout\Basic\Element;
use PdfTemplater\Layout\Basic\ElementFactory;
use PdfTemplater\Layout\Basic\Layer;
use PdfTemplater\Layout\Basic\Page;
use PdfTemplater\Layout\Document as DocumentInterface;
use PdfTemplater\Node\Node;

/**
 * Class Builder
 *
 * A basic implementation of the Builder interface.
 *
 * @package PdfTemplater\Builder\Basic
 */
class Builder implements BuilderInterface
{
    private const PAGE_BOX_ID = "\0";

    /**
     * Accepts a Node tree as input, performs the layout process and returns the final Document.
     *
     * @param Node $rootNode The tree of Nodes obtained from the Parser.
     * @return Document The final, laid-out Document.
     */
    public function build(Node $rootNode): DocumentInterface
    {
        return $this->buildDocument($rootNode);
    }

    /**
     * Builds a Document from a Node tree.
     *
     * @param Node $rootNode
     * @return Document
     */
    protected function buildDocument(Node $rootNode): DocumentInterface
    {
        if ($rootNode->getType() !== 'document') {
            throw new BuildException('Root node must be a document!');
        }

        $document = new Document();

        foreach (['author', 'title', 'keywords'] as $item) {
            $document->setMetadataValue($item, $rootNode->getAttribute($item));
        }
        unset($item);

        $document->setMetadataValue('id', $rootNode->getId());

        foreach ($rootNode->getChildren() as $childNode) {
            if ($childNode->getType() === 'page') {
                $document->addPage($this->buildPage($childNode));
            }
        }
        unset($childNode);

        return $document;
    }

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
            \preg_match('^\s*[0-9]+(?:\.[0-9]+)?\s*%$', $attribute);
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
    protected function buildPage(Node $pageNode): Page
    {
        if (!$this->checkPageNumber($pageNode->getAttribute('number'))) {
            throw new BuildException('Page number must be an integer 0 or greater.');
        }

        if (!$this->checkDimension($pageNode->getAttribute('width'))) {
            throw new BuildException('Page width must be a float 0 or greater.');
        }

        if (!$this->checkDimension($pageNode->getAttribute('height'))) {
            throw new BuildException('Page height must be a float 0 or greater.');
        }

        $page = new Page((int)$pageNode->getAttribute('number'));

        $page->setWidth((float)$pageNode->getAttribute('width'));
        $page->setHeight((float)$pageNode->getAttribute('height'));

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

        $layers = $this->separateIntoLayers($elementNodes);

        foreach ($layers as $num => $layerElements) {
            $layer = $this->buildLayer($num, $layerElements);

            $this->applyLayout($layer, $boxes);
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
            $box->{'set' . \ucfirst($dimension)}($val);
        } elseif ($val !== null) {
            throw new BuildException('Invalid ' . $dimension . ' supplied for Element.');
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
            $box->{'set' . \ucfirst($offset)}($val);
        } elseif ($val !== null) {
            throw new BuildException('Invalid ' . $offset . ' supplied for Element.');
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
                        throw new BuildException('Invalid box values!');
                    }

                    unset($unresolved[$box->getId()]);
                    $resolved[$box->getId()] = $box;
                }
            }
            unset($box);
        }
    }

    /**
     * Builds an Element from a Node.
     *
     * @param Node $elementNode
     * @return Element
     */
    protected function buildElement(Node $elementNode): Element
    {
        $factory = new ElementFactory();

        $element = $factory->createElement($elementNode->getType(), $elementNode->getId());

        $factory->setExtendedAttributes($element, $elementNode->getAttributes());

        if (!$element->isValid()) {
            throw new BuildException('Element cannot be built completely!');
        }

        return $element;
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

        foreach ($elementNodes as $elementNode) {
            $layer->addElement($this->buildElement($elementNode));
        }
        unset($elementNode);

        return $layer;
    }

    /**
     * Applies the dimensions of a set of Boxes to the elements in a Layer.
     *
     * @param Layer $layer
     * @param Box[] $boxes
     */
    private function applyLayout(Layer $layer, array $boxes): void
    {
        foreach ($layer->getElements() as $element) {
            if (isset($boxes[$element->getId()])) {
                $box = $boxes[$element->getId()];

                if (!$box->isResolved() || !$box->isValid()) {
                    throw new BuildException('Attempted to apply an unresolved or invalid Box!');
                }

                $element->setLeft($box->getLeft());
                $element->setTop($box->getTop());
                $element->setHeight($box->getHeight());
                $element->setWidth($box->getWidth());
            }
        }
    }
}