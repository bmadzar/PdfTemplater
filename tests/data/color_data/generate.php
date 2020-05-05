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
    $steps = [0, 1 / 5, 1 / 4, 1 / 3, 2 / 5, 1 / 2, 3 / 5, 2 / 3, 3 / 4, 4 / 5, 1];
    $api_url = 'http://localhost:3000';

    $fh = \fopen(__DIR__ . \DIRECTORY_SEPARATOR . 'conversion.csv', 'w');

    if (!$fh) {
        \file_put_contents('php://stderr', 'Could not open conversion.csv for writing.');

        return 127;
    }

    \fputcsv($fh, ['red', 'green', 'blue', 'cyan', 'magenta', 'yellow', 'black', 'hue', 'saturation', 'lightness', 'alpha']);

    $ch = \curl_init();

    \curl_setopt($ch, \CURLOPT_RETURNTRANSFER, true);
    \curl_setopt($ch, \CURLOPT_HTTPHEADER, ['Accept: application/json']);

    foreach ($steps as $r) {
        foreach ($steps as $g) {
            foreach ($steps as $b) {
                foreach ($steps as $a) {
                    \curl_setopt($ch, \CURLOPT_URL, $api_url . '/rgb/' . round($r * 255) . '/' . round($g * 255) . '/' . round($b * 255) . '/');
                    $result = \curl_exec($ch);

                    if ($result && ($result_data = \json_decode($result, true)) && \json_last_error() === \JSON_ERROR_NONE) {
                        \fputcsv($fh, [
                            $r,
                            $g,
                            $b,
                            $result_data['cmyk']['c'] / 100,
                            $result_data['cmyk']['m'] / 100,
                            $result_data['cmyk']['y'] / 100,
                            $result_data['cmyk']['k'] / 100,
                            $result_data['hsl']['h'] / 360,
                            $result_data['hsl']['s'] / 100,
                            $result_data['hsl']['l'] / 100,
                            $a,
                        ]);
                    }
                }
                unset($a);
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
                    foreach ($steps as $a) {
                        \curl_setopt($ch, \CURLOPT_URL, $api_url . '/cmyk/' . round($c * 100) . '/' . round($m * 100) . '/' . round($y * 100) . '/' . round($k * 100) . '/');
                        $result = \curl_exec($ch);

                        if ($result && ($result_data = \json_decode($result, true)) && \json_last_error() === \JSON_ERROR_NONE) {
                            \fputcsv($fh, [
                                $result_data['rgb']['r'] / 255,
                                $result_data['rgb']['g'] / 255,
                                $result_data['rgb']['b'] / 255,
                                $c,
                                $m,
                                $y,
                                $k,
                                $result_data['hsl']['h'] / 360,
                                $result_data['hsl']['s'] / 100,
                                $result_data['hsl']['l'] / 100,
                                $a,
                            ]);
                        }
                    }
                    unset($a);
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
                foreach ($steps as $a) {
                    \curl_setopt($ch, \CURLOPT_URL, $api_url . '/hsl/' . round($h * 360) . '/' . round($s * 100) . '/' . round($l * 100) . '/');
                    $result = \curl_exec($ch);

                    if ($result && ($result_data = \json_decode($result, true)) && \json_last_error() === \JSON_ERROR_NONE) {
                        \fputcsv($fh, [
                            $result_data['rgb']['r'] / 255,
                            $result_data['rgb']['g'] / 255,
                            $result_data['rgb']['b'] / 255,
                            $result_data['cmyk']['c'] / 100,
                            $result_data['cmyk']['m'] / 100,
                            $result_data['cmyk']['y'] / 100,
                            $result_data['cmyk']['k'] / 100,
                            $h,
                            $s,
                            $l,
                            $a,
                        ]);
                    }
                }
                unset($a);
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