<?php
declare(strict_types=1);
namespace SnowIO\Akeneo2Fredhopper\Test;

use PHPUnit\Framework\TestCase;
use SnowIO\Akeneo2DataModel\CategoryData as AkeneoCategoryData;
use SnowIO\Akeneo2DataModel\CategoryPath;
use SnowIO\Akeneo2DataModel\InternationalizedString as AkeneoInternationalizedString;
use SnowIO\Akeneo2DataModel\LocalizedString;
use SnowIO\Akeneo2Fredhopper\CategoryMapper;
use SnowIO\FredhopperDataModel\CategoryData as FredhopperCategoryData;
use SnowIO\FredhopperDataModel\CategoryData;
use SnowIO\FredhopperDataModel\CategoryDataSet;
use SnowIO\FredhopperDataModel\InternationalizedString as FredhopperInternationalizedString;

class CategoryMapperTest extends TestCase
{

    public function testMap()
    {
        $mapper = CategoryMapper::create();
        $categoryData = AkeneoCategoryData::of(CategoryPath::of(array('clothes', 't_shirts')))
            ->withLabel(LocalizedString::of('T-Shirts', 'en_GB'))
            ->withLabel(LocalizedString::of('Tee-shirt', 'fr_FR'));

        $expected = CategoryDataSet::of([FredhopperCategoryData::of(
            'tshirts',
            FredhopperInternationalizedString::create()
                ->withValue('T-Shirts', 'en_GB')
                ->withValue('Tee-shirt', 'fr_FR')
        )->withParent('clothes')]);

        $actual = $mapper($categoryData);
        self::assertTrue($expected->equals($actual));

        $categoryData = AkeneoCategoryData::of(CategoryPath::of(['t_shirts']))
            ->withLabel(LocalizedString::of('T-Shirts', 'en_GB'))
            ->withLabel(LocalizedString::of('Tee-shirt', 'fr_FR'));

        $expected = CategoryDataSet::of([FredhopperCategoryData::of(
            'tshirts',
            FredhopperInternationalizedString::create()
                ->withValue('T-Shirts', 'en_GB')
                ->withValue('Tee-shirt', 'fr_FR')
        )]);

        $actual = $mapper($categoryData);
        self::assertTrue($expected->equals($actual));

    }

    public function testMapWithCategoryIdMapper()
    {
        $mapper = CategoryMapper::create()->withCategoryIdMapper(function (string $categoryCode) {
            return CategoryData::sanitizeId("foo_$categoryCode");
        });
        $categoryData = AkeneoCategoryData::of(CategoryPath::of(['clothes', 't_shirts']))
            ->withLabel(LocalizedString::of('T-Shirts', 'en_GB'))
            ->withLabel(LocalizedString::of('Tee-shirt', 'fr_FR'));

        $expected = CategoryDataSet::of([FredhopperCategoryData::of(
            'footshirts',
            FredhopperInternationalizedString::create()
                ->withValue('T-Shirts', 'en_GB')
                ->withValue('Tee-shirt', 'fr_FR')
        )->withParent('fooclothes')]);

        $actual = $mapper($categoryData);
        self::assertTrue($expected->equals($actual));
    }

    public function testMapWithNameMapper()
    {
        $mapper = CategoryMapper::create()->withNameMapper(function (AkeneoInternationalizedString $labels) {
            return FredhopperInternationalizedString::create()
                ->withValue($labels->getValue('en_GB'), 'en_GB')
                ->withValue($labels->getValue('en_FR'), 'fr_FR');
        });
        $categoryData = AkeneoCategoryData::of(CategoryPath::of(['clothes', 't_shirts']))
            ->withLabel(LocalizedString::of('T-Shirts', 'en_GB'))
            ->withLabel(LocalizedString::of('Tee-shirt', 'en_FR'));

        $expected = CategoryDataSet::of([FredhopperCategoryData::of(
            'tshirts',
            FredhopperInternationalizedString::create()
                ->withValue('T-Shirts', 'en_GB')
                ->withValue('Tee-shirt', 'fr_FR')
        )->withParent('clothes')]);

        $actual = $mapper($categoryData);
        self::assertTrue($expected->equals($actual));
    }
}
