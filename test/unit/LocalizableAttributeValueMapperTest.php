<?php
declare(strict_types=1);
namespace SnowIO\Akeneo2Fredhopper\Test;

use PHPUnit\Framework\TestCase;
use SnowIO\Akeneo2DataModel\AttributeValueSet as AkeneoAttributeValueSet;
use SnowIO\Akeneo2Fredhopper\LocalizableAttributeValueMapper;
use SnowIO\FredhopperDataModel\AttributeValue as FredhopperAttributeValue;
use SnowIO\FredhopperDataModel\AttributeValueSet as FredhopperAttributeValueSet;

class LocalizableAttributeValueMapperTest extends TestCase
{

    public function testMap()
    {
        $expected = FredhopperAttributeValueSet::of([
            FredhopperAttributeValue::of('size_en_gb', 'Large'), //todo is this really the desired functionality
        ]);
        $actual = (LocalizableAttributeValueMapper::create())(AkeneoAttributeValueSet::fromJson('main', [
            'attribute_values' => [
                'size' => 'large',
                'price' => [
                    'gbp' => '30',
                    'eur' => '37.45',
                ],
                'weight' =>  '30'
            ],
            'localizations' => [
                'en_GB' => [
                    'attribute_values' => [
                        'size' => 'Large'
                    ]
                ]
            ]
        ]));

        self::assertTrue($expected->equals($actual));
    }

    public function testMapWithIdMapper()
    {
        $expected = FredhopperAttributeValueSet::of([
            FredhopperAttributeValue::of('test_size', 'Large'),
        ]);
        $actual = (LocalizableAttributeValueMapper::create()->withAttributeIdMapper(function (string $attributeCode, string $locale){
            return "test_". $attributeCode;
        }))(AkeneoAttributeValueSet::fromJson('main', [
            'attribute_values' => [
                'size' => 'large',
                'price' => [
                    'gbp' => '30',
                    'eur' => '37.45',
                ],
                'weight' =>  '30'
            ],
            'localizations' => [
                'en_GB' => [
                    'attribute_values' => [
                        'size' => 'Large'
                    ]
                ]
            ]
        ]));

        self::assertTrue($expected->equals($actual));
    }
}
