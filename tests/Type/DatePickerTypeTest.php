<?php

/*
 * (c) Christian Gripp <mail@core23.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Core23\Form\Tests\Type;

use Core23\Form\Type\DatePickerType;
use IntlDateFormatter;
use Sonata\Form\Date\MomentFormatConverter;
use Sonata\Form\Type\DatePickerType as BaseDatePickerType;
use Symfony\Component\Translation\TranslatorInterface;

class DatePickerTypeTest extends BaseTypeTest
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

    public function testSubmitNullUsesDefaultEmptyData($emptyData = 'June 3, 2018', $expectedData = null): void
    {
        $builder = $this->factory->createBuilder($this->getTestedType(), null, [
            'format' => IntlDateFormatter::LONG,
        ]);

        if ($builder->getCompound()) {
            $emptyData = [];
            foreach ($builder as $field) {
                // empty children should map null (model data) in the compound view data
                $emptyData[$field->getName()] = null;
            }
        } else {
            // simple fields share the view and the model format, unless they use a transformer
            $expectedData = $emptyData;
        }

        $form = $builder->setEmptyData($emptyData)->getForm()->submit(null);

        static::assertSame($emptyData, $form->getViewData());
        static::assertSame('2018-06-03', $form->getNormData()->format('Y-m-d'));
        static::assertSame('2018-06-03', $form->getData()->format('Y-m-d'));
    }

    protected function getTestedType(): string
    {
        return DatePickerType::class;
    }

    protected function getTypes()
    {
        return [
            new BaseDatePickerType($this->formatConverter->reveal(), $this->translator->reveal()),
            new DatePickerType($this->formatConverter->reveal(), $this->translator->reveal()),
        ];
    }
}
