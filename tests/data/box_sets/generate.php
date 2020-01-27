<?php
declare(strict_types=1);

namespace PdfTemplater\TestGenerator;

if (\PHP_SAPI !== 'cli' || !isset($argv)) {
    exit(1);
} else {
    exit(generate($argv));
}

function generate(array $args): int
{
    if (empty($args[1]) || !\is_numeric($args[1]) || \intval($args[1]) <= 0) {
        \file_put_contents('php://error', 'First parameter must be 1 or greater.');
        return 2;
    }

    if (empty($args[2])) {
        $args[2] = \getcwd();
    }

    if (\file_exists($args[2]) && !\is_dir($args[2])) {
        \file_put_contents('php://error', 'Second parameter must be a directory.');
        return 2;
    } elseif (!\file_exists($args[2])) {
        if (!@\mkdir($args[2], 0775, true)) {
            \file_put_contents('php://error', 'Error creating directory.');
            return 127;
        }
    } elseif (!\is_writable($args[2])) {
        \file_put_contents('php://error', 'Cannot write to directory.');
        return 127;
    }

    $count = \intval($args[1]);
    $dir = \realpath($args[2]);

    for ($i = 0; $i < $count; ++$i) {
        writeBoxSet(generateBoxSet(), $dir . '/' . \sprintf('random-%05d.csv', $i));
    }
    unset($i, $count);
}

function generateBoxSet(?int $seed = null): array
{
    if ($seed !== null) {
        \mt_srand($seed);
    }

    $boxes = [];

    for ($i = 0, $s = \mt_rand(0, 10000); $i < $s; ++$i) {
        $boxes[$i] = [
            'finalLeft'   => \mt_rand(0, 10000),
            'finalTop'    => \mt_rand(0, 10000),
            'finalHeight' => \mt_rand(0, 10000),
            'finalWidth'  => \mt_rand(0, 10000),
        ];

        $boxes[$i]['finalRight'] = $boxes[$i]['finalLeft'] + $boxes[$i]['finalWidth'];
        $boxes[$i]['finalBottom'] = $boxes[$i]['finalTop'] + $boxes[$i]['finalHeight'];
    }
    unset($i, $s);

    return $boxes;
}

function writeBoxSet(array $boxSet, string $path): void
{
    $fh = \fopen($path, 'w', false);

    \fputcsv($fh, [
        'finalLeft',
        'finalRight',
        'finalTop',
        'finalBottom',
        'finalHeight',
        'finalWidth',
        'right',
        'left',
        'top',
        'bottom',
        'width',
        'height',
        'widthPercentage',
        'heightPercentage',
        'rightRelative',
        'leftRelative',
        'topRelative',
        'bottomRelative',
        'widthRelative',
        'heightRelative',
    ]);

    foreach ($boxSet as $box) {
        \fputcsv($fh, [
            $box['finalLeft'] ?? '',
            $box['finalRight'] ?? '',
            $box['finalTop'] ?? '',
            $box['finalBottom'] ?? '',
            $box['finalHeight'] ?? '',
            $box['finalWidth'] ?? '',
            $box['right'] ?? '',
            $box['left'] ?? '',
            $box['top'] ?? '',
            $box['bottom'] ?? '',
            $box['width'] ?? '',
            $box['height'] ?? '',
            $box['widthPercentage'] ?? '',
            $box['heightPercentage'] ?? '',
            $box['rightRelative'] ?? '',
            $box['leftRelative'] ?? '',
            $box['topRelative'] ?? '',
            $box['bottomRelative'] ?? '',
            $box['widthRelative'] ?? '',
            $box['heightRelative'] ?? '',
        ]);
    }
    unset($box);

    \fclose($fh);
}