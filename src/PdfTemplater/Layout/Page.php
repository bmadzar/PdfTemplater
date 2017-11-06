<?php

namespace PdfTemplater\Layout;


interface Page
{
    public function __construct(int $number);

    public function setWidth(float $width): void;

    public function getWidth(): float;

    public function setHeight(float $height): void;

    public function getHeight(): float;

    public function setLayers(array $layers): void;

    public function getLayers(): array;

    public function setLayer(int $number, Layer $layer): void;

    public function getLayer(int $number): Layer;

    public function removeLayer(int $number): bool;

    public function getNumber(): int;

    public function setNumber(int $number): void;
}