<?php
declare(strict_types=1);
namespace SnowIO\Akeneo2Fredhopper;

use SnowIO\Akeneo2DataModel\AttributeValueSet as AkeneoAttributeValueSet;
use SnowIO\FredhopperDataModel\AttributeValueSet as FredhopperAttributeValueSet;

class AttributeValueMapperWithFilter
{
    public static function of(callable $mapper, callable $filter): self
    {
        $mapperWithFilter = new self;
        $mapperWithFilter->mapper = $mapper;
        $mapperWithFilter->filter = $filter;
        return $mapperWithFilter;
    }

    public function __invoke(AkeneoAttributeValueSet $akeneoAttributeValues): FredhopperAttributeValueSet
    {
        $filteredAttributeValues = $akeneoAttributeValues->filter($this->filter);
        return ($this->mapper)($filteredAttributeValues);
    }

    /** @var callable */
    private $mapper;
    /** @var callable */
    private $filter;

    protected function __construct()
    {

    }
}
