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
    $steps = [0, 1 / 5, 1 / 4, 1 / 3, 4 / 5, 1];

    $dim = \count($steps) ** 4;

    $fh = \fopen(__DIR__ . \DIRECTORY_SEPARATOR . 'mixing.csv', 'w');

    if (!$fh) {
        \file_put_contents('php://stderr', 'Could not open mixing.csv for writing.');

        return 127;
    }

    \fputcsv($fh, [
        'red',
        'green',
        'blue',
        'alpha',
    ]);

    $gh1 = \imagecreatetruecolor($dim, $dim);
    $gh2 = \imagecreatetruecolor($dim, $dim);

    if (!$gh1 || !$gh2) {
        \file_put_contents('php://stderr', 'Could not create resource.');

        return 127;
    }

    \imagealphablending($gh1, false);
    \imagealphablending($gh2, false);

    \imagesavealpha($gh1, true);
    \imagesavealpha($gh2, true);

    $idx = 0;

    foreach ($steps as $r) {
        foreach ($steps as $g) {
            foreach ($steps as $b) {
                foreach ($steps as $a) {
                    $color = [(int)\round($r * 255), (int)\round($g * 255), (int)\round($b * 255), (int)\round($a * 127)];

                    $c1 = \imagecolorallocatealpha($gh1, ...$color);
                    $c2 = \imagecolorallocatealpha($gh2, ...$color);

                    \imageline($gh1, 0, $idx, $dim - 1, $idx, $c1);
                    \imageline($gh2, $idx, 0, $idx, $dim - 1, $c2);

                    \fputcsv($fh, [$color[0] / 255, $color[1] / 255, $color[2] / 255, $color[3] / 127]);

                    $idx += 1;
                }
                unset($a);
            }
            unset($b);
        }
        unset($g);
    }
    unset($r);

    \fclose($fh);

    $result1 = \imagepng($gh1, __DIR__ . \DIRECTORY_SEPARATOR . 'mixing1.png', 9, \PNG_NO_FILTER);
    $result2 = \imagepng($gh2, __DIR__ . \DIRECTORY_SEPARATOR . 'mixing2.png', 9, \PNG_NO_FILTER);

    \imagedestroy($gh1);
    \imagedestroy($gh2);

    if (!$result1 || !$result2) {
        \file_put_contents('php://stderr', 'Could not create resource.');

        return 127;
    }

    return 0;
}