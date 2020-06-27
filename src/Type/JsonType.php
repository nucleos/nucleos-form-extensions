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

final class JsonType extends AbstractType implements DataTransformerInterface
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder->addModelTransformer($this);
    }

    public function transform($json): string
    {
        if (null === $json) {
            return '';
        }

        if (!\is_array($json)) {
            $json = json_decode($json, true);
        }

        $text = '';
        if (\is_array($json)) {
            foreach ($json as $key => $value) {
                $text .= trim($key).': '.trim($value)."\r\n";
            }
        }

        return $text;
    }

    public function reverseTransform($text): array
    {
        if (null === $text) {
            return [];
        }

        $json = json_decode($text, true);

        if (false !== $json && \is_string($text)) {
            $json = self::transformTextToArray($text);
        }

        return $json;
    }

    public function getParent()
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
