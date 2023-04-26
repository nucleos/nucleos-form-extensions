<?php

declare(strict_types=1);

/*
 * (c) Christian Gripp <mail@core23.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Nucleos\Form\Tests\Type;

use Nucleos\Form\Type\DACHCountryType;
use Symfony\Component\Form\ChoiceList\View\ChoiceView;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

final class DACHCountryTypeTest extends BaseTypeTestCase
{
    public function testCountriesAreSelectable(): void
    {
        $choices = $this->factory->create($this->getTestedType())
            ->createView()->vars['choices'];

        static::assertContainsEquals(new ChoiceView('DE', 'DE', 'form.choice_de'), $choices);
        static::assertContainsEquals(new ChoiceView('AT', 'AT', 'form.choice_at'), $choices);
        static::assertContainsEquals(new ChoiceView('CH', 'CH', 'form.choice_ch'), $choices);
    }

    public function testUnknownCountryIsNotIncluded(): void
    {
        $choices = $this->factory->create($this->getTestedType(), 'country')
            ->createView()->vars['choices'];

        $countryCodes = [];

        foreach ($choices as $choice) {
            $countryCodes[] = $choice->value;
        }

        static::assertNotContains('ZZ', $countryCodes);
    }

    public function testSubmitNull($expected = null, $norm = null, $view = null): void
    {
        parent::testSubmitNull($expected, $norm, '');
    }

    public function testSubmitNullUsesDefaultEmptyData($emptyData = 'AT', $expectedData = 'AT'): void
    {
        parent::testSubmitNullUsesDefaultEmptyData($emptyData, $expectedData);
    }

    public function testGetParent(): void
    {
        $type = new DACHCountryType();

        static::assertSame(ChoiceType::class, $type->getParent());
    }

    public function testGetBlockPrefix(): void
    {
        $type = new DACHCountryType();

        static::assertSame('nucleos_country', $type->getBlockPrefix());
    }

    protected function getTestedType(): string
    {
        return DACHCountryType::class;
    }
}
