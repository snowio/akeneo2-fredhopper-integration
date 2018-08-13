<?php
namespace SnowIO\Akeneo2Fredhopper\Test\EventMapper;

use PHPUnit\Framework\TestCase;
use SnowIO\Akeneo2Fredhopper\AttributeOptionMapper;
use SnowIO\Akeneo2Fredhopper\CategoryMapper;
use SnowIO\Akeneo2Fredhopper\EventMapper\FredhopperConfiguration;
use SnowIO\Akeneo2Fredhopper\ProductToProductMapper;
use SnowIO\Akeneo2Fredhopper\StandardAttributeMapper;
use SnowIO\FredhopperDataModel\AttributeDataSet;

abstract class CommandEventMapperTest extends TestCase
{
    public function getFredhopperConfiguration() : FredhopperConfiguration
    {
        return new class extends FredhopperConfiguration
        {
            function customAttributeIsBlacklisted(string $attributeCode): bool
            {

            }

            public function getCategoryIdMapper(): callable
            {

            }

            function getInternationalizedStringMapper(): callable
            {

            }

            function getStaticAttributes(): AttributeDataSet
            {

            }

            function getPriceAttributes(): AttributeDataSet
            {

            }

            public function getCategoryMapper(): callable
            {
                return CategoryMapper::create();
            }

            public function getAttributeMapper(): callable
            {
                return StandardAttributeMapper::create();
            }

            public function getAttributeOptionMapper(): callable
            {
                return AttributeOptionMapper::create();
            }

            public function getProductMapper(): callable
            {
                return ProductToProductMapper::create();
            }
        };
    }

    public abstract function testSaveCommandMapper();
    public abstract function testDeleteCommandMapper();
}
