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
    $api_url     = 'http://localhost:3001'; // colorvert

    $dim = \count($steps) ** 4;

    $fh = \fopen(__DIR__ . \DIRECTORY_SEPARATOR . 'mixing.csv', 'w');

    if (!$fh) {
        \file_put_contents('php://stderr', 'Could not open mixing.csv for writing.');

        return 127;
    }

    \fputcsv($fh, [
        'idx',
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

    $ch = \curl_init();

    \curl_setopt($ch, \CURLOPT_RETURNTRANSFER, true);
    \curl_setopt($ch, \CURLOPT_HTTPHEADER, ['Accept: application/json']);

    foreach ($steps as $r) {
        foreach ($steps as $g) {
            foreach ($steps as $b) {
                \curl_setopt($ch, \CURLOPT_URL, $api_url . '/rgb/' . \round($r * 255) . '/' . \round($g * 255) . '/' . \round($b * 255) . '/');
                $result = \curl_exec($ch);

                if ($result && ($result_data = \json_decode($result, true)) && \json_last_error() === \JSON_ERROR_NONE) {
                    $max = \max($r, $g, $b);

                    foreach ($steps as $a) {
                        $color = [(int)\round($r * 255), (int)\round($g * 255), (int)\round($b * 255), (int)\round($a * 127)];

                        $c1 = \imagecolorallocatealpha($gh1, ...$color);
                        $c2 = \imagecolorallocatealpha($gh2, ...$color);

                        \imageline($gh1, 0, $idx, $dim - 1, $idx, $c1);
                        \imageline($gh2, $idx, 0, $idx, $dim - 1, $c2);

                        \fputcsv(
                            $fh,
                            [
                                $idx,
                                \round($r * 255) / 255,
                                \round($g * 255) / 255,
                                \round($b * 255) / 255,
                                $result_data['cmyk']['c'] / 100,
                                $result_data['cmyk']['m'] / 100,
                                $result_data['cmyk']['y'] / 100,
                                $result_data['cmyk']['k'] / 100,
                                $max >= 0.01 ? \round(($max - $r) / $max, 2) : 0.0,
                                $max >= 0.01 ? \round(($max - $g) / $max, 2) : 0.0,
                                $max >= 0.01 ? \round(($max - $b) / $max, 2) : 0.0,
                                $max >= 0.01 ? \round(1 - $max, 2) : 1.0,
                                $result_data['hsl']['h'] / 360,
                                $result_data['hsl']['s'] / 100,
                                $result_data['hsl']['l'] / 100,
                                \round($a * 127) / 127,
                                'RGB',
                            ]
                        );

                        $idx += 1;
                    }
                    unset($a);
                }
            }
            unset($b);
        }
        unset($g);
    }
    unset($r);

    \curl_close($ch);

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