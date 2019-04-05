<?php

/*
 * (c) Christian Gripp <mail@core23.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Core23\Form\Tests\Type;

use Core23\Form\Type\GenderType;
use Symfony\Component\Form\ChoiceList\View\ChoiceView;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class GenderTypeTest extends BaseTypeTest
{
    public function testGendersAreSelectable(): void
    {
        $choices = $this->factory->create($this->getTestedType())
            ->createView()->vars['choices'];

        $this->assertContains(new ChoiceView('m', 'm', 'gender.male'), $choices, '', false, false);
        $this->assertContains(new ChoiceView('f', 'f', 'gender.female'), $choices, '', false, false);
    }

    public function testUnknownGenderIsNotIncluded(): void
    {
        $choices = $this->factory->create($this->getTestedType(), 'gender')
            ->createView()->vars['choices'];

        $genderCodes = [];

        foreach ($choices as $choice) {
            $genderCodes[] = $choice->value;
        }

        $this->assertNotContains('t', $genderCodes);
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
    public function testSubmitNullUsesDefaultEmptyData($emptyData = 'f', $expectedData = 'f'): void
    {
        parent::testSubmitNullUsesDefaultEmptyData($emptyData, $expectedData);
    }

    public function testGetParent(): void
    {
        $type = new GenderType();

        $this->assertSame(ChoiceType::class, $type->getParent());
    }

    public function testGetBlockPrefix(): void
    {
        $type = new GenderType();

        $this->assertSame('gender', $type->getBlockPrefix());
    }

    protected function getTestedType(): string
    {
        return GenderType::class;
    }
}
