<?php
declare(strict_types=1);

namespace PdfTemplater\TestGenerator;

if (\PHP_SAPI !== 'cli') {
    exit(1);
} else {
    exit(generate());
}

function generate(): int {
    $steps       = [0, 1 / 5, 1 / 4, 1 / 3, 4 / 5, 1];
    $alpha_steps = [0, 1 / 2, 1]; // Alpha is not modified during color conversion
    $api_url     = 'http://10.0.0.5:3003'; // colorvert

    $fh = \fopen(__DIR__ . \DIRECTORY_SEPARATOR . 'conversion.csv', 'w');

    if (!$fh) {
        \file_put_contents('php://stderr', 'Could not open conversion.csv for writing.');

        return 127;
    }

    \fputcsv($fh, [
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
    ]);

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

                    foreach ($alpha_steps as $a) {
                        \fputcsv($fh, [
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
                        ]);
                    }
                    unset($a);
                }

            }
            unset($b);
        }
        unset($g);
    }
    unset($r);

    foreach ($steps as $c) {
        foreach ($steps as $m) {
            foreach ($steps as $y) {
                foreach ($steps as $k) {
                    \curl_setopt($ch, \CURLOPT_URL, $api_url . '/cmyk/' . \round($c * 100) . '/' . \round($m * 100) . '/' . \round($y * 100) . '/' . \round($k * 100) . '/');
                    $result = \curl_exec($ch);

                    if ($result && ($result_data = \json_decode($result, true)) && \json_last_error() === \JSON_ERROR_NONE) {
                        $max = \max(
                                $result_data['rgb']['r'],
                                $result_data['rgb']['g'],
                                $result_data['rgb']['b']
                            ) / 255;

                        foreach ($alpha_steps as $a) {
                            \fputcsv($fh, [
                                $result_data['rgb']['r'] / 255,
                                $result_data['rgb']['g'] / 255,
                                $result_data['rgb']['b'] / 255,
                                \round($c, 2),
                                \round($m, 2),
                                \round($y, 2),
                                \round($k, 2),
                                $max >= 0.01 ? \round(($max - ($result_data['rgb']['r'] / 255)) / $max, 2) : 0.0,
                                $max >= 0.01 ? \round(($max - ($result_data['rgb']['g'] / 255)) / $max, 2) : 0.0,
                                $max >= 0.01 ? \round(($max - ($result_data['rgb']['b'] / 255)) / $max, 2) : 0.0,
                                $max >= 0.01 ? \round(1 - $max, 2) : 1.0,
                                $result_data['hsl']['h'] / 360,
                                $result_data['hsl']['s'] / 100,
                                $result_data['hsl']['l'] / 100,
                                $a,
                            ]);
                        }
                        unset($a);
                    }
                }
                unset($k);
            }
            unset($y);
        }
        unset($m);
    }
    unset($c);

    foreach ($steps as $h) {
        foreach ($steps as $s) {
            foreach ($steps as $l) {

                \curl_setopt($ch, \CURLOPT_URL, $api_url . '/hsl/' . \round($h * 360) . '/' . \round($s * 100) . '/' . \round($l * 100) . '/');
                $result = \curl_exec($ch);

                if ($result && ($result_data = \json_decode($result, true)) && \json_last_error() === \JSON_ERROR_NONE) {
                    $max = \max(
                            $result_data['rgb']['r'],
                            $result_data['rgb']['g'],
                            $result_data['rgb']['b']
                        ) / 255;

                    foreach ($alpha_steps as $a) {
                        \fputcsv($fh, [
                            $result_data['rgb']['r'] / 255,
                            $result_data['rgb']['g'] / 255,
                            $result_data['rgb']['b'] / 255,
                            $result_data['cmyk']['c'] / 100,
                            $result_data['cmyk']['m'] / 100,
                            $result_data['cmyk']['y'] / 100,
                            $result_data['cmyk']['k'] / 100,
                            $max >= 0.01 ? \round(($max - ($result_data['rgb']['r'] / 255)) / $max, 2) : 0.0,
                            $max >= 0.01 ? \round(($max - ($result_data['rgb']['g'] / 255)) / $max, 2) : 0.0,
                            $max >= 0.01 ? \round(($max - ($result_data['rgb']['b'] / 255)) / $max, 2) : 0.0,
                            $max >= 0.01 ? \round(1 - $max, 2) : 1.0,
                            \round($h * 360) / 360,
                            \round($s, 2),
                            \round($l, 2),
                            $a,
                        ]);
                    }
                    unset($a);
                }
            }
            unset($l);
        }
        unset($s);
    }
    unset($h);

    \curl_close($ch);
    \fclose($fh);

    return 0;
}