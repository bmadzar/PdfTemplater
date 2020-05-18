<?php

declare(strict_types=1);

namespace PdfTemplater\TestGenerator;

if (\PHP_SAPI !== 'cli') {
    exit(1);
} else {
    exit(generate());
}

function generate(): int
{
    $fh = \fopen(__DIR__ . \DIRECTORY_SEPARATOR . 'mixing-combined.csv', 'w');

    if (!$fh) {
        \file_put_contents('php://stderr', 'Could not open mixing-combined.csv for writing.');

        return 127;
    }

    $gh = \imagecreatefrompng(__DIR__ . \DIRECTORY_SEPARATOR . 'mixing-combined.png');

    if (!$gh) {
        \fclose($fh);

        \file_put_contents('php://stderr', 'Could not open mixing-combined.png for reading.');

        return 127;
    }

    \fputcsv(
        $fh,
        [
            'row',
            'col',
            'red',
            'green',
            'blue',
            'alpha',
            'source',
        ]
    );

    // \imagealphablending($gh, false);

    for ($row = 0, $height = \imagesy($gh); $row < $height; ++$row) {
        for ($col = 0, $width = \imagesx($gh); $col < $width; ++$col) {
            $rgba = \imagecolorat($gh, $col, $row);

            \fputcsv(
                $fh,
                [
                    $row,
                    $col,
                    (($rgba >> 16) & 0xFF) / 255,
                    (($rgba >> 8) & 0xFF) / 255,
                    ($rgba & 0xFF) / 255,
                    (127 - (($rgba & 0x7F000000) >> 24)) / 127,
                    'RGB',
                ]
            );
        }
        unset($col, $width);
    }
    unset($row, $height);

    \imagedestroy($gh);
    \fclose($fh);

    return 0;
}