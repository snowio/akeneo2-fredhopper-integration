<?php
declare(strict_types = 1);
namespace SnowIO\Akeneo2Fredhopper\Test;

use PHPUnit\Framework\TestCase;
use SnowIO\Akeneo2DataModel\AttributeData as AkeneoAttributeData;
use SnowIO\Akeneo2DataModel\AttributeType as AkeneoAttributeType;
use SnowIO\Akeneo2DataModel\AttributeValueSet as AkeneoAttributeValueSet;
use SnowIO\Akeneo2Fredhopper\AttributeValueMapperWithFilter;
use SnowIO\Akeneo2Fredhopper\SimpleAttributeValueMapper;
use SnowIO\FredhopperDataModel\AttributeDataSet;
use SnowIO\Akeneo2Fredhopper\AttributeBlacklist;
use SnowIO\Akeneo2Fredhopper\AttributeMapperWithFilter;
use SnowIO\Akeneo2Fredhopper\StandardAttributeMapper;
use SnowIO\FredhopperDataModel\AttributeValue as FredhopperAttributeValue;
use SnowIO\FredhopperDataModel\AttributeValueSet as FredhopperAttributeValueSet;

class AttributeBlackListTest extends TestCase
{
    public function testAttributeFiltration()
    {
        $attributeBlacklist = AttributeBlacklist::of(['size']);
        $attributeMapperWithFilter = AttributeMapperWithFilter::of(StandardAttributeMapper::create(),
            $attributeBlacklist->getAttributeFilter());

        $akeneoAttributeData = AkeneoAttributeData::fromJson([
            'code' => 'size',
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

        /** @var AttributeDataSet $dataSet */
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

        /** @var AttributeDataSet $actualOutput */
        $dataSet = $attributeMapperWithFilter($akeneoAttributeData);
        self::assertEquals(1, $dataSet->count());
    }

    public function testAttributeValueFiltration()
    {
        $attributeBlacklist = AttributeBlacklist::of(['size']);
        $attributeValueMapperWithFilter = AttributeValueMapperWithFilter::of(SimpleAttributeValueMapper::create(),
            $attributeBlacklist->getAttributeValueFilter());

        $akeneoAttributeValueData = AkeneoAttributeValueSet::fromJson('main', [
            'attribute_values' => [
                'size' => 'large',
                'weight' =>  '30'
            ],
        ]);
        /** @var FredhopperAttributeValueSet $dataSet */
        $dataSet = $attributeValueMapperWithFilter($akeneoAttributeValueData);
        self::assertTrue(FredhopperAttributeValueSet::of([
            FredhopperAttributeValue::of('weight', '30')
        ])->equals($dataSet));

    }

}