<?php
declare(strict_types=1);

namespace PdfTemplater\Builder\Basic;

use PHPUnit\Framework\TestCase;

class BoxTest extends TestCase
{
    private const DATA_FILE_PATH = __DIR__ . '/../../../../data/resolution_tests';

    public function testConstruct()
    {
        $box = new Box('test');

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
            $box->{'set' . $dim}(1.0);
            $box->{'set' . $dim . 'Relative'}($dim);
        }
        unset($dim);

        $deps = $box->getDependencies();

        \sort($deps, SORT_ASC);

        $this->assertSame(['Bottom', 'Height', 'Left', 'Right', 'Top', 'Width'], $deps);
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
        $box1 = new Box('test1');
        $box2 = new Box('test2');

        $box1->setWidthRelative('test2');
        $box2->setWidthRelative('test1');

        $this->expectException(ConstraintException::class);

        $box1->resolve($box2);
    }

    private function doResolutionTest(string $dataFile)
    {
        if (!\is_readable($dataFile) || !\is_file($dataFile)) {
            $this->markTestSkipped();
        }

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

    private function loadBoxData($dataFile)
    {
        $fh = \fopen($dataFile, 'r');

        if ($fh === false) {
            $this->markTestSkipped(\sprintf('Cannot read data file: [%s]', $dataFile));
        }

        $header = \fgetcsv($fh) ?: [];

        if (\array_diff(['finalLeft', 'finalRight', 'finalTop', 'finalBottom', 'finalHeight', 'finalWidth'], $header)) {
            \fclose($fh);
            $this->markTestSkipped(\sprintf('Skipping data file [%s]; missing final fields.', $dataFile));
        } elseif (!\in_array('id', $header, true)) {
            \fclose($fh);
            $this->markTestSkipped(\sprintf('Skipping data file [%s]; missing ID field.', $dataFile));
        }

        $boxData = [];
        while ($line = \fgetcsv($fh)) {
            $line = \array_combine($header, $line);

            $box = new Box($line['id']);
            $finals = [];

            foreach (['Right', 'Left', 'Top', 'Bottom', 'Width', 'Height'] as $dim) {
                $ldim = \strtolower($dim);
                $ldimr = $ldim . 'Relative';
                
                if (isset($line[$ldim]) && $line[$ldim] !== '') {
                    $box->{'set' . $dim}((float)$line[$ldim]);
                }

                if (isset($line[$ldimr]) && $line[$ldimr] !== '') {
                    $box->{'set' . $dim . 'Relative'}((string)$line[$ldimr]);
                }

                $finals[$dim] = (float)$line['final' . $dim];
            }
            unset($dim);

            if (isset($line['widthPercentage']) && $line['widthPercentage'] !== '') {
                $box->setWidthPercentage((float)$line['widthPercentage']);
            }

            if (isset($line['heightPercentage']) && $line['heightPercentage'] !== '') {
                $box->setHeightPercentage((float)$line['heightPercentage']);
            }

            $boxData[] = ['box' => $box, 'finals' => $finals];
        }
        unset($line, $box, $finals, $header);

        \fclose($fh);

        return $boxData;
    }


}
