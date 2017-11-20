<?php

/*
 * (c) Christian Gripp <mail@core23.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Core23\FormExtensionsBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;

class JsonType extends AbstractType implements DataTransformerInterface
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->addModelTransformer($this);
    }

    /**
     * {@inheritdoc}
     */
    public function transform($json)
    {
        if (!is_array($json)) {
            $json = json_decode($json, true);
        }

        $text = '';
        if (is_array($json)) {
            foreach ($json as $key => $value) {
                $text .= trim($key).': '.trim($value)."\r\n";
            }
        }

        return $text;
    }

    /**
     * {@inheritdoc}
     */
    public function reverseTransform($text)
    {
        $json = json_decode($text, true);

        if (!$json) {
            $json = array();
            foreach (explode("\n", $text) as $keyValue) {
                $parts = explode(':', $keyValue);
                if (2 === count($parts)) {
                    $key   = trim($parts[0]);
                    $value = trim($parts[1]);

                    if ($key || $value) {
                        $json[$key] = $value;
                    }
                }
            }
        }

        return $json;
    }

    /**
     * {@inheritdoc}
     */
    public function getParent()
    {
        return TextareaType::class;
    }
}
