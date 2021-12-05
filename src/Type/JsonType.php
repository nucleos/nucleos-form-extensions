<?php

declare(strict_types=1);

/*
 * (c) Christian Gripp <mail@core23.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Nucleos\Form\Type;

use JsonException;
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
            try {
                $value = json_decode($value, true, 512, JSON_THROW_ON_ERROR);
            } catch (JsonException) {
            }
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

        try {
            $json = json_decode($value, true, 512, JSON_THROW_ON_ERROR);
        } catch (JsonException) {
            return [];
        }

        if (\is_string($value)) {
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
