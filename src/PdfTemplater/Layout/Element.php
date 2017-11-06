<?php

namespace PdfTemplater\Layout;


interface Element
{
    public function __construct(string $id);

    public function setLeft(float $left): void;

    public function getLeft(): float;

    public function setTop(float $top): void;

    public function getTop(): float;

    public function setWidth(float $width): void;

    public function getWidth(): float;

    public function setHeight(float $height): void;

    public function getHeight(): float;

    public function setLink(?string $link): void;

    public function getLink(): ?string;

    public function setId(string $id): void;

    public function getId(): string;
}