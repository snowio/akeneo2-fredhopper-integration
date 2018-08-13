<?php
namespace SnowIO\Akeneo2Fredhopper\EventMapper;

use SnowIO\Akeneo2DataModel\Event\CategoryDeletedEvent;
use SnowIO\Akeneo2DataModel\Event\CategorySavedEvent;
use SnowIO\FredhopperDataModel\CategoryDataSet;

class CategoryEventCommandMapper
{
    public static function create(FredhopperConfiguration $fredhopperConfiguration)
    {
        return new self($fredhopperConfiguration);
    }

    public function getSaveCommands(array $eventJson): array {
        $event = CategorySavedEvent::fromJson($eventJson);
        $mapper = $this->fredhopperConfiguration->getCategoryMapper();
        /** @var CategoryDataSet $fhCategoriesData */
        $fhCategoriesData = $mapper($event->getCurrentCategoryData());
        return $fhCategoriesData->mapToSaveCommands($event->getTimestamp());
    }
    
    public function getDeleteCommands(array $eventJson): array {
        $event = CategoryDeletedEvent::fromJson($eventJson);
        $mapper = $this->fredhopperConfiguration->getCategoryMapper();
        /** @var CategoryDataSet $fhCategoriesData */
        $fhCategoriesData = $mapper($event->getPreviousCategoryData());
        return $fhCategoriesData->mapToDeleteCommands($event->getTimestamp());
    }

    private $fredhopperConfiguration;

    public function __construct(FredhopperConfiguration $configuration)
    {
        $this->fredhopperConfiguration = $configuration;
    }


}