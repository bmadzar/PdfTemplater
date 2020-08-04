<?php
declare(strict_types=1);

namespace PdfTemplater\Builder\Basic;

use PdfTemplater\Test\DataFile;
use PHPUnit\Framework\TestCase;

class BoxTest extends TestCase
{
    private const DATA_FILE_PATH =
        __DIR__ . \DIRECTORY_SEPARATOR . '..' . \DIRECTORY_SEPARATOR . '..' . \DIRECTORY_SEPARATOR . '..' . \DIRECTORY_SEPARATOR . '..' . \DIRECTORY_SEPARATOR .
        'data' . \DIRECTORY_SEPARATOR . 'box_sets';

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

        $box->setTop(1.0, null);

        $this->assertSame(1.0, $box->getTop());

        $box->setTop(-1.0, null);

        $this->assertSame(-1.0, $box->getTop());
    }

    public function testBottom()
    {
        $box = new Box('test');

        $this->assertNull($box->getBottom());

        $box->setBottom(1.0, null);

        $this->assertSame(1.0, $box->getBottom());

        $box->setBottom(-1.0, null);

        $this->assertSame(-1.0, $box->getBottom());
    }


    public function testLeft()
    {
        $box = new Box('test');

        $this->assertNull($box->getLeft());

        $box->setLeft(1.0, null);

        $this->assertSame(1.0, $box->getLeft());

        $box->setLeft(-1.0, null);

        $this->assertSame(-1.0, $box->getLeft());
    }

    public function testRight()
    {
        $box = new Box('test');

        $this->assertNull($box->getRight());

        $box->setRight(1.0, null);

        $this->assertSame(1.0, $box->getRight());

        $box->setRight(-1.0, null);

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

        $box->setWidthPercentage(1.0, 'test2');

        $this->assertSame(1.0, $box->getWidthPercentage());

        $this->expectException(BoxArgumentException::class);

        $box->setWidthPercentage(-1.0, 'test2');
    }

    public function testHeightPercentage()
    {
        $box = new Box('test');

        $this->assertNull($box->getHeightPercentage());

        $box->setHeightPercentage(1.0, 'test2');

        $this->assertSame(1.0, $box->getHeightPercentage());

        $this->expectException(BoxArgumentException::class);

        $box->setHeightPercentage(-1.0, 'test2');
    }

    public function testDependencies()
    {
        $box = new Box('test');

        foreach (['Right', 'Left', 'Top', 'Bottom', 'WidthPercentage', 'HeightPercentage'] as $dim) {
            $box->{'set' . $dim}(1.0, $dim);
        }
        unset($dim);

        $deps = $box->getDependencies();

        \sort($deps, SORT_ASC);

        $this->assertSame(['Bottom', 'HeightPercentage', 'Left', 'Right', 'Top', 'WidthPercentage'], $deps);
    }

    public function testAbsoluteIsAlwaysResolved()
    {
        $box = new Box('test');

        $this->assertTrue($box->isResolved());

        foreach (['Right', 'Left', 'Top', 'Bottom', 'Width', 'Height'] as $dim) {
            $box = new Box('test');

            $box->{'set' . $dim}(1.0, null);

            $this->assertTrue($box->isResolved());
        }
        unset($dim);

    }

    public function testRelativeIsNotResolved()
    {
        foreach (['Right', 'Left', 'Top', 'Bottom', 'WidthPercentage', 'HeightPercentage'] as $dim) {
            $box = new Box('test');

            $box->{'set' . $dim}(1.0, 'test2');

            $this->assertFalse($box->isResolved());
        }
        unset($dim);

    }

    public function testBasicResolution()
    {
        $box1 = new Box('test');
        $box2 = new Box('test2');

        $box1->setRight(1.0, 'test2');

        $box2->setRight(1.0, null);

        $this->assertTrue($box2->isResolved());
        $this->assertFalse($box1->isResolved());

        $box1->resolve($box2);
        $this->assertTrue($box1->isResolved());
    }

    public function testDoubleResolution()
    {
        $box1 = new Box('test');
        $box2 = new Box('test2');

        $box1->setRight(1.0, 'test2');

        $box2->setRight(1.0, null);

        $box1->resolve($box2);
        $this->assertTrue($box1->isResolved());

        // Should not throw an exception
        $box1->resolve($box2);
        $this->assertTrue($box1->isResolved());
    }

    public function testResolutionCycleCheckSelf()
    {
        $box1 = new Box('test1');
        $box2 = new Box('test2');

        $box1->setWidthPercentage(100.0, 'test2');
        $box2->setWidthPercentage(100.0, 'test1');

        $this->expectException(ConstraintException::class);

        $box1->resolve($box1);
    }

    public function testResolutionCycleCheckSingle()
    {
        $box1 = new Box('test1');
        $box2 = new Box('test2');

        $box1->setWidthPercentage(100.0, 'test2');
        $box2->setWidthPercentage(100.0, 'test1');

        $this->expectException(ConstraintException::class);

        $box1->resolve($box2);
    }

    public function positionList()
    {
        return [['right'], ['left'], ['top'], ['bottom']];
    }

    public function dimensionList()
    {
        return [['width'], ['height']];
    }

    /**
     * @dataProvider positionList
     * @param string $dim
     */
    public function testRelativeToSelfInvalid1(string $dim)
    {
        $box1 = new Box('test');

        $this->expectException(ConstraintException::class);

        $box1->{'set' . \ucfirst($dim)}(1.0, 'test');
    }

    /**
     * @dataProvider dimensionList
     * @param string $dim
     */
    public function testRelativeToSelfInvalid2(string $dim)
    {
        $box1 = new Box('test');

        $this->expectException(ConstraintException::class);

        $box1->{'set' . \ucfirst($dim) . 'Percentage'}(100.0, 'test');
    }

    /**
     * @dataProvider positionList
     * @param string $dim
     */
    public function testRelativeNull1(string $dim)
    {
        $box1 = new Box('test');

        $this->expectException(ConstraintException::class);

        $box1->{'set' . \ucfirst($dim)}(null, 'test2');
    }

    /**
     * @dataProvider dimensionList
     * @param string $dim
     */
    public function testRelativeNull2(string $dim)
    {
        $box1 = new Box('test');

        $this->expectException(ConstraintException::class);

        $box1->{'set' . \ucfirst($dim) . 'Percentage'}(null, 'test2');
    }

    /**
     * @dataProvider readCsvDirectory
     * @param string $dataFile
     */
    public function testRandomFiles(string $dataFile)
    {
        $this->doResolutionTest($dataFile);
    }

    public function readCsvDirectory()
    {
        $dir = \scandir(self::DATA_FILE_PATH);

        if ($dir === false) {
            $this->markTestIncomplete('Could not read data dir.');
        } else {
            $dir = \array_filter($dir, fn(string $file) => \substr($file, -4) === '.csv' || \substr($file, -7) === '.csv.gz');

            foreach ($dir as $file) {
                yield $file => [self::DATA_FILE_PATH . \DIRECTORY_SEPARATOR . $file];
            }
            unset($file);
        }
    }

    private function doResolutionTest(string $dataFile): void
    {
        $boxData = $this->loadBoxData($dataFile);
        $resolvedBoxData = [];

        while (\count($boxData) > \count($resolvedBoxData)) {
            foreach ($boxData as $boxId => $boxDatum) {
                /** @var Box $box */
                $box = $boxDatum['box'];

                if (!$box->isValid()) {
                    $this->markTestIncomplete();
                }

                foreach ($box->getDependencies() as $dependency) {
                    if (!isset($boxData[$dependency]) && !isset($resolvedBoxData[$dependency])) {
                        $this->markTestIncomplete();
                    }

                    /** @var Box $box2 */
                    $box2 = $boxData[$dependency]['box'] ?? $resolvedBoxData[$dependency]['box'];

                    $box->resolve($box2);
                }
                unset($dependency);

                if ($box->isResolved()) {
                    $resolvedBoxData[$boxId] = $boxDatum;
                    unset($boxData[$boxId]);
                }
            }
            unset($boxDatum, $boxId, $box);
        }

        foreach ($resolvedBoxData as $boxDatum) {
            /** @var Box $box */
            $box = $boxDatum['box'];

            $this->assertTrue($box->isValid());

            foreach (['Right', 'Left', 'Top', 'Bottom', 'Width', 'Height'] as $dim) {
                $this->assertSame($boxDatum['finals'][$dim], $box->{'get' . $dim}());
            }
            unset($dim);
        }
        unset($boxDatum, $box);
    }

    /**
     * @param $dataFile
     * @return array
     */
    private function loadBoxData(string $dataFile): array
    {
        try {
            $fh = new DataFile($dataFile);
        } catch (\RuntimeException $ex) {
            $this->markTestSkipped($ex->getMessage());
        }

        /** @noinspection PhpUndefinedVariableInspection */
        $header = $fh->getParsedLine() ?: [];

        if (\array_diff(['finalLeft', 'finalRight', 'finalTop', 'finalBottom', 'finalHeight', 'finalWidth'], $header)) {
            $this->markTestSkipped(\sprintf('Skipping data file [%s]; missing final fields.', $dataFile));
        } elseif (!\in_array('id', $header, true)) {
            $this->markTestSkipped(\sprintf('Skipping data file [%s]; missing ID field.', $dataFile));
        }

        $boxData = [];
        while ($line = $fh->getParsedLine()) {
            $line = \array_combine($header, $line);

            $box = new Box($line['id']);
            $finals = [];

            foreach (['Right', 'Left', 'Top', 'Bottom'] as $dim) {
                $ldim = \strtolower($dim);
                $ldimr = $ldim . 'Relative';

                if (isset($line[$ldim]) && $line[$ldim] !== '') {
                    if (isset($line[$ldimr]) && $line[$ldimr] !== '') {
                        $relDim = (string)$line[$ldimr];
                    } else {
                        $relDim = null;
                    }

                    $box->{'set' . $dim}((float)$line[$ldim], $relDim);
                }

                $finals[$dim] = (float)$line['final' . $dim];
            }
            unset($dim);

            foreach (['Width', 'Height'] as $dim) {
                $ldim = \strtolower($dim);
                $ldimr = $ldim . 'Relative';
                $ldimp = $ldim . 'Percentage';

                if (isset($line[$ldim]) && $line[$ldim] !== '') {
                    $box->{'set' . $dim}((float)$line[$ldim]);
                } elseif (isset($line[$ldimr], $line[$ldimp]) && $line[$ldimr] !== '' && $line[$ldimp] !== '') {
                    $box->{'set' . $dim . 'Percentage'}((float)$line[$ldimp], (string)$line[$ldimr]);
                }

                $finals[$dim] = (float)$line['final' . $dim];
            }
            unset($dim);

            $boxData[$box->getId()] = ['box' => $box, 'finals' => $finals];
        }
        unset($line, $box, $finals, $header);

        return $boxData;
    }


}
