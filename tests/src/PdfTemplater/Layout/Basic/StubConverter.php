<?php

declare(strict_types=1);

namespace PdfTemplater\Layout\Basic;


use PdfTemplater\Layout\ColorConverter;
use PHPUnit\Framework\SkippedTestError;

class StubConverter implements ColorConverter
{
    private const DATA_FILE_PATH =
        __DIR__ . \DIRECTORY_SEPARATOR . '..' . \DIRECTORY_SEPARATOR . '..' . \DIRECTORY_SEPARATOR . '..' . \DIRECTORY_SEPARATOR . '..' . \DIRECTORY_SEPARATOR .
        'data' . \DIRECTORY_SEPARATOR . 'color_data';

    private array $rgbCmyk = [];
    private array $cmykRgb = [];

    public function __construct()
    {
        $fh = \fopen(self::DATA_FILE_PATH . \DIRECTORY_SEPARATOR . 'conversion.csv', 'r');

        if ($fh === false) {
            throw new SkippedTestError('Cannot read data file: conversion.csv');
        }

        $header = \fgetcsv($fh) ?: [];

        if (\array_diff(
            [
                'red',
                'green',
                'blue',
                'cyan',
                'magenta',
                'yellow',
                'black',
                'cyan-naive',
                'magenta-naive',
                'yellow-naive',
                'black-naive',
                'hue',
                'saturation',
                'lightness',
                'alpha',
                'source',
            ],
            $header
        )) {
            \fclose($fh);
            throw new SkippedTestError('Missing fields in conversion.csv');
        }

        while ($line = \fgetcsv($fh)) {
            $line = \array_combine($header, \array_map('\floatval', $line));

            $this->rgbCmyk[\sprintf('%F.2|%F.2|%F.2', $line['red'], $line['green'], $line['blue'])] = [
                $line['cyan'],
                $line['magenta'],
                $line['yellow'],
                $line['black'],
            ];

            $this->cmykRgb[\sprintf('%F.2|%F.2|%F.2|%F.2', $line['cyan'], $line['magenta'], $line['yellow'], $line['black'])] = [
                $line['red'],
                $line['green'],
                $line['blue'],
            ];
        }
        unset($line);

        \fclose($fh);
    }

    /**
     * Returns true if the converter is enabled, false if it is disabled, and null if it is not available.
     *
     * @return bool|null
     */
    public function isEnabled(): ?bool
    {
        return true;
    }

    /**
     * Sets the ICC color profiles to use for conversion.
     *
     * @param string $rgbProfile
     * @param string $cmykProfile
     */
    public function setColorProfiles(string $rgbProfile, string $cmykProfile): void
    {
        //NO-OP
    }

    /**
     * Returns the ICC color profiles used, as a 2-element array.
     *
     * @return string[]
     */
    public function getColorProfiles(): array
    {
        return ['rgb' => 'rgb', 'cmyk' => 'cmyk'];
    }

    /**
     * Converts the supplied RGB values (0.0-1.0) to CMYK (0.0-1.0).
     *
     * @param float $r
     * @param float $g
     * @param float $b
     * @return float[] The CMYK values as a 4-element array.
     */
    public function rgbToCmyk(float $r, float $g, float $b): array
    {
        if (isset($this->rgbCmyk[\sprintf('%F.2|%F.2|%F.2', $r, $g, $b)])) {
            return $this->rgbCmyk[\sprintf('%F.2|%F.2|%F.2', $r, $g, $b)];
        }

        throw new SkippedTestError('Missing data in conversion file.');
    }

    /**
     * Converts the supplied CMYK values (0.0-1.0) to RGB (0.0-1.0).
     *
     * @param float $c
     * @param float $m
     * @param float $y
     * @param float $k
     * @return float[]
     */
    public function cmykToRgb(float $c, float $m, float $y, float $k): array
    {
        if (isset($this->cmykRgb[\sprintf('%F.2|%F.2|%F.2|%F.2', $c, $m, $y, $k)])) {
            return $this->cmykRgb[\sprintf('%F.2|%F.2|%F.2|%F.2', $c, $m, $y, $k)];
        }

        throw new SkippedTestError('Missing data in conversion file.');
    }
}