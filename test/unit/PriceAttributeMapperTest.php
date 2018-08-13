<?php
declare(strict_types=1);
namespace SnowIO\Akeneo2Fredhopper\Test;

use PHPUnit\Framework\TestCase;
use SnowIO\Akeneo2DataModel\AttributeData as AkeneoAttribute;
use SnowIO\Akeneo2DataModel\AttributeType as AkeneoAttributeType;
use SnowIO\Akeneo2Fredhopper\PriceAttributeMapper;
use SnowIO\FredhopperDataModel\AttributeData as FredhopperAttribute;
use SnowIO\FredhopperDataModel\AttributeDataSet;
use SnowIO\FredhopperDataModel\AttributeType as FredhopperAttributeType;
use SnowIO\FredhopperDataModel\InternationalizedString as FredhopperInternationalizedString;
use SnowIO\Akeneo2DataModel\InternationalizedString as AkeneoInternationalizedString;

class PriceAttributeMapperTest extends TestCase
{

    public function testWithPriceType()
    {
        $mapper = PriceAttributeMapper::of([
            'gbp',
            'eur',
        ]);
        $actual = $mapper(AkeneoAttribute::fromJson([
            'code' => 'price',
            'type' => AkeneoAttributeType::PRICE_COLLECTION,
            'localizable' => false,
            'scopable' => false,
            'sort_order' => 34,
            'labels' => [
                'en_GB' => 'Price',
            ],
            'group' => 'general',
            '@timestamp' => 1508491122,
        ]));

        $expected = AttributeDataSet::of([
            FredhopperAttribute::of(
                'price_gbp',
                FredhopperAttributeType::FLOAT,
                FredhopperInternationalizedString::create()->withValue('Price', 'en_GB')
            ),
            FredhopperAttribute::of(
                'price_eur',
                FredhopperAttributeType::FLOAT,
                FredhopperInternationalizedString::create()->withValue('Price', 'en_GB')
            ),
        ]);

        self::assertTrue($expected->equals($actual));
    }

    public function testWithNameMapper()
    {
        $mapper = PriceAttributeMapper::of([
            'gbp',
            'eur',
        ])->withNameMapper(function (AkeneoInternationalizedString $akeneoInternationalisedString) {
            $result = FredhopperInternationalizedString::create();
            foreach ($akeneoInternationalisedString as $akeneoLocalizedString) {
                $result = $result->withValue($akeneoLocalizedString->getValue() . '_test', $akeneoLocalizedString->getLocale());
            }
            return $result;
        });
        $actual = $mapper(AkeneoAttribute::fromJson([
            'code' => 'price',
            'type' => AkeneoAttributeType::PRICE_COLLECTION,
            'localizable' => false,
            'scopable' => false,
            'sort_order' => 34,
            'labels' => [
                'en_GB' => 'Price',
            ],
            'group' => 'general',
            '@timestamp' => 1508491122,
        ]));

        $expected = AttributeDataSet::of([
            FredhopperAttribute::of(
                'price_gbp',
                FredhopperAttributeType::FLOAT,
                FredhopperInternationalizedString::create()->withValue('Price_test', 'en_GB')
            ),
            FredhopperAttribute::of(
                'price_eur',
                FredhopperAttributeType::FLOAT,
                FredhopperInternationalizedString::create()->withValue('Price_test', 'en_GB')
            ),
        ]);

        self::assertTrue($expected->equals($actual));
    }

    public function testWithoutCurrencies()
    {
        $mapper = PriceAttributeMapper::of([]);
        $actual = $mapper(AkeneoAttribute::fromJson([
            'code' => 'price',
            'type' => AkeneoAttributeType::PRICE_COLLECTION,
            'localizable' => false,
            'scopable' => false,
            'sort_order' => 34,
            'labels' => [
                'en_GB' => 'Price',
            ],
            'group' => 'general',
            '@timestamp' => 1508491122,
        ]));
        $expected = AttributeDataSet::create();
        self::assertTrue($expected->equals($actual));
    }

    public function testWithNonPriceType()
    {
        $mapper = PriceAttributeMapper::of([
            'gbp',
            'eur',
        ]);

        $actual = $mapper(AkeneoAttribute::fromJson([
            'code' => 'size',
            'type' => AkeneoAttributeType::SIMPLESELECT,
            'localizable' => true,
            'scopable' => true,
            'sort_order' => 34,
            'labels' => [
                'en_GB' => 'Size',
                'fr_FR' => 'Taille',
            ],
            'group' => 'general',
            '@timestamp' => 1508491122,
        ]));

        $expected = AttributeDataSet::create();

        self::assertTrue($expected->equals($actual));

    }
}
