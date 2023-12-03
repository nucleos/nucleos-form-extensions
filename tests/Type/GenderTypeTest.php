<?php

declare(strict_types=1);

/*
 * (c) Christian Gripp <mail@core23.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Nucleos\Form\Tests\Type;

use Nucleos\Form\Type\GenderType;
use Symfony\Component\Form\ChoiceList\View\ChoiceView;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

final class GenderTypeTest extends BaseTypeTestCase
{
    public function testGendersAreSelectable(): void
    {
        $choices = $this->factory->create($this->getTestedType())
            ->createView()->vars['choices']
        ;

        self::assertContainsEquals(new ChoiceView('m', 'm', 'gender.male'), $choices);
        self::assertContainsEquals(new ChoiceView('f', 'f', 'gender.female'), $choices);
        self::assertContainsEquals(new ChoiceView('d', 'd', 'gender.non_binary'), $choices);
    }

    public function testUnknownGenderIsNotIncluded(): void
    {
        $choices = $this->factory->create($this->getTestedType(), 'gender')
            ->createView()->vars['choices']
        ;

        $genderCodes = [];

        foreach ($choices as $choice) {
            $genderCodes[] = $choice->value;
        }

        self::assertNotContains('t', $genderCodes);
    }

    public function testSubmitNull($expected = null, $norm = null, $view = null): void
    {
        parent::testSubmitNull($expected, $norm, '');
    }

    public function testSubmitNullUsesDefaultEmptyData($emptyData = 'f', $expectedData = 'f'): void
    {
        parent::testSubmitNullUsesDefaultEmptyData($emptyData, $expectedData);
    }

    public function testGetParent(): void
    {
        $type = new GenderType();

        self::assertSame(ChoiceType::class, $type->getParent());
    }

    public function testGetBlockPrefix(): void
    {
        $type = new GenderType();

        self::assertSame('gender', $type->getBlockPrefix());
    }

    protected function getTestedType(): string
    {
        return GenderType::class;
    }
}
