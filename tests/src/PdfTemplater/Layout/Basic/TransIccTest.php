<?php

declare(strict_types=1);

namespace PdfTemplater\Layout\Basic;

use PdfTemplater\Layout\ColorConverterRuntimeException;
use PHPUnit\Framework\TestCase;

class TransIccTest extends TestCase
{
    private const DATA_FILE_PATH =
        __DIR__ . \DIRECTORY_SEPARATOR . '..' . \DIRECTORY_SEPARATOR . '..' . \DIRECTORY_SEPARATOR . '..' . \DIRECTORY_SEPARATOR . '..' . \DIRECTORY_SEPARATOR .
        'data' . \DIRECTORY_SEPARATOR . 'color_data' . \DIRECTORY_SEPARATOR . 'profiles';

    private const RGB_PROFILE = self::DATA_FILE_PATH . \DIRECTORY_SEPARATOR . 'AdobeRGB1998.icc';
    private const RGB_PROFILE_2 = self::DATA_FILE_PATH . \DIRECTORY_SEPARATOR . 'AppleRGB.icc';
    private const CMYK_PROFILE = self::DATA_FILE_PATH . \DIRECTORY_SEPARATOR . 'USWebCoatedSWOP.icc';
    private const CMYK_PROFILE_2 = self::DATA_FILE_PATH . \DIRECTORY_SEPARATOR . 'USWebUncoated.icc';

    private const DELTA = 0.01;

    private ?bool $executableFound = null;

    private function checkExecutable(): void
    {
        if ($this->executableFound === null) {
            $exitCode = null;
            $output   = null;

            if (\PHP_OS_FAMILY === 'Windows') {
                \exec('where /Q transicc', $output, $exitCode);
            } else {
                \exec('which transicc', $output, $exitCode);
            }

            $this->executableFound = ($exitCode === 0);
        }

        if (!$this->executableFound) {
            $this->markTestSkipped('Cannot find transicc in PATH.');
        }
    }

    private function createNoExecutableInstance(): TransIcc
    {
        return new class(self::RGB_PROFILE, self::CMYK_PROFILE) extends TransIcc {
            protected const CMD_NAME = 'transicc_missing';
        };
    }

    public function setUp(): void
    {
        if (
            !\is_readable(self::RGB_PROFILE) || !\is_file(self::RGB_PROFILE) ||
            !\is_readable(self::RGB_PROFILE_2) || !\is_file(self::RGB_PROFILE_2) ||
            !\is_readable(self::CMYK_PROFILE) || !\is_file(self::CMYK_PROFILE) ||
            !\is_readable(self::CMYK_PROFILE_2) || !\is_file(self::CMYK_PROFILE_2)
        ) {
            $this->markTestSkipped('Cannot load ICC profiles.');
        }
    }

    public function testConstruct()
    {
        $test = new TransIcc(self::RGB_PROFILE, self::CMYK_PROFILE);

        $this->assertEquals(
            ['rgb' => \realpath(self::RGB_PROFILE), 'cmyk' => \realpath(self::CMYK_PROFILE)],
            \array_map('\realpath', $test->getColorProfiles())
        );
    }

    public function testConstructInvalid()
    {
        $this->expectException(ColorConverterRuntimeException::class);

        new TransIcc(self::DATA_FILE_PATH . 'bad_file.icc', self::DATA_FILE_PATH . 'bad_file_2.icc');
    }

    public function testIsEnabled()
    {
        $this->checkExecutable();

        $test = new TransIcc(self::RGB_PROFILE, self::CMYK_PROFILE);

        $this->assertTrue($test->isEnabled());
    }

    public function testIsEnabledMissing()
    {
        $test = $this->createNoExecutableInstance();

        $this->assertNull($test->isEnabled());

        $test->setEnabled(true);

        $this->assertNull($test->isEnabled());

        $test->setEnabled(false);

        $this->assertNull($test->isEnabled());
    }

    public function testCmykToRgb()
    {
        $this->checkExecutable();

        $rgb  = [0.23, 0.68, 0.70];
        $cmyk = [0.91, 0, 0.36, 0];

        $test = new TransIcc(self::RGB_PROFILE, self::CMYK_PROFILE);

        for ($i = 0; $i < 2; ++$i) {
            try {
                /** @noinspection PhpUnhandledExceptionInspection */
                [$r, $g, $b] = $test->cmykToRgb(...$cmyk);
            } catch (ColorConverterRuntimeException $ex) {
                $this->markTestSkipped('Runtime exception during conversion.');

                return; // To satisfy static analyzers
            }

            $this->assertEqualsWithDelta($rgb[0], $r, self::DELTA);
            $this->assertEqualsWithDelta($rgb[1], $g, self::DELTA);
            $this->assertEqualsWithDelta($rgb[2], $b, self::DELTA);
        }
    }

    public function testCmykToRgbMissing()
    {
        $cmyk = [0.91, 0, 0.36, 0];

        $test = $this->createNoExecutableInstance();

        $this->expectException(ColorConverterRuntimeException::class);

        /** @noinspection PhpUnhandledExceptionInspection */
        $test->cmykToRgb(...$cmyk);
    }

    public function testSetColorProfiles()
    {
        $test = new TransIcc(self::RGB_PROFILE, self::CMYK_PROFILE);

        $this->assertEquals(
            ['rgb' => \realpath(self::RGB_PROFILE), 'cmyk' => \realpath(self::CMYK_PROFILE)],
            \array_map('\realpath', $test->getColorProfiles())
        );

        $test->setColorProfiles(self::RGB_PROFILE_2, self::CMYK_PROFILE_2);

        $this->assertEquals(
            ['rgb' => \realpath(self::RGB_PROFILE_2), 'cmyk' => \realpath(self::CMYK_PROFILE_2)],
            \array_map('\realpath', $test->getColorProfiles())
        );
    }

    public function testSetColorProfilesInvalid1()
    {
        $test = new TransIcc(self::RGB_PROFILE, self::CMYK_PROFILE);

        $this->expectException(ColorConverterRuntimeException::class);

        $test->setColorProfiles(self::DATA_FILE_PATH . 'bad_file.icc', self::DATA_FILE_PATH . 'bad_file_2.icc');
    }

    public function testSetColorProfilesInvalid2()
    {
        $test = new TransIcc(self::RGB_PROFILE, self::CMYK_PROFILE);

        $this->expectException(ColorConverterRuntimeException::class);

        $test->setColorProfiles(self::RGB_PROFILE, self::DATA_FILE_PATH . 'bad_file_2.icc');
    }

    public function testSetColorProfilesInvalid3()
    {
        $test = new TransIcc(self::RGB_PROFILE, self::CMYK_PROFILE);

        $this->expectException(ColorConverterRuntimeException::class);

        $test->setColorProfiles(self::DATA_FILE_PATH . 'bad_file.icc', self::CMYK_PROFILE);
    }

    public function testSetEnabled()
    {
        $this->checkExecutable();

        $test = new TransIcc(self::RGB_PROFILE, self::CMYK_PROFILE);

        $test->setEnabled(false);

        $this->assertFalse($test->isEnabled());
    }

    public function testGetColorProfiles()
    {
        $test = new TransIcc(self::RGB_PROFILE, self::CMYK_PROFILE);

        $this->assertEquals(
            ['rgb' => \realpath(self::RGB_PROFILE), 'cmyk' => \realpath(self::CMYK_PROFILE)],
            \array_map('\realpath', $test->getColorProfiles())
        );
    }

    public function testRgbToCmyk()
    {
        $this->checkExecutable();

        $rgb  = [0, 0.8, 0.8];
        $cmyk = [0.91, 0, 0.36, 0];

        $test = new TransIcc(self::RGB_PROFILE, self::CMYK_PROFILE);

        for ($i = 0; $i < 2; ++$i) {
            try {
                /** @noinspection PhpUnhandledExceptionInspection */
                [$c, $m, $y, $k] = $test->rgbToCmyk(...$rgb);
            } catch (ColorConverterRuntimeException $ex) {
                $this->markTestSkipped('Runtime exception during conversion.');

                return; // To satisfy static analyzers
            }

            $this->assertEqualsWithDelta($cmyk[0], $c, self::DELTA);
            $this->assertEqualsWithDelta($cmyk[1], $m, self::DELTA);
            $this->assertEqualsWithDelta($cmyk[2], $y, self::DELTA);
            $this->assertEqualsWithDelta($cmyk[3], $k, self::DELTA);
        }
    }

    public function testRgbToCmykMissing()
    {
        $rgb = [0, 0.8, 0.8];

        $test = $this->createNoExecutableInstance();

        $this->expectException(ColorConverterRuntimeException::class);

        /** @noinspection PhpUnhandledExceptionInspection */
        $test->rgbToCmyk(...$rgb);
    }
}
