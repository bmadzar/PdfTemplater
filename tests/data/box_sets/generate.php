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
        \file_put_contents('php://stderr', 'First parameter must be 1 or greater.');

        return 2;
    }

    if (empty($args[2])) {
        $args[2] = \getcwd();
    }

    if (\file_exists($args[2]) && !\is_dir($args[2])) {
        \file_put_contents('php://stderr', 'Second parameter must be a directory.');

        return 2;
    } elseif (!\file_exists($args[2])) {
        if (!@\mkdir($args[2], 0775, true)) {
            \file_put_contents('php://stderr', 'Error creating directory.');

            return 127;
        }
    } elseif (!\is_writable($args[2])) {
        \file_put_contents('php://stderr', 'Cannot write to directory.');

        return 127;
    }

    $count = \intval($args[1]);
    $dir   = \realpath($args[2]);

    for ($i = 0; $i < $count; ++$i) {
        writeBoxSet(generateBoxSet(), $dir . '/' . \sprintf('random-%05d.csv', $i));
    }
    unset($i, $count);

    return 0;
}

function generateBoxSet(?int $seed = null): array
{
    if ($seed !== null) {
        \mt_srand($seed);
    }

    $boxes = [];

    for ($i = 0, $s = \mt_rand(1, 10000); $i < $s; ++$i) {
        $boxes[$i] = [
            'finalLeft'   => \mt_rand(0, 10000),
            'finalTop'    => \mt_rand(0, 10000),
            'finalHeight' => \mt_rand(1, 10000),
            'finalWidth'  => \mt_rand(1, 10000),
        ];

        $boxes[$i]['finalRight']  = $boxes[$i]['finalLeft'] + $boxes[$i]['finalWidth'];
        $boxes[$i]['finalBottom'] = $boxes[$i]['finalTop'] + $boxes[$i]['finalHeight'];
    }
    unset($i, $s);

    foreach ($boxes as $key => $box) {
        $horizontal = \mt_rand(0, 2); // LW, RW, LR
        $vertical   = \mt_rand(0, 2); // TH, BH, TB

        if ($key > 0) {
            // 0 -> absolute, 1 -> relative
            $top    = \mt_rand(0, 1);
            $bottom = \mt_rand(0, 1);
            $left   = \mt_rand(0, 1);
            $right  = \mt_rand(0, 1);
            $width  = \mt_rand(0, 1);
            $height = \mt_rand(0, 1);
        } else {
            $top    = 0;
            $bottom = 0;
            $left   = 0;
            $right  = 0;
            $width  = 0;
            $height = 0;
        }

        if ($horizontal === 0 || $horizontal === 2) {
            if ($left === 1) {
                $otherKey = \mt_rand(0, $key - 1);

                $boxes[$key]['left'] = $box['finalLeft'] - $boxes[$otherKey]['finalLeft'];
                $boxes[$key]['leftRelative'] = $otherKey;
            } else {
                $boxes[$key]['left'] = $box['finalLeft'];
            }
        }

        if ($horizontal === 1 || $horizontal === 2) {
            if ($right === 1) {
                $otherKey = \mt_rand(0, $key - 1);

                $boxes[$key]['right'] = $box['finalRight'] - $boxes[$otherKey]['finalRight'];
                $boxes[$key]['rightRelative'] = $otherKey;
            } else {
                $boxes[$key]['right'] = $box['finalRight'];
            }
        }

        if ($horizontal === 0 || $horizontal === 1) {
            if ($width === 1) {
                $otherKey = \mt_rand(0, $key - 1);

                $boxes[$key]['widthPercentage'] = $box['finalWidth'] / $boxes[$otherKey]['finalWidth'];
                $boxes[$key]['widthRelative'] = $otherKey;
            } else {
                $boxes[$key]['width'] = $box['finalWidth'];
            }
        }

        if ($vertical === 0 || $vertical === 2) {
            if ($top === 1) {
                $otherKey = \mt_rand(0, $key - 1);

                $boxes[$key]['top'] = $box['finalTop'] - $boxes[$otherKey]['finalTop'];
                $boxes[$key]['topRelative'] = $otherKey;
            } else {
                $boxes[$key]['top'] = $box['finalTop'];
            }
        }

        if ($vertical === 1 || $vertical === 2) {
            if ($bottom === 1) {
                $otherKey = \mt_rand(0, $key - 1);

                $boxes[$key]['bottom'] = $box['finalBottom'] - $boxes[$otherKey]['finalBottom'];
                $boxes[$key]['bottomRelative'] = $otherKey;
            } else {
                $boxes[$key]['bottom'] = $box['finalBottom'];
            }
        }

        if ($vertical === 0 || $vertical === 1) {
            if ($height === 1) {
                $otherKey = \mt_rand(0, $key - 1);

                $boxes[$key]['heightPercentage'] = $box['finalHeight'] / $boxes[$otherKey]['finalHeight'];
                $boxes[$key]['heightRelative'] = $otherKey;
            } else {
                $boxes[$key]['height'] = $box['finalHeight'];
            }
        }
    }
    unset($key, $box);

    return $boxes;
}

function writeBoxSet(array $boxSet, string $path): void
{
    $fh = \fopen($path, 'w', false);

    \fputcsv($fh, [
        'id',
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

    foreach ($boxSet as $key => $box) {
        \fputcsv($fh, [
            \sprintf('box%04d', (int)$key),
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
            isset($box['rightRelative']) ? \sprintf('box%04d', (int)$box['rightRelative']) : '',
            isset($box['leftRelative']) ? \sprintf('box%04d', (int)$box['leftRelative']) : '',
            isset($box['topRelative']) ? \sprintf('box%04d', (int)$box['topRelative']) : '',
            isset($box['bottomRelative']) ? \sprintf('box%04d', (int)$box['bottomRelative']) : '',
            isset($box['widthRelative']) ? \sprintf('box%04d', (int)$box['widthRelative']) : '',
            isset($box['heightRelative']) ? \sprintf('box%04d', (int)$box['heightRelative']) : '',
        ]);
    }
    unset($box);

    \fclose($fh);
}