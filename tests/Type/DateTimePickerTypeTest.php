<?php

/*
 * (c) Christian Gripp <mail@core23.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Core23\Form\Tests\Type;

use Core23\Form\Type\DateTimePickerType;
use Sonata\Form\Date\MomentFormatConverter;
use Sonata\Form\Type\DateTimePickerType as BaseDateTimePickerType;
use Symfony\Component\Translation\TranslatorInterface;

class DateTimePickerTypeTest extends BaseTypeTest
{
    private $translator;

    private $formatConverter;

    protected function setUp(): void
    {
        $this->translator      = $this->prophesize(TranslatorInterface::class);
        $this->translator->getLocale()
            ->willReturn('EN')
        ;
        $this->formatConverter = $this->prophesize(MomentFormatConverter::class);

        parent::setUp();
    }

    public function testSubmitNull($expected = null, $norm = null, $view = ''): void
    {
        parent::testSubmitNull($expected, $norm, $view);
    }

    public function testSubmitNullUsesDefaultEmptyData($emptyData = null, $expectedData = null): void
    {
        parent::testSubmitNullUsesDefaultEmptyData($emptyData, $expectedData);
    }

    protected function getTestedType(): string
    {
        return DateTimePickerType::class;
    }

    protected function getTypes()
    {
        return [
            new DateTimePickerType($this->formatConverter->reveal(), $this->translator->reveal()),
            new BaseDateTimePickerType($this->formatConverter->reveal(), $this->translator->reveal()),
        ];
    }
}
