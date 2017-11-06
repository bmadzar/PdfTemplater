<?php

namespace PdfTemplater\Layout;


interface Layer
{
    public function __construct(int $number);

    public function setElements(array $elements): void;

    public function getElements(): array;

    public function setElement(string $id, Element $element): void;

    public function getElement(string $id): ?Element;

    public function removeElement(string $id): bool;

    public function getNumber(): int;

    public function setNumber(int $number): void;
}