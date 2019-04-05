<?php

/*
 * (c) Christian Gripp <mail@core23.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Core23\Form\Tests\Type;

use Core23\Form\Type\DACHCountryType;
use Symfony\Component\Form\ChoiceList\View\ChoiceView;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class DACHCountryTypeTest extends BaseTypeTest
{
    public function testCountriesAreSelectable(): void
    {
        $choices = $this->factory->create($this->getTestedType())
            ->createView()->vars['choices'];

        $this->assertContains(new ChoiceView('DE', 'DE', 'form.choice_de'), $choices, '', false, false);
        $this->assertContains(new ChoiceView('AT', 'AT', 'form.choice_at'), $choices, '', false, false);
        $this->assertContains(new ChoiceView('CH', 'CH', 'form.choice_ch'), $choices, '', false, false);
    }

    public function testUnknownCountryIsNotIncluded(): void
    {
        $choices = $this->factory->create($this->getTestedType(), 'country')
            ->createView()->vars['choices'];

        $countryCodes = [];

        foreach ($choices as $choice) {
            $countryCodes[] = $choice->value;
        }

        $this->assertNotContains('ZZ', $countryCodes);
    }

    /**
     * {@inheritdoc}
     */
    public function testSubmitNull($expected = null, $norm = null, $view = null): void
    {
        parent::testSubmitNull($expected, $norm, '');
    }

    /**
     * {@inheritdoc}
     */
    public function testSubmitNullUsesDefaultEmptyData($emptyData = 'AT', $expectedData = 'AT'): void
    {
        parent::testSubmitNullUsesDefaultEmptyData($emptyData, $expectedData);
    }

    public function testGetParent(): void
    {
        $type = new DACHCountryType();

        $this->assertSame(ChoiceType::class, $type->getParent());
    }

    public function testGetBlockPrefix(): void
    {
        $type = new DACHCountryType();

        $this->assertSame('core23_country', $type->getBlockPrefix());
    }

    protected function getTestedType(): string
    {
        return DACHCountryType::class;
    }
}
