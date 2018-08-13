<?php
declare(strict_types=1);
namespace SnowIO\Akeneo2Fredhopper\Test;

use PHPUnit\Framework\TestCase;
use SnowIO\Akeneo2DataModel\AttributeValueSet as AkeneoAttributeValueSet;
use SnowIO\Akeneo2Fredhopper\CompositeAttributeValueMapper;
use SnowIO\Akeneo2Fredhopper\SimpleAttributeValueMapper;
use SnowIO\FredhopperDataModel\AttributeValue as FredhopperAttributeValue;
use SnowIO\FredhopperDataModel\AttributeValueSet as FredhopperAttributeValueSet;

class CompositeAttributeValueMapperTest extends TestCase
{

    public function testMap()
    {
        $mapper = CompositeAttributeValueMapper::create()
            ->with(SimpleAttributeValueMapper::create());

        $attributeValueSet = AkeneoAttributeValueSet::fromJson('main', [
            'attribute_values' => [
                'size' => 'Large',
                'price' => [
                    'gbp' => '30',
                    'eur' => '37.45',
                ],
                'weight' => '30',
            ],
        ]);

        $expected = FredhopperAttributeValueSet::of([
            FredhopperAttributeValue::of('size', 'Large'),
            FredhopperAttributeValue::of('weight', '30'),
        ]);

        $actual= $mapper($attributeValueSet);
        self::assertTrue($expected->equals($actual));
    }
}
