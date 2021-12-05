<?php

declare(strict_types=1);

/*
 * (c) Christian Gripp <mail@core23.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Nucleos\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;

/**
 * @phpstan-implements DataTransformerInterface<mixed[], string>
 */
final class JsonType extends AbstractType implements DataTransformerInterface
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder->addModelTransformer($this);
    }

    public function transform($value): mixed
    {
        if (null === $value) {
            return null;
        }

        if (!\is_array($value)) {
            $value = json_decode($value, true);
        }

        $text = '';
        if (\is_array($value)) {
            foreach ($value as $key => $val) {
                $text .= trim($key).': '.trim($val)."\r\n";
            }
        }

        return $text;
    }

    public function reverseTransform($value): mixed
    {
        if (null === $value) {
            return [];
        }

        $json = json_decode($value, true);

        if (false !== $json && \is_string($value)) {
            $json = self::transformTextToArray($value);
        }

        return $json;
    }

    public function getParent(): ?string
    {
        return TextareaType::class;
    }

    private static function transformTextToArray(string $text): array
    {
        $json = [];
        foreach (explode("\n", $text) as $keyValue) {
            $parts = explode(':', $keyValue);
            if (2 === \count($parts)) {
                $key   = trim($parts[0]);
                $value = trim($parts[1]);

                if ('' !== $key || '' !== $value) {
                    $json[$key] = $value;
                }
            }
        }

        return $json;
    }
}
