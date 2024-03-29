<?php

declare(strict_types=1);

/*
 * (c) Christian Gripp <mail@core23.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Nucleos\Form\Tests\Type;

use Nucleos\Form\Type\BatchTimeType;
use Symfony\Component\Form\Extension\Core\Type\FormType;

final class BatchTimeTypeTest extends BaseTypeTestCase
{
    public function testGetParent(): void
    {
        $type = new BatchTimeType();

        self::assertSame(FormType::class, $type->getParent());
    }

    public function testGetBlockPrefix(): void
    {
        $type = new BatchTimeType();

        self::assertSame('batch_time', $type->getBlockPrefix());
    }

    public function testSubmitNull($expected = null, $norm = null, $view = null): void
    {
        $form = $this->create(null, [
            'required' => false,
        ]);
        $form->submit(null);

        self::assertSame($expected, $form->getData());
        self::assertSame($norm, $form->getNormData());
        self::assertSame($view, $form->getViewData());
    }

    public function testSubmitNullUsesDefaultEmptyData($emptyData = null, $expectedData = null): void
    {
        self::markTestSkipped('emptyData is not supported');
    }

    protected function getTestedType(): string
    {
        return BatchTimeType::class;
    }
}
