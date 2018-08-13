<?php
declare(strict_types=1);
namespace SnowIO\Akeneo2Fredhopper\Test;

use PHPUnit\Framework\TestCase;
use SnowIO\Akeneo2Fredhopper\AttributeCodeFilter;
use SnowIO\Akeneo2Fredhopper\AttributeMapperWithFilter;
use SnowIO\Akeneo2Fredhopper\AttributeValueMapperWithFilter;
use SnowIO\Akeneo2Fredhopper\SimpleAttributeValueMapper;
use SnowIO\Akeneo2Fredhopper\StandardAttributeMapper;
use SnowIO\Akeneo2DataModel\AttributeData as AkeneoAttributeData;
use SnowIO\Akeneo2DataModel\AttributeType as AkeneoAttributeType;
use SnowIO\FredhopperDataModel\AttributeDataSet as FredhopperAttributeDataSet;
use SnowIO\FredhopperDataModel\AttributeValue as FredhopperAttributeValue;
use SnowIO\FredhopperDataModel\AttributeValueSet as FredhopperAttributeValueSet;
use SnowIO\Akeneo2DataModel\AttributeValueSet as AkeneoAttributeValueSet;

class AttributeCodeFilterTest extends TestCase
{

    public function testAttributeFiltration()
    {
        $attributeCodeFilter = AttributeCodeFilter::of(function (string $code) {
            return strpos($code, 'vg_') === FALSE;
        });

        $attributeMapperWithFilter = AttributeMapperWithFilter::of(StandardAttributeMapper::create(),
            $attributeCodeFilter->getAttributeFilter());

        $akeneoAttributeData = AkeneoAttributeData::fromJson([
            'code' => 'vg_size',
            'type' => AkeneoAttributeType::SIMPLESELECT,
            'localizable' => false,
            'scopable' => true,
            'sort_order' => 34,
            'labels' => [
                'en_GB' => 'Size',
                'fr_FR' => 'Taille',
            ],
            'group' => 'general',
            '@timestamp' => 1508491122,
        ]);

        /** @var FredhopperAttributeDataSet $dataSet */
        $dataSet = $attributeMapperWithFilter($akeneoAttributeData);
        self::assertEquals(0, $dataSet->count());

        $akeneoAttributeData = AkeneoAttributeData::fromJson([
            'code' => 'color',
            'type' => AkeneoAttributeType::SIMPLESELECT,
            'localizable' => false,
            'scopable' => true,
            'sort_order' => 34,
            'labels' => [
                'en_GB' => 'Color',
            ],
            'group' => 'general',
            '@timestamp' => 1508491122,
        ]);

        /** @var FredhopperAttributeDataSet $dataSet */
        $dataSet = $attributeMapperWithFilter($akeneoAttributeData);
        self::assertEquals(1, $dataSet->count());
    }

    public function testAttributeValueFiltration()
    {
        $attributeCodeFilter = AttributeCodeFilter::of(function (string $code) {
            return strpos($code, 'vg_') === FALSE;
        });

        $attributeValueMapperWithFilter = AttributeValueMapperWithFilter::of(SimpleAttributeValueMapper::create(),
            $attributeCodeFilter->getAttributeValueFilter());

        $akeneoAttributeValueData = AkeneoAttributeValueSet::fromJson('main', [
            'attribute_values' => [
                'vg_size' => 'large',
                'size' => 'large',
                'weight' =>  '30'
            ],
        ]);
        /** @var FredhopperAttributeValueSet $dataSet */
        $dataSet = $attributeValueMapperWithFilter($akeneoAttributeValueData);
        self::assertTrue(FredhopperAttributeValueSet::of([
            FredhopperAttributeValue::of('weight', '30'),
            FredhopperAttributeValue::of('size', 'large'),
        ])->equals($dataSet));

    }

}