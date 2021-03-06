<?php
declare(strict_types=1);
namespace SnowIO\Akeneo2Fredhopper\Test;

use PHPUnit\Framework\TestCase;
use SnowIO\Akeneo2DataModel\AttributeValue as AkeneoAttributeValue;
use SnowIO\Akeneo2Fredhopper\AttributeValueMapperWithFilter;
use SnowIO\Akeneo2Fredhopper\SimpleAttributeValueMapper;
use SnowIO\FredhopperDataModel\AttributeValue as FredhopperAttributeValue;
use SnowIO\Akeneo2DataModel\AttributeValueSet as AkeneoAttributeValueSet;
use SnowIO\FredhopperDataModel\AttributeValueSet as FredhopperAttributeValueSet;

class AttributeValueMapperWithFilterTest extends TestCase
{

    public function testMapFilterWithSize()
    {
        $mapper = AttributeValueMapperWithFilter::of(
            SimpleAttributeValueMapper::create(),
            function (AkeneoAttributeValue $akeneoAttributeValue) {
                return $akeneoAttributeValue->getAttributeCode() === 'size';
            }
        );

        $akeneoAttributeValues = AkeneoAttributeValueSet::fromJson('main', [
            'attribute_values' => [
                'size' => 'large',
                'price' => [
                    'gbp' => '30',
                    'eur' => '37.45',
                ],
                'weight' =>  '30'
            ],
        ]);

        $expected = FredhopperAttributeValueSet::of([
            FredhopperAttributeValue::of('size', 'large'),
        ]);

        $actual = $mapper($akeneoAttributeValues);
        self::assertTrue($expected->equals($actual));
    }

    public function testMapFilterCanReturnEmptyDataSet()
    {
        $mapper = AttributeValueMapperWithFilter::of(
            SimpleAttributeValueMapper::create(),
            function (AkeneoAttributeValue $akeneoAttributeValue) {
                return $akeneoAttributeValue->getAttributeCode() === 'size';
            }
        );

        $akeneoAttributeValues = AkeneoAttributeValueSet::fromJson('main', [
            'attribute_values' => [
                'price' => [
                    'gbp' => '30',
                    'eur' => '37.45',
                ],
            ],
        ]);

        $expected = FredhopperAttributeValueSet::of([]);

        $actual = $mapper($akeneoAttributeValues);
        self::assertTrue($expected->equals($actual));
    }
}
