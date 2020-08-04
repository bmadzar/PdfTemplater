<?php

declare(strict_types=1);

namespace PdfTemplater\TestGenerator;

if (\PHP_SAPI !== 'cli') {
    exit(1);
} else {
    exit(generate());
}

/**
 * Returns $count points between 0 and $length, as a shuffled array.
 * Not very efficient but does the job.
 *
 * @param int $length
 * @param int $count
 * @return int[]
 */
function generateRandomPointsOnLine(int $length, int $count): array
{
    if ($length < 0 || $count < 0 || $count > $length) {
        throw new \RuntimeException(\sprintf('Invalid parameters: %d, %d', $length, $count));
    }

    $array = \range(0, $length - 1);
    \shuffle($array);

    return \array_slice($array, 0, $count);
}

function generate(): int
{
    $api_url    = 'http://localhost:3001'; // colorvert
    $num_points = 100; // Testing the entire image would take too long, so we only check ($num_points * $num_points) points

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
        ]
    );

    \imagealphablending($gh, false);

    $ch = \curl_init();

    \curl_setopt($ch, \CURLOPT_RETURNTRANSFER, true);
    \curl_setopt($ch, \CURLOPT_HTTPHEADER, ['Accept: application/json']);

    $colPoints = generateRandomPointsOnLine(\imagesy($gh), $num_points);
    $rowPoints = generateRandomPointsOnLine(\imagesx($gh), $num_points);

    foreach ($rowPoints as $row) {
        foreach ($colPoints as $col) {
            $rgba = \imagecolorat($gh, $col, $row);

            $r = (($rgba >> 16) & 0xFF) / 255;
            $g = (($rgba >> 8) & 0xFF) / 255;
            $b = ($rgba & 0xFF) / 255;
            $a = (127 - (($rgba & 0x7F000000) >> 24)) / 127;

            \curl_setopt($ch, \CURLOPT_URL, $api_url . '/rgb/' . \round($r * 255) . '/' . \round($g * 255) . '/' . \round($b * 255) . '/');
            $result = \curl_exec($ch);

            if ($result && ($result_data = \json_decode($result, true)) && \json_last_error() === \JSON_ERROR_NONE) {
                $max = \max($r, $g, $b);

                \fputcsv(
                    $fh,
                    [
                        $row,
                        $col,
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
                        $a,
                        'RGB',
                    ]
                );
            }
        }
        unset($col);
    }
    unset($row);

    \imagedestroy($gh);
    \fclose($fh);

    return 0;
}