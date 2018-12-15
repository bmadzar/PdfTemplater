<?php

namespace PdfTemplater\Builder\Basic;

use PHPUnit\Framework\TestCase;
use PHPUnit\Runner\Exception;

class BoxTest extends TestCase
{
    private const RESOLUTION_CSV_PATH = __DIR__ . '/../../../../data/resolution_tests';

    public function testConstruct()
    {
        $box = new Box('test');

        $this->assertInstanceOf(Box::class, $box);
        $this->assertSame('test', $box->getId());
    }

    public function testId()
    {
        $box = new Box('test');

        $this->assertSame('test', $box->getId());

        $box->setId('test2');

        $this->assertSame('test2', $box->getId());
    }

    public function testTop()
    {
        $box = new Box('test');

        $this->assertNull($box->getTop());

        $box->setTop(1.0);

        $this->assertSame(1.0, $box->getTop());

        $box->setTop(-1.0);

        $this->assertSame(-1.0, $box->getTop());
    }

    public function testBottom()
    {
        $box = new Box('test');

        $this->assertNull($box->getBottom());

        $box->setBottom(1.0);

        $this->assertSame(1.0, $box->getBottom());

        $box->setBottom(-1.0);

        $this->assertSame(-1.0, $box->getBottom());
    }


    public function testLeft()
    {
        $box = new Box('test');

        $this->assertNull($box->getLeft());

        $box->setLeft(1.0);

        $this->assertSame(1.0, $box->getLeft());

        $box->setLeft(-1.0);

        $this->assertSame(-1.0, $box->getLeft());
    }

    public function testRight()
    {
        $box = new Box('test');

        $this->assertNull($box->getRight());

        $box->setRight(1.0);

        $this->assertSame(1.0, $box->getRight());

        $box->setRight(-1.0);

        $this->assertSame(-1.0, $box->getRight());
    }

    public function testWidth()
    {
        $box = new Box('test');

        $this->assertNull($box->getWidth());

        $box->setWidth(1.0);

        $this->assertSame(1.0, $box->getWidth());

        $this->expectException(BoxArgumentException::class);

        $box->setWidth(-1.0);
    }

    public function testHeight()
    {
        $box = new Box('test');

        $this->assertNull($box->getHeight());

        $box->setHeight(1.0);

        $this->assertSame(1.0, $box->getHeight());

        $this->expectException(BoxArgumentException::class);

        $box->setHeight(-1.0);
    }

    public function testWidthPercentage()
    {
        $box = new Box('test');

        $this->assertNull($box->getWidthPercentage());

        $box->setWidthPercentage(1.0);

        $this->assertSame(1.0, $box->getWidthPercentage());

        $this->expectException(BoxArgumentException::class);

        $box->setWidthPercentage(-1.0);
    }

    public function testHeightPercentage()
    {
        $box = new Box('test');

        $this->assertNull($box->getHeightPercentage());

        $box->setHeightPercentage(1.0);

        $this->assertSame(1.0, $box->getHeightPercentage());

        $this->expectException(BoxArgumentException::class);

        $box->setHeightPercentage(-1.0);
    }

    public function testTopRelative()
    {
        $box = new Box('test');

        $this->assertNull($box->getTopRelative());

        $box->setTopRelative('test2');

        $this->assertSame('test2', $box->getTopRelative());

        $this->expectException(ConstraintException::class);

        $box->setTopRelative('test');
    }


    public function testBottomRelative()
    {
        $box = new Box('test');

        $this->assertNull($box->getBottomRelative());

        $box->setBottomRelative('test2');

        $this->assertSame('test2', $box->getBottomRelative());

        $this->expectException(ConstraintException::class);

        $box->setBottomRelative('test');
    }


    public function testLeftRelative()
    {
        $box = new Box('test');

        $this->assertNull($box->getLeftRelative());

        $box->setLeftRelative('test2');

        $this->assertSame('test2', $box->getLeftRelative());

        $this->expectException(ConstraintException::class);

        $box->setLeftRelative('test');
    }


    public function testRightRelative()
    {
        $box = new Box('test');

        $this->assertNull($box->getRightRelative());

        $box->setRightRelative('test2');

        $this->assertSame('test2', $box->getRightRelative());

        $this->expectException(ConstraintException::class);

        $box->setRightRelative('test');
    }


    public function testHeightRelative()
    {
        $box = new Box('test');

        $this->assertNull($box->getHeightRelative());

        $box->setHeightRelative('test2');

        $this->assertSame('test2', $box->getHeightRelative());

        $this->expectException(ConstraintException::class);

        $box->setHeightRelative('test');
    }

    public function testWidthRelative()
    {
        $box = new Box('test');

        $this->assertNull($box->getWidthRelative());

        $box->setWidthRelative('test2');

        $this->assertSame('test2', $box->getWidthRelative());

        $this->expectException(ConstraintException::class);

        $box->setWidthRelative('test');
    }

    public function testDependencies()
    {
        $box = new Box('test');

        foreach (['Right', 'Left', 'Top', 'Bottom', 'Width', 'Height'] as $dim) {
            $box = new Box('test');

            $box->{'set' . $dim}(1.0);
            $box->{'set' . $dim . 'Relative'}($dim);
        }
        unset($dim);

        $this->assertArraySubset(['Right', 'Left', 'Top', 'Bottom', 'Width', 'Height'], $box->getDependencies());
        $this->assertArraySubset($box->getDependencies(), ['Right', 'Left', 'Top', 'Bottom', 'Width', 'Height']);
    }

    public function testAbsoluteIsAlwaysResolved()
    {
        $box = new Box('test');

        $this->assertTrue($box->isResolved());

        foreach (['Right', 'Left', 'Top', 'Bottom', 'Width', 'Height'] as $dim) {
            $box = new Box('test');

            $box->{'set' . $dim}(1.0);

            $this->assertTrue($box->isResolved());
        }
        unset($dim);

    }

    public function testRelativeIsNotResolved()
    {
        foreach (['Right', 'Left', 'Top', 'Bottom', 'Width', 'Height'] as $dim) {
            $box = new Box('test');

            $box->{'set' . $dim}(1.0);
            $box->{'set' . $dim . 'Relative'}('test2');

            $this->assertFalse($box->isResolved());
        }
        unset($dim);

    }

    public function testBasicResolution()
    {
        $box1 = new Box('test');
        $box2 = new Box('test2');

        $box1->setRight(1.0);
        $box1->setRightRelative('test2');

        $box2->setLeft(1.0);

        $this->assertTrue($box2->isResolved());
        $this->assertFalse($box1->isResolved());

        $box1->resolve($box2);
        $this->assertTrue($box1->isResolved());
    }

    public function testResolutionCycleCheckSingle()
    {
        $box1 = new Box('test');

        $this->expectException(ConstraintException::class);

        $box1->resolve($box1);
    }

    public function testResolution()
    {
        if (!\is_readable(self::RESOLUTION_CSV_PATH) || !\is_dir(self::RESOLUTION_CSV_PATH)) {
            throw new Exception('Cannot read CSV data directory.');
        }

        foreach (\glob(self::RESOLUTION_CSV_PATH . '/*.csv') as $dataFile) {
            $boxData = $this->loadBoxData($dataFile);

            for ($i = 0, $s = \count($boxData); $i < $s; ++$i) {
                for ($j = ($i + 1); $j < $s; ++$j) {
                    /** @var Box $box1 */
                    $box1 = $boxData[$i]['box'];
                    /** @var Box $box2 */
                    $box2 = $boxData[$j]['box'];

                    $box1->resolve($box2);
                }
                unset($j, $box1, $box2);
            }
            unset($i, $s);

            foreach ($boxData as $boxDatum) {
                /** @var Box $box */
                $box = $boxDatum['box'];

                $this->assertTrue($box->isResolved());
                $this->assertTrue($box->isValid());

                foreach (['Right', 'Left', 'Top', 'Bottom', 'Width', 'Height'] as $dim) {
                    $this->assertSame($boxDatum['finals'][$dim], $box->{'get' . $dim});
                }
                unset($dim);
            }
            unset($boxDatum, $box);
        }
        unset($dataFile);
    }

    private function loadBoxData($dataFile)
    {
        $fh = \fopen($dataFile, 'r');
        $header = \fgetcsv($fh);

        if (\array_diff(['finalLeft', 'finalRight', 'finalTop', 'finalBottom', 'finalHeight', 'finalWidth'], $header)) {
            \fclose($fh);
            throw new Exception(\sprintf('Skipping data file [%s]; missing final fields.', $dataFile));
        } elseif (!\in_array('id', $header, true)) {
            \fclose($fh);
            throw new Exception(\sprintf('Skipping data file [%s]; missing ID field.', $dataFile));
        }

        $boxData = [];
        while ($line = \fgetcsv($fh)) {
            $line = \array_combine($header, $line);

            $box = new Box($line['id']);
            $finals = [];

            foreach (['Right', 'Left', 'Top', 'Bottom', 'Width', 'Height'] as $dim) {
                if (isset($line[\strtolower($dim)])) {
                    $box->{'set' . $dim}((float)$line[\strtolower($dim)]);
                }

                if (isset($line[\strtolower($dim) . 'Relative'])) {
                    $box->{'set' . $dim . 'Relative'}((string)$line[\strtolower($dim)]);
                }

                $finals[$dim] = (float)$line['final' . $dim];
            }
            unset($dim);

            if (isset($line['widthPercentage'])) {
                $box->setWidthPercentage((float)$line['widthPercentage']);
            }

            if (isset($line['heightPercentage'])) {
                $box->setHeightPercentage((float)$line['heightPercentage']);
            }

            $boxData[] = \compact('box', 'finals');
        }
        unset($line, $box, $finals, $header);

        \fclose($fh);

        return $boxData;
    }


}
