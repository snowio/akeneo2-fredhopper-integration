<?php
namespace SnowIO\Akeneo2Fredhopper;

use SnowIO\Akeneo2DataModel\InternationalizedString as AkeneoInternationalizedString;
use SnowIO\Akeneo2DataModel\LocalizedString;
use SnowIO\FredhopperDataModel\InternationalizedString as FredhopperInternationalizedString;

class InternationalizedStringMapper
{
    public static function create(): self
    {
        return new self;
    }

    public function __invoke(AkeneoInternationalizedString $akeneoInternationalizedString): FredhopperInternationalizedString
    {
        /** @var FredhopperInternationalizedString $result */
        $result = FredhopperInternationalizedString::create();
        /** @var LocalizedString $akeneoLocalizedString */
        foreach ($akeneoInternationalizedString as $akeneoLocalizedString) {
            $result = $result->withValue($akeneoLocalizedString->getValue(), $akeneoLocalizedString->getLocale());
        }
        return $result;
    }
}
